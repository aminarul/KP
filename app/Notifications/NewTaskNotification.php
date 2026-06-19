<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTaskNotification extends Notification
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
            'title' => '🔧 Tugas Baru!',
            'message' => 'Anda ditugaskan untuk pemasangan internet ke customer ' . ($this->booking->customer->user->name ?? '') . '. Segera lakukan pemasangan.',
            'booking_id' => $this->booking->id,
            'type' => 'new_task',
            'url' => '/teknisi/tasks/' . $this->booking->id,
            'icon' => 'fa-tasks',
            'color' => 'primary'
        ];
    }
}