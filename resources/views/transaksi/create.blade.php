@extends('layouts.app')
 
@section('title', 'Transaksi Peminjaman')
 
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i>
                    Form Peminjaman Buku
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    
                    {{-- Pilih Anggota --}}
                    <div class="mb-3">
                        <label for="anggota_id" class="form-label">
                            Pilih Anggota <span class="text-danger">*</span>
                        </label>
                        <select name="anggota_id" 
                                id="anggota_id" 
                                class="form-select @error('anggota_id') is-invalid @enderror">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                    {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya anggota dengan status Aktif yang dapat meminjam</small>
                    </div>
                    
                    {{-- Pilih Buku --}}
                    <div class="mb-3">
                        <label for="buku_id" class="form-label">
                            Pilih Buku <span class="text-danger">*</span>
                        </label>
                        <select name="buku_id" 
                                id="buku_id" 
                                class="form-select @error('buku_id') is-invalid @enderror">
                            <option value="">-- Pilih Buku --</option>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                    {{ $buku->judul }} - (Stok: {{ $buku->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya buku dengan stok tersedia yang dapat dipinjam</small>
                    </div>
                    
                    {{-- Tanggal Pinjam --}}
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">
                            Tanggal Pinjam <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pinjam" 
                               id="tanggal_pinjam" 
                               class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}">
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tanggal kembali otomatis 7 hari dari tanggal pinjam</small>
                    </div>
                    
                    {{-- Keterangan --}}
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  rows="3" 
                                  class="form-control @error('keterangan') is-invalid @enderror"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Info Box --}}
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informasi Peminjaman:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Durasi peminjaman: <strong>7 hari</strong></li>
                            <li>Denda keterlambatan: <strong>Rp 5.000/hari</strong></li>
                            <li>Stok buku akan berkurang otomatis setelah peminjaman</li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Proses Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection