<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Restock</h2>
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
                               placeholder="Cari no. invoice..." class="rounded-lg border-gray-300 text-sm">
                        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Cari</button>
                    </form>
                    <a href="{{ route('manajer.restocks.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">+ Input Restock</a>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">No. Invoice</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Diinput oleh</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($restocks as $restock)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3 font-mono text-xs">{{ $restock->invoice_number }}</td>
                                <td class="px-4 py-3">{{ $restock->supplier->name }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($restock->restock_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($restock->total_cost, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $restock->user->name }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('manajer.restocks.show', $restock->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada riwayat restock.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $restocks->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>