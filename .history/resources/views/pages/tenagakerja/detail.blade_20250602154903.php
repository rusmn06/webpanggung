@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    /* Gaya yang sudah ada sebelumnya */
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
        max-height: 80px;
        max-width: 100%;
        border: 1px solid #eee;
    }
    .signature-box .signer-name {
        margin-top: 8px;
        font-weight: bold;
        font-size: 0.85rem;
    }
    .verification-title {
        font-size: 0.9rem;
        font-weight: bold;
        color: #495057;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    .info-list-condensed dt {
        font-weight: normal;
        color: #5a5c69;
        /* padding-right: 0; */ /* Hapus jika menyebabkan dt terlalu sempit */
    }
    .info-list-condensed dd {
        font-weight: 500;
    }
    .card-detail-section .card-title {
        color: #007bff !important;
    }
    .alert-important-note {
        font-size: 0.85rem; /* Sedikit lebih kecil agar muat di kolom */
        line-height: 1.5;
    }
    .alert-important-note .alert-heading {
        font-size: 0.95rem;
    }
    .alert-important-note .icon-warning {
        font-size: 1.1rem;
        margin-right: 8px;
    }
    .info-list-condensed hr.item-divider {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        border-top: 1px solid rgba(0,0,0,.05);
    }
    /* Styling untuk tabel baru agar lebih mirip gambar */
    .table-anggota-keluarga th {
        vertical-align: middle !important;
        text-align: center;
        font-size: 0.8rem; /* Ukuran font header tabel lebih kecil */
        padding: 0.4rem;
        white-space: normal; /* Memungkinkan wrap teks */
    }
    .table-anggota-keluarga td {
        vertical-align: middle !important;
        font-size: 0.85rem;
        padding: 0.4rem;
    }
    .table-anggota-keluarga .nik-display {
        font-family: monospace; /* Font monospace untuk NIK jika diinginkan */
        letter-spacing: 1px; /* Sedikit spasi antar karakter NIK */
    }
    .table-anggota-keluarga th.col-nama {
        min-width: 150px; /* Lebar minimal untuk kolom nama */
    }
    .table-anggota-keluarga th.col-nik {
        min-width: 180px; /* Lebar minimal untuk kolom NIK */
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">
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
        <div>
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- BARIS 1: Info Dasar Pengajuan (dengan Alert di dalamnya) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6>
                    <a href="{{ route('tenagakerja.exportExcel', ['id' => $item->id]) }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel fa-sm"></i> Export ke Excel
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Kolom Kiri: Detail Informasi Pengajuan --}}
                        <div class="col-md-7">
                            <dl class="row info-list-condensed mb-0">
                                {{-- Grup Identitas Pengaju --}}
                                <dt class="col-sm-5 col-lg-4">Nama Pendata</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->nama_pendata }}</dd>

                                <dt class="col-sm-5 col-lg-4">Nama Responden</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->nama_responden }}</dd>

                                <div class="col-12"><hr class="item-divider"></div>

                                {{-- Grup Alamat --}}
                                <dt class="col-sm-5 col-lg-4">Provinsi</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->provinsi }}</dd>

                                <dt class="col-sm-5 col-lg-4">Kota / Kab</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->kabupaten }}</dd>

                                <dt class="col-sm-5 col-lg-4">Kecamatan</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->kecamatan }}</dd>

                                <dt class="col-sm-5 col-lg-4">Desa / Kelurahan</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->desa }}</dd>

                                <dt class="col-sm-5 col-lg-4">RT / RW</dt>
                                <dd class="col-sm-7 col-lg-8">{{ $item->rt }} / {{ $item->rw }}</dd>

                                <div class="col-12"><hr class="item-divider"></div>

                                {{-- Grup Detail Pengajuan --}}
                                <dt class="col-sm-5 col-lg-4">Tanggal Pengajuan</dt>
                                {{-- PERUBAHAN 2: Hapus jam dari tanggal --}}
                                <dd class="col-sm-7 col-lg-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM YYYY') }}</dd>

                                <dt class="col-sm-5 col-lg-4">ID Sistem</dt>
                                <dd class="col-sm-7 col-lg-8">RT-{{ $item->id }}</dd>
                            </dl>
                        </div>
                        {{-- Kolom Kanan: Alert Catatan Penting --}}
                        <div class="col-md-5">
                            {{-- PERUBAHAN 1: Alert Catatan Penting di sini --}}
                            <div class="alert alert-warning alert-important-note mt-3 mt-md-0 h-100" role="alert"> {{-- h-100 untuk mencoba mengisi tinggi, sesuaikan jika perlu --}}
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle icon-warning"></i> Catatan Penting</h5>
                                <p class="mb-1">Mohon luangkan waktu sejenak untuk <strong>memverifikasi kembali semua detail data</strong> yang telah Anda ajukan. Pastikan tidak ada kekeliruan atau informasi yang terlewat.</p>
                                <p class="mb-0">Jika Anda menemukan ketidaksesuaian atau memerlukan perubahan data, jangan ragu untuk segera <strong>menghubungi Administrator Sistem</strong> kami. Keakuratan data Anda sangat penting.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Anggota Keluarga --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3">
                    {{-- PERUBAHAN 3: Judul kartu disesuaikan dengan gambar --}}
                    <h6 class="m-0 font-weight-bold card-title">KETERANGAN STATUS PEKERJAAN</h6>
                </div>
                <div class="card-body">
                    {{-- Alert di atas tabel anggota keluarga (opsional, bisa dihapus jika tidak perlu lagi) --}}
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert" style="font-size: 0.875rem;">
                        <h6 class="alert-heading" style="font-size: 0.95rem;"><i class="fas fa-info-circle"></i> Petunjuk Pengisian:</h6>
                        <p class="mb-1"><em>Tulis siapa saja yang biasanya tinggal dan makan di rumah tangga ini (Baik DEWASA, ANAK-ANAK, MAUPUN BAYI). Tuliskan nama sesuai dengan identitas, beserta Nomor Induk Kependudukan (NIK).</em></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="table-responsive">
                        {{-- PERUBAHAN 3: Struktur tabel disesuaikan --}}
                        <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2" class="col-nama">Nama Anggota Rumah Tangga</th>
                                    <th rowspan="2" class="col-nik">Nomor Induk Kependudukan (NIK)</th>
                                    <th rowspan="2">Hubungan dengan Kepala Rumah Tangga</th>
                                    <th rowspan="2">Nomor Urut Keluarga</th>
                                    <th colspan="2">Jenis Kelamin</th>
                                    <th rowspan="2">Status Perkawinan</th>
                                    <th rowspan="2">Status Pekerjaan</th>
                                    <th rowspan="2">Jenis Pekerjaan</th>
                                    <th rowspan="2">Suku Jenis Pekerjaan (KBLI)*</th>
                                    <th rowspan="2">Pendidikan Terakhir</th>
                                    <th rowspan="2">Pendapatan Rata-rata perbulan*</th>
                                </tr>
                                <tr>
                                    <th>Laki-Laki (1)</th>
                                    <th>Perempuan (2)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->anggotaKeluarga as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td class="nik-display">{{ $anggota->nik }}</td>
                                        <td class="text-center">{{ $anggota->hdkrt_text }} {{-- Asumsi ini teks, sesuaikan jika kode --}}</td>
                                        <td class="text-center">
                                            {{-- Asumsi untuk Nomor Urut Keluarga, gunakan $loop->iteration jika tidak ada field khusus --}}
                                            {{ $anggota->nomor_urut_keluarga ?? $loop->iteration }}
                                        </td>
                                        <td class="text-center">{{ $anggota->kelamin == '1' || strtolower($anggota->kelamin_text) == 'laki-laki' ? '1' : '' }}</td>
                                        <td class="text-center">{{ $anggota->kelamin == '2' || strtolower($anggota->kelamin_text) == 'perempuan' ? '2' : '' }}</td>
                                        <td class="text-center">{{ $anggota->status_perkawinan_text }} {{-- Asumsi ini teks, sesuaikan jika kode --}}</td>
                                        <td class="text-center">{{ $anggota->status_pekerjaan_text }} {{-- Asumsi ini teks, sesuaikan jika kode --}}</td>
                                        <td class="text-center">
                                            @if($anggota->status_pekerjaan == '1') {{-- Hanya tampilkan jenis pekerjaan jika bekerja --}}
                                                {{ $anggota->jenis_pekerjaan_text }} {{-- Asumsi ini teks, sesuaikan jika kode --}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- Kolom Suku Jenis Pekerjaan - ISI DENGAN FIELD YANG SESUAI JIKA ADA --}}
                                            {{ $anggota->suku_jenis_pekerjaan ?? '-' }}
                                        </td>
                                        <td class="text-center">{{ $anggota->pendidikan_terakhir_text }} {{-- Asumsi ini teks, sesuaikan jika kode --}}</td>
                                        <td class="text-right">
                                            {{-- Kolom Pendapatan - ISI DENGAN FIELD YANG SESUAI JIKA ADA --}}
                                            {{ $anggota->pendapatan_bulanan ? 'Rp ' . number_format($anggota->pendapatan_bulanan, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="13" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <small class="text-muted">* Kolom ini mungkin memerlukan field data tambahan yang belum ada.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Verifikasi dan Validasi --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                {{-- Konten Verifikasi dan Validasi tidak berubah --}}
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
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
                        <div class="col-md-6">
                             <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
                                <div class="text-center mb-2">
                                     Tgl/Bulan/Tahun: <strong>{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD / MM / YYYY') }}</strong>
                                </div>
                                <div class="signature-box">
                                    @if($item->admin_ttd_kepala_dusun)
                                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun) }}" alt="TTD Kepala Dusun">
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Kepala Dusun Tidak Ada' }} )</p>
                                    @elseif($item->admin_ttd_pendata && !$item->admin_ttd_kepala_dusun)
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
                    @if($item->status_validasi == 'rejected' && $item->admin_catatan_validasi)
                    <div class="mt-3">
                        <h6 class="text-dark">Catatan Penolakan:</h6>
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
@endpush