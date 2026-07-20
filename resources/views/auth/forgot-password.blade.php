<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-6">
        <div class="w-full max-w-sm">

            <div class="flex justify-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-brand-gradient flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7 text-center">
                <h2 class="font-display font-bold text-xl text-slate-800 mb-1.5">Lupa Password?</h2>
                <p class="text-sm text-slate-400 mb-6">Masukkan email Anda, kami akan kirimkan link untuk atur ulang password.</p>

                @if (session('status'))
                    <div class="mb-5 p-3.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm text-left">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 rounded-xl text-sm text-rose-600 text-left">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-left">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-slate-600">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required autofocus>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-brand-gradient text-white font-semibold text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 transition-all">
                        Kirim Link Reset Password
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-slate-400 mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-brand-600 font-medium hover:text-brand-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Login
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>