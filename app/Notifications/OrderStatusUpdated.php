<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $statusMessages = [
            'processing' => 'sedang diproses',
            'ready' => 'siap diambil',
            'completed' => 'telah selesai',
            'cancelled' => 'dibatalkan'
        ];

        $message = $statusMessages[$this->order->status] ?? 'status telah diperbarui';

        return (new MailMessage)
            ->subject('Update Status Pesanan #' . $this->order->id)
            ->line('Pesanan Anda #' . $this->order->id . ' ' . $message . '.')
            ->line('Total Pesanan: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', url('/orders/' . $this->order->id))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => 'Pesanan #' . $this->order->id . ' telah diperbarui ke status ' . $this->order->status
        ];
    }
} 