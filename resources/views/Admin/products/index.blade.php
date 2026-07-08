<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Produk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama/SKU..."
                               class="rounded-lg border-gray-300 text-sm">
                        <select name="category_id" class="rounded-lg border-gray-300 text-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">Cari</button>
                    </form>

                    <a href="{{ route('admin.products.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        + Tambah Produk
                    </a>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Harga Jual</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/40' }}"
                                         class="w-10 h-10 rounded-lg object-cover">
                                    <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $product->sku }}</td>
                                <td class="px-4 py-3">{{ $product->category->name }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $product->stock <= $product->min_stock ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>