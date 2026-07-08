<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $repository)
    {
    }

    public function list(?string $search = null, ?int $categoryId = null)
    {
        return $this->repository->all($search, $categoryId);
    }

    public function store(array $data, ?UploadedFile $image = null)
    {
        $data['sku'] = $this->generateSku($data['category_id']);

        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $image = null)
    {
        $product = $this->repository->find($id);

        if ($image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image->store('products', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function destroy(int $id): bool
    {
        $product = $this->repository->find($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        return $this->repository->delete($id);
    }

    protected function generateSku(int $categoryId): string
    {
        $prefix = 'PRD' . str_pad($categoryId, 2, '0', STR_PAD_LEFT);
        do {
            $sku = $prefix . '-' . strtoupper(Str::random(6));
        } while ($this->repository->findBySku($sku));

        return $sku;
    }
}