<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Helper methods for role checking
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    // Relationships
    public function teknisi()
    {
        return $this->hasOne(Teknisi::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // Get unread notifications count
    public function getUnreadNotificationsCount()
    {
        return $this->unreadNotifications->count();
    }

    // Get latest notifications (as method)
    public function getLatestNotifications($limit = 5)
    {
        return $this->notifications()->latest()->take($limit)->get();
    }

    // Get latest notifications as accessor (dynamic attribute)
    public function getLatestNotificationsAttribute()
    {
        return $this->notifications()->latest()->take(10)->get();
    }
}