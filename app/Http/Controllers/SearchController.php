<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $results = ['buku' => collect(), 'anggota' => collect(), 'transaksi' => collect()];

        if ($keyword) {
            $results['buku'] = Buku::where('judul', 'LIKE', "%{$keyword}%")
                                   ->orWhere('pengarang', 'LIKE', "%{$keyword}%")
                                   ->orWhere('isbn', 'LIKE', "%{$keyword}%")
                                   ->get();

            $results['anggota'] = Anggota::where('nama', 'LIKE', "%{$keyword}%")
                                         ->orWhere('email', 'LIKE', "%{$keyword}%")
                                         ->orWhere('kode_anggota', 'LIKE', "%{$keyword}%")
                                         ->get();

            $results['transaksi'] = Transaksi::with(['anggota', 'buku'])
                ->where('kode_transaksi', 'LIKE', "%{$keyword}%")
                ->orWhereHas('anggota', fn($q) => $q->where('nama', 'LIKE', "%{$keyword}%"))
                ->orWhereHas('buku', fn($q) => $q->where('judul', 'LIKE', "%{$keyword}%"))
                ->get();
        }

        return view('search.index', compact('keyword', 'results'));
    }
}
