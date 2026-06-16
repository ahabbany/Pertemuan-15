<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Perpustakaan'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { transition: background-color .3s, color .3s; }
        [data-bs-theme="dark"] .navbar { background-color: #1a1d23 !important; }
        [data-bs-theme="dark"] .card { background-color: #2d3139; border-color: #40444f; }
        [data-bs-theme="dark"] .card-header { background-color: #1a1d23 !important; border-color: #40444f; }
        [data-bs-theme="dark"] .table { color: #e4e6ea; }
        [data-bs-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) { background-color: rgba(255,255,255,0.04); }
        [data-bs-theme="dark"] .bg-light { background-color: #2d3139 !important; color: #e4e6ea; }
        [data-bs-theme="dark"] .list-group-item { background-color: #2d3139; border-color: #40444f; }
        [data-bs-theme="dark"] .border-bottom { border-color: #40444f !important; }
        [data-bs-theme="dark"] .border-top { border-color: #40444f !important; }
        [data-bs-theme="dark"] .text-muted { color: #9ca0ab !important; }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select { background-color: #1e2128; border-color: #40444f; color: #e4e6ea; }
        [data-bs-theme="dark"] .form-control:focus, [data-bs-theme="dark"] .form-select:focus { background-color: #1e2128; color: #e4e6ea; }
        [data-bs-theme="dark"] .page-link { background-color: #2d3139; border-color: #40444f; color: #e4e6ea; }
        [data-bs-theme="dark"] .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
        [data-bs-theme="dark"] .page-item.disabled .page-link { background-color: #1a1d23; border-color: #40444f; }
        [data-bs-theme="dark"] .dropdown-menu { background-color: #2d3139; border-color: #40444f; }
        [data-bs-theme="dark"] .dropdown-item { color: #e4e6ea; }
        [data-bs-theme="dark"] .dropdown-item:hover { background-color: #3a3f4b; color: #fff; }
        [data-bs-theme="dark"] .modal-content { background-color: #2d3139; border-color: #40444f; }
        [data-bs-theme="dark"] .btn-outline-primary { color: #8ab4f8; border-color: #8ab4f8; }
        [data-bs-theme="dark"] .btn-outline-primary:hover { background-color: #8ab4f8; color: #000; }
        [data-bs-theme="dark"] .alert-info { background-color: #1a3a4a; border-color: #2a5a6a; color: #b8d8e8; }
        [data-bs-theme="dark"] .alert-danger { background-color: #4a1a1a; border-color: #6a2a2a; color: #e8b8b8; }
        [data-bs-theme="dark"] .alert-success { background-color: #1a3a1a; border-color: #2a5a2a; color: #b8e8b8; }
        [data-bs-theme="dark"] footer.bg-light { background-color: #1a1d23 !important; color: #9ca0ab; }
        [data-bs-theme="dark"] .table-responsive { color: #e4e6ea; }
        [data-bs-theme="dark"] .btn-secondary { background-color: #40444f; border-color: #40444f; }
        [data-bs-theme="dark"] .btn-secondary:hover { background-color: #505560; border-color: #505560; }
        [data-bs-theme="dark"] .btn-info { background-color: #1a6a7a; border-color: #1a6a7a; color: #fff; }
        [data-bs-theme="dark"] .btn-info:hover { background-color: #208a9a; border-color: #208a9a; color: #fff; }
    </style>

    @stack('styles')
</head>
<body>
    @include('layouts.navbar')

    <form class="d-flex" action="{{ route('search') }}" method="GET" style="max-width: 400px; margin: 0 auto; padding: 10px 0;">
        <input class="form-control me-2" type="search" name="q"
               placeholder="Cari buku, anggota, transaksi..." value="{{ request('q') }}">
        <button class="btn btn-outline-primary" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>

    <div class="container my-4">
        @yield('content')
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Dark Mode
    (function() {
        var theme = localStorage.getItem('theme');
        if (!theme) {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.documentElement.classList.toggle('dark', theme === 'dark');
        var icon = document.getElementById('darkModeIcon');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
    })();

    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('#darkModeToggle');
        if (!toggle) return;
        e.stopPropagation();
        var html = document.documentElement;
        var current = html.getAttribute('data-bs-theme');
        var next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        html.classList.toggle('dark', next === 'dark');
        localStorage.setItem('theme', next);
        var icon = document.getElementById('darkModeIcon');
        if (icon) icon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    });

    // Auto-update notifikasi badge setiap 30 detik
    setInterval(function() {
        fetch('{{ route("notifications.unreadCount") }}')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                }
            })
            .catch(function() {});
    }, 30000);

    // Tampilkan flash message dari session
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    });
    @endif
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: @json(session('error')).replace(/\n/g, '<br>'),
        timer: 5000,
        showConfirmButton: true,
        confirmButtonText: 'OK',
    });
    @endif
    @if(session('info'))
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: @json(session('info')),
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    });
    @endif
    @if(session('warning'))
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: @json(session('warning')),
        timer: 4000,
        showConfirmButton: true,
    });
    @endif
    </script>
    @stack('scripts')
</body>
</html>
