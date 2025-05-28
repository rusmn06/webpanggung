@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800 mb-0">
            Detail Pengajuan Saya Ke-{{ $item->user_sequence_number ?? '??' }}
            <small class="text-muted" style="font-size: 0.85rem; font-weight:normal;">(ID Sistem: RT-{{ $item->id }})</small>
        </h1>
        {{-- Tombol kembali ke daftar riwayat (tenagakerja.index) --}}
        <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
        </a>
    </div>
    {{-- BARIS 1: Status Utama & Info Dasar Pengajuan (Full Width) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start">
                        <div class="mb-2 mb-sm-0 pr-sm-3">
                            {{-- Bagian Nama --}}
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

                            {{-- Bagian Lokasi --}}
                            <div class="mt-3">
                                <p class="mb-1">Provinsi: {{ $item->provinsi }}</p>
                                <p class="mb-1">Kota/Kab: {{ $item->kabupaten }}</p>
                                <p class="mb-1">Kecamatan: {{ $item->kecamatan }}</p>
                                <p class="mb-1">Desa/Kelurahan: {{ $item->desa }}</p>
                                <p class="mb-0">RT/RW: {{ $item->rt }} / {{ $item->rw }}</p>
                            </div>

                            {{-- Bagian Tanggal --}}
                            <p class="card-text mb-0 mt-3"><small class="text-muted"> {{-- Beri jarak atas & pertahankan small/muted untuk tanggal --}}
                                Diajukan pada: {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM<y_bin_46>') }}
                            </small></p>
                        </div>

                        {{-- Bagian Kanan: Status Validasi --}}
                        <div class="mt-2 mt-sm-0"> {{-- mt-2: beri jarak atas di mobile --}}
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark border'; // Default badge dengan border
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-2" style="font-size: 0.9rem;">Status: {{ $statusText }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- BARIS 2: Keterangan Tempat & Rekapitulasi (Berdampingan) --}}
    <div class="row">
        {{-- Kolom Kiri: Keterangan Tempat & Info Pengaju --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark card-title">Informasi Tempat & Pengajuan</h6>
                </div>
                <div class="card-body">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd>
                        <dt class="col-sm-4">Kabupaten</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd>
                        <dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd>
                        <dt class="col-sm-4">Desa/Kel.</dt><dd class="col-sm-8">{{ $item->desa }}</dd>
                        <dt class="col-sm-4">RT/RW</dt><dd class="col-sm-8">{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                    <hr class="my-2">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd>
                        @if($item->ttd_pendata)
                            <dt class="col-sm-4 mt-2">TTD Pengaju</dt>
                            <dd class="col-sm-8 mt-2"><img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee;" class="img-thumbnail"></dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Rekapitulasi Rumah Tangga --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark card-title">Rekapitulasi Rumah Tangga</h6>
                </div>
                <div class="card-body">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-8">Jml. Anggota Rumah Tangga (JART)</dt><dd class="col-sm-4 text-right">{{ $item->jart }} orang</dd>
                        <dt class="col-sm-8">JART Usia 10+ Aktif Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_ab }} orang</dd>
                        <dt class="col-sm-8">JART Usia 10+ Tidak Bekerja</dt><dd class="col-sm-4 text-right">{{ $item->jart_tb }} orang</dd>
                        <dt class="col-sm-8">JART Usia 10+ Mengurus RT/Sekolah</dt><dd class="col-sm-4 text-right">{{ $item->jart_ms }} orang</dd>
                    </dl>
                    <hr class="my-2">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-8">Pendapatan Rata-Rata RT/Bulan</dt><dd class="col-sm-4 text-right">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: Data Anggota Keluarga (Di Tengah, Lebar Penuh) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark card-title">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
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

    {{-- BARIS 4: Hasil Validasi Admin (Jika Sudah Ada) --}}
    @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark card-title">Informasi Hasil Validasi Admin</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row info-list-condensed">
                                <dt class="col-sm-4">Tgl. Validasi</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') }}</dd>
                                <dt class="col-sm-4">Pejabat Validasi</dt><dd class="col-sm-8">{{ $item->admin_nama_kepaladusun ?? '-' }}</dd>
                            </dl>
                        </div>
                        @if($item->admin_ttd_pendata)
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">TTD Pejabat:</small></p>
                            <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Bootstrap Modal untuk Notifikasi Sukses --}}
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
                    {{-- Pastikan route 'tenagakerja.index' adalah daftar riwayat Anda --}}
                    <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list-alt"></i> Lihat Riwayat Pengajuan
                    </a>
                     {{-- Pastikan route 'tkw.step1' adalah awal wizard Anda --}}
                    <a href="{{ route('tkw.step1') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ajukan Data Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div> {{-- End Container-fluid --}}
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
    // Pastikan jQuery sudah dimuat sebelum skrip ini
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