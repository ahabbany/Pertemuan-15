@extends('layouts.app')
@section('title', 'Pencarian')

@section('content')
<div class="container py-4">
    <h2>Hasil Pencarian: "{{ $keyword }}"</h2>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-buku">
                Buku ({{ $results['buku']->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-anggota">
                Anggota ({{ $results['anggota']->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-transaksi">
                Transaksi ({{ $results['transaksi']->count() }})
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-buku">
            @forelse($results['buku'] as $buku)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6>{!! str_ireplace($keyword, '<mark>'.$keyword.'</mark>', e($buku->judul)) !!}</h6>
                        <small class="text-muted">{{ $buku->pengarang }} — Stok: {{ $buku->stok }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">Tidak ada buku yang cocok.</p>
            @endforelse
        </div>

        <div class="tab-pane fade" id="tab-anggota">
            @forelse($results['anggota'] as $anggota)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6>{!! str_ireplace($keyword, '<mark>'.$keyword.'</mark>', e($anggota->nama)) !!}</h6>
                        <small class="text-muted">{{ $anggota->kode_anggota }} — {{ $anggota->email }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">Tidak ada anggota yang cocok.</p>
            @endforelse
        </div>

        <div class="tab-pane fade" id="tab-transaksi">
            @forelse($results['transaksi'] as $trx)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6>{{ $trx->kode_transaksi }}</h6>
                        <small>{{ $trx->anggota->nama }} — {{ $trx->buku->judul }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">Tidak ada transaksi yang cocok.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
