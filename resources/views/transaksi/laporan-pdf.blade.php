<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #4a90d9; color: white; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; }
        .summary-item { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2 style="text-align:center; margin-bottom:5px;">Laporan Transaksi Peminjaman Buku</h2>
    <p style="text-align:center; color:#666; margin-top:0;">
        Periode: {{ request('tanggal_mulai', 'Awal') }} s/d {{ request('tanggal_selesai', 'Sekarang') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $transaksi)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $transaksi->kode_transaksi }}</td>
                <td>{{ $transaksi->anggota->nama }}</td>
                <td>{{ $transaksi->buku->judul }}</td>
                <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $transaksi->tanggal_kembali->format('d/m/Y') }}</td>
                <td>{{ $transaksi->status }}</td>
                <td class="text-right">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h4>Ringkasan</h4>
        <div class="summary-item">Total Transaksi: {{ $totalTransaksi }}</div>
        <div class="summary-item">Dipinjam: {{ $totalDipinjam }}</div>
        <div class="summary-item">Dikembalikan: {{ $totalDikembalikan }}</div>
        <div class="summary-item">Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
    </div>
</body>
</html>
