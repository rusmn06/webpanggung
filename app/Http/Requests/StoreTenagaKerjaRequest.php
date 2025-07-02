<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenagaKerjaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set ke true agar semua pengguna yang terautentikasi bisa menggunakan request ini.
        // Anda bisa menambahkan logika otorisasi yang lebih spesifik di sini jika perlu.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Aturan dasar yang berlaku untuk create dan update
        $rules = [
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'tgl_pembuatan' => 'required|date',
            'nama_pendata' => 'required|string|max:100',
            'nama_responden' => 'required|string|max:100',
            'jpr2rtp' => 'required|in:1,2,3,4,5',
            'verif_nama_pendata' => 'required|string|max:100',
            'verif_tgl_pembuatan' => 'required|date',
            'nama' => 'required|array|min:1',
            'nama.*' => 'required|string|max:100',
            'nik' => 'required|array|min:1',
            'nik.*' => 'required|string|digits:16|distinct',
            'kelamin' => 'required|array',
            'kelamin.*' => 'required|in:1,2',
            // ... (lanjutkan semua aturan lain untuk anggota keluarga)
            'hdkrt' => 'required|array',
            'hdkrt.*' => 'required|integer',
            'hdkk' => 'required|array',
            'hdkk.*' => 'required|integer',
            'nuk' => 'required|array',
            'nuk.*' => 'required|integer',
            'status_perkawinan' => 'required|array',
            'status_perkawinan.*' => 'required|integer',
            'status_pekerjaan' => 'required|array',
            'status_pekerjaan.*' => 'required|integer',
            'pendidikan_terakhir' => 'required|array',
            'pendidikan_terakhir.*' => 'required|integer',
            'jenis_pekerjaan' => 'nullable|array',
            'jenis_pekerjaan.*' => 'nullable|integer',
            'sub_jenis_pekerjaan' => 'nullable|array',
            'sub_jenis_pekerjaan.*' => 'nullable|integer',
            'pendapatan_per_bulan' => 'nullable|array',
            'pendapatan_per_bulan.*' => 'nullable|integer',
        ];

        // ### LOGIKA KONDISIONAL UNTUK TANDA TANGAN ###
        if ($this->isMethod('POST')) {
            // Jika ini adalah request CREATE (membuat baru), TTD wajib diisi.
            $rules['ttd_pendata'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            // Jika ini adalah request UPDATE (mengubah), TTD bersifat opsional.
            // Aturan hanya akan dicek jika pengguna MENGUNGGAH file baru.
            $rules['ttd_pendata'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nama.min' => 'Minimal harus ada 1 anggota keluarga.',
            'nik.*.distinct' => 'Terdapat NIK yang duplikat. Pastikan semua NIK unik.',
            'nik.*.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'ttd_pendata.required' => 'Tanda tangan pendata wajib diunggah.',
            'jpr2rtp.required' => 'Kategori pendapatan rata-rata rumah tangga wajib dipilih.',
        ];
    }
}