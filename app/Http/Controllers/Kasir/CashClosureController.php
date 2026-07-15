<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Services\CashClosureService;
use Illuminate\Http\Request;

class CashClosureController extends Controller
{
    public function __construct(protected CashClosureService $service)
    {
    }

    public function create()
    {
        $summary = $this->service->getCurrentSummary(auth()->id());
        return view('kasir.cash-closure.create', compact('summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'physical_cash' => 'required|numeric|min:0',
            'note'          => 'nullable|string',
        ]);

        $closure = $this->service->store(auth()->id(), $validated['physical_cash'], $validated['note'] ?? null);

        return redirect()->route('kasir.cash-closure.show', $closure->id)
            ->with('success', 'Tutup kasir berhasil disimpan.');
    }

    public function show(int $id)
    {
        $closure = \App\Models\CashClosure::where('user_id', auth()->id())->findOrFail($id);
        return view('kasir.cash-closure.show', compact('closure'));
    }

    public function history()
    {
        $closures = $this->service->history(auth()->id());
        return view('kasir.cash-closure.history', compact('closures'));
    }
}