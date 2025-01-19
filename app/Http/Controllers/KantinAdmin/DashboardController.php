<?php

namespace App\Http\Controllers\KantinAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Apply filters
        $query = Order::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('items.product', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get statistics
        $todayStats = $this->getTodayStats();
        $revenueStats = $this->getRevenueStats();
        $salesData = $this->getSalesData();
        $paymentMethodStats = $this->getPaymentMethodStats();
        
        // Get new orders for notifications
        $newOrders = Order::where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(1))
            ->get();

        // Get categories for filter
        $categories = Category::all();

        return view('kantin-admin.dashboard', compact(
            'todayStats',
            'revenueStats',
            'salesData',
            'paymentMethodStats',
            'newOrders',
            'categories'
        ));
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'orders-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPDF()
    {
        $data = [
            'orders' => Order::with(['user', 'items.product'])->latest()->get(),
            'stats' => $this->getTodayStats(),
        ];

        $pdf = PDF::loadView('exports.orders-pdf', $data);
        return $pdf->download('orders-'.now()->format('Y-m-d').'.pdf');
    }

    // ... existing private methods ...
} 