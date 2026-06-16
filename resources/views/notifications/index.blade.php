@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-bell"></i>
        Notifikasi
        @if($unreadCount > 0)
            <span class="badge bg-danger">{{ $unreadCount }} belum dibaca</span>
        @endif
    </h1>
    <div>
        @if($unreadCount > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="button" class="btn btn-outline-primary" id="btn-mark-all-read">
                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="notif-list-container">
            <div id="notif-new-banner" class="alert alert-info d-none text-center mb-3" style="cursor:pointer" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Notifikasi baru tersedia. Klik untuk memuat ulang.
            </div>

            @forelse($notifications as $notif)
            <div class="d-flex align-items-start border-bottom pb-3 mb-3 notif-item {{ $notif->dibaca ? '' : 'bg-light rounded p-2' }}">
                <div class="me-3">
                    @if($notif->tipe == 'peringatan')
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-3"></i>
                    @else
                        <i class="bi bi-info-circle-fill text-info fs-3"></i>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1 {{ $notif->dibaca ? '' : 'fw-bold' }}">
                            {{ $notif->judul }}
                            @if(!$notif->dibaca)
                                <span class="badge bg-danger ms-1">Baru</span>
                            @endif
                        </h6>
                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $notif->pesan }}</p>
                    <div class="d-flex gap-2">
                        @if($notif->transaksi)
                            <a href="{{ route('transaksi.show', $notif->transaksi_id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Lihat Transaksi
                            </a>
                        @endif
                        @if(!$notif->dibaca)
                            <button type="button" class="btn btn-sm btn-outline-success btn-mark-read" data-id="{{ $notif->id }}">
                                <i class="bi bi-check"></i> Tandai Dibaca
                            </button>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-notif" data-id="{{ $notif->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div id="notif-empty" class="text-center text-muted py-5">
                <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                <p class="mt-2">Tidak ada notifikasi</p>
            </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
// === Cek notifikasi baru setiap 30 detik ===
(function() {
    var firstNotifId = null;
    var firstItem = document.querySelector('.notif-item');
    if (firstItem) {
        var delBtn = firstItem.querySelector('.btn-delete-notif');
        if (delBtn) firstNotifId = delBtn.getAttribute('data-id');
    }

    setInterval(function() {
        fetch('{{ route("notifications.json") }}')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var banner = document.getElementById('notif-new-banner');
                var hasItems = data.notifications.length > 0;

                if (banner && firstNotifId && hasItems) {
                    var latestId = data.notifications[0].id;
                    if (latestId != firstNotifId) {
                        banner.classList.remove('d-none');
                    }
                }

                var headerBadge = document.querySelector('h1 .badge');
                if (headerBadge) {
                    if (data.unread_count > 0) {
                        headerBadge.textContent = data.unread_count + ' belum dibaca';
                        headerBadge.style.display = '';
                    } else {
                        headerBadge.style.display = 'none';
                    }
                }

                var markAllBtn = document.querySelector('form[action="{{ route("notifications.markAllRead") }}"]');
                if (markAllBtn) {
                    markAllBtn.style.display = data.unread_count > 0 ? '' : 'none';
                }
            })
            .catch(function() {});
    }, 30000);
})();

// === AJAX: Tandai Semua Dibaca ===
document.addEventListener('click', function(e) {
    var btn = e.target.closest('#btn-mark-all-read');
    if (!btn) return;

    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("notifications.markAllRead") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            document.querySelectorAll('.notif-item').forEach(function(item) {
                item.classList.remove('bg-light', 'rounded', 'p-2');
                var title = item.querySelector('h6');
                if (title) title.classList.remove('fw-bold');
                var badge = item.querySelector('h6 .badge');
                if (badge) badge.remove();
                var readBtn = item.querySelector('.btn-mark-read');
                if (readBtn) readBtn.remove();
            });
            btn.style.display = 'none';
            // Refresh global badge
            var nb = document.getElementById('notif-badge');
            if (nb) nb.classList.add('d-none');
        }
    })
    .catch(function() {});
});

// === AJAX: Tandai Dibaca ===
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-mark-read');
    if (!btn) return;

    var id = btn.getAttribute('data-id');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/notifications/' + id + '/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var item = btn.closest('.notif-item');
            if (item) {
                item.classList.remove('bg-light', 'rounded', 'p-2');
                var title = item.querySelector('h6');
                if (title) title.classList.remove('fw-bold');
                var badge = item.querySelector('h6 .badge');
                if (badge) badge.remove();
                btn.remove();
            }
            // Refresh global badge
            fetch('{{ route("notifications.unreadCount") }}')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (d.count > 0) {
                            badge.textContent = d.count;
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                    }
                });
        }
    })
    .catch(function() {});
});

// === AJAX: Hapus Notifikasi ===
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-delete-notif');
    if (!btn) return;

    if (!confirm('Hapus notifikasi ini?')) return;

    var id = btn.getAttribute('data-id');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/notifications/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var item = btn.closest('.notif-item');
            if (item) item.remove();
            // Refresh global badge
            fetch('{{ route("notifications.unreadCount") }}')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (d.count > 0) {
                            badge.textContent = d.count;
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                    }
                });
        }
    })
    .catch(function() {});
});
</script>
@endpush
@endsection
