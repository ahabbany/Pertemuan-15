<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
 
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/buku/search', [BukuController::class,'search'])
    ->name('buku.search');
    
// Resource route untuk Buku
Route::resource('buku', BukuController::class);
 
// Custom route untuk filter kategori
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
     ->name('buku.kategori');
 
// Resource route untuk Anggota (akan dibuat nanti)
Route::resource('anggota', AnggotaController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');