<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service)
    {
    }

    public function index(Request $request)
    {
        $suppliers = $this->service->list($request->search);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $this->service->store($request->only('name', 'phone', 'email', 'address'));

        return back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $this->service->update($id, $request->only('name', 'phone', 'email', 'address'));

        return back()->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);
        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}