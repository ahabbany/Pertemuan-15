<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'denda',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function getDurasiPeminjamanAttribute()
    {
        if ($this->tanggal_dikembalikan) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_dikembalikan);
        }
        return $this->tanggal_pinjam->diffInDays(now());
    }

    public function getTerlambatAttribute()
    {
        if ($this->status == 'Dikembalikan') {
            if ($this->tanggal_dikembalikan > $this->tanggal_kembali) {
                return $this->tanggal_kembali->diffInDays($this->tanggal_dikembalikan);
            }
            return 0;
        }

        if (now() > $this->tanggal_kembali) {
            return $this->tanggal_kembali->diffInDays(now());
        }

        return 0;
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status == 'Dipinjam'
            ? '<span class="badge bg-warning text-dark">Dipinjam</span>'
            : '<span class="badge bg-success">Dikembalikan</span>';
    }
}
