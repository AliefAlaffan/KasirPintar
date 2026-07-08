<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestockDetail extends Model
{
    protected $fillable = ['restock_id', 'product_id', 'quantity', 'cost_price'];

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}