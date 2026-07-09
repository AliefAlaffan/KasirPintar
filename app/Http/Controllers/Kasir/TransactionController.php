<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;

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
}