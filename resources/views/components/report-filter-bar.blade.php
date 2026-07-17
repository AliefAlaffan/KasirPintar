@props(['from', 'to', 'exportType', 'backRoute' => 'reports.index'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Dari Tanggal</label>
            <input type="date" name="from" value="{{ $from }}"
                   class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
        </div>
        <div>
            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Sampai Tanggal</label>
            <input type="date" name="to" value="{{ $to }}"
                   class="w-full rounded-xl border-slate-200 text-sm mt-1 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition">
            Terapkan
        </button>

        <div class="flex gap-2 ms-auto">
            <a href="{{ route('reports.export.generic.pdf', ['type' => $exportType, 'from' => $from, 'to' => $to]) }}"
               class="flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 text-rose-600 text-sm font-medium rounded-xl hover:bg-rose-100 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>
            <a href="{{ route('reports.export.generic.csv', ['type' => $exportType, 'from' => $from, 'to' => $to]) }}"
               class="flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 text-emerald-600 text-sm font-medium rounded-xl hover:bg-emerald-100 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
                </svg>
                Excel
            </a>
        </div>
    </form>
</div>