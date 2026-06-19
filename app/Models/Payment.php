<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'amount',
        'payment_method',
        'payment_date',
        'bukti_transfer',
        'catatan',
        'status',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:0',
    ];

    // Status constants
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';

    public static function getStatusBadge($status)
    {
        return match($status) {
            self::STATUS_UNPAID => 'danger',
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'success',
            self::STATUS_FAILED => 'secondary',
            default => 'secondary',
        };
    }

    public static function getStatusLabel($status)
    {
        return match($status) {
            self::STATUS_UNPAID => 'Belum Dibayar',
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_PAID => 'Lunas',
            self::STATUS_FAILED => 'Gagal',
            default => ucfirst($status),
        };
    }

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods
    public function getAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function canBeVerified()
    {
        return $this->status === self::STATUS_PENDING;
    }
}