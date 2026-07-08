<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $repository)
    {
    }

    public function list(?string $search = null)
    {
        return $this->repository->all($search);
    }

    public function store(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->repository->update($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
