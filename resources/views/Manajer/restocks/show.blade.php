<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Restock — {{ $restock->invoice_number }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div><span class="text-gray-500">Supplier:</span> <span class="font-medium">{{ $restock->supplier->name }}</span></div>
                    <div><span class="text-gray-500">Tanggal:</span> <span class="font-medium">{{ \Carbon\Carbon::parse($restock->restock_date)->format('d M Y') }}</span></div>
                    <div><span class="text-gray-500">Diinput oleh:</span> <span class="font-medium">{{ $restock->user->name }}</span></div>
                    <div><span class="text-gray-500">Total:</span> <span class="font-medium">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</span></div>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Harga Modal</th>
                            <th class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($restock->details as $detail)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3">{{ $detail->product->name }}</td>
                                <td class="px-4 py-3">{{ $detail->quantity }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($detail->cost_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($detail->quantity * $detail->cost_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($restock->note)
                    <p class="text-sm text-gray-500 mt-4">Catatan: {{ $restock->note }}</p>
                @endif

                <a href="{{ route('manajer.restocks.index') }}" class="inline-block mt-6 text-sm text-blue-600 hover:underline">← Kembali ke riwayat</a>
            </div>
        </div>
    </div>
</x-app-layout>