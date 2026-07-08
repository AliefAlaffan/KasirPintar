<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Supplier;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service)
    {
    }

    public function index(Request $request)
    {
        $products = $this->service->list($request->search, $request->category_id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name'        => 'required|string|max:255',
            'cost_price'  => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|gte:cost_price',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'unit'        => 'required|string|max:20',
            'image'       => 'nullable|image|max:2048',
        ], [
            'sell_price.gte' => 'Harga jual tidak boleh lebih kecil dari harga modal.',
        ]);

        $this->service->store($validated, $request->file('image'));

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $product = $this->service->list()->firstWhere('id', $id) ?? \App\Models\Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name'        => 'required|string|max:255',
            'cost_price'  => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|gte:cost_price',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'unit'        => 'required|string|max:20',
            'image'       => 'nullable|image|max:2048',
        ], [
            'sell_price.gte' => 'Harga jual tidak boleh lebih kecil dari harga modal.',
        ]);

        $this->service->update($id, $validated, $request->file('image'));

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}