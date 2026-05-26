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
                'nama_kategori' => 'Programming',
                'deskripsi' => 'Kategori buku pemrograman',
                'icon' => 'code-slash',
                'warna' => 'primary'
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi' => 'Kategori buku database',
                'icon' => 'database',
                'warna' => 'success'
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi' => 'Kategori buku desain web',
                'icon' => 'palette',
                'warna' => 'info'
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi' => 'Kategori buku jaringan',
                'icon' => 'wifi',
                'warna' => 'warning'
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi' => 'Kategori buku data science',
                'icon' => 'graph-up',
                'warna' => 'danger'
            ]
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}