<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenagaKerja extends Model {
use HasFactory;

protected $table = 'tb_tenagakerja';

protected $fillable = [
    'provinsi','kabupaten','kecamatan','desa','rt','rw','tgl_pembuatan','nama_pendata','nama_responden',
    'nama','nik','hdkrt','nuk','hdkk','kelamin','status_perkawinan','status_pekerjaan','jenis_pekerjaan',
    'sub_jenis_pekerjaan','pendidikan_terakhir','pendapatan_per_bulan',
    'jart','jart_ab','jart_tb','jart_ms','jpr2rtp',
    'verif_tgl_pembuatan','verif_nama_pendata','ttd_pendata','admin_tgl_validasi',
    'admin_nama_kepaladusun','admin_ttd_pendata','status_validasi',
];

};