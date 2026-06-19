<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverageArea extends Model
{
    use HasFactory;

    protected $table = 'coverage_areas';

    protected $fillable = [
        'nama_wilayah',
        'kode_pos',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}