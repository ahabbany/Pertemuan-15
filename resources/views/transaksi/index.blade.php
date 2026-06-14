@extends('layouts.app')
 
@section('title', 'Daftar Transaksi')
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-arrow-left-right"></i>
        Daftar Transaksi Peminjaman
    </h1>
    <div>
        <a href="{{ route('transaksi.laporan') }}" class="btn btn-info text-white">
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
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Transaksi</h6>
                <h2>{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted">Sedang Dipinjam</h6>
                <h2>{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Sudah Dikembalikan</h6>
                <h2>{{ $transaksis->where('status', 'Dikembalikan')->count() }}</h2>
            </div>
        </div>
    </div>
</div>
 
{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $transaksi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                        <td>{{ $transaksi->anggota->nama }}</td>
                        <td>{{ $transaksi->buku->judul }}</td>
                        <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        <td>{{ $transaksi->tanggal_kembali->format('d M Y') }}</td>
                        <td>
                            @if($transaksi->status == 'Dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            @if($transaksi->terlambat > 0)
                                <span class="badge bg-danger">{{ $transaksi->terlambat }} hari</span>
                            @else
                                <span class="badge bg-light text-muted">Tepat waktu</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection