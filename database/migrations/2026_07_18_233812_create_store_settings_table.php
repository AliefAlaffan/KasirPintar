<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('KasirPintar');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('receipt_footer', 500)->default('Terima kasih atas kunjungan Anda!');
            $table->boolean('enable_tax')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->timestamps();
        });

        // Buat 1 baris default supaya selalu ada data untuk diedit
        \Illuminate\Support\Facades\DB::table('store_settings')->insert([
            'store_name'     => 'KasirPintar',
            'receipt_footer' => 'Terima kasih atas kunjungan Anda!',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};