<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Anggota;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
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

        $anggotas = Anggota::orderBy('nama')->get();

        return view('laporan.index', compact('transaksis', 'summary', 'anggotas'));
    }
}
