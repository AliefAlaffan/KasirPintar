<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Contracts\RestockRepositoryInterface;
use Illuminate\Support\Str;

class RestockService
{
    public function __construct(protected RestockRepositoryInterface $repository)
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

    public function store(array $data): mixed
    {
        $totalCost = collect($data['items'])->sum(fn ($item) => $item['quantity'] * $item['cost_price']);

        $header = [
            'invoice_number' => 'RS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'supplier_id'    => $data['supplier_id'],
            'user_id'        => auth()->id(),
            'total_cost'     => $totalCost,
            'restock_date'   => $data['restock_date'],
            'note'           => $data['note'] ?? null,
        ];

        $restock = $this->repository->create($header, $data['items']);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Restock barang',
            'description' => "Invoice {$restock->invoice_number}, total Rp " . number_format($totalCost, 0, ',', '.'),
        ]);

        return $restock;
    }
}