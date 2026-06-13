<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
 
class UpdateAnggotaRequest extends FormRequest
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
        // Get anggota ID from route parameter
        $anggotaId = $this->route('anggota');
        
        return [
            'kode_anggota' => 'required|string|max:20|unique:anggota,kode_anggota,' . $anggotaId,
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:anggota,email,' . $anggotaId . '|max:100',
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
            'kode_anggota.unique' => 'Kode anggota sudah digunakan.',
            'email.unique' => 'Email sudah terdaftar.',
            'telepon.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_daftar.before_or_equal' => 'Tanggal pendaftaran tidak boleh di masa depan.',
        ];
    }
}