<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['anggota', 'buku'])
                               ->latest()
                               ->get();

        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('transaksi.create', compact('anggotas', 'bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $anggota = Anggota::findOrFail($request->anggota_id);
                $buku = Buku::findOrFail($request->buku_id);

                if ($buku->stok <= 0) {
                    throw new \Exception("Stok buku \"{$buku->judul}\" sedang habis (0).");
                }

                $tglPinjam = Carbon::parse($request->tanggal_pinjam);
                if ($tglPinjam->lt($anggota->tanggal_daftar)) {
                    throw new \Exception(
                        "Anggota \"{$anggota->nama}\" baru terdaftar pada {$anggota->tanggal_daftar->format('d M Y')}, " .
                        "tidak bisa meminjam pada {$tglPinjam->format('d M Y')} (sebelum tanggal pendaftaran)."
                    );
                }

                $pinjamanAktif = Transaksi::where('anggota_id', $request->anggota_id)
                    ->where('status', 'Dipinjam');

                if ($pinjamanAktif->where('buku_id', $request->buku_id)->exists()) {
                    throw new \Exception("Anggota \"{$anggota->nama}\" masih memiliki pinjaman aktif untuk buku \"{$buku->judul}\" yang sama. Silakan kembalikan buku tersebut terlebih dahulu.");
                }

                $totalPinjaman = Transaksi::where('anggota_id', $request->anggota_id)->where('status', 'Dipinjam')->count();
                if ($totalPinjaman >= 3) {
                    throw new \Exception("Anggota \"{$anggota->nama}\" sudah mencapai batas maksimal 3 pinjaman aktif. Saat ini memiliki {$totalPinjaman} pinjaman.");
                }

                $overdueBooks = Transaksi::with('buku')
                    ->where('anggota_id', $request->anggota_id)
                    ->where('status', 'Dipinjam')
                    ->where('tanggal_kembali', '<', now())
                    ->get();

                if ($overdueBooks->isNotEmpty()) {
                    $detail = [];
                    foreach ($overdueBooks as $ob) {
                        $hari = $ob->terlambat;
                        $denda = $hari * 2000;
                        $detail[] = "{$ob->buku->judul} (terlambat {$hari} hari, denda Rp " . number_format($denda, 0, ',', '.') . ")";
                    }
                    throw new \Exception(
                        "Anggota \"{$anggota->nama}\" tidak bisa meminjam karena memiliki pinjaman yang melewati batas waktu:\n- " . implode("\n- ", $detail)
                    );
                }

                $kodeTransaksi = $this->generateKodeTransaksi();
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);

                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id' => $request->anggota_id,
                    'buku_id' => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'Dipinjam',
                    'keterangan' => $request->keterangan,
                ]);

                $buku->decrement('stok');
            });

            return redirect()->route('transaksi.index')
                             ->with('success', 'Transaksi peminjaman berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    public function kembalikan(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $transaksi = Transaksi::findOrFail($id);

                if ($transaksi->status === 'Dikembalikan') {
                    throw new \Exception('Buku sudah dikembalikan sebelumnya.');
                }

                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $hariTerlambat = $this->hitungHariTerlambat($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status' => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda' => $denda,
                    'hari_terlambat' => $hariTerlambat,
                ]);

                $transaksi->buku->increment('stok');
            });

            return redirect()->route('transaksi.show', $id)
                             ->with('success', 'Buku berhasil dikembalikan!');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();

        $summary = [
            'total'        => $transaksis->count(),
            'dipinjam'     => $transaksis->where('status', 'Dipinjam')->count(),
            'dikembalikan' => $transaksis->where('status', 'Dikembalikan')->count(),
            'total_denda'  => $transaksis->sum('denda'),
        ];

        $pdf = Pdf::loadView('transaksi.export-pdf', compact('transaksis', 'summary'));
        return $pdf->download('laporan_transaksi_' . date('Y-m-d_His') . '.pdf');
    }

    public function exportCsv()
    {
        $transaksis = Transaksi::with(['anggota', 'buku'])->latest()->get();

        $filename = 'transaksi_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transaksis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Transaksi', 'Anggota', 'Buku', 'Tanggal Pinjam', 'Tanggal Kembali', 'Tanggal Dikembalikan', 'Status', 'Denda', 'Hari Terlambat']);

            foreach ($transaksis as $trx) {
                fputcsv($file, [
                    $trx->kode_transaksi,
                    $trx->anggota->nama,
                    $trx->buku->judul,
                    $trx->tanggal_pinjam->format('Y-m-d'),
                    $trx->tanggal_kembali->format('Y-m-d'),
                    $trx->tanggal_dikembalikan?->format('Y-m-d') ?? '',
                    $trx->status,
                    $trx->denda,
                    $trx->hari_terlambat,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::latest()->first();

        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    private function hitungHariTerlambat($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);
        return $hariTerlambat > 0 ? $hariTerlambat : 0;
    }

    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $this->hitungHariTerlambat($transaksi, $tanggalDikembalikan);
        return $hariTerlambat * 2000;
    }
}
