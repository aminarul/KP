<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number', 50)->unique();
            $table->decimal('amount', 12, 0);
            $table->enum('payment_method', ['transfer', 'cash', 'qris'])->default('transfer');
            $table->date('payment_date')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['unpaid', 'pending', 'paid', 'failed'])->default('unpaid');
            $table->datetime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};