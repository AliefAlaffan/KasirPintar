<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_closures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // kasir yang tutup kasir
            $table->dateTime('period_start'); // sejak kapan dihitung (tutup kasir sebelumnya / awal hari)
            $table->dateTime('period_end');   // saat tutup kasir dilakukan
            $table->integer('transaction_count')->default(0);
            $table->decimal('system_cash_total', 15, 2)->default(0);  // total transaksi TUNAI menurut sistem
            $table->decimal('system_qris_total', 15, 2)->default(0);  // info saja, bukan bagian rekonsiliasi kas fisik
            $table->decimal('system_debit_total', 15, 2)->default(0); // info saja
            $table->decimal('physical_cash', 15, 2)->default(0);      // uang fisik hasil hitung kasir
            $table->decimal('difference', 15, 2)->default(0);         // physical_cash - system_cash_total
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_closures');
    }
};