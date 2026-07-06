@extends('layouts.app')
  
@section('title', 'Daftar Anggota')
  
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
    .anggota-table-view { display: none; }
    .anggota-card-view { display: none !important; }
    body[data-anggota-view="table"] .anggota-table-view { display: block; }
    body[data-anggota-view="card"] .anggota-card-view { display: flex !important; }
    .anggota-card .card {
        transition: transform .2s, box-shadow .2s;
    }
    .anggota-card .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .anggota-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-people"></i>
        Daftar Anggota
    </h1>
    <div class="d-flex gap-2 align-items-center">
        <div class="btn-group view-toggle" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary active-view" data-view="table" title="Tampilan Tabel">
                <i class="bi bi-list-ul"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" data-view="card" title="Tampilan Kartu">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
        </div>
        <a href="{{ route('anggota.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Anggota
        </a>
    </div>
</div>

{{-- Statistik --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="opacity-75">Total Anggota</h6>
                        <h2 class="fw-bold">{{ $totalAnggota }}</h2>
                    </div>
                    <i class="bi bi-people-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-butter);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="opacity-75">Anggota Aktif</h6>
                        <h2 class="fw-bold">{{ $anggotaAktif }}</h2>
                    </div>
                    <i class="bi bi-person-check-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white" style="background-color: var(--color-cokelat-gelap);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="opacity-75">Anggota Nonaktif</h6>
                        <h2 class="fw-bold">{{ $anggotaNonaktif }}</h2>
                    </div>
                    <i class="bi bi-person-x-fill opacity-50" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
 
{{-- Tabel Anggota --}}
<div class="card anggota-table-view">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggotas as $anggota)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <code>{{ $anggota->kode_anggota }}</code>
                            </td>
                            <td>
                                <strong>{{ $anggota->nama }}</strong>
                            </td>
                            <td>
                                <i class="bi bi-envelope"></i>
                                {{ $anggota->email }}
                            </td>
                            <td>
                                <i class="bi bi-telephone"></i>
                                {{ $anggota->telepon }}
                            </td>
                            <td>
                                @if ($anggota->jenis_kelamin == 'Laki-laki')
                                    <i class="bi bi-gender-male text-primary"></i>
                                @else
                                    <i class="bi bi-gender-female text-danger"></i>
                                @endif
                                {{ $anggota->jenis_kelamin }}
                            </td>
                            <td>
                                @if ($anggota->status == 'Aktif')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('anggota.show', $anggota->id) }}" 
                                       class="btn btn-sm btn-primary text-white"
                                       title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('anggota.edit', $anggota->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('anggota.destroy', $anggota->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-delete-anggota"
                                                data-nama="{{ $anggota->nama }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox"></i>
                                Tidak ada data anggota
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Card View Anggota --}}
<div class="row anggota-card-view">
    @forelse ($anggotas as $anggota)
        <div class="col-md-4 mb-3 anggota-card">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="anggota-avatar mb-3
                        @if ($anggota->jenis_kelamin == 'Laki-laki')
                            bg-primary bg-opacity-10 text-primary
                        @else
                            bg-danger bg-opacity-10 text-danger
                        @endif">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h5 class="card-title mb-1">{{ $anggota->nama }}</h5>
                    <code class="text-muted small">{{ $anggota->kode_anggota }}</code>
                    
                    <div class="mt-2">
                        @if ($anggota->status == 'Aktif')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Nonaktif</span>
                        @endif
                    </div>

                    <hr>

                    <div class="text-start small">
                        <p class="mb-1">
                            <i class="bi bi-envelope text-muted"></i>
                            {{ $anggota->email }}
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-telephone text-muted"></i>
                            {{ $anggota->telepon }}
                        </p>
                        <p class="mb-0">
                            @if ($anggota->jenis_kelamin == 'Laki-laki')
                                <i class="bi bi-gender-male text-primary"></i>
                            @else
                                <i class="bi bi-gender-female text-danger"></i>
                            @endif
                            {{ $anggota->jenis_kelamin }} | {{ $anggota->umur }} tahun
                        </p>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-primary text-white btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning btn-sm flex-fill">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm w-100 btn-delete-anggota"
                                        data-nama="{{ $anggota->nama }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Tidak ada data anggota
        </div>
    @endforelse
</div>

@push('scripts')
<script>
    (function() {
        var savedView = localStorage.getItem('anggotaView') || 'table';
        document.body.setAttribute('data-anggota-view', savedView);

        document.querySelectorAll('.view-toggle .btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-toggle .btn').forEach(function(b) {
                    b.classList.remove('active-view');
                });
                this.classList.add('active-view');
                var view = this.getAttribute('data-view');
                document.body.setAttribute('data-anggota-view', view);
                localStorage.setItem('anggotaView', view);
            });
        });

        var activeBtn = document.querySelector('.view-toggle .btn[data-view="' + savedView + '"]');
        if (activeBtn) activeBtn.classList.add('active-view');
    })();

    document.querySelectorAll('.btn-delete-anggota').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            var nama = this.getAttribute('data-nama');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus anggota "' + nama + '"?',
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