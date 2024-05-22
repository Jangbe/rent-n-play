<?php

namespace App\Notifications;

use App\Events\OrderPlacedEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct($transaction, $admin)
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
            'message' => $this->transaction->user->name . ' telah melakukan pemesanan dengan nomer transaksi ' . $this->transaction->transaction_number,
            'icon' => 'info',
            'url' => '/admin/transaction/' . $this->transaction->transaction_number
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        broadcast(new OrderPlacedEvent($this->transaction));
        return new BroadcastMessage($notifiable->notifications()->find($this->id)->toArray());
    }
}
