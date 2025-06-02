<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumahTangga extends Model
{
    use HasFactory;

    protected $table = 'fm_rumah_tangga';

    protected $fillable = [
        'provinsi', 'kabupaten', 'kecamatan', 'desa', 'rt', 'rw',
        'tgl_pembuatan', 'nama_pendata', 'nama_responden',
        'jart', 'jart_ab', 'jart_tb', 'jart_ms', 'jpr2rtp',
        'verif_tgl_pembuatan', 'verif_nama_pendata', 'ttd_pendata',
        'status_validasi',
        'user_id',
        'admin_tgl_validasi', 'admin_nama_kepaladusun', 'admin_ttd_pendata',
    ];

    protected $casts = [
        'tgl_pembuatan' => 'date',
        'verif_tgl_pembuatan' => 'date',
        'admin_tgl_validasi' => 'date',
    ];

    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ---  ACCESSOR  ---

    public function getJpr2rtpTextAttribute(): string
    {
        // $this->jpr2rtp akan mengambil nilai asli dari kolom 'jpr2rtp'
        switch ($this->attributes['jpr2rtp']) { // Lebih baik akses via $this->attributes['nama_kolom'] di accessor
            case '0':
                return 'Tidak Ada Pendapatan';
            case '1':
                return '< Rp 500.000';
            case '2':
                return 'Rp 500.000 - Rp 1.000.000';
            case '3':
                return 'Rp 1.000.001 - Rp 2.000.000';
            case '4':
                return '> Rp 2.000.000';
            default:
                return 'Tidak Diketahui/Kosong'; // Teks default jika nilainya tidak cocok
        }
    }

    /**
     * Accessor untuk mendapatkan teks dari status validasi.
     * Cara panggil nanti: $rumahTangga->status_validasi_text
     */
    public function getStatusValidasiTextAttribute(): string
    {
        switch ($this->attributes['status_validasi']) {
            case 'pending':
                return 'Pending';
            case 'validated':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            default:
                return ucfirst($this->attributes['status_validasi'] ?? 'Belum Ada Status');
        }
    }

}