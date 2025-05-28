@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    .info-list-condensed dt, .info-list-condensed dd {
        padding-top: .25rem; padding-bottom: .25rem; margin-bottom: .15rem;
        font-size: 0.875rem; line-height: 1.3;
    }
    .info-list-condensed dt { font-weight: 600; color: #666; }
    .info-list-condensed dd { word-break: break-word; color: #333; }

    .section-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.65rem 1.1rem; /* Padding header kartu bagian sedikit dikecilkan */
    }
    .section-card .card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0;
    }
    .table-sm th, .table-sm td {
        padding: 0.4rem;
    }
    .top-info-block {
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
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

    {{-- Informasi Responden & Status (Polos dengan Garis Bawah) --}}
    <div class="top-info-block">
        <div class="row">
            <div class="col-md-8">
                <h5 class="text-dark mb-1">Responden: <span class="font-weight-normal">{{ $item->nama_responden }}</span></h5>
                <small class="text-muted">Diajukan pada: {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</small> {{-- Jam dihilangkan --}}
            </div>
            <div class="col-md-4 text-md-right mt-2 mt-md-0">
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

    {{-- Baris untuk Keterangan Tempat, Rekapitulasi --}}
    <div class="row">
        {{-- Kolom Kiri: Informasi Tempat --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 section-card">
                <div class="card-header">
                    <h6 class="card-title">Informasi Tempat</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd>
                        <dt class="col-sm-4">Kabupaten</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd>
                        <dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd>
                        <dt class="col-sm-4">Desa/Kel.</dt><dd class="col-sm-8">{{ $item->desa }}</dd>
                        <dt class="col-sm-4">RT/RW</dt><dd class="col-sm-8">{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Rekapitulasi Rumah Tangga --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 section-card">
                <div class="card-header">
                    <h6 class="card-title">Rekapitulasi Rumah Tangga</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-8">Jml. Anggota RT (JART)</dt><dd class="col-sm-4 text-right">{{ $item->jart }}</dd>
                        <dt class="col-sm-8">JART Usia 10+ Aktif Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_ab }}</dd>
                        <dt class="col-sm-8">JART Usia 10+ Tidak Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_tb }}</dd>
                        <dt class="col-sm-8">JART Usia 10+ Mengurus RT/Sekolah</dt><dd class="col-sm-4 text-right">{{ $item->jart_ms }}</dd>
                        <dt class="col-12"><hr class="my-1"></dt>
                        <dt class="col-sm-8">Pendapatan Rata-Rata RT/Bulan</dt><dd class="col-sm-4 text-right">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Informasi Pendata (Nama & TTD) --}}
    <div class="row">
        <div class="col-lg-6 mb-4"> {{-- Bisa dibuat col-12 jika ingin full width atau sesuaikan --}}
            <div class="card shadow-sm h-100 section-card">
                <div class="card-header">
                    <h6 class="card-title">Informasi Pendata/Pengaju</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-4">Nama Pendata</dt>
                        <dd class="col-sm-8">{{ $item->nama_pendata }}</dd>

                        @if($item->ttd_pendata)
                            <dt class="col-sm-4 mt-2">TTD Pengaju</dt>
                            <dd class="col-sm-8 mt-2">
                                <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee;" class="img-thumbnail">
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
         {{-- Anda bisa menambahkan Kartu Catatan di sini jika mau, di col-lg-6 berikutnya --}}
         {{-- <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 section-card">
                <div class="card-header">
                    <h6 class="card-title">Catatan Tambahan</h6>
                </div>
                <div class="card-body py-2 px-3">
                    <p class="text-muted" style="font-size: 0.85rem;">
                        <em>(Isi catatan di sini...)</em>
                    </p>
                </div>
            </div>
        </div> --}}
    </div>


    {{-- Kartu Data Anggota Keluarga --}}
    <div class="card shadow-sm mb-4 section-card">
        <div class="card-header">
            <h6 class="card-title">Data Anggota Keluarga</h6>
        </div>
        <div class="card-body p-0"> {{-- p-0 agar tabel mepet --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0" style="font-size: 0.875rem;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-middle py-2">No.</th>
                            <th class="align-middle py-2">Nama Lengkap</th>
                            {{-- ... header tabel lainnya ... --}}
                            <th class="align-middle py-2">NIK</th>
                            <th class="text-center align-middle py-2">Kelamin</th>
                            <th class="align-middle py-2">Hub. KRT</th>
                            <th class="align-middle py-2">Pendidikan</th>
                            <th class="align-middle py-2">Pekerjaan</th>
                            <th class="text-center align-middle py-2">Sts. Kawin</th>
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

    {{-- Kartu Hasil Validasi Admin (Jika Ada) --}}
    @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
    <div class="card shadow-sm mb-4 section-card">
        <div class="card-header">
            <h6 class="card-title">Informasi Hasil Validasi Admin</h6>
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
    @endif

    {{-- Bootstrap Modal untuk Notifikasi Sukses --}}
    {{-- ... (Kode modal tetap sama) ... --}}

</div> {{-- End Container-fluid --}}
@endsection

@push('scripts')
    {{-- ... (Skrip Modal Sukses tetap sama) ... --}}
@endpush