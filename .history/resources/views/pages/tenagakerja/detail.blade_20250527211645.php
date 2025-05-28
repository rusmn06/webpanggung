@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    .info-list-condensed dt, .info-list-condensed dd {
        padding-top: .3rem; padding-bottom: .3rem; margin-bottom: .2rem;
        font-size: 0.875rem; line-height: 1.4;
    }
    .info-list-condensed dt { font-weight: 600; color: #666; } /* Label sedikit lebih tebal & abu-abu */
    .info-list-condensed dd { word-break: break-word; color: #333; } /* Nilai lebih gelap */

    /* Style untuk header kartu-kartu internal */
    .internal-card-header {
        background-color: #f8f9fc; /* Latar abu-abu muda standar */
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
    }
    .internal-card-title {
        font-size: 0.95rem; /* Ukuran judul kartu bagian internal */
        font-weight: 600;   /* Bold standar */
        color: #5a5c69;     /* Warna teks gelap standar */
        margin-bottom: 0;
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem;
    }
    .main-card-header-title {
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman Utama dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800 mb-0">
            Detail Pengajuan Ke-{{ $item->user_sequence_number ?? '??' }}
            <small class="text-muted" style="font-size: 0.85rem; font-weight:normal;">(ID Sistem: RT-{{ $item->id }})</small>
        </h1>
        <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
        </a>
    </div>

    {{-- KARTU UTAMA YANG MEMBUNGKUS SEMUANYA --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            {{-- Informasi Responden dan Status di Header Kartu Utama --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div class="mb-2 mb-sm-0">
                    <h5 class="text-dark mb-0 main-card-header-title">Responden: <span class="font-weight-normal">{{ $item->nama_responden }}</span></h5>
                    <p class="card-text mb-0"><small class="text-muted">Diajukan pada: {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</small></p>
                </div>
                <div>
                    @php
                        $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                        $badgeClass = 'badge-light text-dark border';
                        if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                        if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                        if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                    @endphp
                    <span class="badge {{ $badgeClass }} p-2" style="font-size: 0.9rem;">Status: {{ $statusText }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <hr class="mt-0 mb-3">

            {{-- BARIS UNTUK INFORMASI TEMPAT, REKAPITULASI, DAN CATATAN (3 KOLOM) --}}
            <div class="row">
                {{-- Kolom Kiri: Informasi Tempat & Pengajuan --}}
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-none border h-100">
                        <div class="internal-card-header">
                            <h6 class="internal-card-title">Informasi Tempat & Pengajuan</h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <dl class="row info-list-condensed mb-0">
                                <dt class="col-sm-5">Provinsi</dt><dd class="col-sm-7">{{ $item->provinsi }}</dd>
                                <dt class="col-sm-5">Kabupaten</dt><dd class="col-sm-7">{{ $item->kabupaten }}</dd>
                                <dt class="col-sm-5">Kecamatan</dt><dd class="col-sm-7">{{ $item->kecamatan }}</dd>
                                <dt class="col-sm-5">Desa/Kel.</dt><dd class="col-sm-7">{{ $item->desa }}</dd>
                                <dt class="col-sm-5">RT/RW</dt><dd class="col-sm-7">{{ $item->rt }}/{{ $item->rw }}</dd>
                                <dt class="col-12"><hr class="my-1"></dt> {{-- Pemisah internal --}}
                                <dt class="col-sm-5">Nama Pendata</dt><dd class="col-sm-7">{{ $item->nama_pendata }}</dd>
                                @if($item->ttd_pendata)
                                    <dt class="col-sm-5 mt-1">TTD Pengaju</dt>
                                    <dd class="col-sm-7 mt-1"><img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 50px; border: 1px solid #eee;" class="img-thumbnail"></dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Kolom Tengah: Rekapitulasi Rumah Tangga --}}
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-none border h-100">
                        <div class="internal-card-header">
                            <h6 class="internal-card-title">Rekapitulasi Rumah Tangga</h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <dl class="row info-list-condensed mb-0">
                                <dt class="col-8">Jml. Anggota RT (JART)</dt><dd class="col-4 text-right">{{ $item->jart }}</dd>
                                <dt class="col-8">JART Aktif Bekerja</dt><dd class="col-4 text-right">{{ $item->jart_ab }}</dd>
                                <dt class="col-8">JART Tidak Bekerja</dt><dd class="col-4 text-right">{{ $item->jart_tb }}</dd>
                                <dt class="col-8">JART Mengurus RT/Sekolah</dt><dd class="col-4 text-right">{{ $item->jart_ms }}</dd>
                                <dt class="col-12"><hr class="my-1"></dt> {{-- Pemisah internal --}}
                                <dt class="col-8">Pendapatan RT/Bulan</dt><dd class="col-4 text-right">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Kartu Catatan Baru --}}
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-none border h-100">
                        <div class="internal-card-header">
                            <h6 class="internal-card-title">Catatan Tambahan</h6>
                        </div>
                        <div class="card-body py-2 px-3">
                            <p class="text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                                <em>
                                    (Anda bisa mengisi catatan, penjelasan singkatan dari rekapitulasi, atau informasi relevan lainnya di sini nanti.)
                                    <br><br>
                                    Contoh:
                                    <br>JART: Jumlah Anggota Rumah Tangga.
                                    <br>JART AB: JART (usia produktif) yang Aktif Bekerja.
                                </em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATA ANGGOTA KELUARGA (Di bawah, lebar penuh) --}}
            <div class="mt-2">
                <div class="card shadow-none border">
                    <div class="internal-card-header">
                        <h6 class="internal-card-title">Data Anggota Keluarga</h6>
                    </div>
                    <div class="card-body p-0"> {{-- p-0 agar tabel mepet dengan border kartu --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm mb-0" style="font-size: 0.875rem;">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center align-middle py-2" style="width: 5%;">No.</th>
                                        <th class="align-middle py-2" style="width: 20%;">Nama Lengkap</th>
                                        <th class="align-middle py-2" style="width: 15%;">NIK</th>
                                        <th class="text-center align-middle py-2" style="width: 10%;">Kelamin</th>
                                        <th class="align-middle py-2" style="width: 15%;">Hub. KRT</th>
                                        <th class="align-middle py-2" style="width: 15%;">Pendidikan</th>
                                        <th class="align-middle py-2">Pekerjaan</th>
                                        <th class="text-center align-middle py-2" style="width: 12%;">Sts. Kawin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->anggotaKeluarga as $anggota)
                                        <tr>
                                            <td class="text-center py-2">{{ $loop->iteration }}</td>
                                            <td class="py-2">{{ $anggota->nama }}</td>
                                            <td class="py-2">'{{ $anggota->nik }}</td>
                                            <td class="text-center py-2">{{ $anggota->kelamin_text }}</td>
                                            <td class="py-2">{{ $anggota->hdkrt_text }}</td>
                                            <td class="py-2">{{ $anggota->pendidikan_terakhir_text }}</td>
                                            <td class="py-2">{{ $anggota->status_pekerjaan_text }}
                                                @if($anggota->status_pekerjaan == '1')
                                                    <small class="d-block text-muted">({{ $anggota->jenis_pekerjaan_text }})</small>
                                                @endif
                                            </td>
                                            <td class="text-center py-2">{{ $anggota->status_perkawinan_text }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center py-3">Tidak ada data anggota keluarga.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HASIL VALIDASI ADMIN (Jika Ada) --}}
            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
            <div class="mt-4">
                <div class="card shadow-none border">
                    <div class="internal-card-header">
                        <h6 class="internal-card-title">Informasi Hasil Validasi Admin</h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row info-list-condensed mb-0">
                                    <dt class="col-sm-5">Tgl. Validasi</dt><dd class="col-sm-7">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') }}</dd>
                                    <dt class="col-sm-5">Pejabat Validasi</dt><dd class="col-sm-7">{{ $item->admin_nama_kepaladusun ?? '-' }}</dd>
                                </dl>
                            </div>
                            @if($item->admin_ttd_pendata)
                            <div class="col-md-6">
                                <p class="mb-1"><small class="text-muted">TTD Pejabat:</small></p>
                                <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee;" class="img-thumbnail">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div> {{-- End Card Body Utama --}}
    </div> {{-- End Card Utama --}}

    {{-- Bootstrap Modal untuk Notifikasi Sukses --}}
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