<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['revenue_today'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['revenue_month'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Laba Bersih Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['profit_month'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-orange-500">
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $summary['transactions_today'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Grafik Tren Penjualan --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Tren Penjualan 7 Hari Terakhir</h3>
                    <canvas id="salesTrendChart" height="100"></canvas>
                </div>

                {{-- Alert Stok Menipis --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        ⚠️ Stok Menipis
                    </h3>
                    @forelse ($lowStock as $product)
                        <div class="flex justify-between items-center py-2 border-b last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">Min. stok: {{ $product->min_stock }}</p>
                            </div>
                            <span class="text-sm font-bold text-red-500">{{ $product->stock }} {{ $product->unit }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Semua stok aman ✅</p>
                    @endforelse
                </div>
            </div>

            {{-- Log Aktivitas Terbaru --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Log Aktivitas Terbaru</h3>
                <ul class="divide-y divide-gray-100">
                    @forelse ($activities as $log)
                        <li class="py-2 flex justify-between text-sm">
                            <span class="text-gray-700">
                                <span class="font-medium">{{ $log->user->name ?? 'Sistem' }}</span> — {{ $log->action }}
                            </span>
                            <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <p class="text-sm text-gray-400">Belum ada aktivitas.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script>
        const trendData = @json($salesTrend);

        new Chart(document.getElementById('salesTrendChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.date),
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: trendData.map(d => d.total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>