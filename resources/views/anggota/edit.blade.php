@extends('layouts.app')
 
@section('title', 'Edit Anggota')
 
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
 
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Anggota: {{ $anggota->nama }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('anggota.update', $anggota->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        {{-- Kode Anggota --}}
                        <div class="col-md-4 mb-3">
                            <label for="kode_anggota" class="form-label">
                                Kode Anggota <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="kode_anggota" 
                                   id="kode_anggota" 
                                   class="form-control @error('kode_anggota') is-invalid @enderror"
                                   value="{{ old('kode_anggota', $anggota->kode_anggota) }}">
                            @error('kode_anggota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Nama --}}
                        <div class="col-md-8 mb-3">
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $anggota->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $anggota->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Telepon --}}
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label">
                                Nomor Telepon <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="telepon" 
                                   id="telepon" 
                                   class="form-control @error('telepon') is-invalid @enderror"
                                   value="{{ old('telepon', $anggota->telepon) }}">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <textarea name="alamat" 
                                  id="alamat" 
                                  rows="3" 
                                  class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $anggota->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        {{-- Tanggal Lahir --}}
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_lahir" 
                                   id="tanggal_lahir" 
                                   class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Jenis Kelamin --}}
                        <div class="col-md-4 mb-3">
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_kelamin" 
                                    id="jenis_kelamin" 
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                @foreach(['Laki-laki', 'Perempuan'] as $jk)
                                    <option value="{{ $jk }}" 
                                            {{ old('jenis_kelamin', $anggota->jenis_kelamin) == $jk ? 'selected' : '' }}>
                                        {{ $jk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Pekerjaan --}}
                        <div class="col-md-4 mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" 
                                   name="pekerjaan" 
                                   id="pekerjaan" 
                                   class="form-control @error('pekerjaan') is-invalid @enderror"
                                   value="{{ old('pekerjaan', $anggota->pekerjaan) }}">
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        {{-- Tanggal Daftar --}}
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_daftar" class="form-label">
                                Tanggal Pendaftaran <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_daftar" 
                                   id="tanggal_daftar" 
                                   class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                   value="{{ old('tanggal_daftar', $anggota->tanggal_daftar?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('tanggal_daftar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror">
                                @foreach(['Aktif', 'Nonaktif'] as $st)
                                    <option value="{{ $st }}" 
                                            {{ old('status', $anggota->status) == $st ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Info Update --}}
        <div class="card mt-3">
            <div class="card-body">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong><br />
                    - Anggota terdaftar: {{ $anggota->created_at->format('d M Y H:i') }}<br />
                    - Terakhir diupdate: {{ $anggota->updated_at->format('d M Y H:i') }}<br />
                    - Lama menjadi anggota: {{ $anggota->lama_anggota }} hari ({{ round($anggota->lama_anggota / 365, 1) }} tahun)
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    // Initialize Flatpickr
    flatpickr("#tanggal_lahir", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: "id",
        altInput: true,
        altFormat: "d F Y",
    });
    
    flatpickr("#tanggal_daftar", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: "id",
        altInput: true,
        altFormat: "d F Y",
    });
</script>
@endpush