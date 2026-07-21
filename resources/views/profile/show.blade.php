@php $title = 'Profil Saya'; @endphp

@if (auth()->user()->isKasir())
    <x-kasir-layout :title="$title">
        @include('profile.partials.content')
    </x-kasir-layout>
@else
    <x-dashboard-layout :title="$title">
        @include('profile.partials.content')
    </x-dashboard-layout>
@endif