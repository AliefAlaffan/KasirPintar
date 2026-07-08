<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Stock Opname</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari..." class="rounded-lg border-gray-300 text-sm">
                        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Cari</button>
                    </form>
                    <a href="{{ route('manajer.stock-opnames.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">+ Opname Baru</a>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Tanggal Opname</th>
                            <th class="px-4 py-3">Diinput oleh</th>
                            <th class="px-4 py-3">Jumlah Item</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($opnames as $opname)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $opname->user->name }}</td>
                                <td class="px-4 py-3">{{ $opname->details_count ?? $opname->details->count() }} produk</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $opname->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $opname->status === 'completed' ? 'Selesai' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('manajer.stock-opnames.show', $opname->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada riwayat stock opname.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $opnames->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>