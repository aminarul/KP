<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InstallationCompletedNotification extends Notification
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
            'title' => '🎉 Pemasangan Selesai!',
            'message' => 'Pemasangan internet untuk booking #' . $this->booking->id . ' telah selesai. Selamat menikmati layanan internet!',
            'booking_id' => $this->booking->id,
            'type' => 'installation_completed',
            'url' => '/customer/booking/' . $this->booking->id,
            'icon' => 'fa-check-double',
            'color' => 'success'
        ];
    }
}