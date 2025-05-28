@extends('layouts.main')

@section('title', 'Detail Pengajuan - ' . $item->nama_responden)

@push('styles')
<style>
    .info-section .info-label {
        font-size: 0.8rem; /* Ukuran font label lebih kecil */
        color: #858796;   /* text-muted */
        display: block;   /* Label di atas nilai */
        margin-bottom: 0.1rem;
    }
    .info-section .info-value {
        font-size: 0.9rem; /* Ukuran font nilai */
        color: #5a5c69;
        word-break: break-word;
        line-height: 1.3;
    }
    .info-section .info-value.font-weight-bold {
        color: #363636; /* Warna lebih gelap untuk yang bold */
    }
    .sub-section-heading {
        font-size: 1rem; /* Ukuran sub-judul seksi */
        font-weight: 600;
        color: #4e73df; /* Warna primer untuk sub-judul */
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 1rem; /* Jarak setelah sub-judul */
        margin-top: 1.5rem; /* Jarak sebelum sub-judul (kecuali yang pertama) */
    }
    .sub-section-heading:first-of-type {
        margin-top: 0; /* Hapus margin atas untuk sub-judul pertama */
    }
    .table-sm th, .table-sm td { /* Padding untuk tabel anggota */
        padding: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- KARTU UTAMA UNTUK SEMUA DETAIL --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">
                Pengajuan Atas Nama: <span class="text-primary">{{ $item->nama_responden }}</span>
                <small class="text-muted d-block" style="font-size: 0.8rem; font-weight:normal;">
                    (ID Sistem: RT-{{ $item->id }} | Diajukan: {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMM YYYY, HH:mm') }})
                </small>
            </h6>
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary">
                 <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            {{-- Status Pengajuan di Atas --}}
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Status Pengajuan Saat Ini:</span>
                    @php
                        $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                        $badgeClass = 'badge-light text-dark border';
                        if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                        if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                        if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                    @endphp
                    <span class="badge {{ $badgeClass }} py-2 px-3" style="font-size: 0.9rem;">{{ $statusText }}</span>
                </div>
            </div>

            {{-- SEKSI 1: INFORMASI TEMPAT & PENDATAAN --}}
            <h5 class="sub-section-heading">A. Keterangan Tempat & Pendataan</h5>
            <div class="row info-section">
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">Provinsi:</span><span class="info-value">{{ $item->provinsi }}</span></div>
                    <div class="info-item"><span class="info-label">Kabupaten:</span><span class="info-value">{{ $item->kabupaten }}</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">Kecamatan:</span><span class="info-value">{{ $item->kecamatan }}</span></div>
                    <div class="info-item"><span class="info-label">Desa/Kelurahan:</span><span class="info-value">{{ $item->desa }}</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">RT/RW:</span><span class="info-value">{{ $item->rt }}/{{ $item->rw }}</span></div>
                    <div class="info-item"><span class="info-label">Nama Pendata:</span><span class="info-value">{{ $item->nama_pendata }}</span></div>
                </div>
                @if($item->ttd_pendata)
                <div class="col-lg-12 mt-2">
                    <div class="info-item">
                        <span class="info-label">TTD Pengaju/Pendata:</span>
                        <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee; padding:2px; background-color:#fff;" class="img-thumbnail info-value">
                    </div>
                </div>
                @endif
            </div>

            {{-- SEKSI 2: REKAPITULASI RUMAH TANGGA --}}
            <h5 class="sub-section-heading">B. Rekapitulasi Rumah Tangga</h5>
            <div class="row info-section">
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">Jml. Anggota RT (JART):</span><span class="info-value">{{ $item->jart }} orang</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">JART Aktif Bekerja:</span><span class="info-value">{{ $item->jart_ab }} orang</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">JART Tidak Bekerja:</span><span class="info-value">{{ $item->jart_tb }} orang</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">JART Mengurus RT/Sekolah:</span><span class="info-value">{{ $item->jart_ms }} orang</span></div>
                </div>
                <div class="col-lg-8 col-md-6"> {{-- Kolom lebih lebar untuk pendapatan --}}
                    <div class="info-item"><span class="info-label">Pendapatan Rata-Rata RT/Bulan:</span><span class="info-value">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</span></div>
                </div>
            </div>

            {{-- SEKSI 3: DATA ANGGOTA KELUARGA --}}
            <h5 class="sub-section-heading">C. Data Anggota Keluarga</h5>
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

            {{-- SEKSI 4: INFORMASI HASIL VALIDASI ADMIN (JIKA SUDAH ADA) --}}
            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
            <h5 class="sub-section-heading">D. Informasi Hasil Validasi Admin</h5>
            <div class="row info-section">
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">Tgl. Validasi:</span><span class="info-value">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM<y_bin_46>) }}</span></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-item"><span class="info-label">Pejabat Validasi:</span><span class="info-value">{{ $item->admin_nama_kepaladusun ?? '-' }}</span></div>
                </div>
                @if($item->admin_ttd_pendata)
                <div class="col-lg-4 col-md-12"> {{-- TTD bisa full di mobile --}}
                    <div class="info-item">
                        <span class="info-label">TTD Pejabat:</span>
                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee;" class="img-thumbnail info-value">
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div> {{-- End Card Body Utama --}}
    </div> {{-- End Card Utama --}}

    {{-- Bootstrap Modal untuk Notifikasi Sukses (jika halaman ini dituju setelah submit form) --}}
    @if(session('show_success_modal'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle mr-2"></i> {{ session('success_message_title', 'Berhasil!') }}</h5>
                </div>
                <div class="modal-body"><div class="text-center py-3"><p style="font-size: 1.1rem;">{{ session('success_message_body', 'Data Anda telah berhasil diproses.') }}</p></div></div>
                <div class="modal-footer justify-content-center">
                    <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-primary"><i class="fas fa-list-alt"></i> Lihat Riwayat</a>
                    <a href="{{ route('tkw.step1') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Ajukan Lain</a>
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
    $(document).ready(function(){ $('#successModal').modal('show'); });
</script>
@endif
@endpush