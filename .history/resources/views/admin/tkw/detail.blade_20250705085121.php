@extends('layouts.main')

@section('title', 'Detail Responden: ' . $item->nama_responden)

@push('styles')
<style>
    /* === Styling Global Kartu Detail === */
    .card-detail-section .card-title {
        color: #4e73df !important; /* Warna biru primer tema */
    }

    /* === Styling untuk Daftar Informasi "Bergaris" === */
    .info-list-lined dt, .info-list-lined dd {
        padding-top: 0.6rem;
        padding-bottom: 0.6rem;
        margin-bottom: 0;
        border-bottom: 1px solid #eaecf4;
        display: flex; /* Untuk alignment vertikal */
        align-items: center;
    }
    .info-list-lined > .row:last-of-type > dt,
    .info-list-lined > .row:last-of-type > dd {
        border-bottom: none; /* Hapus border pada item terakhir */
    }
    .info-list-lined dt {
        font-weight: 500;
        color: #5a5c69;
    }
    .info-list-lined dd {
        font-weight: 400;
    }
    
    /* === Styling untuk Kartu Ringkasan === */
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px solid #eaecf4;
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-item .label {
        color: #5a5c69;
    }
    .summary-item .value {
        font-weight: bold;
        font-size: 1.1rem;
        color: #36b9cc;
    }

    /* === Styling untuk Tabel Anggota Keluarga (dari referensi) === */
    .table-anggota-keluarga th {
        vertical-align: middle !important;
        text-align: center;
        font-size: 0.78rem;
        padding: 0.5rem 0.3rem;
        white-space: normal; /* Agar judul kolom bisa wrap */
    }
    .table-anggota-keluarga td {
        vertical-align: middle !important;
        font-size: 0.85rem;
        padding: 0.4rem;
    }
    .table-anggota-keluarga th.th-nik {
        width: 190px;
    }
    .table-anggota-keluarga td.td-nik {
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 1.5px;
        white-space: nowrap;
        text-align: center;
    }
    .alert-petunjuk-tabel ul {
        font-size: 0.8rem;
        margin-bottom: 0;
        padding-left: 20px;
    }

    /* === Styling untuk Verifikasi & TTD (dari referensi) === */
    .verification-title {
        font-size: 0.9rem;
        font-weight: bold;
        color: #495057;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    .signature-box {
        border: 1px dashed #ccc;
        padding: 15px;
        text-align: center;
        margin-top: 10px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: .25rem;
    }
    .signature-box img {
        height: 80px;
        width: 100%;
        object-fit: contain; /* Memastikan gambar pas dan tidak distorsi */
        border: 1px solid #eee;
    }
    .signature-box .signer-name {
        margin-top: 8px;
        font-weight: bold;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Aksi --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- PERUBAHAN 1: Judul dengan Badge Status --}}
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">Detail Pengajuan</h1>
            @php
                $status = $item->status_validasi;
                $badgeClass = 'secondary';
                if ($status === 'pending') $badgeClass = 'warning';
                if ($status === 'validated') $badgeClass = 'success';
                if ($status === 'rejected') $badgeClass = 'danger';
            @endphp
            <span class="badge badge-{{ $badgeClass }} p-2 ml-3" style="font-size: 0.9rem;">{{ ucfirst($status) }}</span>
        </div>
        
        {{-- PERUBAHAN 2: Menambahkan Tombol Export --}}
        <div>
            <a href="{{ route('admin.tkw.exportExcel', $item->id) }}" class="btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-download fa-sm"></i> Export ke Excel
            </a>
            <a href="{{ route('admin.tkw.showrt', ['rt' => $item->rt]) }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar RT {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}
            </a>
        </div>
        
    </div>

    {{-- Ganti seluruh blok <div class="row"> pertama Anda dengan ini --}}
    <div class="row d-flex align-items-stretch">
        {{-- DIREVISI: Menggunakan struktur dan style dari halaman user --}}
        <div class="col-lg-7 mb-4 mb-lg-0 d-flex">
            <div class="card shadow-sm card-detail-section h-100 w-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM GGGG') }}
                    </small>
                </div>
                <div class="card-body p-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-5 col-lg-4">Nama Pendata</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->nama_pendata }}</dd>
                        
                        <dt class="col-sm-5 col-lg-4">Nama Responden</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->nama_responden }}</dd>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        
                        <dt class="col-sm-5 col-lg-4">Provinsi</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->provinsi }}</dd>
                        
                        <dt class="col-sm-5 col-lg-4">Kota / Kab</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->kabupaten }}</dd>
                        
                        <dt class="col-sm-5 col-lg-4">Kecamatan</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->kecamatan }}</dd>
                        
                        <dt class="col-sm-5 col-lg-4">Desa / Kelurahan</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->desa }}</dd>
                        
                        <dt class="col-sm-5 col-lg-4">RT / RW</dt>
                        <dd class="col-sm-7 col-lg-8">{{ str_pad($item->rt, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($item->rw, 2, '0', STR_PAD_LEFT) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- DIREVISI: Menggunakan struktur dan style dari halaman user --}}
        <div class="col-lg-5 mb-4 d-flex">
            <div class="card shadow-sm h-100 w-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Rumah Tangga</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-8">Total Anggota</dt>
                        <dd class="col-4 text-right font-weight-bold">{{ $item->jart }} Orang</dd>
                        
                        <dt class="col-8">Anggota Bekerja</dt>
                        <dd class="col-4 text-right font-weight-bold">{{ $item->jart_ab }} Orang</dd>
                        
                        <dt class="col-8">Anggota Tdk/Belum Bekerja</dt>
                        <dd class="col-4 text-right font-weight-bold">{{ $item->jart_tb }} Orang</dd>

                        <dt class="col-8">Anggota Masih Sekolah</dt>
                        <dd class="col-4 text-right font-weight-bold">{{ $item->jart_ms }} Orang</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- PERUBAHAN 3 & 4: Kartu Detail Anggota Keluarga yang Lengkap + Petunjuk --}}
    <div class="card shadow-sm card-detail-section mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                <ul class="mb-0">
                    <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga</li>
                    <li><strong>NUK:</strong> Nomor Urut Anggota dalam Keluarga</li>
                    <li><strong>HDKK:</strong> Hubungan Dengan Kepala Keluarga</li>
                    <li><strong>J. Kelamin:</strong> Jenis Kelamin</li>
                    <li><strong>Sts. Kawin:</strong> Status Perkawinan</li>
                    <li><strong>Sts. Kerja:</strong> Status Pekerjaan</li>
                    <li><strong>Jns. Kerja:</strong> Jenis Pekerjaan Utama</li>
                    <li><strong>Sub Jns. Kerja:</strong> Sub Jenis Pekerjaan</li>
                    <li><strong>Pddk. Akhir:</strong> Pendidikan Terakhir</li>
                    <li><strong>Pendapatan/bln:</strong> Pendapatan Rata-rata per Bulan</li>
                </ul>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th class="th-nik">NIK</th>
                            <th>Hub. KRT</th>
                            <th>NUK</th>
                            <th>HDKK</th>
                            <th>J. Kelamin</th>
                            <th>Sts. Kawin</th>
                            <th>Sts. Kerja</th>
                            <th>Jns. Kerja</th>
                            <th>Sub Jns. Kerja</th>
                            <th>Pddk. Akhir</th>
                            <th>Pendapatan/bln</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td class="td-nik">{{ $anggota->nik }}</td>
                                <td class="text-center">{{ $anggota->hdkrt_text }}</td>
                                <td class="text-center">{{ $anggota->nuk ?? '-' }}</td>
                                <td class="text-center">{{ $anggota->hdkk_text ?? '-' }}</td>
                                <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                                <td class="text-center">{{ $anggota->status_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->sub_jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->pendidikan_terakhir_text }}</td>
                                <td class="text-right">{{ $anggota->pendapatan_per_bulan_text }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="13" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Kartu Verifikasi dan Tanda Tangan --}}
    <div class="card shadow-sm card-detail-section">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="verification-title text-center">DISERAHKAN OLEH PENDATA</p>
                    <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>{{ $item->tgl_pembuatan ? \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD / MM / YYYY') : '- / - / ----' }}</strong></div>
                    <div class="signature-box">
                        {{-- PERUBAHAN 5: Memperbaiki path gambar dengan asumsi ada di subfolder 'ttd/pendata' --}}
                        @if($item->ttd_pendata)
                            <img src="{{ asset('storage/ttd/pendata/' . $item->ttd_pendata) }}" alt="TTD Pendata">
                            <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata' }} )</p>
                        @else
                            <p class="text-muted my-5"><em>Belum ada TTD Pendata</em></p>
                            <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata' }} )</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                     <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                     <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>{{ $item->admin_tgl_validasi ? \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD / MM / YYYY') : '- / - / ----' }}</strong></div>
                     <div class="signature-box">
                         {{-- Menggunakan logika TTD dari referensi Anda --}}
                        @if($item->admin_ttd_kepala_dusun)
                            <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun) }}" alt="TTD Kepala Dusun">
                            <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Kepala Dusun' }} )</p>
                        @elseif($item->admin_ttd_pendata && !$item->admin_ttd_kepala_dusun)
                            <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" alt="TTD Verifikator">
                            <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator' }} )</p>
                        @else
                            <p class="text-muted my-5"><em>Belum Diverifikasi</em></p>
                            <p class="signer-name">( .................................................. )</p>
                        @endif
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection