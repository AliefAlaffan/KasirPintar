<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashClosure extends Model
{
    protected $fillable = [
        'user_id', 'period_start', 'period_end', 'transaction_count',
        'system_cash_total', 'system_qris_total', 'system_debit_total',
        'physical_cash', 'difference', 'note',
    ];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}