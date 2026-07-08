<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->unique(); // buat barcode
            $table->string('name');
            $table->decimal('cost_price', 15, 2); // harga modal
            $table->decimal('sell_price', 15, 2); // harga jual
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5); // batas low stock alert
            $table->string('unit')->default('pcs'); // satuan: pcs, kg, dus, dll
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
