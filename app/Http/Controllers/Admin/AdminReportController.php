<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Order::query()
            ->where('status', 'completed')
            ->where('payment_status', 'paid');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $sales = $query->with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        $totalSales = $query->sum('total_amount');

        return view('admin.reports.sales', compact('sales', 'totalSales'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->with('user')
            ->latest()
            ->paginate(10);

        $summary = Transaction::selectRaw('
            type,
            COUNT(*) as total_count,
            SUM(amount) as total_amount
        ')
        ->groupBy('type')
        ->get();

        return view('admin.reports.transactions', compact('transactions', 'summary'));
    }
} 