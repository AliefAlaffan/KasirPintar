<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'email'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function restocks(): HasMany
    {
        return $this->hasMany(Restock::class);
    }
}