<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InstallationCompletedByTeknisiNotification extends Notification
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
        $teknisiName = $this->booking->teknisi->user->name ?? 'Teknisi';
        
        return [
            'title' => '✅ Pemasangan Selesai!',
            'message' => 'Teknisi ' . $teknisiName . ' telah menyelesaikan pemasangan untuk booking #' . $this->booking->id . '.',
            'booking_id' => $this->booking->id,
            'type' => 'installation_completed_admin',
            'url' => '/admin/bookings/' . $this->booking->id,
            'icon' => 'fa-check-circle',
            'color' => 'success'
        ];
    }
}