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
     */
    public function getKelaminTextAttribute(): string
    {
        switch ($this->attributes['kelamin']) {
            case '1':
                return 'Laki-laki';
            case '2':
                return 'Perempuan';
            default:
                return 'Tidak Diketahui';
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
            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Pendidikan Terakhir.
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
            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Status Pekerjaan.
     */
    public function getStatusPekerjaanTextAttribute(): string
    {
        switch ($this->attributes['status_pekerjaan']) {
            case '1': return 'Bekerja';
            case '2': return 'Tidak Bekerja (Mencari Pekerjaan)';
            case '3': return 'Tidak Bekerja (Sekolah/Kuliah)';  
            case '4': return 'Tidak Bekerja (Mengurus Rumah Tangga)';
            case '5': return 'Tidak Bekerja (Lainnya)';         

            default: return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk Jenis Pekerjaan.
     */
    public function getJenisPekerjaanTextAttribute(): string
    {
        if ($this->attributes['status_pekerjaan'] == '1') {
            switch ($this->attributes['jenis_pekerjaan']) {
                case '1': return 'PNS/TNI/POLRI/BUMN/BUMD';
                case '2': return 'Karyawan Swasta';         
                case '3': return 'Wiraswasta/Pengusaha';    
                case '4': return 'Pekerja Lepas/Serabutan'; 
                default: return 'Jenis Pekerjaan Tidak Terdefinisi';
            }
        }
        return '-';
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