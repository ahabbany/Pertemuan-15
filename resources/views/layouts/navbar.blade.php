<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="bi bi-book-fill"></i>
            Perpustakaan
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                        <i class="bi bi-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('anggota*') ? 'active' : '' }}" href="{{ route('anggota.index') }}">
                        <i class="bi bi-people"></i> Anggota
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('transaksi*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
                        <i class="bi bi-arrow-left-right"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('notifications*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell"></i> Notifikasi
                        @php $unreadNotif = \App\Models\Notification::where('dibaca', false)->count(); @endphp
                        @if($unreadNotif > 0)
                            <span class="badge bg-danger" id="notif-badge">{{ $unreadNotif }}</span>
                        @else
                            <span class="badge bg-danger d-none" id="notif-badge">0</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                        <span id="darkModeToggle" title="Toggle Dark Mode" style="cursor:pointer;color:inherit;padding:0;line-height:1;display:inline-flex;align-items:center">
                            <i class="bi bi-moon-fill" id="darkModeIcon"></i>
                        </span>
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>