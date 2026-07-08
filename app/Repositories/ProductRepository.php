<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(?string $search = null, ?int $categoryId = null, int $perPage = 10)
    {
        return Product::query()
            ->with(['category', 'supplier'])
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id)
    {
        return Product::findOrFail($id);
    }

    public function findBySku(string $sku)
    {
        return Product::where('sku', $sku)->first();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }
}