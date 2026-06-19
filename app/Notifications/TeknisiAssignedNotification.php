<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeknisiAssignedNotification extends Notification
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
            'title' => '🔧 Teknisi Ditugaskan!',
            'message' => 'Teknisi ' . ($this->booking->teknisi->user->name ?? '') . ' akan segera melakukan pemasangan untuk booking #' . $this->booking->id,
            'booking_id' => $this->booking->id,
            'type' => 'teknisi_assigned',
            'url' => '/customer/booking/' . $this->booking->id,
            'icon' => 'fa-wrench',
            'color' => 'warning'
        ];
    }
}