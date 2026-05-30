@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Dashboard Perpustakaan
</h1>

<div class="grid grid-cols-3 gap-4 mb-8">

    <div class="bg-white p-4 shadow rounded">
        <h3>Total Buku</h3>
        <p class="text-2xl font-bold">{{ $totalBuku }}</p>
    </div>

    <div class="bg-green-100 p-4 shadow rounded">
        <h3>Buku Tersedia</h3>
        <p class="text-2xl font-bold">{{ $bukuTersedia }}</p>
    </div>

    <div class="bg-red-100 p-4 shadow rounded">
        <h3>Buku Habis</h3>
        <p class="text-2xl font-bold">{{ $bukuHabis }}</p>
    </div>

</div>

<div class="grid grid-cols-2 gap-6">

    <div>
        <h2 class="font-bold mb-3">5 Buku Terbaru</h2>

        @foreach($bukuTerbaru as $buku)
            <div class="border p-2 mb-2">
                {{ $buku->judul }}
            </div>
        @endforeach
    </div>

    <div>
        <h2 class="font-bold mb-3">5 Anggota Terbaru</h2>

        @foreach($anggotaTerbaru as $anggota)
            <div class="border p-2 mb-2">
                {{ $anggota->nama }}
            </div>
        @endforeach
    </div>

</div>

@endsection