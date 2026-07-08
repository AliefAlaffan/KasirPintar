<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Produk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-lg border-gray-300 mt-1" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" class="w-full rounded-lg border-gray-300 mt-1" required>
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Supplier (opsional)</label>
                            <select name="supplier_id" class="w-full rounded-lg border-gray-300 mt-1">
                                <option value="">Tanpa supplier</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Harga Modal (Rp)</label>
                            <input type="number" name="cost_price" value="{{ old('cost_price') }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                            @error('cost_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Harga Jual (Rp)</label>
                            <input type="number" name="sell_price" value="{{ old('sell_price') }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                            @error('sell_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Stok Awal</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Stok Minimum</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Satuan</label>
                            <input type="text" name="unit" value="{{ old('unit', 'pcs') }}"
                                   placeholder="pcs, kg, dus"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Gambar Produk (opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full rounded-lg border-gray-300 mt-1">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>