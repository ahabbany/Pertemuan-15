@extends('layouts.app')

@section('content')

<h2 class="mb-4">Daftar Kategori Buku</h2>

<div class="row">

@foreach($kategori_list as $kategori)

<div class="col-md-4 mb-4">

    <div class="card shadow h-100">

        <div class="card-body">

            <h4>{{ $kategori->nama_kategori }}</h4>

            <p>{{ $kategori->deskripsi }}</p>

            <span class="badge bg-primary">
                {{ $kategori->buku_count }} Buku
            </span>

            <br><br>

            <a href="{{ route('kategori.show', $kategori->id) }}"
               class="btn btn-dark">
                Detail
            </a>

        </div>

    </div>

</div>

@endforeach

</div>

@endsection