<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name', 'address', 'phone', 'logo',
        'receipt_footer', 'enable_tax', 'tax_percentage',
    ];

    protected $casts = [
        'enable_tax' => 'boolean',
    ];

    /**
     * Ambil baris settingan toko (selalu ada 1 baris karena di-seed saat migration).
     * Di-cache dalam request supaya tidak query berkali-kali kalau dipanggil di banyak tempat.
     */
    public static function current(): self
    {
        return once(fn () => self::first() ?? self::create(['store_name' => 'KasirPintar']));
    }
}