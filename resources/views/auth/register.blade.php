<x-guest-layout>
    <style>
        .mesh-bg {
            background:
                radial-gradient(ellipse 70% 55% at 20% 15%, rgba(255,255,255,0.16), transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 75%, rgba(124,58,237,0.35), transparent 60%),
                linear-gradient(150deg, #3730a3 0%, #4f46e5 55%, #5b21b6 100%);
        }
        .grain::before {
            content: '';
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.35'/%3E%3C/svg%3E");
            opacity: 0.5; mix-blend-mode: overlay; pointer-events: none;
        }
        @keyframes fade-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .anim-fade-up { animation: fade-up 0.7s cubic-bezier(0.16, 1, 0.3, 1) both; }
    </style>

    <div class="min-h-screen flex">

        {{-- ============ PANEL KIRI ============ --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between p-14 mesh-bg grain">
            <div class="relative flex items-center gap-3 anim-fade-up">
                <div class="w-9 h-9 rounded-lg bg-white/10 ring-1 ring-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="font-display font-bold text-white text-lg tracking-tight">KasirPintar</span>
            </div>

            <div class="relative flex-1 flex flex-col items-center justify-center text-center px-6">
                <div class="w-16 h-16 rounded-2xl bg-white/10 ring-1 ring-white/20 flex items-center justify-center mb-6 anim-fade-up" style="animation-delay: 0.1s">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                </div>
                <h1 class="font-display font-bold text-white text-2xl mb-3 anim-fade-up" style="animation-delay: 0.15s">Bergabung dengan tim toko Anda</h1>
                <p class="text-white/70 text-sm max-w-xs anim-fade-up" style="animation-delay: 0.2s">
                    Akun ini akan menjadi pemilik/administrator utama. Anda bisa mengundang Manajer & Kasir setelahnya.
                </p>
            </div>

            <p class="relative text-white/40 text-xs anim-fade-up" style="animation-delay: 0.25s">© {{ date('Y') }} KasirPintar.</p>
        </div>

        {{-- ============ PANEL KANAN: Form Register ============ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-sm anim-fade-up" style="animation-delay: 0.1s">

                <div class="flex items-center gap-2.5 mb-8 lg:hidden">
                    <div class="w-9 h-9 rounded-xl bg-brand-gradient flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="font-display font-bold text-slate-800 text-lg">KasirPintar</span>
                </div>

                <h2 class="font-display font-bold text-[1.7rem] text-slate-900 mb-1.5 tracking-tight">Buat akun baru</h2>
                <p class="text-sm text-slate-400 mb-8">Isi data di bawah untuk mendaftar.</p>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 rounded-xl border border-rose-100">
                        <p class="text-sm font-medium text-rose-700 mb-1">Terjadi kesalahan</p>
                        <ul class="text-xs text-rose-600 space-y-0.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ showPassword: false }">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-600">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required autofocus>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Password</label>
                        <div class="relative mt-1.5">
                            <input :type="showPassword ? 'text' : 'password'" name="password"
                                   class="w-full rounded-xl border-slate-200 py-2.5 pr-10 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Konfirmasi Password</label>
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>

                    @if (\Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="terms" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30 mt-0.5" required>
                            <span class="text-xs text-slate-500">
                                Saya menyetujui <a href="{{ route('terms.show') }}" class="text-brand-600 hover:underline" target="_blank">Ketentuan Layanan</a>
                                dan <a href="{{ route('policy.show') }}" class="text-brand-600 hover:underline" target="_blank">Kebijakan Privasi</a>.
                            </span>
                        </label>
                    @endif

                    <button type="submit"
                            class="relative w-full py-3 bg-brand-600 text-white font-semibold text-sm rounded-xl overflow-hidden
                            shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_1px_2px_rgba(79,70,229,0.1)]
                            hover:bg-brand-700 hover:shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_8px_20px_-6px_rgba(79,70,229,0.5)]
                            hover:-translate-y-px active:translate-y-0 transition-all duration-200">
                        Daftar
                    </button>
                </form>

                <p class="text-center text-sm text-slate-400 mt-8">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-brand-600 font-medium hover:text-brand-700 transition">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>