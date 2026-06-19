<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Teknisi
        User::updateOrCreate(
            ['email' => 'teknisi@gmail.com'],
            [
                'name' => 'Ahmad Teknisi',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi.teknisi@gmail.com'],
            [
                'name' => 'Budi Prasetyo',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Admin & Teknisi Seeder berhasil!');
    }
}