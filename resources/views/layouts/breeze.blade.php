<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @endisset
                @yield('content')
            </main>
        </div>

        <script>
        // Dark Mode
        (function() {
            var theme = localStorage.getItem('theme');
            if (!theme) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.setAttribute('data-bs-theme', theme);
            var icons = [
                document.getElementById('darkModeIconBreeze'),
                document.getElementById('darkModeIconBreezeMobile'),
            ];
            var label = document.getElementById('darkModeLabelBreeze');
            icons.forEach(function(icon) {
                if (icon) icon.className = theme === 'dark' ? 'bi bi-sun-fill text-lg' : 'bi bi-moon-fill text-lg';
            });
            if (label) label.textContent = theme === 'dark' ? 'Mode Terang' : 'Mode Gelap';
        })();

        function toggleDarkBreeze() {
            var html = document.documentElement;
            var current = html.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            html.classList.toggle('dark', next === 'dark');
            localStorage.setItem('theme', next);
            var icons = [
                document.getElementById('darkModeIconBreeze'),
                document.getElementById('darkModeIconBreezeMobile'),
            ];
            var label = document.getElementById('darkModeLabelBreeze');
            icons.forEach(function(icon) {
                if (icon) icon.className = next === 'dark' ? 'bi bi-sun-fill text-lg' : 'bi bi-moon-fill text-lg';
            });
            if (label) label.textContent = next === 'dark' ? 'Mode Terang' : 'Mode Gelap';
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('#darkModeToggleBreeze');
            if (btn) {
                e.stopPropagation();
                toggleDarkBreeze();
            }
            if (e.target.closest('#darkModeToggleBreezeMobile')) {
                toggleDarkBreeze();
            }
        });

        @if(session('success'))
        Swal.fire({ icon:'success', title:'Berhasil', text:@json(session('success')), timer:3000, showConfirmButton:false, toast:true, position:'top-end' });
        @endif
        @if(session('error'))
        Swal.fire({ icon:'error', title:'Gagal', html:@json(session('error')).replace(/\n/g,'<br>'), timer:5000, showConfirmButton:true, confirmButtonText:'OK' });
        @endif
        @if(session('status') === 'profile-updated')
        Swal.fire({ icon:'success', title:'Berhasil', text:'Profil berhasil diperbarui.', timer:3000, showConfirmButton:false, toast:true, position:'top-end' });
        @endif
        </script>
    </body>
</html>
