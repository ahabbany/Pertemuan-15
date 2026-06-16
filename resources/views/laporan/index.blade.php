@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="container py-4">
    <h2>Laporan Transaksi</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control"
                           value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control"
                           value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Anggota</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6>Total Transaksi</h6>
                    <h3>{{ $summary['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h6>Dipinjam</h6>
                    <h3>{{ $summary['dipinjam'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6>Dikembalikan</h6>
                    <h3>{{ $summary['dikembalikan'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6>Total Denda</h6>
                    <h3>Rp {{ number_format($summary['total_denda'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>Kode</th><th>Anggota</th><th>Buku</th>
                        <th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $i => $trx)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->anggota->nama }}</td>
                        <td>{{ $trx->buku->judul }}</td>
                        <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $trx->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                        <td>{{ $trx->denda ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .card-body form, .btn, nav, footer { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection
