<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Buku;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori_list = Kategori::withCount('buku')->get();

        return view('kategori.index', compact('kategori_list'));
    }

    public function show($id)
    {
        $kategori = Kategori::withCount('buku')->findOrFail($id);
        $buku_list = Buku::where('kategori', $kategori->nama_kategori)->latest()->get();

        return view('kategori.show', compact('kategori', 'buku_list'));
    }

    public function search($keyword)
    {
        $hasil = Kategori::withCount('buku')
            ->where('nama_kategori', 'like', "%{$keyword}%")
            ->get();

        return view('kategori.search', compact('hasil', 'keyword'));
    }
}