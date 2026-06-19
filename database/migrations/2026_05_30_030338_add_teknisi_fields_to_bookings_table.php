<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'foto_pemasangan')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->text('foto_pemasangan')->nullable();
            });
        }

        if (!Schema::hasColumn('bookings', 'serial_ont')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('serial_ont', 50)->nullable();
            });
        }

        if (!Schema::hasColumn('bookings', 'catatan_teknisi')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->text('catatan_teknisi')->nullable();
            });
        }

        if (!Schema::hasColumn('bookings', 'tanggal_mulai')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->datetime('tanggal_mulai')->nullable();
            });
        }

        if (!Schema::hasColumn('bookings', 'tanggal_selesai')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->datetime('tanggal_selesai')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $columns = [
                'foto_pemasangan',
                'serial_ont',
                'catatan_teknisi',
                'tanggal_mulai',
                'tanggal_selesai'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};