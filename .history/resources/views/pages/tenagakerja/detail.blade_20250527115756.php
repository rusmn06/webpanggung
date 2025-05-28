@extends('layouts.main')

@section('title', 'Detail Validasi Rumah Tangga #RT-' . $item->id)

@push('styles')
<style>
    /* Styling untuk informasi yang lebih padat */
    .info-item {
        margin-bottom: 0.5rem; /* Jarak antar item info */
        line-height: 1.3;
    }
    .info-item .info-label {
        font-size: 0.8rem; /* Label sedikit lebih kecil */
        color: #858796;   /* text-muted */
        display: block;
    }
    .info-item .info-value {
        font-size: 0.875rem; /* Ukuran font nilai */
        color: #5a5c69;    /* Warna teks standar */
        word-break: break-word;
    }
    .card-body hr.my-3 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0"> {{-- px-0 jika Anda ingin menghilangkan padding default container-fluid --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Validasi Rumah Tangga #RT-{{ $item->id }} (Responden: {{ $item->nama_responden }})</h6>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
        <div class="card-body">

            {{-- SEKSI 1: INFORMASI RUMAH TANGGA --}}
            <h5 class="text-dark mb-3">Informasi Rumah Tangga</h5>
            <div class="row" style="font-size: 0.875rem;"> {{-- Ukuran font dasar untuk seksi ini --}}
                {{-- Kolom 1 Info --}}
                <div class="col-md-4 col-sm-6">
                    <div class="info-item">
                        <span class="info-label">Provinsi:</span>
                        <span class="info-value">{{ $item->provinsi }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kabupaten:</span>
                        <span class="info-value">{{ $item->kabupaten }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kecamatan:</span>
                        <span class="info-value">{{ $item->kecamatan }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Desa/Kelurahan:</span>
                        <span class="info-value">{{ $item->desa }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">RT/RW:</span>
                        <span class="info-value">{{ $item->rt }}/{{ $item->rw }}</span>
                    </div>
                </div>
                {{-- Kolom 2 Info --}}
                <div class="col-md-4 col-sm-6">
                    <div class="info-item">
                        <span class="info-label">Nama Pendata:</span>
                        <span class="info-value">{{ $item->nama_pendata }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nama Responden:</span>
                        <span class="info-value font-weight-bold">{{ $item->nama_responden }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tgl. Pengajuan:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMM YYYY') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status Saat Ini:</span>
                        <span class="info-value">
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                        </span>
                    </div>
                </div>
                {{-- Kolom 3 Info (Statistik RT & TTD) --}}
                <div class="col-md-4 col-sm-12"> {{-- Di layar kecil, TTD akan di bawah --}}
                    <div class="info-item">
                        <span class="info-label">Jml. Anggota RT:</span>
                        <span class="info-value">{{ $item->jart }} orang</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jml. ART Bekerja:</span>
                        <span class="info-value">{{ $item->jart_ab }} orang</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pendapatan RT/Bulan:</span>
                        <span class="info-value">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</span>
                    </div>
                    @if($item->ttd_pendata)
                    <div class="info-item mt-2">
                        <span class="info-label">TTD Pendata (Pengirim):</span>
                        <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 60px; border: 1px solid #eee; padding: 2px; background-color: #fff;" class="img-thumbnail info-value">
                    </div>
                    @endif
                </div>
            </div>

            <hr class="my-3">

            {{-- SEKSI 2: DATA ANGGOTA KELUARGA --}}
            <h5 class="text-dark mb-3">Data Anggota Keluarga</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" style="font-size: 0.875rem;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-middle" style="width: 5%;">No.</th>
                            <th class="align-middle" style="width: 20%;">Nama Lengkap</th>
                            <th class="align-middle" style="width: 15%;">NIK</th>
                            <th class="text-center align-middle" style="width: 10%;">Kelamin</th>
                            <th class="align-middle" style="width: 15%;">Hub. KRT</th>
                            <th class="align-middle" style="width: 15%;">Pendidikan</th>
                            <th class="align-middle">Pekerjaan</th>
                            <th class="text-center align-middle" style="width: 12%;">Sts. Kawin</th>
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

            <hr class="my-3">

            {{-- SEKSI 3: FORM TINDAKAN VALIDASI --}}
            <h5 class="text-dark mb-3">Tindakan Validasi</h5>
            <div class="row">
                <div class="col-md-8 col-lg-7">
                    {{-- Form Validasi tetap sama --}}
                    <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- ... Isi form ... --}}
                         <div class="mb-3">
                            <label for="admin_tgl_validasi" class="form-label">Tanggal Validasi <span class="text-danger">*</span></label>
                            <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi"
                                value="{{ old('admin_tgl_validasi', $item->admin_tgl_validasi ?? now()->toDateString()) }}"
                                class="form-control form-control-sm @error('admin_tgl_validasi') is-invalid @enderror"
                                {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                            @error('admin_tgl_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_nama_kepaladusun" class="form-label">Nama Pejabat Verifikasi <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                                value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}"
                                class="form-control form-control-sm @error('admin_nama_kepaladusun') is-invalid @enderror"
                                {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                            @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_ttd_pendata" class="form-label">Upload TTD Pejabat (Opsional)</label>
                            <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg"
                                class="form-control form-control-sm @error('admin_ttd_pendata') is-invalid @enderror"
                                {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                            @error('admin_ttd_pendata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="preview-ttd" class="mt-2 text-center" style="min-height: 100px; border: 1px dashed #ddd; padding: 10px; background-color: #fdfdfd;">
                                @if($item->admin_ttd_pendata)
                                    <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 100px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                                @else
                                    <small class="text-muted d-block mt-1">Preview TTD akan muncul di sini.</small>
                                @endif
                            </div>
                        </div>

                        @if($item->status_validasi == 'pending')
                            <div class="d-flex justify-content-end pt-2">
                                <button type="button" class="btn btn-outline-danger btn-sm mr-2" data-toggle="modal" data-target="#rejectModal">
                                     <i class="fas fa-times"></i> Tolak
                                </button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </div>
                        @else
                            <div class="alert alert-info mt-3 text-center" role="alert">
                                Tindakan validasi sudah dilakukan. Status: <strong>{{ $item->status_validasi_text }}</strong>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        </div> {{-- End Card Body Utama --}}
    </div> {{-- End Card Utama --}}
</div> {{-- End Container-fluid --}}

{{-- Modal Konfirmasi Tolak --}}
{{-- ... (kode modal tetap sama) ... --}}

@push('scripts')
{{-- Script Preview Gambar --}}
<script>
// ... (Script preview gambar tetap sama) ...
</script>
@endpush
@endsection