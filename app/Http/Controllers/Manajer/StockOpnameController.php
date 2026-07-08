<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\StockOpnameService;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function __construct(protected StockOpnameService $service)
    {
    }

    public function index()
    {
        $opnames = $this->service->list();
        return view('manajer.stock-opnames.index', compact('opnames'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'stock', 'unit']);
        return view('manajer.stock-opnames.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'opname_date'                => 'required|date',
            'note'                       => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.product_id'         => 'required|exists:products,id',
            'items.*.physical_stock'     => 'required|integer|min:0',
        ]);

        $opname = $this->service->store($validated);

        return redirect()->route('manajer.stock-opnames.show', $opname->id)
            ->with('success', 'Data opname tersimpan sebagai draft. Silakan cek selisihnya sebelum konfirmasi.');
    }

    public function show(int $id)
    {
        $opname = $this->service->find($id);
        return view('manajer.stock-opnames.show', compact('opname'));
    }

    public function complete(int $id)
    {
        $this->service->complete($id);
        return back()->with('success', 'Stock opname dikonfirmasi, stok sistem telah disesuaikan.');
    }
}