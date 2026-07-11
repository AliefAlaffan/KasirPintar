<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - KasirPintar' : 'KasirPintar' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            #page-loader {
                position: fixed; top: 0; left: 0; height: 3px; width: 0%;
                background: linear-gradient(90deg, #3b82f6, #60a5fa);
                z-index: 100; transition: width 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <x-toast />
        <div id="page-loader"></div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const loader = document.getElementById('page-loader');
                document.querySelectorAll('a[href]:not([target="_blank"])').forEach(link => {
                    link.addEventListener('click', () => {
                        loader.style.width = '80%';
                    });
                });
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', () => {
                        loader.style.width = '80%';
                    });
                });
            });
        </script>
    </body>
</html>
