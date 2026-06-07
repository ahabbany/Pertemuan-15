<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
 
Route::get('/', function () {
    return view('home');
})->name('home');

// Custom routes (harus sebelum resource route)
Route::get('/buku/search', [BukuController::class,'search'])
    ->name('buku.search');
Route::get('/buku/export', [BukuController::class, 'export'])
    ->name('buku.export');
Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])
    ->name('buku.bulk-delete');
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
    ->name('buku.kategori');

// Resource route untuk Buku
Route::resource('buku', BukuController::class);
 
// Resource route untuk Anggota (akan dibuat nanti)
Route::resource('anggota', AnggotaController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');