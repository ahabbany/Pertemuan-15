<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

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
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
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

                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status' => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda' => $denda,
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

    public function laporan(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter anggota
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();
        $anggotas = Anggota::orderBy('nama')->get();

        // Hitung total
        $totalTransaksi = $transaksis->count();
        $totalDipinjam = $transaksis->where('status', 'Dipinjam')->count();
        $totalDikembalikan = $transaksis->where('status', 'Dikembalikan')->count();
        $totalDenda = $transaksis->sum('denda');

        return view('transaksi.laporan', compact(
            'transaksis', 'anggotas',
            'totalTransaksi', 'totalDipinjam', 'totalDikembalikan', 'totalDenda'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();

        $totalTransaksi = $transaksis->count();
        $totalDipinjam = $transaksis->where('status', 'Dipinjam')->count();
        $totalDikembalikan = $transaksis->where('status', 'Dikembalikan')->count();
        $totalDenda = $transaksis->sum('denda');

        $pdf = PDF::loadView('transaksi.laporan-pdf', compact(
            'transaksis',
            'totalTransaksi', 'totalDipinjam', 'totalDikembalikan', 'totalDenda'
        ));

        return $pdf->download('laporan-transaksi.pdf');
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

    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);

        if ($hariTerlambat > 0) {
            return $hariTerlambat * 5000;
        }

        return 0;
    }
}
