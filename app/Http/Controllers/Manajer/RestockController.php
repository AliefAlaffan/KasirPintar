<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\RestockService;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    public function __construct(protected RestockService $service)
    {
    }

    public function index(Request $request)
    {
        $restocks = $this->service->list($request->search);
        return view('manajer.restocks.index', compact('restocks'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'cost_price', 'unit']);
        return view('manajer.restocks.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'            => 'required|exists:suppliers,id',
            'restock_date'           => 'required|date',
            'note'                   => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.quantity'       => 'required|integer|min:1',
            'items.*.cost_price'     => 'required|numeric|min:0',
        ]);

        $restock = $this->service->store($validated);

        return redirect()->route('manajer.restocks.show', $restock->id)
            ->with('success', 'Restock berhasil disimpan, stok telah diperbarui.');
    }

    public function show(int $id)
    {
        $restock = $this->service->find($id);
        return view('manajer.restocks.show', compact('restock'));
    }
}