@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .gradient-card {
        transition: transform .2s, box-shadow .2s;
    }
    .gradient-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .gradient-card .card-body {
        position: relative;
        overflow: hidden;
    }
    .gradient-card .card-body::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .gradient-card .card-body .bi {
        position: relative;
        z-index: 1;
    }
    .gradient-card .card-body div {
        position: relative;
        z-index: 1;
    }
    .widget-list-item {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .widget-list-item:last-child {
        border-bottom: none;
    }
    [data-bs-theme="dark"] .widget-list-item {
        border-color: #40444f;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">
        <i class="bi bi-speedometer2"></i> Dashboard Perpustakaan
    </h2>

    {{-- Gradient Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-cokelat);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-book-fill fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Total Buku</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_buku'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-butter);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-people-fill fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Anggota Aktif</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_anggota'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-cokelat-gelap);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-journal-arrow-up fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Sedang Dipinjam</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['sedang_dipinjam'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-cokelat);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Terlambat</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['terlambat'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-butter);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-calendar-check fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Transaksi Hari Ini</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['transaksi_hari_ini'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card text-white gradient-card h-100" style="background-color: var(--color-cokelat-gelap);">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-cash-stack fs-1 me-3 opacity-75"></i>
                    <div>
                        <h6 class="opacity-75 mb-1">Total Denda</h6>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up"></i> Transaksi 6 Bulan Terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="chartTransaksi" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribusi Kategori --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart"></i> Distribusi Kategori Buku
                </div>
                <div class="card-body">
                    <canvas id="chartKategori" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-trophy"></i> Top 5 Buku Populer
                </div>
                <div class="card-body">
                    <canvas id="chartBuku" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bawah: 3 kolom widget --}}
    <div class="row">
        {{-- Widget: Buku Stok Menipis --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-diamond"></i> Stok Menipis</span>
                    <span class="badge bg-dark">{{ $stokMenipis->count() }}</span>
                </div>
                <div class="card-body p-3">
                    @forelse($stokMenipis as $buku)
                        <div class="widget-list-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none fw-medium">
                                    {{ Str::limit($buku->judul, 30) }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $buku->pengarang }}
                                </small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $buku->stok }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-check-circle text-success fs-3"></i>
                            <p class="mb-0 mt-1">Semua stok aman</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Widget: Anggota Teraktif --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person-lines-fill"></i> Anggota Teraktif</span>
                </div>
                <div class="card-body p-3">
                    @forelse($anggotaAktif as $anggota)
                        <div class="widget-list-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('anggota.show', $anggota->id) }}" class="text-decoration-none fw-medium">
                                    {{ $anggota->nama }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    <code>{{ $anggota->kode_anggota }}</code>
                                </small>
                            </div>
                            <span class="badge bg-info text-white">{{ $anggota->transaksis_count }} pinjam</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-people fs-3"></i>
                            <p class="mb-0 mt-1">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Widget: Transaksi Terbaru --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history"></i> Transaksi Terbaru</span>
                </div>
                <div class="card-body p-3">
                    @forelse($recentTransaksi as $trx)
                        <div class="widget-list-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="text-decoration-none fw-medium">
                                    <code>{{ $trx->kode_transaksi }}</code>
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ $trx->anggota->nama }} &middot; {{ Str::limit($trx->buku->judul, 20) }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                {{ $trx->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-inbox fs-3"></i>
                            <p class="mb-0 mt-1">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chartTransaksi'), {
    type: 'line',
    data: {
        labels: @json($chartData->pluck('bulan')),
        datasets: [
            { label: 'Peminjaman', data: @json($chartData->pluck('pinjam')),
              borderColor: '#667eea', backgroundColor: 'rgba(102,126,234,0.1)',
              tension: 0.3, fill: true },
            { label: 'Pengembalian', data: @json($chartData->pluck('kembali')),
              borderColor: '#38ef7d', backgroundColor: 'rgba(56,239,125,0.1)',
              tension: 0.3, fill: true }
        ]
    },
    options: { responsive: true, interaction: { intersect: false, mode: 'index' } }
});

new Chart(document.getElementById('chartBuku'), {
    type: 'doughnut',
    data: {
        labels: @json($bukuPopuler->pluck('judul')),
        datasets: [{
            data: @json($bukuPopuler->pluck('transaksis_count')),
            backgroundColor: ['#667eea','#11998e','#f093fb','#ff6a00','#4facfe']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
});

new Chart(document.getElementById('chartKategori'), {
    type: 'pie',
    data: {
        labels: @json($grafikKategori->pluck('kategori')),
        datasets: [{
            data: @json($grafikKategori->pluck('total')),
            backgroundColor: ['#667eea','#11998e','#f093fb','#ff6a00','#4facfe']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
});
</script>
@endpush
