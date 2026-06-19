<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->foreignId('paket_id')
                ->constrained('paket_internets')
                ->onDelete('cascade');

            $table->foreignId('teknisi_id')
                ->nullable()
                ->constrained('teknisis')
                ->nullOnDelete();

            $table->date('tanggal_booking');
            $table->text('alamat_pasang');
            $table->string('maps_link')->nullable();
            $table->text('catatan')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'assigned',
                'on_progress',
                'selesai',
                'cancelled'
            ])->default('pending');

            $table->text('foto_pemasangan')->nullable();
            $table->string('serial_ont')->nullable();
            $table->text('catatan_teknisi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}