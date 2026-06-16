<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Buku;

class BukuFactory extends Factory
{
    protected $model = Buku::class;

    public function definition()
    {
        $kategori = ['Programming', 'Database', 'Web Design', 'Networking', 'Data Science'];

        return [
            'kode_buku' => 'BK-' . strtoupper(fake()->bothify('???###')),
            'judul' => fake()->sentence(4),
            'kategori' => fake()->randomElement($kategori),
            'pengarang' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahun_terbit' => fake()->year(),
            'isbn' => fake()->isbn13(),
            'harga' => fake()->randomFloat(2, 10000, 200000),
            'stok' => 5,
            'deskripsi' => fake()->paragraph(),
            'bahasa' => 'Indonesia',
        ];
    }
}
