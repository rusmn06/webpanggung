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
        font-weight: normal;
        color: #5a5c69;
    }
    .info-list-condensed dd {
        font-weight: 500;
    }
    .card-detail-section .card-title {
        color: #007bff !important;
    }
    /* Gaya untuk alert catatan penting */
    .alert-important-note {
        font-size: 0.9rem;
        line-height: 1.6;
    }
    .alert-important-note .icon-warning {
        font-size: 1.2rem; /* Ukuran ikon disesuaikan untuk alert */
        margin-right: 10px;
    }
    /* Margin untuk hr di dalam dl */
    .info-list-condensed hr.item-divider {
        margin-top: 0.75rem;
        margin-bottom: 0.75rem;
        border-top: 1px solid rgba(0,0,0,.05);
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

    {{-- BARIS 1: Info Dasar Pengajuan (Full Width) --}}
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

                                <dt class="col-sm-5 col-lg-4">Tanggal Pengajuan</dt>
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
                    <h6 class="m-0 font-weight-bold card-title">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert" style="font-size: 0.875rem;">
                        <h6 class="alert-heading" style="font-size: 0.95rem;"><i class="fas fa-info-circle"></i> Informasi Tambahan:</h6>
                        <p class="mb-1"><em>Silakan isi bagian ini dengan catatan spesifik terkait data anggota keluarga jika diperlukan. Contoh: "Perhatikan status pekerjaan dan pendidikan terakhir untuk setiap anggota."</em></p>
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
                                        <td>'{{ $anggota->nik }}</td>
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

    {{-- Kartu Verifikasi dan Validasi --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6>
                </div>
                <div class="card-body">
                    {{-- Konten Verifikasi dan Validasi tidak berubah --}}
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
        {{-- Konten Modal tidak berubah --}}
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