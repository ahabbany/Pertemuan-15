<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Transaksi;
use Illuminate\Console\Command;

class GenerateOverdueNotifications extends Command
{
    protected $signature = 'notifications:generate';
    protected $description = 'Generate notifications for overdue transactions';

    public function handle()
    {
        $overdueTransaksis = Transaksi::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->get();

        $count = 0;

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

            $count++;
        }

        $this->info("Generated {$count} overdue notification(s).");
    }
}
