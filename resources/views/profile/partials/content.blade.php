<p class="text-xs font-semibold text-brand-600 uppercase tracking-widest mb-1">Akun</p>
<h2 class="font-display font-bold text-2xl text-slate-800 mb-6">Profil Saya</h2>

<div class="max-w-3xl space-y-6">
    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        @livewire('profile.update-profile-information-form')
    @endif

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        @livewire('profile.update-password-form')
    @endif

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        @livewire('profile.two-factor-authentication-form')
    @endif

    @livewire('profile.logout-other-browser-sessions-form')

    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        @livewire('profile.delete-user-form')
    @endif
</div>g