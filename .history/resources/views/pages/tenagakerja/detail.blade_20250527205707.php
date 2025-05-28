@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    .info-list-condensed dt, .info-list-condensed dd {
        padding-top: .3rem; padding-bottom: .3rem; margin-bottom: .2rem;
        font-size: 0.875rem; line-height: 1.4;
    }
    .info-list-condensed dt { font-weight: 600; color: #666; }
    .info-list-condensed dd { word-break: break-word; color: #333; }

    /* .card-detail-section .card-header diganti menjadi style header kartu internal */
    .internal-card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem; /* Default Bootstrap card-header padding */
    }
    .internal-card-title {
        font-size: 0.95rem; /* Ukuran judul kartu bagian internal */
        font-weight: 600;
        color: #5a5c69; /* text-dark atau sedikit lebih muda */
        margin-bottom: 0;
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem; /* Padding konsisten untuk tabel */
    }
    .main-card-header-title {
        font-size: 1.1rem; /* Ukuran judul utama kartu */
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
            <hr class="mt-0 mb-3"> {{-- Garis tipis setelah info responden/status --}}

            {{-- BARIS UNTUK INFORMASI TEMPAT, REKAPITULASI, DAN CATATAN (3 KOLOM) --}}
            <div class="row">
                {{-- Kolom Kiri: Informasi Tempat & Pengajuan --}}
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-none border h-100">
                        <div class="internal-card-header">
                            <h6 class="internal-card-title">Informasi Tempat & Pengajuan</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row info-list-condensed">
                                <dt class="col-sm-5">Provinsi</dt><dd class="col-sm-7">{{ $item->provinsi }}</dd>
                                <dt class="col-sm-5">Kabupaten</dt><dd class="col-sm-7">{{ $item->kabupaten }}</dd>
                                <dt class="col-sm-5">Kecamatan</dt><dd class="col-sm-7">{{ $item->kecamatan }}</dd>
                                <dt class="col-sm-5">Desa/Kel.</dt><dd class="col-sm-7">{{ $item->desa }}</dd>
                                <dt class="col-sm-5">RT/RW</dt><dd class="col-sm-7">{{ $item->rt }}/{{ $item->rw }}</dd>
                            </dl>
                            <hr class="my-2">
                            <dl class="row info-list-condensed">
                                <dt class="col-sm-5">Nama Pendata</dt><dd class="col-sm-7">{{ $item->nama_pendata }}</dd>
                                @if($item->ttd_pendata)
                                    <dt class="col-sm-5 mt-2">TTD Pengaju</dt>
                                    <dd class="col-sm-7 mt-2"><img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 50px; border: 1px solid #eee;" class="img-thumbnail"></dd>
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
                        <div class="card-body">
                            <dl class="row info-list-condensed">
                                <dt class="col-sm-8">Jml. Anggota RT (JART)</dt><dd class="col-sm-4 text-right">{{ $item->jart }}</dd>
                                <dt class="col-sm-8">JART Aktif Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_ab }}</dd>
                                <dt class="col-sm-8">JART Tidak Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_tb }}</dd>
                                <dt class="col-sm-8">JART Mengurus RT/Sekolah</dt><dd class="col-sm-4 text-right">{{ $item->jart_ms }}</dd>
                            </dl>
                            <hr class="my-2">
                            <dl class="row info-list-condensed">
                                <dt class="col-sm-8">Pendapatan Rata-Rata RT/Bulan</dt><dd class="col-sm-4 text-right">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
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
                        <div class="card-body">
                            <p class="text-muted" style="font-size: 0.85rem;">
                                <em>
                                    Anda bisa mengisi catatan atau penjelasan mengenai singkatan pada rekapitulasi di sini nanti.
                                    <br><br>
                                    Contoh:
                                    <br>JART: Jumlah Anggota Rumah Tangga.
                                    <br>JART AB: JART Usia Produktif yang Aktif Bekerja.
                                    <br>...dan seterusnya.
                                </em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATA ANGGOTA KELUARGA (Di bawah, lebar penuh) --}}
            <div class="mt-2"> {{ Mengurangi margin atas sedikit jika perlu }}
                <div class="card shadow-none border">
                    <div class="internal-card-header">
                        <h6 class="internal-card-title">Data Anggota Keluarga</h6>
                    </div>
                    <div class="card-body py-2 px-0"> {{ Kurangi padding agar tabel lebih mepet }}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm mb-0" style="font-size: 0.875rem;">
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

            {{-- HASIL VALIDASI ADMIN (Jika Ada) --}}
            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
            <div class="mt-4">
                <div class="card shadow-none border">
                    <div class="internal-card-header">
                        <h6 class="internal-card-title">Informasi Hasil Validasi Admin</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row info-list-condensed">
                                    <dt class="col-sm-5">Tgl. Validasi</dt><dd class="col-sm-7">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM