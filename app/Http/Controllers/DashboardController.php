<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();

        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'nonaktif')->count();

        $bukuTerbaru = Buku::latest()->take(5)->get();
        $anggotaTerbaru = Anggota::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru'
        ));
    }
}