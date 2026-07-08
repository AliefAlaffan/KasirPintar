<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stock Opname Baru</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">
                <form action="{{ route('manajer.stock-opnames.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Tanggal Opname</label>
                            <input type="date" name="opname_date" value="{{ date('Y-m-d') }}"
                                   class="w-full rounded-lg border-gray-300 mt-1" required>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-3">Isi jumlah stok fisik hasil hitung langsung di toko untuk tiap produk. Kosongkan/biarkan sama jika stok fisik sesuai dengan sistem.</p>

                    <table class="w-full text-sm text-left text-gray-500 mb-4">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Stok Sistem</th>
                                <th class="px-4 py-3">Stok Fisik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr class="bg-white border-b">
                                    <td class="px-4 py-3">
                                        {{ $product->name }}
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                    </td>
                                    <td class="px-4 py-3">{{ $product->stock }} {{ $product->unit }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $index }}][physical_stock]"
                                               value="{{ $product->stock }}" min="0"
                                               class="w-24 rounded-lg border-gray-300 text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Catatan (opsional)</label>
                        <textarea name="note" class="w-full rounded-lg border-gray-300 mt-1" rows="2"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('manajer.stock-opnames.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan Draft</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>