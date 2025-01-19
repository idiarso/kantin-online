<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('payment_status', 'paid')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Tanggal',
            'Pelanggan',
            'Role',
            'Item',
            'Total Qty',
            'Total',
            'Metode Pembayaran',
            'Status'
        ];
    }

    public function map($order): array
    {
        return [
            '#' . $order->id,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name,
            ucfirst($order->user->role),
            $order->items->map(function($item) {
                return $item->product->name . ' (x' . $item->quantity . ')';
            })->implode(', '),
            $order->items->sum('quantity'),
            $order->total_amount,
            $order->payment_method === 'balance' ? 'Saldo' : 'Tunai',
            ucfirst($order->status)
        ];
    }
} 