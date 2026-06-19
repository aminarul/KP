<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class PaketInternet extends Model
{
    use HasFactory;

    protected $table = 'paket_internets';

    protected $fillable = [
        'nama_paket',
        'speed',
        'harga',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:0',
    ];

    /**
     * Relasi ke booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'paket_id');
    }

    /**
     * Cek status aktif
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Format harga rupiah
     */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}