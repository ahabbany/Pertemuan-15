@extends('layouts.app')
  
@section('title', 'Daftar Buku')
  
@push('styles')
<style>
    .view-toggle .btn.active-view {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .view-toggle .btn {
        border-color: #dee2e6;
    }
    [data-bs-theme="dark"] .view-toggle .btn {
        border-color: #40444f;
        color: #e4e6ea;
    }
    [data-bs-theme="dark"] .view-toggle .btn.active-view {
        border-color: #0d6efd;
    }
    .buku-table-view { display: none; }
    .buku-card-view { display: flex !important; }
    body[data-view="table"] .buku-table-view { display: block; }
    body[data-view="table"] .buku-card-view { display: none !important; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <div class="d-flex gap-2 align-items-center">
        <div class="btn-group view-toggle" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary active-view" data-view="card" title="Tampilan Kartu">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-view="table" title="Tampilan Tabel">
                <i class="bi bi-list-ul"></i>
            </button>
        </div>
        <a href="{{ route('buku.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 opacity-75">Total Buku</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalBuku }}</h2>
                    </div>
                    <i class="bi bi-book-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-butter);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 opacity-75">Buku Tersedia</h6>
                        <h2 class="mb-0 fw-bold">{{ $bukuTersedia }}</h2>
                    </div>
                    <i class="bi bi-check-circle-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat-gelap);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 opacity-75">Buku Habis</h6>
                        <h2 class="mb-0 fw-bold">{{ $bukuHabis }}</h2>
                    </div>
                    <i class="bi bi-x-circle-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Card View --}}
<div class="row buku-card-view">
    @forelse ($bukus as $buku)
        <div class="col-md-4 mb-3">
            <x-buku-card
                :buku="$buku"
                :show-actions="true"
                :show-checkbox="false"
            />
        </div>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Tidak ada data buku
        </div>
    @endforelse
</div>

{{-- Table View --}}
<div class="card buku-table-view">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukus as $buku)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $buku->kode_buku }}</code></td>
                            <td>
                                <strong>{{ Str::limit($buku->judul, 40) }}</strong>
                            </td>
                            <td><i class="bi bi-person"></i> {{ $buku->pengarang }}</td>
                            <td>
                                <span class="badge bg-{{ $buku->kategori == 'Pemrograman' ? 'primary' : ($buku->kategori == 'Basis Data' ? 'success' : ($buku->kategori == 'Desain Web' ? 'info' : ($buku->kategori == 'Jaringan' ? 'warning' : 'danger'))) }}">
                                    {{ $buku->kategori }}
                                </span>
                            </td>
                            <td>
                                @if($buku->stok > 0)
                                    <span class="badge bg-success">{{ $buku->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($buku->harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-primary text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-judul="{{ $buku->judul }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> Tidak ada data buku
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($bukus->count() > 0)
    <div class="text-center mt-4">
        <p class="text-muted">
            Menampilkan {{ $bukus->count() }} buku
            @isset($kategori)
                dari kategori <strong>{{ $kategori }}</strong>
            @endisset
        </p>
    </div>
@endif

@push('scripts')
<script>
    (function() {
        var savedView = localStorage.getItem('bukuView') || 'card';
        document.body.setAttribute('data-view', savedView);

        document.querySelectorAll('.view-toggle .btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-toggle .btn').forEach(function(b) {
                    b.classList.remove('active-view');
                });
                this.classList.add('active-view');
                var view = this.getAttribute('data-view');
                document.body.setAttribute('data-view', view);
                localStorage.setItem('bukuView', view);
            });
        });

        var activeBtn = document.querySelector('.view-toggle .btn[data-view="' + savedView + '"]');
        if (activeBtn) activeBtn.classList.add('active-view');
    })();

    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const judul = this.getAttribute('data-judul');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus buku "' + judul + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@endsection