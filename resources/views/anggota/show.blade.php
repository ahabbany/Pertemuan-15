@extends('layouts.app')
 
@section('title', $anggota->nama)
 
@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
                <li class="breadcrumb-item active">{{ $anggota->nama }}</li>
            </ol>
        </nav>
    </div>
</div>
 
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person"></i>
                    Detail Anggota
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if ($anggota->jenis_kelamin == 'Laki-laki')
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                    @else
                        <i class="bi bi-person-circle text-danger" style="font-size: 5rem;"></i>
                    @endif
                    <h3 class="mt-2">{{ $anggota->nama }}</h3>
                    @if ($anggota->status == 'Aktif')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i> Anggota Aktif
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-x-circle"></i> Nonaktif
                        </span>
                    @endif
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td width="200" class="fw-bold">
                            <i class="bi bi-upc text-success"></i> Kode Anggota
                        </td>
                        <td>: <code>{{ $anggota->kode_anggota }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-envelope text-success"></i> Email
                        </td>
                        <td>: {{ $anggota->email }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-telephone text-success"></i> Telepon
                        </td>
                        <td>: {{ $anggota->telepon }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-geo-alt text-success"></i> Alamat
                        </td>
                        <td>: {{ $anggota->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar text-success"></i> Tanggal Lahir
                        </td>
                        <td>: {{ $anggota->tanggal_lahir->format('d F Y') }} ({{ $anggota->umur }} tahun)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-gender-ambiguous text-success"></i> Jenis Kelamin
                        </td>
                        <td>: {{ $anggota->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-briefcase text-success"></i> Pekerjaan
                        </td>
                        <td>: {{ $anggota->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar-check text-success"></i> Tanggal Daftar
                        </td>
                        <td>: {{ $anggota->tanggal_daftar->format('d F Y') }} ({{ $anggota->lama_anggota }} hari)</td>
                    </tr>
                </table>
                
                <hr>
                <div class="row text-muted small">
                    <div class="col-md-6">
                        <i class="bi bi-clock"></i> 
                        Ditambahkan: {{ $anggota->created_at }}
                    </div>
                    <div class="col-md-6 text-end">
                        <i class="bi bi-clock-history"></i> 
                        Terakhir Update: {{ $anggota->updated_at }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Aksi
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Anggota
                </a>
                <a href="{{ route('anggota.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <hr>
                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Anggota
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">
                    <i class="bi bi-qr-code"></i> QR Code Anggota
                </h6>
            </div>
            <div class="card-body text-center">
                {!! QrCode::size(180)->generate($anggota->kode_anggota) !!}
                <p class="mt-2 mb-0 text-muted small">
                    <code>{{ $anggota->kode_anggota }}</code>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Peminjaman --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i>
                    Riwayat Peminjaman
                </h5>
            </div>
            <div class="card-body">
                {{-- Statistik Peminjaman --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Pinjaman</h6>
                                <h3>{{ $stats['total_pinjam'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Sedang Dipinjam</h6>
                                <h3>{{ $stats['sedang_dipinjam'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Sudah Dikembalikan</h6>
                                <h3>{{ $stats['sudah_kembali'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Denda</h6>
                                <h3>Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Riwayat --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Tgl Dikembalikan</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatTransaksis as $trx)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $trx->kode_transaksi }}</code></td>
                                <td>{{ $trx->buku->judul }}</td>
                                <td>{{ $trx->tanggal_pinjam->format('d M Y') }}</td>
                                <td>{{ $trx->tanggal_kembali->format('d M Y') }}</td>
                                <td>{{ $trx->tanggal_dikembalikan?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if($trx->status == 'Dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                    @if($trx->terlambat > 0)
                                        <span class="badge bg-danger">{{ $trx->terlambat }} hari</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trx->denda > 0)
                                        Rp {{ number_format($trx->denda, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="bi bi-inbox"></i>
                                    Belum ada riwayat peminjaman
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