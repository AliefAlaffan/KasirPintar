<?php

namespace App\Repositories\Contracts;

interface RestockRepositoryInterface
{
    public function all(?string $search = null, int $perPage = 10);
    public function find(int $id);
    public function create(array $header, array $details);
}