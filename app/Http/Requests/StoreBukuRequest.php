<?php

namespace App\Http\Requests;

use App\Rules\KodeBukuFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_buku' => ['required', 'string', 'max:20', 'unique:buku,kode_buku', new KodeBukuFormat],
            'judul' => 'required|string|max:200',
            'kategori' => 'required|in:Programming,Database,Web Design,Networking,Data Science',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'bahasa' => 'required|string|max:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();

            if (isset($data['kategori']) && $data['kategori'] === 'Programming') {
                if (isset($data['bahasa']) && $data['bahasa'] !== 'Inggris') {
                    $validator->errors()->add('bahasa', 'Untuk kategori Programming, bahasa harus Inggris.');
                }
            }

            if (isset($data['tahun_terbit']) && (int) $data['tahun_terbit'] < 2000) {
                if (isset($data['stok']) && (int) $data['stok'] > 5) {
                    $validator->errors()->add('stok', 'Stok maksimal 5 untuk buku terbit sebelum tahun 2000.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'kode_buku.max' => 'Kode buku maksimal 20 karakter.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 200 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'pengarang.required' => 'Nama pengarang wajib diisi.',
            'pengarang.max' => 'Nama pengarang maksimal 100 karakter.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'penerbit.max' => 'Nama penerbit maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
            'isbn.max' => 'ISBN maksimal 20 karakter.',
            'harga.required' => 'Harga buku wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'stok.max' => 'Stok maksimal 5 untuk buku terbit sebelum tahun 2000.',
            'bahasa.required' => 'Bahasa buku wajib diisi.',
            'bahasa.max' => 'Bahasa maksimal 20 karakter.',
            'bahasa.in' => 'Untuk kategori Programming, bahasa harus Inggris.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
        ];
    }

    public function attributes()
    {
        return [
            'kode_buku' => 'Kode Buku',
            'judul' => 'Judul Buku',
            'kategori' => 'Kategori',
            'pengarang' => 'Nama Pengarang',
            'penerbit' => 'Nama Penerbit',
            'tahun_terbit' => 'Tahun Terbit',
            'isbn' => 'ISBN',
            'harga' => 'Harga',
            'stok' => 'Stok',
            'bahasa' => 'Bahasa',
            'deskripsi' => 'Deskripsi',
        ];
    }
}
