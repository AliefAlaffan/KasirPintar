<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-6">
        <div class="w-full max-w-sm" x-data="{ showPassword: false }">

            <div class="flex justify-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-brand-gradient flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-7">
                <h2 class="font-display font-bold text-xl text-slate-800 mb-1.5 text-center">Atur Ulang Password</h2>
                <p class="text-sm text-slate-400 mb-6 text-center">Buat password baru untuk akun Anda.</p>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 rounded-xl text-sm text-rose-600">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <div>
                        <label class="text-sm font-medium text-slate-600">Email</label>
                        <input type="email" name="email" value="{{ old('email', request()->email) }}"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required autofocus>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Password Baru</label>
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
                        <label class="text-sm font-medium text-slate-600">Konfirmasi Password Baru</label>
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                               class="w-full rounded-xl border-slate-200 mt-1.5 py-2.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-brand-gradient text-white font-semibold text-sm rounded-xl hover:shadow-lg hover:shadow-brand-600/20 transition-all">
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>