@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">{{ $transaksi->kode_transaksi }}</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i>
                    Informasi Transaksi
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Kode Transaksi</td>
                        <td>: <code>{{ $transaksi->kode_transaksi }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Pinjam</td>
                        <td>: {{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Kembali</td>
                        <td>: {{ $transaksi->tanggal_kembali->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Dikembalikan</td>
                        <td>: {{ $transaksi->tanggal_dikembalikan ? $transaksi->tanggal_dikembalikan->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status</td>
                        <td>:
                            @if($transaksi->status == 'Dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Denda</td>
                        <td>: Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                    </tr>
                    @if($transaksi->keterangan)
                    <tr>
                        <td class="fw-bold">Keterangan</td>
                        <td>: {{ $transaksi->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i>
                    Informasi Anggota & Buku
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">Nama Anggota</td>
                        <td>: {{ $transaksi->anggota->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kode Anggota</td>
                        <td>: <code>{{ $transaksi->anggota->kode_anggota }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Judul Buku</td>
                        <td>: {{ $transaksi->buku->judul }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kode Buku</td>
                        <td>: <code>{{ $transaksi->buku->kode_buku }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Durasi Peminjaman</td>
                        <td>: {{ $transaksi->durasi_peminjaman }} hari</td>
                    </tr>
                    @if($transaksi->terlambat > 0)
                    <tr>
                        <td class="fw-bold text-danger">Terlambat</td>
                        <td>: <span class="text-danger fw-bold">{{ $transaksi->terlambat }} hari</span></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if($transaksi->status === 'Dipinjam')
<div class="d-flex justify-content-between">
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <button type="button" class="btn btn-success" id="btn-kembalikan">
        <i class="bi bi-arrow-return-left"></i> Kembalikan Buku
    </button>
    <form id="form-kembalikan" action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" class="d-none">
        @csrf
        @method('PATCH')
    </form>
</div>
@else
<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    @if($transaksi->tanggal_dikembalikan <= $transaksi->tanggal_kembali)
        <div class="alert alert-success mb-0">
            <i class="bi bi-check-circle"></i> Dikembalikan tepat waktu pada
            {{ $transaksi->tanggal_dikembalikan->format('d M Y') }}
        </div>
    @else
        <div class="alert alert-warning mb-0">
            <i class="bi bi-exclamation-triangle"></i> Terlambat dikembalikan!
            Denda: Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
        </div>
    @endif
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('btn-kembalikan')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        text: 'Apakah Anda yakin ingin mengembalikan buku ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-kembalikan').submit();
        }
    });
});
</script>
@endpush
