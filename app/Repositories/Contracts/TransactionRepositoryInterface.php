<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryInterface
{
    public function create(array $header, array $items);
    public function find(int $id);
}