@props(['active', 'from', 'to'])

@php
    $tabs = [
        'summary'      => ['label' => 'Ringkasan', 'route' => 'reports.index'],
        'products'     => ['label' => 'Produk Terlaris', 'route' => 'reports.products'],
        'cashiers'     => ['label' => 'Performa Kasir', 'route' => 'reports.cashiers'],
        'transactions' => ['label' => 'Detail Transaksi', 'route' => 'reports.transactions'],
        'voided'       => ['label' => 'Riwayat Void', 'route' => 'reports.voided'],
        'cash'         => ['label' => 'Laporan Kas', 'route' => 'reports.cash-closures'],
    ];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-2 mb-6 flex gap-1 overflow-x-auto">
    @foreach ($tabs as $key => $tab)
        <a href="{{ route($tab['route'], ['from' => $from, 'to' => $to]) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition
           {{ $active === $key ? 'bg-brand-gradient text-white' : 'text-slate-500 hover:bg-slate-50' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>