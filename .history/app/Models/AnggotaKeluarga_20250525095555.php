<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    protected $table = 'fm_anggota_keluarga';

    // $fillable, relasi, dan accessor lainnya
    protected $fillable = [
        'rumah_tangga_id',
        'nama', 'nik', 'hdkrt', 'nuk', 'hdkk', 'kelamin',
        'status_perkawinan', 'status_pekerjaan', 'jenis_pekerjaan',
        'sub_jenis_pekerjaan', 'pendidikan_terakhir', 'pendapatan_per_bulan',
    ];

    public function rumahTangga()
    {
        return $this->belongsTo(RumahTangga::class);
    }

}