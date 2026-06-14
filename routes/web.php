<?php
 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
 
// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
});
 
// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
 
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // Buku - CRUD
    Route::resource('buku', BukuController::class);
 
    // Anggota - CRUD
    Route::resource('anggota', AnggotaController::class);
 
    // Transaksi - Custom routes (sebelum resource agar tidak ditangkap show)
    Route::get('/transaksi/laporan/export-pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.export-pdf');
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');

    // Transaksi - CRUD
    Route::resource('transaksi', TransaksiController::class);

    // Kategori
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/{id}', [App\Http\Controllers\KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/search/{keyword}', [App\Http\Controllers\KategoriController::class, 'search'])->name('kategori.search');
});
 
require __DIR__.'/auth.php';
