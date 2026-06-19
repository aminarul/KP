<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'paket_id',
        'teknisi_id',
        'tanggal_booking',
        'alamat_pasang',
        'maps_link',
        'catatan',
        'status',
        // Payment fields
        'invoice_number',
        'payment_status',
        'bukti_transfer',
        'paid_at',
        'confirmed_by',
        // Modem fields
        'foto_modem',
        'type_modem',
        'sn_ont',
        'keterangan_pemasangan',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'paid_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ON_PROGRESS = 'on_progress';
    const STATUS_SELESAI = 'selesai';
    const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';

    // Status badge colors
    public static function getStatusBadge($status)
    {
        return match($status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_ON_PROGRESS => 'orange',
            self::STATUS_SELESAI => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary',
        };
    }

    public static function getStatusLabel($status)
    {
        return match($status) {
            self::STATUS_PENDING => 'Menunggu Approval',
            self::STATUS_APPROVED => 'Menunggu Pembayaran',
            self::STATUS_ON_PROGRESS => 'Pemasangan',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($status),
        };
    }

    public static function getPaymentStatusBadge($status)
    {
        return match($status) {
            self::PAYMENT_UNPAID => 'danger',
            self::PAYMENT_PENDING => 'warning',
            self::PAYMENT_PAID => 'success',
            default => 'secondary',
        };
    }

    public static function getPaymentStatusLabel($status)
    {
        return match($status) {
            self::PAYMENT_UNPAID => 'Belum Bayar',
            self::PAYMENT_PENDING => 'Verifikasi',
            self::PAYMENT_PAID => 'Lunas',
            default => ucfirst($status),
        };
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketInternet::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class);
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // Helper methods
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->paket->harga, 0, ',', '.');
    }

    public function generateInvoiceNumber()
    {
        $this->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        $this->save();
    }
}