<?php
  
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
 
// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
});
 
// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // Export (sebelum resource agar tidak ditangkap show)
    Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('/transaksi/export-pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.exportPdf');
    Route::get('/transaksi/export-csv', [TransaksiController::class, 'exportCsv'])->name('transaksi.exportCsv');

    // Buku - CRUD
    Route::resource('buku', BukuController::class);
  
    // Anggota - CRUD
    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
  
    // Transaksi - Custom routes (sebelum resource agar tidak ditangkap show)
    Route::patch('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
  
    // Transaksi - CRUD
    Route::resource('transaksi', TransaksiController::class);
  
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
  
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/json', [NotificationController::class, 'latestJson'])->name('notifications.json');
    Route::post('/notifications/{id}/kirim-peringatan', [NotificationController::class, 'kirimPeringatan'])->name('notifications.kirimPeringatan');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
 
    // Kategori
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/{id}', [App\Http\Controllers\KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/search/{keyword}', [App\Http\Controllers\KategoriController::class, 'search'])->name('kategori.search');
});
 
require __DIR__.'/auth.php';
