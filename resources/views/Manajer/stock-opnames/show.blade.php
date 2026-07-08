<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Stock Opname</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-sm">
                        <span class="text-gray-500">Tanggal:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</span>
                        —
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $opname->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $opname->status === 'completed' ? 'Selesai' : 'Draft' }}
                        </span>
                    </div>

                    @if ($opname->status === 'draft')
                        <form action="{{ route('manajer.stock-opnames.complete', $opname->id) }}" method="POST"
                              onsubmit="return confirm('Konfirmasi opname ini? Stok sistem akan disesuaikan mengikuti hasil hitung fisik dan tidak bisa diubah lagi setelah ini.')">
                            @csrf
                            <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">Konfirmasi & Terapkan</button>
                        </form>
                    @endif
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Stok Sistem</th>
                            <th class="px-4 py-3">Stok Fisik</th>
                            <th class="px-4 py-3">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opname->details as $detail)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3">{{ $detail->product->name }}</td>
                                <td class="px-4 py-3">{{ $detail->system_stock }}</td>
                                <td class="px-4 py-3">{{ $detail->physical_stock }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-medium {{ $detail->difference < 0 ? 'text-red-600' : ($detail->difference > 0 ? 'text-green-600' : 'text-gray-400') }}">
                                        {{ $detail->difference > 0 ? '+' : '' }}{{ $detail->difference }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($opname->note)
                    <p class="text-sm text-gray-500 mt-4">Catatan: {{ $opname->note }}</p>
                @endif

                <a href="{{ route('manajer.stock-opnames.index') }}" class="inline-block mt-6 text-sm text-blue-600 hover:underline">← Kembali ke riwayat</a>
            </div>
        </div>
    </div>
</x-app-layout>