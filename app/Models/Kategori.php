<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'warna'
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori', 'nama_kategori');
    }
}