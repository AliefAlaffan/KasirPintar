<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Penjualan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Tanggal --}}
            <div class="bg-white rounded-xl shadow p-6">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ $from }}" class="w-full rounded-lg border-gray-300 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ $to }}" class="w-full rounded-lg border-gray-300 mt-1">
                    </div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Tampilkan</button>

                    <div class="ms-auto flex gap-2">
                        <a href="{{ route('reports.export.pdf', ['from' => $from, 'to' => $to]) }}"
                           class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100">Export PDF</a>
                        <a href="{{ route('reports.export.excel', ['from' => $from, 'to' => $to]) }}"
                           class="px-4 py-2 bg-green-50 text-green-600 rounded-lg text-sm hover:bg-green-100">Export Excel</a>
                    </div>
                </form>
            </div>

            {{-- Kartu Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500">Total Modal (HPP)</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['total_cost'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Laba Bersih</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                    <p class="text-xl font-bold text-gray-800">{{ $summary['total_transactions'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Grafik Tren Harian --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Tren Pendapatan Harian</h3>
                    <canvas id="dailyTrendChart" height="200"></canvas>
                </div>

                {{-- Produk Terlaris --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Produk Terlaris</h3>
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-3 py-2">Produk</th>
                                <th class="px-3 py-2">Terjual</th>
                                <th class="px-3 py-2">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bestSelling as $item)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $item->name }}</td>
                                    <td class="px-3 py-2">{{ $item->total_sold }}</td>
                                    <td class="px-3 py-2">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Performa Kasir --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Performa Kasir</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">Nama Kasir</th>
                            <th class="px-3 py-2">Jumlah Transaksi</th>
                            <th class="px-3 py-2">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashierPerformance as $cp)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $cp->name }}</td>
                                <td class="px-3 py-2">{{ $cp->total_transactions }}</td>
                                <td class="px-3 py-2">Rp {{ number_format($cp->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Detail Transaksi --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Detail Transaksi</h3>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-3 py-2">No. Invoice</th>
                            <th class="px-3 py-2">Tanggal</th>
                            <th class="px-3 py-2">Kasir</th>
                            <th class="px-3 py-2">Total</th>
                            <th class="px-3 py-2">Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr class="border-b">
                                <td class="px-3 py-2 font-mono text-xs">{{ $trx->invoice_number }}</td>
                                <td class="px-3 py-2">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-3 py-2">{{ $trx->user->name }}</td>
                                <td class="px-3 py-2">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 uppercase text-xs">{{ $trx->payment_method }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-4 text-center text-gray-400">Tidak ada transaksi di rentang tanggal ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const trend = @json($dailyTrend);
        new Chart(document.getElementById('dailyTrendChart'), {
            type: 'bar',
            data: {
                labels: trend.map(d => d.date),
                datasets: [{
                    label: 'Pendapatan',
                    data: trend.map(d => d.revenue),
                    backgroundColor: '#3b82f6',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
            }
        });
    </script>
</x-app-layout>