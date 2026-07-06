<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Perpustakaan'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --color-cokelat: #1A237E;
            --color-butter: #FF6F00;
            --color-cokelat-gelap: #0D47A1;
            --sidebar-width: 260px;
        }
        [data-bs-theme="dark"] {
            --color-cokelat: #0D1B6E;
            --color-butter: #E65100;
            --color-cokelat-gelap: #072A6E;
        }

        body {
            transition: background-color .3s, color .3s;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1040;
            background-color: var(--color-cokelat);
            display: flex;
            flex-direction: column;
            transform: translateX(0);
            transition: transform .3s ease;
        }
        .sidebar.collapsed {
            transform: translateX(100%);
        }

        .sidebar-body {
            flex: 1;
            overflow-y: auto;
        }
        .sidebar-body::-webkit-scrollbar { width: 4px; }
        .sidebar-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-left: 3px solid transparent;
            transition: background-color .2s, border-color .2s;
            color: rgba(255,255,255,0.7);
        }
        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar-link.active {
            background-color: rgba(255,255,255,0.15);
            color: #fff;
            border-left-color: #fff;
        }
        [data-bs-theme="dark"] .sidebar-link:hover {
            background-color: rgba(255,255,255,0.08);
        }

        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Sidebar parent & arrow */
        .sidebar-parent {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background-color .2s, border-color .2s;
        }
        .sidebar-parent:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar-parent.active {
            background-color: rgba(255,255,255,0.15);
            color: #fff;
            border-left-color: #fff;
        }
        .sidebar-arrow {
            transition: transform .25s ease;
            font-size: .7rem;
        }
        .sidebar-parent.expanded .sidebar-arrow {
            transform: rotate(180deg);
        }

        /* Submenu */
        .sidebar-submenu {
            max-height: 0;
            overflow: hidden;
            background-color: rgba(0,0,0,0.15);
            transition: max-height .3s ease;
        }
        .sidebar-submenu.show {
            max-height: 300px;
        }
        .sidebar-sublink {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px 8px 44px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: .9rem;
            transition: background-color .2s, color .2s;
            border-left: 3px solid transparent;
        }
        .sidebar-sublink:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
        }
        .sidebar-sublink.active {
            color: #fff;
            background-color: rgba(255,255,255,0.12);
            border-left-color: rgba(255,255,255,0.7);
        }
        .sidebar-sublink i {
            font-size: .85rem;
            width: 16px;
            text-align: center;
        }

        .sidebar-dark-toggle:hover {
            background-color: rgba(255,255,255,0.15) !important;
        }

        /* Main content */
        .main-wrapper {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-right .3s ease;
        }
        .main-wrapper.sidebar-collapsed {
            margin-right: 0;
        }

        /* Top bar */
        .top-bar {
            background-color: var(--color-cokelat);
            position: sticky;
            top: 0;
            z-index: 1025;
        }
        .top-bar .form-control {
            background-color: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.2);
            color: #fff;
        }
        .top-bar .form-control::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .top-bar .form-control:focus {
            background-color: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            color: #fff;
            box-shadow: none;
        }
        .top-bar .btn-outline-light:hover {
            background-color: rgba(255,255,255,0.2);
        }
        [data-bs-theme="dark"] .top-bar {
            background-color: #0a0e1a;
        }
        [data-bs-theme="dark"] .sidebar {
            background-color: #0a0e1a;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }
        .sidebar-overlay.show {
            display: block;
        }

        /* Sidebar reopen button (floating on right edge when collapsed) */
        .sidebar-reopen {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1035;
            width: 36px;
            height: 48px;
            background-color: var(--color-cokelat);
            color: #fff;
            border: none;
            border-radius: 8px 0 0 8px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: -2px 0 8px rgba(0,0,0,0.15);
            transition: right .3s ease, opacity .2s;
            padding: 0;
        }
        @media (max-width: 767.98px) {
            .sidebar-reopen { display: none !important; }
        }
        .sidebar-reopen:hover {
            opacity: 0.9;
        }
        .sidebar-reopen.show {
            display: flex;
        }
        [data-bs-theme="dark"] .sidebar-reopen {
            background-color: #0a0e1a;
        }

        /* Sidebar toggle inside sidebar */
        .sidebar-toggle-btn {
            opacity: 0.8;
            transition: opacity .2s;
        }
        .sidebar-toggle-btn:hover {
            opacity: 1;
        }
        .sidebar-close-btn {
            display: none;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            .main-wrapper {
                margin-right: 0 !important;
            }
            .sidebar {
                transform: translateX(100%);
                width: 280px;
                box-shadow: -4px 0 20px rgba(0,0,0,0.3);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-close-btn {
                display: inline-flex;
            }
        }

        /* Existing dark mode overrides */
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
        [data-bs-theme="dark"] .btn-primary { background-color: #1565C0; border-color: #1565C0; }
        [data-bs-theme="dark"] .btn-primary:hover { background-color: #1976D2; border-color: #1976D2; }
    </style>

    @stack('styles')
</head>
<body>
    @include('layouts.navbar')

    <div class="main-wrapper" id="mainWrapper">
        <div class="top-bar d-flex align-items-center gap-2 px-3 py-2">
            <button id="sidebarToggleMobile" class="btn btn-link text-white p-1 border-0 d-md-none" type="button">
                <i class="bi bi-list fs-4"></i>
            </button>
            <form class="d-flex flex-grow-1" action="{{ route('search') }}" method="GET" style="max-width:400px;">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="search" name="q"
                           placeholder="Cari buku, anggota, transaksi..." value="{{ request('q') }}">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="container-fluid my-4 flex-grow-1">
            @yield('content')
        </div>

        @include('layouts.footer')
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <button id="sidebarReopen" class="sidebar-reopen" type="button" title="Buka sidebar">
        <i class="bi bi-chevron-left fs-5"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Dark Mode
    (function() {
        try { var saved = localStorage.getItem('theme'); } catch(e) {}
        var theme = saved;
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

    function toggleDarkMode() {
        var html = document.documentElement;
        var current = html.getAttribute('data-bs-theme');
        var next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        html.classList.toggle('dark', next === 'dark');
        try { localStorage.setItem('theme', next); } catch(e) {}
        var icon = document.getElementById('darkModeIcon');
        if (icon) icon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('#darkModeToggle')) {
            e.stopPropagation();
            toggleDarkMode();
        }
    });

    // Submenu Toggle
    document.querySelectorAll('[data-toggle="submenu"]').forEach(function(parent) {
        parent.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('data-target'));
            if (target) {
                target.classList.toggle('show');
                this.classList.toggle('expanded');
            }
        });
    });

    // Sidebar Toggle
    (function() {
        var sidebar = document.getElementById('sidebar');
        var wrapper = document.getElementById('mainWrapper');
        var overlay = document.getElementById('sidebarOverlay');
        var toggleBtn = document.getElementById('sidebarToggle');
        var toggleMobileBtn = document.getElementById('sidebarToggleMobile');
        var closeBtn = document.getElementById('sidebarClose');
        var reopenBtn = document.getElementById('sidebarReopen');
        var isMobile = function() { return window.innerWidth < 768; };

        function updateReopenButton() {
            if (!reopenBtn) return;
            if (!isMobile() && sidebar.classList.contains('collapsed')) {
                reopenBtn.classList.add('show');
            } else {
                reopenBtn.classList.remove('show');
            }
        }

        function openSidebar() {
            if (isMobile()) {
                sidebar.classList.add('open');
                overlay.classList.add('show');
            } else {
                sidebar.classList.remove('collapsed');
                wrapper.classList.remove('sidebar-collapsed');
            }
            updateReopenButton();
        }

        function closeSidebar() {
            if (isMobile()) {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            } else {
                sidebar.classList.add('collapsed');
                wrapper.classList.add('sidebar-collapsed');
            }
            updateReopenButton();
        }

        function toggleSidebar() {
            if (isMobile()) {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            } else {
                if (sidebar.classList.contains('collapsed')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }
        }

        if (toggleBtn) toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });

        if (toggleMobileBtn) toggleMobileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });

        if (closeBtn) closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeSidebar();
        });

        if (overlay) overlay.addEventListener('click', function() {
            closeSidebar();
        });

        if (reopenBtn) reopenBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            openSidebar();
        });

        // Init state
        updateReopenButton();
    })();

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
