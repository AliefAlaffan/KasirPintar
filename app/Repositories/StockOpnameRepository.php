<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Product;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function all(?string $search = null, int $perPage = 10)
    {
        return StockOpname::with('user')
            ->withCount('details')
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return StockOpname::with(['user', 'details.product'])->findOrFail($id);
    }

    public function create(array $header, array $details)
    {
        return DB::transaction(function () use ($header, $details) {
            $opname = StockOpname::create($header);

            foreach ($details as $item) {
                $product = Product::findOrFail($item['product_id']);
                $difference = $item['physical_stock'] - $product->stock;

                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'product_id'      => $item['product_id'],
                    'system_stock'    => $product->stock,
                    'physical_stock'  => $item['physical_stock'],
                    'difference'      => $difference,
                ]);
            }

            return $opname;
        });
    }

    public function markCompleted(int $id): void
    {
        DB::transaction(function () use ($id) {
            $opname = $this->find($id);

            foreach ($opname->details as $detail) {
                // Stok sistem disesuaikan mengikuti hasil hitung fisik
                $detail->product->update(['stock' => $detail->physical_stock]);
            }

            $opname->update(['status' => 'completed']);
        });
    }
}