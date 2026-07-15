<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $service)
    {
    }

    public function receipt(int $id)
    {
        $transaction = $this->service->find($id);
        return view('kasir.receipt', compact('transaction'));
    }

    public function history()
    {
        $transactions = $this->service->history(auth()->id());
        return view('kasir.transactions.history', compact('transactions'));
    }

    public function void(Request $request, int $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:5|max:255',
        ]);

        try {
            $this->service->voidTransaction($id, auth()->id(), $validated['reason']);
            return back()->with('success', 'Transaksi berhasil dibatalkan, stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}