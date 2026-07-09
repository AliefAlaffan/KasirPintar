<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Str;

class TransactionService
{
    public function __construct(protected TransactionRepositoryInterface $repository)
    {
    }

    public function checkout(array $cart, float $discount, string $paymentMethod, float $paidAmount)
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $total = $subtotal - $discount;

        if ($paidAmount < $total) {
            throw new \Exception('Jumlah bayar kurang dari total belanja.');
        }

        $header = [
            'invoice_number' => 'TRX-' . now()->format('Ymd-His') . '-' . strtoupper(Str::random(3)),
            'user_id'        => auth()->id(),
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax'            => 0,
            'total'          => $total,
            'paid_amount'    => $paidAmount,
            'change_amount'  => $paidAmount - $total,
            'payment_method' => $paymentMethod,
        ];

        $items = collect($cart)->map(fn ($item) => [
            'product_id' => $item['id'],
            'quantity'   => $item['quantity'],
        ])->toArray();

        $transaction = $this->repository->create($header, $items);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Transaksi penjualan',
            'description' => "Invoice {$transaction->invoice_number}, total Rp " . number_format($total, 0, ',', '.'),
        ]);

        return $transaction;
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}