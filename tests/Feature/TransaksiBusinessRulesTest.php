<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Carbon\Carbon;

class TransaksiBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $anggota;
    private $buku;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->anggota = Anggota::factory()->create(['status' => 'Aktif']);

        $this->buku = Buku::factory()->create([
            'stok' => 3,
            'judul' => 'Buku Test',
            'pengarang' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'kategori' => 'Programming',
            'kode_buku' => 'BK-TEST',
        ]);
    }

    public function test_pinjam_buku_saat_stok_habis()
    {
        $bukuHabis = Buku::factory()->create([
            'stok' => 0,
            'kode_buku' => 'BK-HABIS',
            'judul' => 'Buku Habis',
            'pengarang' => 'Test',
            'penerbit' => 'Test',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'kategori' => 'Programming',
        ]);

        $response = $this->actingAs($this->user)
            ->from('/transaksi/create')
            ->post('/transaksi', [
                'anggota_id' => $this->anggota->id,
                'buku_id' => $bukuHabis->id,
                'tanggal_pinjam' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHas('error');
        $response->assertRedirect('/transaksi/create');
        $this->assertStringContainsString('sedang habis', session('error'));
    }

    public function test_pinjam_buku_saat_memiliki_pinjaman_overdue()
    {
        Transaksi::factory()->create([
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'status' => 'Dipinjam',
            'tanggal_pinjam' => now()->subDays(14),
            'tanggal_kembali' => now()->subDays(7),
            'kode_transaksi' => 'TRX-OLD',
        ]);

        $bukuBaru = Buku::factory()->create([
            'stok' => 1,
            'kode_buku' => 'BK-BARU',
            'judul' => 'Buku Baru',
            'pengarang' => 'Test',
            'penerbit' => 'Test',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'kategori' => 'Programming',
        ]);

        $response = $this->actingAs($this->user)
            ->from('/transaksi/create')
            ->post('/transaksi', [
                'anggota_id' => $this->anggota->id,
                'buku_id' => $bukuBaru->id,
                'tanggal_pinjam' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('melewati batas waktu', session('error'));
    }

    public function test_pinjam_lebih_dari_3_buku()
    {
        $bukuList = [];
        for ($i = 1; $i <= 3; $i++) {
            $b = Buku::factory()->create([
                'stok' => 1,
                'kode_buku' => "BK-PINJAM-$i",
                'judul' => "Buku Pinjam $i",
                'pengarang' => 'Test',
                'penerbit' => 'Test',
                'tahun_terbit' => 2024,
                'harga' => 50000,
                'kategori' => 'Programming',
            ]);
            $bukuList[] = $b;

            Transaksi::factory()->create([
                'anggota_id' => $this->anggota->id,
                'buku_id' => $b->id,
                'status' => 'Dipinjam',
                'tanggal_pinjam' => now()->subDays($i),
                'tanggal_kembali' => now()->addDays(7 - $i),
                'kode_transaksi' => "TRX-$i",
            ]);
        }

        $bukuKeempat = Buku::factory()->create([
            'stok' => 1,
            'kode_buku' => 'BK-KEEMPAT',
            'judul' => 'Buku Keempat',
            'pengarang' => 'Test',
            'penerbit' => 'Test',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'kategori' => 'Programming',
        ]);

        $response = $this->actingAs($this->user)
            ->from('/transaksi/create')
            ->post('/transaksi', [
                'anggota_id' => $this->anggota->id,
                'buku_id' => $bukuKeempat->id,
                'tanggal_pinjam' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('batas maksimal 3', session('error'));
    }

    public function test_pinjam_buku_yang_sama()
    {
        Transaksi::factory()->create([
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'status' => 'Dipinjam',
            'tanggal_pinjam' => now()->subDays(3),
            'tanggal_kembali' => now()->addDays(4),
            'kode_transaksi' => 'TRX-SAMA',
        ]);

        $response = $this->actingAs($this->user)
            ->from('/transaksi/create')
            ->post('/transaksi', [
                'anggota_id' => $this->anggota->id,
                'buku_id' => $this->buku->id,
                'tanggal_pinjam' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('pinjaman aktif untuk buku', session('error'));
    }

    public function test_pengembalian_tepat_waktu()
    {
        $transaksi = Transaksi::factory()->create([
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'status' => 'Dipinjam',
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali' => now()->addDays(2),
            'kode_transaksi' => 'TRX-TEPAT',
        ]);

        $stokSebelum = $this->buku->fresh()->stok;

        $response = $this->actingAs($this->user)
            ->patch("/transaksi/{$transaksi->id}/kembalikan");

        $response->assertSessionHas('success');

        $transaksi->refresh();
        $this->assertEquals('Dikembalikan', $transaksi->status);
        $this->assertEquals(0, $transaksi->denda);
        $this->assertEquals(0, $transaksi->hari_terlambat);
        $this->assertEquals($stokSebelum + 1, $this->buku->fresh()->stok);
    }

    public function test_pengembalian_terlambat_dan_perhitungan_denda()
    {
        $transaksi = Transaksi::factory()->create([
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'status' => 'Dipinjam',
            'tanggal_pinjam' => now()->subDays(14),
            'tanggal_kembali' => now()->subDays(7),
            'kode_transaksi' => 'TRX-TELAT',
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/transaksi/{$transaksi->id}/kembalikan");

        $response->assertSessionHas('success');

        $transaksi->refresh();
        $this->assertEquals('Dikembalikan', $transaksi->status);
        $this->assertGreaterThan(0, $transaksi->denda);
        $this->assertGreaterThan(0, $transaksi->hari_terlambat);
        $this->assertEquals(2000, $transaksi->denda / $transaksi->hari_terlambat);
    }

    public function test_update_stok_setelah_peminjaman_dan_pengembalian()
    {
        $stokAwal = $this->buku->stok;

        $response = $this->actingAs($this->user)->post('/transaksi', [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'tanggal_pinjam' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals($stokAwal - 1, $this->buku->fresh()->stok);

        $transaksi = Transaksi::where('anggota_id', $this->anggota->id)
            ->where('buku_id', $this->buku->id)
            ->where('status', 'Dipinjam')
            ->first();

        $response = $this->actingAs($this->user)
            ->patch("/transaksi/{$transaksi->id}/kembalikan");

        $response->assertSessionHas('success');
        $this->assertEquals($stokAwal, $this->buku->fresh()->stok);
    }
}
