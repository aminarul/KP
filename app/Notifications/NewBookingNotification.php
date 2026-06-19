<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
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
            'title' => '📋 Booking Baru!',
            'message' => 'Customer ' . ($this->booking->customer->user->name ?? '') . ' telah membuat booking baru #' . $this->booking->id . '. Silakan proses.',
            'booking_id' => $this->booking->id,
            'type' => 'new_booking',
            'url' => '/admin/bookings/' . $this->booking->id,
            'icon' => 'fa-calendar-plus',
            'color' => 'info'
        ];
    }
}