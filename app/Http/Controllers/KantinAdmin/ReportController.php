<?php

namespace App\Http\Controllers\KantinAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Exports\SalesExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        // Sales report
        $salesReport = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->get();

        // Product performance
        $productPerformance = Product::withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('payment_status', 'paid');
                });
            }])
            ->withSum(['orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('payment_status', 'paid');
                });
            }], 'subtotal')
            ->orderByDesc('total_sold')
            ->get();

        // Category performance
        $categoryPerformance = Category::withCount(['products as total_sales' => function($query) use ($startDate, $endDate) {
                $query->whereHas('orderItems.order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('payment_status', 'paid');
                });
            }])
            ->withSum(['products.orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('payment_status', 'paid');
                });
            }], 'subtotal')
            ->get();

        // Payment method stats
        $paymentStats = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select('payment_method', 
                DB::raw('count(*) as total_transactions'),
                DB::raw('sum(total_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get();

        return view('kantin-admin.reports.index', compact(
            'salesReport',
            'productPerformance',
            'categoryPerformance',
            'paymentStats',
            'startDate',
            'endDate'
        ));
    }

    public function export()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        return Excel::download(
            new SalesExport($startDate, $endDate),
            'laporan-penjualan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.xlsx'
        );
    }
} 