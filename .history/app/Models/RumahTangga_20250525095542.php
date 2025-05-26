<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RumahTangga extends Model
{
    use HasFactory;

    protected $table = 'fm_rumah_tangga';

    // $fillable, $casts, dan relasi
    protected $fillable = [
        'provinsi', 'kabupaten', 'kecamatan', 'desa', 'rt', 'rw',
        'tgl_pembuatan', 'nama_pendata', 'nama_responden',
        'jart', 'jart_ab', 'jart_tb', 'jart_ms', 'jpr2rtp',
        'verif_tgl_pembuatan', 'verif_nama_pendata', 'ttd_pendata',
        'status_validasi', 'user_id',
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

    // ... (Accessor lainnya seperti statusValidasiText, jpr2rtpText) ...
}