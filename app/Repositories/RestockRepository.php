<?php

namespace App\Repositories;

use App\Models\Restock;
use App\Models\RestockDetail;
use App\Models\Product;
use App\Repositories\Contracts\RestockRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RestockRepository implements RestockRepositoryInterface
{
    public function all(?string $search = null, int $perPage = 10)
    {
        return Restock::with(['supplier', 'user'])
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return Restock::with(['supplier', 'user', 'details.product'])->findOrFail($id);
    }

    public function create(array $header, array $details)
    {
        return DB::transaction(function () use ($header, $details) {
            $restock = Restock::create($header);

            foreach ($details as $item) {
                RestockDetail::create([
                    'restock_id' => $restock->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);

                // Tambah stok & update harga modal terbaru
                $product = Product::findOrFail($item['product_id']);
                $product->increment('stock', $item['quantity']);
                $product->update(['cost_price' => $item['cost_price']]);
            }

            return $restock;
        });
    }
}