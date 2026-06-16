<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaksi;
use App\Models\Anggota;
use App\Models\Buku;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        return [
            'kode_transaksi' => 'TRX-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'anggota_id' => Anggota::factory(),
            'buku_id' => Buku::factory(),
            'tanggal_pinjam' => now()->subDays(rand(1, 10)),
            'tanggal_kembali' => now()->addDays(rand(1, 7)),
            'status' => 'Dipinjam',
            'denda' => 0,
            'hari_terlambat' => 0,
        ];
    }
}
