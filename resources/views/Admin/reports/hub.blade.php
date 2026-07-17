<x-dashboard-layout title="Pusat Laporan">

    <p class="text-xs font-semibold text-brand-600 uppercase tracking-widest mb-1">Analitik</p>
    <h2 class="font-display font-bold text-2xl text-slate-800 mb-1">Pusat Laporan</h2>
    <p class="text-sm text-slate-400 mb-8">Pilih jenis laporan yang ingin ditinjau atau diekspor.</p>

    @php
        $reports = [
            [
                'route' => 'reports.sales',
                'title' => 'Laporan Penjualan',
                'desc'  => 'Ringkasan pendapatan, laba, dan tren harian.',
                'icon'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'bg'    => 'bg-indigo-50', 'text' => 'text-indigo-600',
            ],
            [
                'route' => 'reports.products',
                'title' => 'Produk Terlaris',
                'desc'  => 'Ranking produk berdasarkan jumlah terjual & pendapatan.',
                'icon'  => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
                'bg'    => 'bg-emerald-50', 'text' => 'text-emerald-600',
            ],
            [
                'route' => 'reports.cashiers',
                'title' => 'Performa Kasir',
                'desc'  => 'Jumlah transaksi & pendapatan per kasir.',
                'icon'  => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4',
                'bg'    => 'bg-amber-50', 'text' => 'text-amber-600',
            ],
            [
                'route' => 'reports.transactions',
                'title' => 'Detail Transaksi',
                'desc'  => 'Riwayat transaksi lengkap dengan filter tanggal.',
                'icon'  => 'M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4',
                'bg'    => 'bg-blue-50', 'text' => 'text-blue-600',
            ],
            [
                'route' => 'reports.voided',
                'title' => 'Riwayat Void',
                'desc'  => 'Transaksi yang dibatalkan beserta alasannya.',
                'icon'  => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
                'bg'    => 'bg-rose-50', 'text' => 'text-rose-600',
            ],
            [
                'route' => 'reports.cash-closures',
                'title' => 'Laporan Kas',
                'desc'  => 'Rekonsiliasi kas fisik tiap kasir per periode.',
                'icon'  => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'bg'    => 'bg-violet-50', 'text' => 'text-violet-600',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($reports as $report)
            <a href="{{ route($report['route']) }}"
               class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-5 hover:shadow-md hover:-translate-y-0.5 hover:border-brand-200 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl {{ $report['bg'] }} {{ $report['text'] }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $report['icon'] }}" />
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-brand-500 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="font-display font-semibold text-slate-800 mb-1">{{ $report['title'] }}</h3>
                <p class="text-sm text-slate-400 leading-relaxed">{{ $report['desc'] }}</p>
            </a>
        @endforeach
    </div>
</x-dashboard-layout>