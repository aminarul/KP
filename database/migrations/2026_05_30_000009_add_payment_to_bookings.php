<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Payment fields
            $table->string('invoice_number', 50)->nullable()->unique();
            $table->enum('payment_status', ['unpaid', 'pending', 'paid'])->default('unpaid');
            $table->string('bukti_transfer')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number', 'payment_status', 'bukti_transfer', 
                'paid_at', 'confirmed_by', 'foto_modem', 'type_modem', 
                'sn_ont', 'keterangan_pemasangan'
            ]);
        });
    }
};