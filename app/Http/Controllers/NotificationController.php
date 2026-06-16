<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $this->generateOverdueNotifications();

        $notifications = Notification::with('transaksi.anggota', 'transaksi.buku')
                                     ->latest()
                                     ->paginate(20);

        $unreadCount = Notification::where('dibaca', false)->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function unreadCount()
    {
        $this->generateOverdueNotifications();

        return response()->json([
            'count' => Notification::where('dibaca', false)->count(),
        ]);
    }

    public function latestJson()
    {
        $this->generateOverdueNotifications();

        $notifications = Notification::with('transaksi.anggota', 'transaksi.buku')
                                     ->latest()
                                     ->take(20)
                                     ->get()
                                     ->map(function ($n) {
                                         return [
                                             'id' => $n->id,
                                             'judul' => $n->judul,
                                             'pesan' => $n->pesan,
                                             'tipe' => $n->tipe,
                                             'dibaca' => $n->dibaca,
                                             'created_at' => $n->created_at->diffForHumans(),
                                             'transaksi_id' => $n->transaksi_id,
                                         ];
                                     });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('dibaca', false)->count(),
        ]);
    }

    private function generateOverdueNotifications()
    {
        $overdueTransaksis = Transaksi::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->get();

        foreach ($overdueTransaksis as $transaksi) {
            $alreadyNotified = Notification::where('transaksi_id', $transaksi->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            $hariTerlambat = $transaksi->terlambat;

            Notification::create([
                'transaksi_id' => $transaksi->id,
                'judul' => 'Peringatan Keterlambatan',
                'pesan' => "Anggota {$transaksi->anggota->nama} terlambat mengembalikan buku \"{$transaksi->buku->judul}\" selama {$hariTerlambat} hari. Denda: Rp " . number_format($hariTerlambat * 2000, 0, ',', '.'),
                'tipe' => 'peringatan',
            ]);
        }
    }

    public function kirimPeringatan(string $transaksiId)
    {
        try {
            $transaksi = Transaksi::with('anggota', 'buku')->findOrFail($transaksiId);

            if ($transaksi->status !== 'Dipinjam') {
                return redirect()->back()->with('error', 'Buku sudah dikembalikan, tidak perlu peringatan.');
            }

            $hariTerlambat = $transaksi->terlambat;

            if ($hariTerlambat <= 0) {
                return redirect()->back()->with('error', 'Pinjaman belum melewati jatuh tempo.');
            }

            $notif = Notification::create([
                'transaksi_id' => $transaksi->id,
                'judul' => 'Peringatan Keterlambatan',
                'pesan' => "Anggota {$transaksi->anggota->nama} terlambat mengembalikan buku \"{$transaksi->buku->judul}\" selama {$hariTerlambat} hari. Denda: Rp " . number_format($hariTerlambat * 2000, 0, ',', '.'),
                'tipe' => 'peringatan',
            ]);

            return redirect()->back()->with('success', "Peringatan berhasil dikirim untuk {$transaksi->anggota->nama}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim peringatan: ' . $e->getMessage());
        }
    }

    public function markAsRead(string $id)
    {
        $notif = Notification::findOrFail($id);
        $notif->update(['dibaca' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        Notification::where('dibaca', false)->update(['dibaca' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(string $id)
    {
        $notif = Notification::findOrFail($id);
        $notif->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}
