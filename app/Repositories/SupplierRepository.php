<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all(?string $search = null, int $perPage = 10)
    {
        return Supplier::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id) { return Supplier::findOrFail($id); }
    public function create(array $data) { return Supplier::create($data); }
    public function update(int $id, array $data) { $s = $this->find($id); $s->update($data); return $s; }
    public function delete(int $id): bool { return $this->find($id)->delete(); }
}