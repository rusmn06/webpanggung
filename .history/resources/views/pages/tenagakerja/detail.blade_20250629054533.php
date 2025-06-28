@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    /* Style yang disesuaikan untuk konsistensi */
    .info-list-split dt, .info-list-split dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; }
    .info-list-split .row { border-bottom: 1px solid #eaecf4; }
    .info-list-split .row:last-child { border-bottom: none; }
    .info-list-split dt { font-weight: 500; color: #5a5c69; }

    /* Style untuk sub-judul di dalam kartu */
    .sub-header-info { font-size: 0.9rem; font-weight: bold; color: #4e73df; margin-top: 1rem; margin-bottom: 0.25rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4e73df; display: inline-block; }
    #detail-card .card-body > .sub-header-info:first-of-type { margin-top: 0; }
    
    /* Style untuk tanda tangan */
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { max-height: 80px; width: 100%; object-fit: contain; border: 1px solid #eee; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .verification-title { font-size: 0.9rem; font-weight: bold; color: #495057; margin-bottom: 5px; text-transform: uppercase; }

    /* Style untuk tabel anggota keluarga */
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; margin-bottom: 0; padding-left: 20px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">Detail Pengajuan Ke-{{ $item->user_sequence_number ?? '??' }}</h1>
            @php
                $status = $item->status_validasi;
                $statusText = ucfirst($status);
                $badgeClass = 'secondary';
                if ($status === 'pending') { $badgeClass = 'warning'; $statusText = 'Menunggu Validasi'; }
                if ($status === 'validated') { $badgeClass = 'success'; $statusText = 'Tervalidasi'; }
                if ($status === 'rejected') { $badgeClass = 'danger'; $statusText = 'Ditolak'; }
            @endphp
            <span class="badge badge-{{ $badgeClass }} p-2 ml-3" style="font-size: 0.9rem;">{{ $statusText }}</span>
        </div>
        <div>
            <a href="{{ route('tenagakerja.exportExcel', ['id' => $item->id]) }}" class="btn btn-sm btn-success shadow-sm mr-2"><i class="fas fa-download fa-sm"></i> Export ke Excel</a>
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat</a>
        </div>
    </div>

    {{-- Alert jika ada catatan dari admin --}}
    @if($item->admin_catatan)
        <div class="alert alert-{{ $item->status_validasi === 'rejected' ? 'danger' : 'info' }}">
            <strong>Catatan dari Admin:</strong> {{ $item->admin_catatan }}
        </div>
    @endif

    <div class="row">
        {{-- KOLOM KIRI: KARTU INFORMASI GABUNGAN --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100" id="detail-card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Pengajuan</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM YYYY') }}</small>
                </div>
                <div class="card-body py-3 px-3">
                    <h6 class="sub-header-info">Informasi Pengajuan:</h6>
                    <dl class="info-list-split mb-0">
                        <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                    </dl>
                    <h6 class="sub-header-info">Lokasi:</h6>
                    <dl class="info-list-split mb-0">
                        <div class="row"><dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kabupaten/Kota</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $item->desa }}</dd></div>
                        <div class="row"><dt class="col-sm-4">RT / RW</dt><dd class="col-sm-8">{{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} / {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: KARTU RINGKASAN --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Ringkasan Rumah Tangga</h6></div>
                <div class="card-body py-2 px-3">
                    <dl class="info-list-split mb-0">
                        <div class="row"><dt class="col-sm-8">Total Anggota</dt><dd class="col-sm-4 text-right font-weight-bold">{{ $item->jart }}</dd></div>
                        <div class="row"><dt class="col-sm-8">Anggota Bekerja</dt><dd class="col-sm-4 text-right font-weight-bold">{{ $item->jart_ab }}</dd></div>
                        <div class="row"><dt class="col-sm-8">Anggota Tidak Bekerja</dt><dd class="col-sm-4 text-right font-weight-bold">{{ $item->jart_tb }}</dd></div>
                        <div class="row"><dt class="col-sm-8">Anggota Masih Sekolah</dt><dd class="col-sm-4 text-right font-weight-bold">{{ $item->jart_ms }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU TABEL ANGGOTA KELUARGA --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                {{-- PETUNJUK TABEL DENGAN STYLE BARU YANG LEBIH MUDAH DIBACA --}}
                <ul class="mb-0 pl-3">
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
                            <th>No</th><th>Nama</th><th>NIK</th><th>Hub. KRT</th><th>NUK</th><th>HDKK</th><th>J. Kelamin</th><th>Sts. Kawin</th><th>Sts. Kerja</th><th>Jns. Kerja</th><th>Sub Jns. Kerja</th><th>Pddk. Akhir</th><th>Pendapatan/bln</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td class="text-center font-weight-bold">{{ $anggota->nik }}</td>
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

    {{-- KARTU TANDA TANGAN --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tanda Tangan & Validasi</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="verification-title text-center">DIAJUKAN OLEH PENDATA</p>
                    <div class="text-center mb-2"><small>Pada: {{ $item->verif_tgl_pembuatan ? \Carbon\Carbon::parse($item->verif_tgl_pembuatan)->isoFormat('D MMMM YYYY') : '-' }}</small></div>
                    <div class="signature-box">
                        @if($item->ttd_pendata && file_exists(public_path('storage/ttd/pendata/'.$item->ttd_pendata)))
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="TTD Pendata">
                            <p class="signer-name">( {{ $item->verif_nama_pendata ?? 'Nama Pendata' }} )</p>
                        @else
                            <p class="text-muted my-auto"><em>Tidak ada Tanda Tangan</em></p>
                            <p class="signer-name">( {{ $item->verif_nama_pendata ?? 'Nama Pendata' }} )</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="verification-title text-center">DIVERIFIKASI OLEH ADMIN</p>
                    <div class="text-center mb-2"><small>Pada: {{ $item->admin_tgl_validasi ? \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') : '-' }}</small></div>
                    <div class="signature-box">
                        @if($item->status_validasi !== 'pending')
                            @if($item->admin_ttd_pendata && file_exists(public_path('storage/ttd/admin/'.$item->admin_ttd_pendata)))
                                <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" alt="TTD Verifikator">
                                <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator' }} )</p>
                            @else
                                <p class="text-muted my-auto"><em>Tidak ada Tanda Tangan</em></p>
                                <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator' }} )</p>
                            @endif
                        @else
                            <p class="text-muted my-auto"><em>Menunggu Verifikasi</em></p>
                            <p class="signer-name">( .................................................. )</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection