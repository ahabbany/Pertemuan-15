<aside class="sidebar" id="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3">
        <div class="d-flex align-items-center gap-2">
            <button type="button" id="sidebarToggle" class="btn btn-link text-white p-1 border-0 sidebar-toggle-btn">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a class="sidebar-brand text-white text-decoration-none fw-bold fs-5 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-book-fill"></i>
                <span>Perpustakaan</span>
            </a>
        </div>
        <button type="button" id="sidebarClose" class="btn btn-link text-white p-0 border-0 sidebar-close-btn">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>
    <hr class="my-0 opacity-25">

    <div class="sidebar-body">
        <ul class="sidebar-nav list-unstyled mb-0">
            {{-- Dashboard --}}
            <li>
                <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Buku --}}
            <li>
                <a class="sidebar-link sidebar-parent {{ Request::is('buku*') || Request::is('kategori*') ? 'active' : '' }}" data-toggle="submenu" data-target="#subBuku" role="button">
                    <i class="bi bi-book"></i>
                    <span>Buku</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-arrow"></i>
                </a>
                <div class="sidebar-submenu {{ Request::is('buku*') || Request::is('kategori*') ? 'show' : '' }}" id="subBuku">
                    <a class="sidebar-sublink {{ request()->routeIs('buku.index') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                        <i class="bi bi-list"></i> Daftar Buku
                    </a>
                    <a class="sidebar-sublink {{ request()->routeIs('buku.create') ? 'active' : '' }}" href="{{ route('buku.create') }}">
                        <i class="bi bi-plus-circle"></i> Tambah Buku
                    </a>
                    <a class="sidebar-sublink {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        <i class="bi bi-tags"></i> Kategori
                    </a>
                </div>
            </li>

            {{-- Anggota --}}
            <li>
                <a class="sidebar-link sidebar-parent {{ Request::is('anggota*') ? 'active' : '' }}" data-toggle="submenu" data-target="#subAnggota" role="button">
                    <i class="bi bi-people"></i>
                    <span>Anggota</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-arrow"></i>
                </a>
                <div class="sidebar-submenu {{ Request::is('anggota*') ? 'show' : '' }}" id="subAnggota">
                    <a class="sidebar-sublink {{ request()->routeIs('anggota.index') ? 'active' : '' }}" href="{{ route('anggota.index') }}">
                        <i class="bi bi-list"></i> Daftar Anggota
                    </a>
                    <a class="sidebar-sublink {{ request()->routeIs('anggota.create') ? 'active' : '' }}" href="{{ route('anggota.create') }}">
                        <i class="bi bi-plus-circle"></i> Tambah Anggota
                    </a>
                </div>
            </li>

            {{-- Transaksi --}}
            <li>
                <a class="sidebar-link sidebar-parent {{ Request::is('transaksi*') ? 'active' : '' }}" data-toggle="submenu" data-target="#subTransaksi" role="button">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Transaksi</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-arrow"></i>
                </a>
                <div class="sidebar-submenu {{ Request::is('transaksi*') ? 'show' : '' }}" id="subTransaksi">
                    <a class="sidebar-sublink {{ request()->routeIs('transaksi.index') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
                        <i class="bi bi-list"></i> Semua Transaksi
                    </a>
                    <a class="sidebar-sublink {{ request()->routeIs('transaksi.create') ? 'active' : '' }}" href="{{ route('transaksi.create') }}">
                        <i class="bi bi-plus-circle"></i> Tambah Transaksi
                    </a>
                </div>
            </li>

            {{-- Notifikasi --}}
            <li>
                <a class="sidebar-link {{ request()->routeIs('notifications*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <i class="bi bi-bell"></i>
                    <span>
                        Notifikasi
                        @php $unreadNotif = \App\Models\Notification::where('dibaca', false)->count(); @endphp
                        @if($unreadNotif > 0)
                            <span class="badge bg-danger ms-1" id="notif-badge">{{ $unreadNotif }}</span>
                        @else
                            <span class="badge bg-danger d-none ms-1" id="notif-badge">0</span>
                        @endif
                    </span>
                </a>
            </li>

            {{-- Laporan --}}
            <li>
                <a class="sidebar-link {{ request()->routeIs('laporan*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-file-text"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <hr class="my-0 opacity-25">
        <div class="d-flex align-items-center gap-2 px-3 py-2">
            <span id="darkModeToggle" title="Toggle Dark Mode" style="cursor:pointer;color:rgba(255,255,255,0.75);display:inline-flex;align-items:center;padding:6px;border-radius:6px;" class="sidebar-dark-toggle">
                <i class="bi bi-moon-fill fs-5" id="darkModeIcon"></i>
            </span>
            <div class="dropdown flex-grow-1">
                <button class="btn btn-link text-white opacity-75 text-decoration-none dropdown-toggle d-flex align-items-center gap-2 w-100 px-2 py-1 border-0" type="button" data-bs-toggle="dropdown" style="text-align:left;">
                    <i class="bi bi-person-circle fs-5"></i>
                    <small class="text-truncate">{{ Auth::user()->name }}</small>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-100">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>

