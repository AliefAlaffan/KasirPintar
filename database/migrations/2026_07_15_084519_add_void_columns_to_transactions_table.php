<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_voided')->default(false)->after('payment_method');
            $table->text('void_reason')->nullable()->after('is_voided');
            $table->dateTime('voided_at')->nullable()->after('void_reason');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_voided', 'void_reason', 'voided_at']);
        });
    }
};