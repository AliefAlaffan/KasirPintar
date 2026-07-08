<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;

class StockOpnameService
{
    public function __construct(protected StockOpnameRepositoryInterface $repository)
    {
    }

    public function list(?string $search = null)
    {
        return $this->repository->all($search);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {
        $header = [
            'user_id'     => auth()->id(),
            'opname_date' => $data['opname_date'],
            'status'      => 'draft',
            'note'        => $data['note'] ?? null,
        ];

        return $this->repository->create($header, $data['items']);
    }

    public function complete(int $id): void
    {
        $this->repository->markCompleted($id);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Menyelesaikan stock opname',
            'description' => "Stock opname #{$id} telah dikonfirmasi, stok sistem disesuaikan.",
        ]);
    }
}