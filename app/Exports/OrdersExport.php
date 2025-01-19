<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with(['user', 'items.product'])
            ->get()
            ->map(function ($order) {
                return [
                    'ID' => $order->id,
                    'Customer' => $order->user->name,
                    'Total' => $order->total_amount,
                    'Status' => $order->status,
                    'Payment Method' => $order->payment_method,
                    'Created At' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer',
            'Total',
            'Status',
            'Payment Method',
            'Created At',
        ];
    }
} 