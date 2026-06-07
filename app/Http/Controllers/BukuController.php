<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
 
class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();
        
        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();
        
        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis'
        ));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Akan diimplementasi di pertemuan 12
        return view('buku.create');
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        try {
            Buku::create($request->validated());

            return redirect()
                 ->route('buku.index')
                 ->with('success', 'Buku berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: '. $e->getMessage());
        
        }
    }
        
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);
        
        // Return view detail buku
        return view('buku.show', compact('buku'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
        $buku = Buku::findOrFail($id);
        
        // Update buku dengan validated data
        $buku->update($request->validated());
        
        // Redirect dengan success message
        return redirect()->route('buku.show', $buku->id)
                         ->with('success', 'Buku berhasil diupdate!');
                         
    } catch (\Exception $e) {
        // Redirect dengan error message jika gagal
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
    }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        $buku = Buku::findOrFail($id);
        $judulBuku = $buku->judul;
        
        // Delete buku
        $buku->delete();
        
        // Redirect dengan success message
        return redirect()->route('buku.index')
                         ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
                         
    } catch (\Exception $e) {
        // Redirect dengan error message jika gagal
        return redirect()->back()
                         ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
    }
    }
    
    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();
        
        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();
        
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->buku_ids;
        Buku::whereIn('id', $ids)->delete();
        return redirect()->route('buku.index')
                         ->with('success', count($ids) . ' buku berhasil dihapus!');
    }

    public function export()
    {
        $bukus = Buku::all();

        $filename = 'buku_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($bukus) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Kode Buku', 'Judul', 'Kategori', 'Pengarang',
                'Penerbit', 'Tahun', 'ISBN', 'Harga', 'Stok'
            ]);

            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku,
                    $buku->judul,
                    $buku->kategori,
                    $buku->pengarang,
                    $buku->penerbit,
                    $buku->tahun_terbit,
                    $buku->isbn,
                    $buku->harga,
                    $buku->stok,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function search(Request $request)
{
    $query = Buku::query();

    if ($request->keyword) {

        $query->where(function($q) use ($request){

            $q->where('judul', 'like', '%' . $request->keyword . '%')
              ->orWhere('pengarang', 'like', '%' . $request->keyword . '%')
              ->orWhere('penerbit', 'like', '%' . $request->keyword . '%');
        });
    }

    if ($request->kategori) {
        $query->where('kategori', $request->kategori);
    }

    if ($request->tahun) {
        $query->where('tahun_terbit', $request->tahun);
    }

    if ($request->ketersediaan == 'tersedia') {
        $query->where('stok', '>', 0);
    }

    if ($request->ketersediaan == 'habis') {
        $query->where('stok', 0);
    }

    $bukus = $query->latest()->get();

    $totalBuku = $bukus->count();
    $bukuTersedia = $bukus->where('stok', '>', 0)->count();
    $bukuHabis = $bukus->where('stok', 0)->count();

    return view('buku.index', compact(
        'bukus',
        'totalBuku',
        'bukuTersedia',
        'bukuHabis'
    ));
}
}