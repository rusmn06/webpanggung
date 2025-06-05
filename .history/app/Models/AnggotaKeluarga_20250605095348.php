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
        'hdkrt',                  // Hubungan Dengan Kepala Rumah Tangga
        'nuk',
        'hdkk',
        'kelamin',
        'status_perkawinan',
        'status_pekerjaan',
        'jenis_pekerjaan',
        'sub_jenis_pekerjaan',
        'pendidikan_terakhir',
        'pendapatan_per_bulan', // Kolom target untuk accessor baru
    ];

    public function rumahTangga()
    {
        return $this->belongsTo(RumahTangga::class);
    }

    // --- ACCESSOR  ---

    /**
     * Accessor untuk Jenis Kelamin.
     */
    public function getKelaminTextAttribute(): string
    {
        switch ($this->attributes['kelamin']) {
            case '1':
                return 'Laki-laki';
            case '2':
                return 'Perempuan';
            default:
                return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Hubungan Dengan Kepala Rumah Tangga (HDKRT).
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
            case '7': return 'Pembantu Rumah Tangga';
            case '8': return 'Lainnya';
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Hubungan Dengan Kepala keluarga (HDKK).
     */
    public function getHdkkTextAttribute(): string
    {
        // Asumsi logika HDKK sama dengan HDKRT, jika berbeda silakan disesuaikan
        switch ($this->attributes['hdkk']) {
            case '1': return 'Kepala Keluarga';
            case '2': return 'Istri/Suami';
            case '3': return 'Anak';
            case '4': return 'Menantu';
            case '5': return 'Cucu';
            case '6': return 'Orang Tua/Mertua';
            case '7': return 'Pembantu Rumah Tangga';
            case '8': return 'Lainnya';
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Pendidikan Terakhir.
     */
    public function getPendidikanTerakhirTextAttribute(): string
    {
        switch ($this->attributes['pendidikan_terakhir']) {
            case '1': return 'Tidak/Belum Sekolah';
            case '2': return 'Tamat SD/Sederajat';
            case '3': return 'Tamat SMP/Sederajat';
            case '4': return 'Tamat SMA/Sederajat';
            case '5': return 'Tamat Perguruan Tinggi (Diploma,S1, S2, S3)';
            case '6': return 'Tidak Pernah Sekolah'; // Anda memiliki 'Tidak Pernah Sekolah' di sini, pastikan ini benar dan berbeda dari 'Tidak/Belum Sekolah'
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Status Pekerjaan.
     */
    public function getStatusPekerjaanTextAttribute(): string
    {
        switch ($this->attributes['status_pekerjaan']) {
            case '1': return 'Bekerja';
            case '2': return 'Ibu Rumah Tangga';
            case '3': return 'Bersekolah';
            case '4': return 'Tidak/Belum Bekerja';
            case '5': return 'Lainnya';
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Jenis Pekerjaan.
     */
    public function getJenisPekerjaanTextAttribute(): string
    {
        // Hanya tampilkan jenis pekerjaan jika status_pekerjaan adalah 'Bekerja' (nilai '1')
        if (isset($this->attributes['status_pekerjaan']) && $this->attributes['status_pekerjaan'] == '1') {
            if (!isset($this->attributes['jenis_pekerjaan'])) {
                return 'Jenis Pekerjaan Tidak Diisi'; // Atau nilai default lain yang sesuai
            }
            switch ($this->attributes['jenis_pekerjaan']) {
                case '1': return 'PNS/TNI dan POLRI';
                case '2': return 'Karyawan, Honorer';
                case '3': return 'Wiraswasta';
                case '4': return 'Lainnya';
                default: return 'Jenis Pekerjaan Tidak Terdefinisi';
            }
        }
        // Jika status pekerjaan bukan 'Bekerja' atau tidak diset, kembalikan strip atau teks lain.
        return '-';
    }

    /**
     * Accessor untuk Sub Jenis Pekerjaan.
     * (Nama method diperbaiki dari getJSubenisPekerjaanTextAttribute menjadi getSubJenisPekerjaanTextAttribute)
     */
    public function getSubJenisPekerjaanTextAttribute(): string
    {
        if (!isset($this->attributes['sub_jenis_pekerjaan']) || $this->attributes['sub_jenis_pekerjaan'] === null || $this->attributes['sub_jenis_pekerjaan'] === '') {
            return '-';
        }
        switch ($this->attributes['sub_jenis_pekerjaan']) {
            case '1': return 'Aparatur Pemerintah/Negara';
            case '2': return 'Tenaga Ahli/Professional';
            case '3': return 'Tenaga Kerja Harian';
            case '4': return 'Pengusaha/Wira Usaha';
            case '5': return 'Lainnya';
            default: return 'Sub Jenis Pekerjaan Tidak Terdefinisi';
        }
    }

    /**
     * Accessor untuk Status Perkawinan.
     */
    public function getStatusPerkawinanTextAttribute(): string
    {
        switch ($this->attributes['status_perkawinan']) {
            case '1': return 'Belum Kawin';
            case '2': return 'Kawin';
            case '3': return 'Cerai Hidup';
            case '4': return 'Cerai Mati';
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Pendapatan Per Bulan.
     */
    public function getPendapatanPerBulanTextAttribute(): string
    {
        if (!isset($this->attributes['pendapatan_per_bulan'])) {
            return 'Data Tidak Terverifikasi';
        }
        switch ($this->attributes['pendapatan_per_bulan']) {
            case '0':
                return 'Tidak Ada Pendapatan';
            case '1':
                return 'Pendapatan di Atas Rp 500.000';
            case '2':
                return 'Pendapatan di Atas Rp 1.000.000';
            case '3':
                return 'Pendapatan di Atas Rp 2.000.000';
            case '4':
                return 'Pendapatan di Atas Rp 4.000.000';
            default:
                return 'Data Tidak Terverifikasi';
        }
    }
}