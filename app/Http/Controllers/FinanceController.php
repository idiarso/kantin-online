<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function deposits()
    {
        $deposits = Deposit::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.finance.deposits.index', compact('deposits'));
    }

    public function createDeposit()
    {
        $users = User::where('status', 'active')
            ->whereIn('role', ['student', 'teacher'])
            ->get();

        return view('admin.finance.deposits.create', compact('users'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer,online_payment',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($request) {
            // Create deposit record
            $deposit = Deposit::create([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'status' => 'completed',
                'processed_by' => auth()->id()
            ]);

            // Update user balance
            $user = User::find($request->user_id);
            $user->balance += $request->amount;
            $user->save();

            // Create transaction record
            Transaction::create([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'completed',
                'description' => 'Deposit via ' . ucfirst(str_replace('_', ' ', $request->payment_method)),
                'reference_id' => $deposit->id,
                'reference_type' => 'deposit'
            ]);
        });

        return redirect()->route('admin.finance.deposits')
            ->with('success', 'Deposit processed successfully');
    }

    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.finance.transactions.index', compact('transactions'));
    }

    public function reports()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        $monthlyStats = $this->getMonthlyStats();
        $dailyStats = $this->getDailyStats();
        $topProducts = $this->getTopProducts();
        $paymentMethods = $this->getPaymentMethodStats();

        return view('admin.finance.reports.index', compact(
            'monthlyStats',
            'dailyStats',
            'topProducts',
            'paymentMethods'
        ));
    }

    private function getMonthlyStats()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        return [
            'total_revenue' => Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('type', 'payment')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_deposits' => Deposit::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('amount'),
            'total_transactions' => Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count(),
            'average_transaction' => Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->where('type', 'payment')
                ->where('status', 'completed')
                ->avg('amount') ?? 0
        ];
    }

    private function getDailyStats()
    {
        $startDate = Carbon::now()->subDays(30);
        
        return Transaction::where('created_at', '>=', $startDate)
            ->where('type', 'payment')
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_amount, COUNT(*) as transaction_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getTopProducts()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', Carbon::now()->startOfMonth())
            ->selectRaw('products.name, SUM(order_items.quantity) as total_quantity, SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
    }

    private function getPaymentMethodStats()
    {
        return Transaction::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('type', 'payment')
            ->where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('payment_method')
            ->get();
    }

    public function downloadReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:transactions,deposits',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        if ($request->type === 'transactions') {
            $data = Transaction::with('user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $filename = 'transactions_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        } else {
            $data = Deposit::with('user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $filename = 'deposits_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($data, $request) {
            $file = fopen('php://output', 'w');
            
            if ($request->type === 'transactions') {
                fputcsv($file, ['Date', 'User', 'Type', 'Amount', 'Status', 'Description']);
                foreach ($data as $transaction) {
                    fputcsv($file, [
                        $transaction->created_at->format('Y-m-d H:i:s'),
                        $transaction->user->name,
                        $transaction->type,
                        $transaction->amount,
                        $transaction->status,
                        $transaction->description
                    ]);
                }
            } else {
                fputcsv($file, ['Date', 'User', 'Amount', 'Payment Method', 'Reference', 'Status', 'Notes']);
                foreach ($data as $deposit) {
                    fputcsv($file, [
                        $deposit->created_at->format('Y-m-d H:i:s'),
                        $deposit->user->name,
                        $deposit->amount,
                        $deposit->payment_method,
                        $deposit->reference_number,
                        $deposit->status,
                        $deposit->notes
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 