@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    /* Gaya tambahan untuk kartu verifikasi */
    .signature-box {
        border: 1px dashed #ccc;
        padding: 15px;
        text-align: center;
        margin-top: 10px;
        min-height: 120px; /* Memberikan ruang jika TTD belum ada */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: .25rem;
    }
    .signature-box img {
        max-height: 80px;
        max-width: 100%;
        border: 1px solid #eee;
    }
    .signature-box .signer-name {
        margin-top: 8px;
        font-weight: bold;
        font-size: 0.85rem;
    }
    .signature-box .signature-date {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .verification-title {
        font-size: 0.9rem;
        font-weight: bold;
        color: #495057;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    .info-list-condensed dt {
        font-weight: normal; /* Sedikit lebih tipis dari default */
        color: #5a5c69;
    }
    .info-list-condensed dd {
        font-weight: 500; /* Sedikit lebih tebal untuk nilai */
    }
    .card-detail-section .card-title {
        color: #007bff !important; /* Warna biru untuk judul kartu bagian */
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">
                {{-- PERUBAHAN 1: Penomoran Judul dan Badge Status --}}
                Detail Pengajuan Ke-{{ $item->user_sequence_number ?? '??' }}
            </h1>
            @php
                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                $badgeClass = 'badge-light text-dark border';
                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
            @endphp
            <span class="badge {{ $badgeClass }} p-2 ml-2" style="font-size: 0.9rem;">{{ $statusText }}</span>
        </div>

        <div> {{-- Wrapper untuk tombol di kanan --}}
            {{-- Tombol kembali ke daftar riwayat --}}
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- BARIS 1: Info Dasar Pengajuan (Full Width) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                {{-- PERUBAHAN 2: Card Header untuk Kartu Informasi Pengajuan --}}
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6>
                </div>
                <div class="card-body p-3">
                    {{-- Isi data tetap sama --}}
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start">
                        <div class="mb-2 mb-sm-0 pr-sm-3">
                            <div class="d-flex flex-column flex-sm-row">
                                <div class="mb-3 mb-sm-0 mr-sm-4">
                                    <h6 class="text-dark mb-1">Nama Pendata:</h6>
                                    <p class="font-weight-normal mb-0">{{ $item->nama_pendata }}</p>
                                </div>
                                <div>
                                    <h6 class="text-dark mb-1">Nama Responden:</h6>
                                    <p class="font-weight-normal mb-0">{{ $item->nama_responden }}</p>
                                </div>
                            </div>
                            <div class="mt-3"> {{-- Tambah margin top agar tidak terlalu rapat --}}
                                <dl class="row info-list-condensed mb-0">
                                    <dt class="col-sm-4 col-md-3">Provinsi</dt><dd class="col-sm-8 col-md-9">{{ $item->provinsi }}</dd>
                                    <dt class="col-sm-4 col-md-3">Kota / Kab</dt><dd class="col-sm-8 col-md-9">{{ $item->kabupaten }}</dd>
                                    <dt class="col-sm-4 col-md-3">Kecamatan</dt><dd class="col-sm-8 col-md-9">{{ $item->kecamatan }}</dd>
                                    <dt class="col-sm-4 col-md-3">Desa / Kelurahan</dt><dd class="col-sm-8 col-md-9">{{ $item->desa }}</dd>
                                    <dt class="col-sm-4 col-md-3">RT / RW</dt><dd class="col-sm-8 col-md-9">{{ $item->rt }} / {{ $item->rw }}</dd>
                                </dl>
                            </div>
                            <p class="card-text mt-2 mb-0"><small class="text-muted">
                                Diajukan pada: {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}
                                (ID Sistem: RT-{{ $item->id }}) {{-- ID Sistem dipindah kesini --}}
                            </small></p>
                        </div>

                        {{-- Badge status sudah dipindah ke judul utama, jadi bagian ini bisa dihapus jika tidak ada konten lain --}}
                        {{-- <div class="mt-2 mt-sm-0">
                             Status badge was here
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PERUBAHAN 3: Kartu Informasi Tempat & Pengajuan dihapus --}}
    {{-- BARIS 2: Keterangan Tempat & Rekapitulasi (Berdampingan) --}}
    {{-- <div class="row"> ... Konten kartu ini dihapus ... </div> --}}

    {{-- BARIS BARU (Sebelumnya BARIS 3): Data Anggota Keluarga --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold card-title">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
                    {{-- PERUBAHAN 3: Tambahkan Note/Tips --}}
                    <div class="alert alert-info alert-dismissible fade show" role="alert" style="font-size: 0.875rem;">
                        <h6 class="alert-heading" style="font-size: 0.95rem;"><i class="fas fa-info-circle"></i> Catatan Penting:</h6>
                        <p class="mb-1"><em>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</em></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" style="font-size: 0.875rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center align-middle">No.</th>
                                    <th class="align-middle">Nama Lengkap</th>
                                    <th class="align-middle">NIK</th>
                                    <th class="text-center align-middle">Kelamin</th>
                                    <th class="align-middle">Hub. KRT</th>
                                    <th class="align-middle">Pendidikan</th>
                                    <th class="align-middle">Pekerjaan</th>
                                    <th class="text-center align-middle">Sts. Kawin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->anggotaKeluarga as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>'{{ $anggota->nik }}</td> {{-- Tetap ada petik di depan NIK --}}
                                        <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                        <td>{{ $anggota->hdkrt_text }}</td>
                                        <td>{{ $anggota->pendidikan_terakhir_text }}</td>
                                        <td>{{ $anggota->status_pekerjaan_text }}
                                            @if($anggota->status_pekerjaan == '1')
                                                <small class="d-block text-muted">({{ $anggota->jenis_pekerjaan_text }})</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PERUBAHAN 4: Kartu Verifikasi dan Validasi BARU --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6>
                    {{-- PERUBAHAN 4: Tombol Export dipindahkan ke sini --}}
                    <a href="{{ route('tenagakerja.exportExcel', ['id' => $item->id]) }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel fa-sm"></i> Export ke Excel
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Kolom Kiri: Diserahkan oleh Pendata --}}
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="verification-title text-center">DISERAHKAN OLEH PENDATA</p>
                            <div class="text-center mb-2">
                                Tgl/Bulan/Tahun: <strong>{{ $item->tgl_pembuatan ? \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD / MM / YYYY') : '- / - / ----' }}</strong>
                            </div>
                            <div class="signature-box">
                                @if($item->ttd_pendata)
                                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="TTD Pendata">
                                    <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                                @else
                                    <p class="text-muted"><em>Belum ada TTD Pendata</em></p>
                                    <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Kanan: Diverifikasi Kepala Dusun/Admin --}}
                        <div class="col-md-6">
                             <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
                                <div class="text-center mb-2">
                                     Tgl/Bulan/Tahun: <strong>{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD / MM / YYYY') }}</strong>
                                </div>
                                <div class="signature-box">
                                    {{-- Asumsi: $item->admin_ttd_kepala_dusun untuk TTD Kepala Dusun/Admin.
                                         Jika fieldnya adalah $item->admin_ttd_pendata, itu bisa jadi ambigu.
                                         Ganti 'admin_ttd_kepala_dusun' dengan field yang benar jika perlu. --}}
                                    @if($item->admin_ttd_kepala_dusun) {{-- GANTI JIKA NAMA FIELD BERBEDA --}}
                                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun) }}" alt="TTD Kepala Dusun">
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Kepala Dusun Tidak Ada' }} )</p>
                                    @elseif($item->admin_ttd_pendata && !$item->admin_ttd_kepala_dusun)
                                        {{-- Fallback jika menggunakan field lama untuk TTD Admin, namun ini kurang ideal --}}
                                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" alt="TTD Verifikator">
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                                    @else
                                        <p class="text-muted"><em>Belum ada TTD Verifikator</em></p>
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-2">
                                    Tgl/Bulan/Tahun: <strong>- / - / ----</strong>
                                </div>
                                <div class="signature-box">
                                    <p class="text-muted"><em>Belum Diverifikasi</em></p>
                                    <p class="signer-name">( .................................................. )</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Menampilkan Catatan Validasi jika ada --}}
                    @if($item->status_validasi == 'rejected' && $item->admin_catatan_validasi)
                    <div class="mt-3">
                        <h6 class="text-dark">Catatan Validasi:</h6>
                        <p class="text-danger"><em>{{ $item->admin_catatan_validasi }}</em></p>
                    </div>
                    @elseif($item->status_validasi == 'validated' && $item->admin_catatan_validasi)
                     <div class="mt-3">
                        <h6 class="text-dark">Catatan Validasi:</h6>
                        <p class="text-muted"><em>{{ $item->admin_catatan_validasi }}</em></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- BARIS LAMA: Hasil Validasi Admin (Sudah diintegrasikan ke kartu Verifikasi di atas) --}}
    {{-- @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
    <div class="row"> ... Konten kartu ini sudah dipindahkan/digabung ... </div>
    @endif --}}

    {{-- Bootstrap Modal untuk Notifikasi Sukses (Tetap sama) --}}
    @if(session('show_success_modal'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success_message_title', 'Berhasil!') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <p style="font-size: 1.1rem;">{{ session('success_message_body', 'Data Anda telah berhasil diproses.') }}</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list-alt"></i> Lihat Riwayat Pengajuan
                    </a>
                    <a href="{{ route('tkw.step1') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ajukan Data Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
    if (typeof jQuery == 'undefined') {
        console.error('jQuery tidak termuat! Modal Bootstrap membutuhkan jQuery.');
    } else {
        $(document).ready(function(){
            $('#successModal').modal('show');
        });
    }
</script>
@endif
{{-- Jika ada script khusus untuk halaman ini, tambahkan di sini --}}
@endpush