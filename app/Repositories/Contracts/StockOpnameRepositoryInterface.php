<?php

namespace App\Repositories\Contracts;

interface StockOpnameRepositoryInterface
{
    public function all(?string $search = null, int $perPage = 10);
    public function find(int $id);
    public function create(array $header, array $details);
    public function markCompleted(int $id): void;
}