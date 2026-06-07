@extends('layouts.app')
 
@section('title', 'Daftar Buku')
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <div>
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
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
{{-- Filter Kategori --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">
            <i class="bi bi-funnel"></i> Filter Kategori:
        </h6>
        <div class="btn-group" role="group">
            <a href="{{ route('buku.index') }}" class="btn btn-sm {{ !isset($kategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            <a href="{{ route('buku.kategori', 'Programming') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Programming' ? 'btn-primary' : 'btn-outline-primary' }}">
                Programming
            </a>
            <a href="{{ route('buku.kategori', 'Database') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Database' ? 'btn-primary' : 'btn-outline-primary' }}">
                Database
            </a>
            <a href="{{ route('buku.kategori', 'Web Design') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Web Design' ? 'btn-primary' : 'btn-outline-primary' }}">
                Web Design
            </a>
            <a href="{{ route('buku.kategori', 'Networking') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Networking' ? 'btn-primary' : 'btn-outline-primary' }}">
                Networking
            </a>
            <a href="{{ route('buku.kategori', 'Data Science') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Data Science' ? 'btn-primary' : 'btn-outline-primary' }}">
                Data Science
            </a>
        </div>
    </div>
</div>

<form action="{{ route('buku.search') }}"
      method="GET">

    <input
        type="text"
        name="keyword"
        placeholder="Cari buku">

    <select name="kategori">

        <option value="">
            Semua Kategori
        </option>

        <option value="Novel">
            Novel
        </option>

        <option value="Teknologi">
            Teknologi
        </option>

    </select>

    <select name="ketersediaan">

        <option value="">
            Semua
        </option>

        <option value="tersedia">
            Tersedia
        </option>

        <option value="habis">
            Habis
        </option>

    </select>

    <button type="submit">
        Cari
    </button>

</form>

{{-- Bulk Delete Controls --}}
@if ($bukus->count() > 0)
<div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded border">
    <div class="form-check">
        <input type="checkbox" id="select-all" class="form-check-input">
        <label class="form-check-label" for="select-all">Pilih Semua</label>
    </div>
    <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" disabled>
        <i class="bi bi-trash"></i> Hapus Terpilih
    </button>
</div>
@endif

<div class="row">
    @forelse ($bukus as $buku)

        <div class="col-md-4 mb-3">
            <x-buku-card
                :buku="$buku"
                :show-actions="true"
                :show-checkbox="true"
            />
        </div>

    @empty

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Tidak ada data buku
        </div>

    @endforelse
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
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const bulkDeleteBtn = document.getElementById('btn-bulk-delete');
            const checkboxes = document.querySelectorAll('input[name="buku_ids[]"]');

            if (!selectAll || !bulkDeleteBtn) return;

            function toggleBulkDeleteButton() {
                const checked = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
                bulkDeleteBtn.disabled = checked === 0;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
                toggleBulkDeleteButton();
            });

            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', toggleBulkDeleteButton);
            });

            bulkDeleteBtn.addEventListener('click', function() {
                const checked = document.querySelectorAll('input[name="buku_ids[]"]:checked');
                if (checked.length === 0) return;

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus ' + checked.length + ' buku yang dipilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("buku.bulk-delete") }}';

                        var token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);

                        checked.forEach(function(cb) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'buku_ids[]';
                            input.value = cb.value;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var form = this.closest('form');
                    var judul = this.getAttribute('data-judul');

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
        });
    </script>
@endpush
@endsection