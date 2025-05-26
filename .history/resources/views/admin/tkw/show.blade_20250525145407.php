@extends('layouts.main')

@section('title', 'Detail Validasi Rumah Tangga #RT-' . $item->id)

@section('content')
<div class="container-fluid">
    {{-- Kartu Utama untuk Seluruh Konten Validasi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Validasi Rumah Tangga #RT-{{ $item->id }} (Responden: {{ $item->nama_responden }})</h6>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
        <div class="card-body">

            {{-- SEKSI 1: INFORMASI RUMAH TANGGA (DIBUAT LEBIH PADAT LAGI) --}}
            <h5 class="text-dark mb-3">Informasi Rumah Tangga</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="dl-horizontal-condensed"> {{-- Kelas kustom untuk dl jika perlu styling lebih lanjut --}}
                        <dt><small class="text-muted">Provinsi</small></dt>
                        <dd>{{ $item->provinsi }}</dd>

                        <dt><small class="text-muted">Kabupaten</small></dt>
                        <dd>{{ $item->kabupaten }}</dd>

                        <dt><small class="text-muted">Kecamatan</small></dt>
                        <dd>{{ $item->kecamatan }}</dd>

                        <dt><small class="text-muted">Desa/Kelurahan</small></dt>
                        <dd>{{ $item->desa }}</dd>

                        <dt><small class="text-muted">RT/RW</small></dt>
                        <dd>{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-horizontal-condensed">
                        <dt><small class="text-muted">Nama Pendata</small></dt>
                        <dd>{{ $item->nama_pendata }}</dd>

                        <dt><small class="text-muted">Nama Responden</small></dt>
                        <dd class="font-weight-bold">{{ $item->nama_responden }}</dd> {{-- Responden bisa tetap bold --}}

                        <dt><small class="text-muted">Tgl. Pengajuan</small></dt>
                        <dd>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd>

                        <dt><small class="text-muted">Status Saat Ini</small></dt>
                        <dd>
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
            {{-- Informasi Statistik Rumah Tangga --}}
            <div class="row mt-0"> {{-- mt-0 agar lebih rapat --}}
                <div class="col-md-6">
                    <dl class="dl-horizontal-condensed">
                        <dt><small class="text-muted">Jml. Anggota RT</small></dt>
                        <dd>{{ $item->jart }} orang</dd>

                        <dt><small class="text-muted">Jml. ART Bekerja</small></dt>
                        <dd>{{ $item->jart_ab }} orang</dd>

                        <dt><small class="text-muted">Pendapatan RT/Bulan</small></dt>
                        <dd>{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
                    </dl>
                </div>
                @if($item->ttd_pendata)
                <div class="col-md-6">
                    <p class="mb-1"><small class="text-muted">TTD Pendata (Pengirim):</small></p>
                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee; padding: 2px; background-color: #fff;" class="img-thumbnail">
                </div>
                @endif
            </div>

            <hr class="my-4">

            {{-- SEKSI 2: DATA ANGGOTA KELUARGA --}}
            <h5 class="text-dark mb-3">Data Anggota Keluarga</h5>
            {{-- Tabel Anggota Keluarga tetap sama, sudah cukup rapi --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" style="font-size: 0.9rem;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 20%;">Nama Lengkap</th>
                            <th style="width: 15%;">NIK</th>
                            <th style="width: 10%;" class="text-center">Kelamin</th>
                            <th style="width: 15%;">Hub. KRT</th>
                            <th style="width: 15%;">Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th style="width: 10%;" class="text-center">Sts. Kawin</th>
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
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data anggota keluarga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr class="my-4">

            {{-- SEKSI 3: FORM TINDAKAN VALIDASI --}}
            <h5 class="text-dark mb-3">Tindakan Validasi</h5>
             {{-- Form Validasi tetap sama --}}
            <div class="row">
                <div class="col-md-8 col-lg-7">
                    <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- ... isi form validasi ... --}}
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

{{-- Tambahkan sedikit CSS kustom untuk dl-horizontal-condensed jika diperlukan --}}
@push('styles')
<style>
    .dl-horizontal-condensed dt {
        font-weight: normal; /* Membuat label tidak bold */
        /* margin-bottom: 0.1rem; */ /* Atur spasi bawah dt jika perlu */
    }
    .dl-horizontal-condensed dd {
        margin-bottom: 0.5rem; /* Spasi antar item dd */
        /* word-break: break-word; */ /* Agar teks panjang tidak meluber */
    }
    /* Jika ingin dt dan dd lebih rapat lagi atau sejajar di layar tertentu, bisa tambahkan: */
    /* @media (min-width: 576px) {
        .dl-horizontal-condensed dt {
            float: left;
            width: 160px;
            clear: left;
            text-align: right;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-right: 10px;
        }
        .dl-horizontal-condensed dd {
            margin-left: 180px;
        }
    } */
</style>
@endpush

@endsection