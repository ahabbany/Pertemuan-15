<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreAnggotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_anggota' => 'required|string|max:20|unique:anggota,kode_anggota',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:anggota,email|max:100',
            'telepon' => [
                'required',
                'regex:/^(\+62|62|0)[0-9]{9,12}$/',
                'min:10',
                'max:15',
            ],
            'alamat' => 'required|string',
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|string|max:50',
            'tanggal_daftar' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'status' => 'required|in:Aktif,Nonaktif',
        ];
    }
 
    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'kode_anggota.required' => 'Kode anggota wajib diisi.',
            'kode_anggota.unique' => 'Kode anggota sudah digunakan.',
            'kode_anggota.max' => 'Kode anggota maksimal 20 karakter.',
            
            'nama.required' => 'Nama anggota wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.max' => 'Email maksimal 100 karakter.',
            
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890 atau +6281234567890',
            'telepon.min' => 'Nomor telepon minimal 10 karakter.',
            'telepon.max' => 'Nomor telepon maksimal 15 karakter.',
            
            'alamat.required' => 'Alamat wajib diisi.',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_lahir.after' => 'Tanggal lahir tidak valid.',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            
            'pekerjaan.max' => 'Pekerjaan maksimal 50 karakter.',
            
            'tanggal_daftar.required' => 'Tanggal pendaftaran wajib diisi.',
            'tanggal_daftar.date' => 'Format tanggal pendaftaran tidak valid.',
            'tanggal_daftar.before_or_equal' => 'Tanggal pendaftaran tidak boleh di masa depan.',
            
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }
 
    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'kode_anggota' => 'kode anggota',
            'nama' => 'nama',
            'email' => 'email',
            'telepon' => 'nomor telepon',
            'alamat' => 'alamat',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'pekerjaan' => 'pekerjaan',
            'tanggal_daftar' => 'tanggal pendaftaran',
            'status' => 'status',
        ];
    }
}