<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $header, array $items)
    {
        return DB::transaction(function () use ($header, $items) {
            $transaction = Transaction::create($header);

            foreach ($items as $item) {
                // Lock baris produk saat transaksi berlangsung, cegah race condition
                // jika 2 kasir checkout produk yang sama di waktu bersamaan
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi (tersisa {$product->stock}).");
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'product_name'   => $product->name,
                    'quantity'       => $item['quantity'],
                    'price'          => $product->sell_price,
                    'subtotal'       => $product->sell_price * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            return $transaction;
        });
    }

    public function find(int $id)
    {
        return Transaction::with(['details', 'user'])->findOrFail($id);
    }
}