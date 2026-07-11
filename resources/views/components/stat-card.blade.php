@props(['label', 'value', 'icon' => 'chart', 'accent' => 'brand'])

@php
$accents = [
    'brand'   => ['bar' => 'bg-brand-gradient', 'iconBg' => 'bg-indigo-50', 'iconText' => 'text-indigo-600'],
    'emerald' => ['bar' => 'bg-emerald-500', 'iconBg' => 'bg-emerald-50', 'iconText' => 'text-emerald-600'],
    'amber'   => ['bar' => 'bg-amber-500', 'iconBg' => 'bg-amber-50', 'iconText' => 'text-amber-600'],
    'rose'    => ['bar' => 'bg-rose-500', 'iconBg' => 'bg-rose-50', 'iconText' => 'text-rose-600'],
];
$style = $accents[$accent] ?? $accents['brand'];

$icons = [
    'revenue'  => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'chart'    => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    'cart'     => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
    'warning'  => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="h-1 {{ $style['bar'] }}"></div>
    <div class="p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-400 font-medium">{{ $label }}</p>
            <p class="text-2xl font-display font-bold text-slate-800 mt-1">{{ $value }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl {{ $style['iconBg'] }} {{ $style['iconText'] }} flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] ?? $icons['chart'] }}" />
            </svg>
        </div>
    </div>
</div>