<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Anggota;

class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    public function definition()
    {
        return [
            'kode_anggota' => 'AGT-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telepon' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'pekerjaan' => fake()->jobTitle(),
            'tanggal_daftar' => now(),
            'status' => 'Aktif',
        ];
    }
}
