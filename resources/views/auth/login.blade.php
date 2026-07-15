<x-guest-layout>
    <style>
        @keyframes mockup-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bar-grow {
            from { transform: scaleY(0); }
            to { transform: scaleY(1); }
        }
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
            opacity: 0.5;
            mix-blend-mode: overlay;
            pointer-events: none;
        }
        .mockup-tilt { animation: mockup-float 4s ease-in-out infinite; }
        .anim-fade-up { animation: fade-up 0.7s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .bar-anim { transform-origin: bottom; animation: bar-grow 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
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

            {{-- Mockup dashboard mengambang --}}
            <div class="relative flex-1 flex items-center justify-center">
                <div class="mockup-tilt w-full max-w-sm rounded-2xl bg-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.35)] ring-1 ring-black/5 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-brand-gradient"></div>
                            <div class="h-2 w-16 bg-slate-100 rounded-full"></div>
                        </div>
                        <div class="flex gap-1">
                            <div class="w-2 h-2 rounded-full bg-rose-300"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-300"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-300"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <div class="rounded-lg bg-indigo-50 p-2.5">
                            <div class="h-1.5 w-6 bg-indigo-200 rounded-full mb-2"></div>
                            <div class="h-2.5 w-10 bg-indigo-400 rounded-full"></div>
                        </div>
                        <div class="rounded-lg bg-emerald-50 p-2.5">
                            <div class="h-1.5 w-6 bg-emerald-200 rounded-full mb-2"></div>
                            <div class="h-2.5 w-8 bg-emerald-400 rounded-full"></div>
                        </div>
                        <div class="rounded-lg bg-amber-50 p-2.5">
                            <div class="h-1.5 w-6 bg-amber-200 rounded-full mb-2"></div>
                            <div class="h-2.5 w-9 bg-amber-400 rounded-full"></div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-slate-50 p-3 flex items-end gap-1.5 h-20">
                        @php $heights = [40, 65, 50, 80, 60, 95, 70]; @endphp
                        @foreach ($heights as $i => $h)
                            <div class="flex-1 bg-brand-gradient rounded-t bar-anim" style="height: {{ $h }}%; animation-delay: {{ 0.3 + $i * 0.07 }}s;"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative anim-fade-up" style="animation-delay: 0.15s">
                <p class="text-white/50 text-xs font-medium uppercase tracking-widest mb-3">Dipercaya untuk operasional harian</p>
                <h1 class="font-display font-bold text-white text-[1.75rem] leading-snug mb-3 max-w-md">
                    Setiap transaksi, stok, dan laporan — dalam satu tampilan yang jernih.
                </h1>
                <div class="flex items-center gap-2 mt-6">
                    <div class="flex -space-x-2">
                        @foreach (['A', 'M', 'K'] as $i => $letter)
                            <div class="w-7 h-7 rounded-full bg-white/15 ring-2 ring-indigo-600 flex items-center justify-center text-white text-xs font-semibold">{{ $letter }}</div>
                        @endforeach
                    </div>
                    <p class="text-white/60 text-xs">Admin · Manajer · Kasir — satu sistem</p>
                </div>
            </div>
        </div>

        {{-- ============ PANEL KANAN: Form ============ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-sm anim-fade-up" style="animation-delay: 0.1s"
                 x-data="{ showPassword: false, focusEmail: false, focusPass: false, emailVal: '{{ old('email') }}' }">

                <div class="flex items-center gap-2.5 mb-10 lg:hidden">
                    <div class="w-9 h-9 rounded-xl bg-brand-gradient flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="font-display font-bold text-slate-800 text-lg">KasirPintar</span>
                </div>

                <h2 class="font-display font-bold text-[1.7rem] text-slate-900 mb-1.5 tracking-tight">Masuk ke akun Anda</h2>
                <p class="text-sm text-slate-400 mb-9">Isi kredensial di bawah untuk melanjutkan.</p>

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

                @if (session('status'))
                    <div class="mb-5 p-3.5 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="relative">
                        <input type="email" name="email" id="email" x-model="emailVal"
                               @focus="focusEmail = true" @blur="focusEmail = false"
                               class="peer w-full rounded-xl border-slate-200 pt-5 pb-2 px-4 text-slate-800 focus:ring-[3px] focus:ring-brand-500/15 focus:border-brand-500 transition-all duration-150"
                               required autofocus>
                        <label for="email"
                               class="absolute left-4 transition-all duration-150 pointer-events-none text-slate-400"
                               :class="(focusEmail || emailVal) ? 'top-1.5 text-[10px] font-semibold text-brand-600 tracking-wide' : 'top-3.5 text-sm'">
                            EMAIL
                        </label>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5 h-4">
                            <span></span>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium transition">Lupa password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                   @focus="focusPass = true" @blur="focusPass = false"
                                   class="peer w-full rounded-xl border-slate-200 pt-5 pb-2 px-4 pr-11 text-slate-800 focus:ring-[3px] focus:ring-brand-500/15 focus:border-brand-500 transition-all duration-150"
                                   required>
                            <label for="password"
                                   class="absolute left-4 transition-all duration-150 pointer-events-none text-slate-400"
                                   :class="focusPass ? 'top-1.5 text-[10px] font-semibold text-brand-600 tracking-wide' : 'top-3.5 text-sm'">
                                PASSWORD
                            </label>
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

                    <label class="flex items-center gap-2 cursor-pointer group select-none">
                        <input type="checkbox" name="remember"
                               class="rounded border-slate-300 text-brand-600 focus:ring-brand-500/30 transition">
                        <span class="text-sm text-slate-500 group-hover:text-slate-700 transition">Ingat saya di perangkat ini</span>
                    </label>

                    <button type="submit"
                            class="relative w-full py-3 bg-brand-600 text-white font-semibold text-sm rounded-xl overflow-hidden
                            shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_1px_2px_rgba(79,70,229,0.1)]
                            hover:bg-brand-700 hover:shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_8px_20px_-6px_rgba(79,70,229,0.5)]
                            hover:-translate-y-px active:translate-y-0 active:shadow-inner
                            transition-all duration-200">
                        Masuk
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-slate-400 mt-9">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-brand-600 font-medium hover:text-brand-700 transition">Daftar di sini</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>