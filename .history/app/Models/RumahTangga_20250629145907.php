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
        'user_sequence_number',
        'admin_catatan',
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
        switch ($this->attributes['jpr2rtp']) {
            
            case '1':
                return 'Pendapat Rumah Tangga di Atas Rp 500.000';
            case '2':
                return 'Pendapat Rumah Tangga di Atas Rp 1.000.000';
            case '3':
                return 'Pendapat Rumah Tangga di Atas Rp 2.000.000';
            case '4':
                return 'Pendapat Rumah Tangga di Atas Rp 4.000.000';
            case '5 ':
                return 'Tidak Ada Pendapatan';
            default:
                return 'Tidak Diketahui/Kosong';
        }
    }

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