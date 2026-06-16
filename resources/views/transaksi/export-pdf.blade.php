<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; font-size: 10px; }
        th { background: #e0e0e0; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .summary-item { text-align: center; padding: 8px; border: 1px solid #ccc; flex: 1; margin: 0 4px; }
        .summary-item h4 { margin: 0; font-size: 11px; color: #666; }
        .summary-item p { margin: 5px 0 0; font-size: 16px; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi Perpustakaan</h2>
    <p class="subtitle">{{ config('app.name') }} — Generated: {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Transaksi</h4>
            <p>{{ $summary['total'] }}</p>
        </div>
        <div class="summary-item">
            <h4>Dipinjam</h4>
            <p>{{ $summary['dipinjam'] }}</p>
        </div>
        <div class="summary-item">
            <h4>Dikembalikan</h4>
            <p>{{ $summary['dikembalikan'] }}</p>
        </div>
        <div class="summary-item">
            <h4>Total Denda</h4>
            <p>Rp {{ number_format($summary['total_denda'], 0, ',', '.') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Terlambat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $trx)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->anggota->nama }}</td>
                <td>{{ $trx->buku->judul }}</td>
                <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $trx->tanggal_kembali->format('d/m/Y') }}</td>
                <td>{{ $trx->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $trx->status }}</td>
                <td class="text-center">{{ $trx->denda ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                <td class="text-center">{{ $trx->hari_terlambat ? $trx->hari_terlambat . ' hari' : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
