@extends('layouts.app')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('kategori.index') }}">
                Kategori
            </a>
        </li>
        <li class="breadcrumb-item active">
            Detail
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">

    <div class="card-body">

        <h3>{{ $kategori->nama_kategori }}</h3>

        <p>{{ $kategori->deskripsi }}</p>

        <span class="badge bg-success">
            {{ $kategori->buku_count }} Buku
        </span>

    </div>

</div>

<h4>Daftar Buku</h4>

<div class="table-responsive">
<table class="table table-bordered">

    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
        </tr>
    </thead>

    <tbody>

        @forelse($buku_list as $index => $buku)

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $buku->judul }}</td>
            <td>{{ $buku->pengarang }}</td>
            <td>{{ $buku->tahun_terbit }}</td>
        </tr>

        @empty

        <tr>
            <td colspan="4" class="text-center text-muted">
                Belum ada buku di kategori ini
            </td>
        </tr>

        @endforelse

    </tbody>

</table>
</div>

@endsection