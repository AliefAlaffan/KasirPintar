<x-dashboard-layout title="Pengaturan Toko">

    <p class="text-xs font-semibold text-brand-600 uppercase tracking-widest mb-1">Sistem</p>
    <h2 class="font-display font-bold text-2xl text-slate-800 mb-6">Pengaturan Toko</h2>

    @if (session('success'))
        <div class="mb-5 p-3.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <div class="max-w-2xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
              x-data="{ preview: '{{ $setting->logo ? Storage::url($setting->logo) : '' }}', enableTax: {{ $setting->enable_tax ? 'true' : 'false' }} }" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Identitas Toko --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-display font-semibold text-slate-800 mb-4">Identitas Toko</h3>

                <div class="flex items-center gap-4 mb-5">
                    <label class="relative w-20 h-20 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center cursor-pointer hover:border-brand-400 transition shrink-0 overflow-hidden bg-slate-50">
                        <template x-if="!preview">
                            <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </template>
                        <img :src="preview" x-show="preview" x-cloak class="w-full h-full object-cover">
                        <input type="file" name="logo" accept="image/*" class="hidden"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Logo Toko</p>
                        <p class="text-xs text-slate-400">Opsional. Muncul di struk & halaman login. Maks 1MB.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Nama Toko</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $setting->store_name) }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                        @error('store_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-600">Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}"
                                   class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Alamat</label>
                        <textarea name="address" rows="2"
                                  class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ old('address', $setting->address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Struk --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-display font-semibold text-slate-800 mb-4">Pengaturan Struk</h3>
                <label class="text-sm font-medium text-slate-600">Teks Footer Struk</label>
                <textarea name="receipt_footer" rows="2" placeholder="Contoh: Terima kasih atas kunjungan Anda!"
                          class="w-full rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ old('receipt_footer', $setting->receipt_footer) }}</textarea>
            </div>

            {{-- Pajak --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-display font-semibold text-slate-800 mb-4">Pajak (PPN)</h3>

                <label class="flex items-center justify-between cursor-pointer mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Aktifkan Pajak</p>
                        <p class="text-xs text-slate-400">Kalau aktif, pajak otomatis ditambahkan ke setiap transaksi kasir.</p>
                    </div>
                    <button type="button" @click="enableTax = !enableTax"
                            class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors duration-200 shrink-0"
                            :class="enableTax ? 'bg-emerald-500' : 'bg-slate-200'">
                        <span class="inline-block w-4 h-4 transform bg-white rounded-full shadow transition-transform duration-200"
                              :class="enableTax ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                    <input type="hidden" name="enable_tax" :value="enableTax ? 1 : 0">
                </label>

                <div x-show="enableTax" x-cloak>
                    <label class="text-sm font-medium text-slate-600">Persentase Pajak (%)</label>
                    <input type="number" name="tax_percentage" step="0.1" value="{{ old('tax_percentage', $setting->tax_percentage) }}"
                           class="w-full sm:w-48 rounded-xl border-slate-200 mt-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-brand-gradient text-white font-semibold text-sm rounded-xl hover:shadow-lg transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>