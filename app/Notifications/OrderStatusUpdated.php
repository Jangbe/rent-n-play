<?php

namespace App\Notifications;

use App\Events\OrderStatusUpdatedEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Status pemesanan dengan nomor transaksi ' . $this->transaction->transaction_number . ' status diubah menjadi ' . $this->transaction->status,
            'icon' => 'success',
            'url' => '/customer/transaction/' . $this->transaction->transaction_number,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        broadcast(new OrderStatusUpdatedEvent($this->transaction));
        return new BroadcastMessage($notifiable->notifications()->find($this->id)->toArray());
    }
}
