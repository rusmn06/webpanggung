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
        'nama',
        'nik',
        'hdkrt',                  // Hubungan Dengan Kepala Rumah Tangga
        'nuk',                    // Nomor Urut Keluarga
        'hdkk',                   // Hubungan Dengan Kepala Keluarga
        'kelamin',
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

    // --- ACCESSORS ---

    /**
     * Accessor untuk NIK yang diformat.
     */
    public function getFormattedNikAttribute(): string
    {
        $nik = $this->attributes['nik'] ?? null;

        if (empty($nik)) {
            return '-';
        }

        $cleanedNik = preg_replace('/[^0-9]/', '', (string) $nik);

        if (strlen($cleanedNik) == 16) {
            return substr($cleanedNik, 0, 6) . ' ' . substr($cleanedNik, 6, 6) . ' ' . substr($cleanedNik, 12, 4);
        }
        return $cleanedNik;
    }

    /**
     * Accessor untuk Jenis Kelamin.
     */
    public function getKelaminTextAttribute(): string
    {
        if (!isset($this->attributes['kelamin'])) {
            return 'Data Tidak Terverifikasi';
        }
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
        if (!isset($this->attributes['hdkrt'])) {
            return 'Data Tidak Terverifikasi';
        }
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
        if (!isset($this->attributes['hdkk'])) {
            return 'Data Tidak Terverifikasi';
        }
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
        if (!isset($this->attributes['pendidikan_terakhir'])) {
            return 'Data Tidak Terverifikasi';
        }
        switch ($this->attributes['pendidikan_terakhir']) {
            case '1': return 'Tidak/Belum Sekolah';
            case '2': return 'Tamat SD/Sederajat';
            case '3': return 'Tamat SMP/Sederajat';
            case '4': return 'Tamat SMA/Sederajat';
            case '5': return 'Tamat Perguruan Tinggi (Diploma,S1, S2, S3)';
            case '6': return 'Tidak Pernah Sekolah';
            default: return 'Data Tidak Terverifikasi';
        }
    }

    /**
     * Accessor untuk Status Pekerjaan.
     */
    public function getStatusPekerjaanTextAttribute(): string
    {
        if (!isset($this->attributes['status_pekerjaan'])) {
            return 'Data Tidak Terverifikasi';
        }
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
        if (!isset($this->attributes['jenis_pekerjaan']) || $this->attributes['jenis_pekerjaan'] === null || $this->attributes['jenis_pekerjaan'] === '') {
            return '-';
        }
        switch ($this->attributes['jenis_pekerjaan']) {
            case '1': return 'PNS/TNI dan POLRI';
            case '2': return 'Karyawan, Honorer';
            case '3': return 'Wiraswasta';
            case '4': return 'Lainnya';
            default: return 'Jenis Pekerjaan Tidak Terdefinisi';
        }
    }

    /**
     * Accessor untuk Sub Jenis Pekerjaan.
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
        if (!isset($this->attributes['status_perkawinan'])) {
            return 'Data Tidak Terverifikasi';
        }
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
        if (!isset($this->attributes['pendapatan_per_bulan']) || $this->attributes['pendapatan_per_bulan'] === null) {
            return '-';
        }
        switch ($this->attributes['pendapatan_per_bulan']) {
            case '1': return 'Pendapatan di Atas Rp 500.000';
            case '2': return 'Pendapatan di Atas Rp 1.000.000';
            case '3': return 'Pendapatan di Atas Rp 2.000.000';
            case '4': return 'Pendapatan di Atas Rp 4.000.000';
            case '5': return 'Tidak Ada Pendapatan';
            default: return 'Data Tidak Terverifikasi';
        }
    }
}