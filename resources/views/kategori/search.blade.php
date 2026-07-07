@extends('layouts.app')

@section('content')

<h2>
    Hasil Pencarian:
    <span class="text-danger">
        "{{ $keyword }}"
    </span>
</h2>

<div class="row mt-4">

@forelse($hasil as $kategori)

<div class="col-md-4 mb-4">

    <div class="card shadow">

        <div class="card-body">

            <h4>{{ $kategori->nama_kategori }}</h4>

            <p>{{ $kategori->deskripsi }}</p>

            <span class="badge bg-primary">
                {{ $kategori->buku_count }} Buku
            </span>

        </div>

    </div>

</div>

@empty

<div class="alert alert-danger">
    Kategori tidak ditemukan
</div>

@endforelse

</div>

@endsection