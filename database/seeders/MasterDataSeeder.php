<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketInternet;
use App\Models\CoverageArea;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Paket Internet
        $pakets = [
            [
                'nama_paket' => 'Paket Silver',
                'speed' => '30 Mbps',
                'harga' => 150000,
                'deskripsi' => 'Cocok untuk browsing dan streaming HD',
                'status' => 'aktif',
            ],
            [
                'nama_paket' => 'Paket Gold',
                'speed' => '50 Mbps',
                'harga' => 250000,
                'deskripsi' => 'Cocok untuk gaming dan streaming 4K',
                'status' => 'aktif',
            ],
            [
                'nama_paket' => 'Paket Platinum',
                'speed' => '100 Mbps',
                'harga' => 400000,
                'deskripsi' => 'Untuk bisnis dan kebutuhan berat',
                'status' => 'aktif',
            ],
            [
                'nama_paket' => 'Paket Diamond',
                'speed' => '200 Mbps',
                'harga' => 650000,
                'deskripsi' => 'Kecepatan tinggi untuk office',
                'status' => 'aktif',
            ],
        ];

        foreach ($pakets as $paket) {
            PaketInternet::updateOrCreate(
                ['nama_paket' => $paket['nama_paket']],
                $paket
            );
        }

        // Seed Coverage Area
        $areas = [
            [
                'nama_wilayah' => 'Jakarta Pusat',
                'kode_pos' => '10110',
                'keterangan' => 'Area Menteng, Gambir, Senen',
                'is_active' => true,
            ],
            [
                'nama_wilayah' => 'Jakarta Selatan',
                'kode_pos' => '12110',
                'keterangan' => 'Area Kebayoran, Pancoran, Mampang',
                'is_active' => true,
            ],
            [
                'nama_wilayah' => 'Jakarta Barat',
                'kode_pos' => '11110',
                'keterangan' => 'Area Grogol, Taman Sari, Kembangan',
                'is_active' => true,
            ],
            [
                'nama_wilayah' => 'Jakarta Timur',
                'kode_pos' => '13110',
                'keterangan' => 'Area Cakung, Jatinegara, Kramatjati',
                'is_active' => true,
            ],
            [
                'nama_wilayah' => 'Jakarta Utara',
                'kode_pos' => '14110',
                'keterangan' => 'Area Cilincing, Tanjung Priok, Kelapa Gading',
                'is_active' => true,
            ],
        ];

        foreach ($areas as $area) {
            CoverageArea::updateOrCreate(
                ['nama_wilayah' => $area['nama_wilayah']],
                $area
            );
        }

        $this->command->info('✅ Master data (paket & coverage area) berhasil di-seed!');
    }
}