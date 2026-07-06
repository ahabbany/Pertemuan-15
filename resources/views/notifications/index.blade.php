@extends('layouts.app')
@section('title', 'Notifikasi')

@push('styles')
<style>
    .notif-feed-item {
        border-radius: 12px;
        transition: transform .15s, box-shadow .15s;
        border-left: 4px solid transparent;
    }
    .notif-feed-item:hover {
        transform: translateX(4px);
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    .notif-feed-item.unread {
        border-left-color: #0d6efd;
    }
    .notif-feed-item.warning {
        border-left-color: #dc3545;
    }
    .notif-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notif-time-badge {
        font-size: 0.75rem;
    }
    [data-bs-theme="dark"] .notif-feed-item.unread {
        background-color: rgba(13,110,253,0.08);
    }
    [data-bs-theme="dark"] .notif-feed-item.warning {
        background-color: rgba(220,53,69,0.08);
    }
    [data-bs-theme="dark"] .notif-feed-item:hover {
        box-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-bell"></i>
        Notifikasi
        @if($unreadCount > 0)
            <span class="badge bg-danger ms-2">{{ $unreadCount }} belum dibaca</span>
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

<div id="notif-new-banner" class="alert alert-info d-none text-center mb-3" style="cursor:pointer" onclick="location.reload()">
    <i class="bi bi-arrow-clockwise"></i> Notifikasi baru tersedia. Klik untuk memuat ulang.
</div>

@forelse($notifications as $notif)
    <div class="card mb-3 notif-feed-item 
        {{ !$notif->dibaca ? 'unread' : '' }}
        {{ $notif->tipe == 'peringatan' ? 'warning' : '' }}">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3">
                {{-- Icon --}}
                <div class="notif-icon-wrap 
                    @if($notif->tipe == 'peringatan') bg-danger bg-opacity-10
                    @else bg-info bg-opacity-10 @endif">
                    @if($notif->tipe == 'peringatan')
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    @else
                        <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-0 {{ !$notif->dibaca ? 'fw-bold' : '' }}">
                                {{ $notif->judul }}
                                @if(!$notif->dibaca)
                                    <span class="badge bg-danger ms-1">Baru</span>
                                @endif
                            </h6>
                            <small class="text-muted notif-time-badge">
                                <i class="bi bi-clock"></i> {{ $notif->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    <p class="mb-2 mt-1">{{ $notif->pesan }}</p>
                    <div class="d-flex gap-2 flex-wrap">
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
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div id="notif-empty" class="text-center text-muted py-5">
        <i class="bi bi-bell-slash" style="font-size: 4rem;"></i>
        <h5 class="mt-3">Tidak ada notifikasi</h5>
        <p>Semua notifikasi akan muncul di sini</p>
    </div>
@endforelse

<div class="mt-3 d-flex justify-content-center">
    {{ $notifications->links() }}
</div>

@push('scripts')
<script>
(function() {
    var firstNotifId = null;
    var firstItem = document.querySelector('.notif-feed-item');
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
            document.querySelectorAll('.notif-feed-item').forEach(function(item) {
                item.classList.remove('unread');
                var title = item.querySelector('h6');
                if (title) title.classList.remove('fw-bold');
                var badge = item.querySelector('h6 .badge');
                if (badge) badge.remove();
                var readBtn = item.querySelector('.btn-mark-read');
                if (readBtn) readBtn.remove();
            });
            btn.style.display = 'none';
            var nb = document.getElementById('notif-badge');
            if (nb) nb.classList.add('d-none');
        }
    })
    .catch(function() {});
});

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
            var item = btn.closest('.notif-feed-item');
            if (item) {
                item.classList.remove('unread');
                var title = item.querySelector('h6');
                if (title) title.classList.remove('fw-bold');
                var badge = item.querySelector('h6 .badge');
                if (badge) badge.remove();
                btn.remove();
            }
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
            var item = btn.closest('.notif-feed-item');
            if (item) item.remove();
            var container = document.querySelector('.notif-feed-item');
            if (!container) {
                var emptyHtml = '<div id="notif-empty" class="text-center text-muted py-5">' +
                    '<i class="bi bi-bell-slash" style="font-size: 4rem;"></i>' +
                    '<h5 class="mt-3">Tidak ada notifikasi</h5>' +
                    '<p>Semua notifikasi akan muncul di sini</p></div>';
                document.querySelector('#notif-new-banner').insertAdjacentHTML('afterend', emptyHtml);
            }
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
