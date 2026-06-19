<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '💰 Pembayaran Dikonfirmasi!',
            'message' => 'Pembayaran untuk booking #' . $this->booking->id . ' telah dikonfirmasi. Teknisi akan segera diassign.',
            'booking_id' => $this->booking->id,
            'type' => 'payment_confirmed',
            'url' => '/customer/booking/' . $this->booking->id,
            'icon' => 'fa-credit-card',
            'color' => 'primary'
        ];
    }
}