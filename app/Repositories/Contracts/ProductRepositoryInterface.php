<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function all(?string $search = null, ?int $categoryId = null, int $perPage = 10);
    public function find(int $id);
    public function findBySku(string $sku);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
}