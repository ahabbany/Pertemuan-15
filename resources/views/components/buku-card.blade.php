<div class="card h-100 shadow-sm">
    <div class="card-body">

        <div class="text-center mb-3">
            <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
        </div>

        <h5 class="card-title">
            {{ $buku->judul }}
        </h5>

        <p class="text-muted mb-1">
            <i class="bi bi-person"></i>
            {{ $buku->pengarang }}
        </p>

        <p class="fw-bold text-primary">
            Rp {{ number_format($buku->harga,0,',','.') }}
        </p>

        <p>
            Stok: {{ $buku->stok }}
        </p>

        <span class="badge bg-primary">
            {{ $buku->kategori }}
        </span>

        <div class="mt-3">
            @if($buku->stok > 0)
                <span class="badge bg-success">
                    Tersedia
                </span>
            @else
                <span class="badge bg-danger">
                    Habis
                </span>
            @endif
        </div>

        @if($showActions)
            <div class="d-grid gap-2 mt-3">

                <a href="{{ route('buku.show',$buku->id) }}"
                   class="btn btn-info text-white btn-sm">
                    Detail
                </a>

                <a href="{{ route('buku.edit',$buku->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

            </div>
        @endif

    </div>
</div>