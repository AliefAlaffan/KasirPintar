<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\CashClosure;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
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

    public function history(int $userId, int $perPage = 15)
    {
        return Transaction::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function voidTransaction(int $transactionId, int $userId, string $reason): void
    {
        $transaction = Transaction::with('details')->findOrFail($transactionId);

        if ($transaction->user_id !== $userId) {
            throw new \Exception('Anda tidak bisa membatalkan transaksi milik kasir lain.');
        }

        if ($transaction->is_voided) {
            throw new \Exception('Transaksi ini sudah dibatalkan sebelumnya.');
        }

        // Cegah void transaksi yang sudah masuk periode tutup kasir yang selesai,
        // supaya rekonsiliasi kas yang sudah tersimpan tidak jadi tidak akurat
        $alreadyClosed = CashClosure::where('user_id', $userId)
            ->where('period_end', '>=', $transaction->created_at)
            ->exists();

        if ($alreadyClosed) {
            throw new \Exception('Transaksi ini sudah masuk periode tutup kasir yang selesai, tidak bisa dibatalkan lagi.');
        }

        DB::transaction(function () use ($transaction, $reason) {
            foreach ($transaction->details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }

            $transaction->update([
                'is_voided'   => true,
                'void_reason' => $reason,
                'voided_at'   => now(),
            ]);

            ActivityLog::create([
                'user_id'     => $transaction->user_id,
                'action'      => 'Membatalkan transaksi',
                'description' => "Invoice {$transaction->invoice_number} dibatalkan. Alasan: {$reason}",
            ]);
        });
    }
}