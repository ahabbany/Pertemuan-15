@extends('layouts.app')
  
@section('title', 'Daftar Transaksi')
  
@push('styles')
<style>
    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 20px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 32px;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-item .timeline-dot {
        position: absolute;
        left: 10px;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid;
        background: #fff;
        z-index: 1;
    }
    [data-bs-theme="dark"] .timeline-item::before {
        background: #40444f;
    }
    [data-bs-theme="dark"] .timeline-item .timeline-dot {
        background: #2d3139;
    }
    .split-panel-card {
        height: 100%;
    }
    .split-panel-card .card-body {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-arrow-left-right"></i>
        Daftar Transaksi Peminjaman
    </h1>
    <div>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-download"></i> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('transaksi.exportPdf') }}"><i class="bi bi-file-pdf"></i> Export PDF</a></li>
                <li><a class="dropdown-item" href="{{ route('transaksi.exportCsv') }}"><i class="bi bi-filetype-csv"></i> Export CSV</a></li>
            </ul>
        </div>
        <a href="{{ route('laporan.index') }}" class="btn btn-primary text-white">
            <i class="bi bi-file-text"></i> Laporan
        </a>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Pinjam Buku
        </a>
    </div>
</div>
 
{{-- Statistik --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat);">
            <div class="card-body">
                <h6 class="opacity-75">Total Transaksi</h6>
                <h2 class="fw-bold">{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-butter);">
            <div class="card-body">
                <h6 class="opacity-75">Sedang Dipinjam</h6>
                <h2 class="fw-bold">{{ $dipinjam->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat-gelap);">
            <div class="card-body">
                <h6 class="opacity-75">Sudah Dikembalikan</h6>
                <h2 class="fw-bold">{{ $dikembalikan->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Left Panel: Sedang Dipinjam (Timeline) --}}
    <div class="col-lg-6 mb-4">
        <div class="card split-panel-card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-journal-arrow-up"></i> Sedang Dipinjam
                    <span class="badge bg-dark ms-2">{{ $dipinjam->count() }}</span>
                </h5>
                @if($dipinjam->where('hari_terlambat', '>', 0)->count() > 0)
                    <span class="badge bg-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ $dipinjam->where('hari_terlambat', '>', 0)->count() }} terlambat
                    </span>
                @endif
            </div>
            <div class="card-body">
                @forelse($dipinjam as $transaksi)
                    <div class="timeline-item">
                        <div class="timeline-dot 
                            @if($transaksi->terlambat > 0) border-danger
                            @else border-warning @endif">
                        </div>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $transaksi->buku->judul }}</strong>
                                <p class="mb-0 small text-muted">
                                    <i class="bi bi-person"></i> {{ $transaksi->anggota->nama }}
                                    (<code>{{ $transaksi->kode_transaksi }}</code>)
                                </p>
                                <p class="mb-0 small">
                                    <i class="bi bi-calendar"></i> Pinjam: {{ $transaksi->tanggal_pinjam->format('d M Y') }}
                                    &middot; <i class="bi bi-calendar-check"></i> Jatuh tempo: {{ $transaksi->tanggal_kembali->format('d M Y') }}
                                </p>
                                @if($transaksi->terlambat > 0)
                                    <p class="mb-0 small text-danger">
                                        <i class="bi bi-exclamation-circle"></i>
                                        Terlambat {{ $transaksi->terlambat }} hari
                                        @if($transaksi->denda > 0) | Denda: Rp {{ number_format($transaksi->denda, 0, ',', '.') }} @endif
                                    </p>
                                @endif
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0">
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                                   class="btn btn-sm btn-primary text-white" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($transaksi->terlambat > 0)
                                    <form action="{{ route('notifications.kirimPeringatan', $transaksi->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                title="Kirim Peringatan"
                                                onclick="return confirm('Kirim peringatan keterlambatan untuk {{ $transaksi->anggota->nama }}?')">
                                            <i class="bi bi-bell"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-2">Tidak ada pinjaman aktif</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Panel: Riwayat Dikembalikan (Compact Table) --}}
    <div class="col-lg-6 mb-4">
        <div class="card split-panel-card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-journal-check"></i> Riwayat Dikembalikan
                    <span class="badge bg-light text-success ms-2">{{ $dikembalikan->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Buku</th>
                                <th>Anggota</th>
                                <th>Kembali</th>
                                <th>Denda</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dikembalikan as $transaksi)
                                <tr>
                                    <td>
                                        <small>{{ Str::limit($transaksi->buku->judul, 25) }}</small>
                                    </td>
                                    <td><small>{{ $transaksi->anggota->nama }}</small></td>
                                    <td>
                                        <small>{{ $transaksi->tanggal_dikembalikan?->format('d M') }}</small>
                                    </td>
                                    <td>
                                        @if($transaksi->denda > 0)
                                            <span class="badge bg-danger">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="badge bg-success">Tepat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                                           class="btn btn-sm btn-primary text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> Belum ada riwayat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection