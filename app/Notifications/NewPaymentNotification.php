<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPaymentNotification extends Notification
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
            'title' => '💰 Pembayaran Baru!',
            'message' => 'Customer ' . ($this->booking->customer->user->name ?? '') . ' telah mengupload bukti pembayaran untuk booking #' . $this->booking->id . '. Silakan verifikasi.',
            'booking_id' => $this->booking->id,
            'type' => 'new_payment',
            'url' => '/admin/bookings/' . $this->booking->id,
            'icon' => 'fa-money-bill',
            'color' => 'info'
        ];
    }
}