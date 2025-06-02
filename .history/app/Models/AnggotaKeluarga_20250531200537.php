<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    protected $table = 'fm_anggota_keluarga';

    protected $fillable = [
        'rumah_tangga_id',
        'nama', 'nik',
        'hdkrt',              // Hubungan Dengan Kepala Rumah Tangga
        'nuk',
        'hdkk',
        'kelamin',            // Jenis Kelamin
        'status_perkawinan',
        'status_pekerjaan',
        'jenis_pekerjaan',
        'sub_jenis_pekerjaan',
        'pendidikan_terakhir',
        'pendapatan_per_bulan',
    ];

    public function rumahTangga()
    {
        return $this->belongsTo(RumahTangga::class);
    }

    // --- ACCESSOR  ---

    /**
     * Accessor untuk Jenis Kelamin.
     * Panggil: $anggota->kelamin_text
     */
    public function getKelaminTextAttribute(): string
    {
        switch ($this->attributes['kelamin']) {
            case '1': // Asumsi 1 = Laki-laki
                return 'Laki-laki';
            case '2': // Asumsi 2 = Perempuan
                return 'Perempuan';
            default:
                return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Hubungan Dengan Kepala Rumah Tangga (HDKRT).
     * Panggil: $anggota->hdkrt_text
     */
    public function getHdkrtTextAttribute(): string
    {
        switch ($this->attributes['hdkrt']) {
            case '1': return 'Kepala Keluarga';
            case '2': return 'Istri/Suami';
            case '3': return 'Anak';
            case '4': return 'Menantu';
            case '5': return 'Cucu';
            case '6': return 'Orang Tua/Mertua';
            case '7': return 'Pembantu Rumah Tangga'; // Sesuai Blade Anda dulu
            case '8': return 'Lainnya';
            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Pendidikan Terakhir.
     * Panggil: $anggota->pendidikan_terakhir_text
     */
    public function getPendidikanTerakhirTextAttribute(): string
    {
        switch ($this->attributes['pendidikan_terakhir']) {
            case '1': return 'Tidak/Belum Sekolah';
            case '2': return 'SD/Sederajat';
            case '3': return 'SMP/Sederajat';
            case '4': return 'SMA/Sederajat';
            case '5': return 'Diploma I/II/III';
            case '6': return 'S1/S2/S3 (Akademi/Universitas)';
            // Tambahkan case lain jika ada
            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Status Pekerjaan.
     * Panggil: $anggota->status_pekerjaan_text
     */
    public function getStatusPekerjaanTextAttribute(): string
    {
        switch ($this->attributes['status_pekerjaan']) {
            case '1': return 'Bekerja';
            case '2': return 'Tidak Bekerja (Mencari Pekerjaan)'; // Contoh
            case '3': return 'Tidak Bekerja (Sekolah/Kuliah)';   // Contoh
            case '4': return 'Tidak Bekerja (Mengurus Rumah Tangga)'; // Contoh
            case '5': return 'Tidak Bekerja (Lainnya)';          // Contoh
            // Sesuaikan dengan definisi di Blade Anda:
            // $anggota->status_pekerjaan_text (sudah ada)
            // if($anggota->status_pekerjaan == '1') -> ini berarti '1' adalah bekerja
            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Jenis Pekerjaan.
     * Hanya relevan jika status_pekerjaan adalah 'Bekerja' (asumsi kode '1').
     * Panggil: $anggota->jenis_pekerjaan_text
     */
    public function getJenisPekerjaanTextAttribute(): string
    {
        if ($this->attributes['status_pekerjaan'] == '1') { // Asumsi '1' adalah kode untuk "Bekerja"
            switch ($this->attributes['jenis_pekerjaan']) {
                case '1': return 'PNS/TNI/POLRI/BUMN/BUMD'; // Contoh
                case '2': return 'Karyawan Swasta';          // Contoh
                case '3': return 'Wiraswasta/Pengusaha';     // Contoh
                case '4': return 'Pekerja Lepas/Serabutan';  // Contoh
                // Tambahkan case lain jika ada, sesuaikan dengan Blade Anda: $anggota->jenis_pekerjaan_text
                default: return 'Jenis Pekerjaan Tidak Terdefinisi';
            }
        }
        return '-'; // Atau string kosong jika tidak bekerja atau jenis pekerjaan tidak berlaku
    }

    /**
     * Accessor untuk Status Perkawinan.
     * Panggil: $anggota->status_perkawinan_text
     */
    public function getStatusPerkawinanTextAttribute(): string
    {
        switch ($this->attributes['status_perkawinan']) {
            case '1': return 'Belum Kawin';
            case '2': return 'Kawin';
            case '3': return 'Cerai Hidup';
            case '4': return 'Cerai Mati';
            // Sesuaikan dengan Blade Anda: $anggota->status_perkawinan_text
            default: return 'Tidak Diketahui';
        }
    }
}