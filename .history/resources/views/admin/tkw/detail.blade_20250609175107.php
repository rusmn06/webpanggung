@extends('layouts.app')

@section('title', 'Detail Responden: ' . $item->nama_responden)

@push('styles')
<style>
    /* Semua style yang sudah kita buat sebelumnya */
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { max-height: 80px; max-width: 100%; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .verification-title { font-size: 0.9rem; font-weight: bold; color: #495057; margin-bottom: 5px; text-transform: uppercase; }
    .card-detail-section .card-title { color: #4e73df !important; }
    .info-list-lined dt, .info-list-lined dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; border-bottom: 1px solid #eaecf4; }
    .info-list-lined > .row:last-child > dt, .info-list-lined > .row:last-child > dd { border-bottom: none; }
    .info-list-lined dt { font-weight: 500; color: #5a5c69; }
    .info-list-lined dd { font-weight: 400; }
    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid #eaecf4; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: #5a5c69; }
    .summary-item .value { font-weight: bold; font-size: 1.1rem; color: #36b9cc; }
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .table-anggota-keluarga th.th-nik { width: 190px; }
    .table-anggota-keluarga td.td-nik { font-family: 'Courier New', monospace; letter-spacing: 1.5px; white-space: nowrap; text-align: center; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Aksi --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
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
        
        <div>
            <a href="{{ route('admin.tkw.exportExcel', $item->id) }}" class="btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-download fa-sm"></i> Export ke Excel
            </a>
            <a href="{{ route('admin.tkw.showrt', ['rt' => $item->rt]) }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar RT {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}
            </a>
        </div>
    </div>

    {{-- BARIS UTAMA: Info Pengajuan & Ringkasan --}}
    <div class="row">
        <div class="col-lg-7 mb-4">
            {{-- Kartu Informasi Pengajuan --}}
            <div class="card shadow-sm card-detail-section h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6></div>
                <div class="card-body">
                    <dl class="dl-horizontal info-list-lined mb-0">
                        <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kabupaten/Kota</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $item->desa }}</dd></div>
                        <div class="row"><dt class="col-sm-4">RT / RW</dt><dd class="col-sm-8">{{ $item->rt }} / {{ $item->rw }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            {{-- Kartu Ringkasan --}}
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Ringkasan Rumah Tangga</h6></div>
                <div class="card-body">
                    <div class="summary-item"><span class="label"><i class="fas fa-users fa-fw mr-2 text-gray-400"></i>Total Anggota</span><span class="value">{{ $item->anggotaKeluarga->count() }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-briefcase fa-fw mr-2 text-gray-400"></i>Anggota Bekerja</span><span class="value">{{ $item->anggotaKeluarga->where('status_pekerjaan', 1)->count() }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-home fa-fw mr-2 text-gray-400"></i>Anggota Tidak Bekerja</span><span class="value">{{ $item->anggotaKeluarga->where('status_pekerjaan', '!=', 1)->count() }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Anggota Keluarga --}}
    <div class="card shadow-sm card-detail-section mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                <ul class="mb-0 pl-3">
                    <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga</li>
                    <li><strong>NUK:</strong> Nomor Urut Anggota dalam Keluarga</li>
                    <li>...dan seterusnya seperti di referensi...</li>
                </ul>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                    {{-- Tabel detail anggota dari referensi Anda --}}
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th class="th-nik">NIK</th>
                            <th>Hub. KRT</th>
                            <th>NUK</th>
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
                                <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                                <td class="text-center">{{ $anggota->status_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->sub_jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->pendidikan_terakhir_text }}</td>
                                <td class="text-right">{{ $anggota->pendapatan_per_bulan_text }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm card-detail-section">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="verification-title text-center">DISERAHKAN OLEH PENDATA</p>
                    <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>{{ $item->tgl_pembuatan ? \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD / MM / GGGG') : '- / - / ----' }}</strong></div>
                    <div class="signature-box">
                        @if($item->ttd_pendata)
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="TTD Pendata">
                            <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                        @else
                            <p class="text-muted my-5"><em>Belum ada TTD Pendata</em></p>
                            <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                    @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
                        <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD / MM / GGGG') }}</strong></div>
                        <div class="signature-box">
                            @if($item->admin_ttd_kepala_dusun)
                                <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun) }}" alt="TTD Kepala Dusun">
                                <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Kepala Dusun Tidak Ada' }} )</p>
                            @elseif($item->admin_ttd_pendata && !$item->admin_ttd_kepala_dusun)
                                <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" alt="TTD Verifikator">
                                <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                            @else
                                <p class="text-muted my-5"><em>Belum ada TTD Verifikator</em></p>
                                <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                            @endif
                        </div>
                    @else
                        <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>- / - / ----</strong></div>
                        <div class="signature-box">
                            <p class="text-muted my-5"><em>Belum Diverifikasi</em></p>
                            <p class="signer-name">( .................................................. )</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection