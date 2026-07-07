<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kategori' => 'Pemrograman',
                'deskripsi' => 'Kategori buku pemrograman',
                'icon' => 'code-slash',
                'warna' => 'primary'
            ],
            [
                'nama_kategori' => 'Basis Data',
                'deskripsi' => 'Kategori buku basis data',
                'icon' => 'database',
                'warna' => 'success'
            ],
            [
                'nama_kategori' => 'Desain Web',
                'deskripsi' => 'Kategori buku desain web',
                'icon' => 'palette',
                'warna' => 'info'
            ],
            [
                'nama_kategori' => 'Jaringan',
                'deskripsi' => 'Kategori buku jaringan',
                'icon' => 'wifi',
                'warna' => 'warning'
            ],
            [
                'nama_kategori' => 'Ilmu Data',
                'deskripsi' => 'Kategori buku ilmu data',
                'icon' => 'graph-up',
                'warna' => 'danger'
            ]
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}