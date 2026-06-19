<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingApprovedNotification extends Notification implements ShouldQueue
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
            'title' => '✅ Booking Disetujui!',
            'message' => 'Booking #' . $this->booking->id . ' Anda telah disetujui. Silakan lakukan pembayaran.',
            'booking_id' => $this->booking->id,
            'type' => 'booking_approved',
            'url' => '/customer/booking/' . $this->booking->id,
            'icon' => 'fa-check-circle',
            'color' => 'success'
        ];
    }
}