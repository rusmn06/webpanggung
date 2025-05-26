@extends('layouts.main')

@section('title', 'Detail Validasi Rumah Tangga #RT-' . $item->id)

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800 mb-0">Validasi Rumah Tangga #RT-{{ $item->id }} (Responden: {{ $item->nama_responden }})</h1>
        <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-gray-700"></i> Kembali ke Daftar Validasi
        </a>
    </div>

    {{-- Kartu Utama untuk Seluruh Konten Validasi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Validasi Rumah Tangga</h6>
            {{-- Tombol Kembali bisa juga diletakkan di sini jika preferensi berubah --}}
        </div>
        <div class="card-body">

            {{-- SEKSI 1: INFORMASI RUMAH TANGGA (DIBUAT LEBIH PADAT) --}}
            <h5 class="text-primary mb-3"><i class="fas fa-home mr-2"></i>Informasi Rumah Tangga</h5>
            <div class="row">
                {{-- Kolom Kiri Informasi --}}
                <div class="col-md-6">
                    <div class="mb-2">
                        <small class="text-muted d-block">Provinsi:</small>
                        <span class="text-dark">{{ $item->provinsi }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Kabupaten:</small>
                        <span class="text-dark">{{ $item->kabupaten }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Kecamatan:</small>
                        <span class="text-dark">{{ $item->kecamatan }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Desa/Kelurahan:</small>
                        <span class="text-dark">{{ $item->desa }}</span>
                    </div>
                    <div class="mb-3"> {{-- mb-3 untuk group terakhir di kolom ini --}}
                        <small class="text-muted d-block">RT/RW:</small>
                        <span class="text-dark">{{ $item->rt }}/{{ $item->rw }}</span>
                    </div>
                </div>
                {{-- Kolom Kanan Informasi --}}
                <div class="col-md-6">
                    <div class="mb-2">
                        <small class="text-muted d-block">Nama Pendata:</small>
                        <span class="text-dark">{{ $item->nama_pendata }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Nama Responden:</small>
                        <span class="text-dark font-weight-bold">{{ $item->nama_responden }}</span> {{-- Nama Responden dibuat bold --}}
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Tgl. Pengajuan:</small>
                        <span class="text-dark">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    <div class="mb-3"> {{-- mb-3 untuk group terakhir di kolom ini --}}
                        <small class="text-muted d-block">Status Saat Ini:</small>
                        @php
                            $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                            $badgeClass = 'badge-light text-dark';
                            if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                            if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                            if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                        @endphp
                        <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                    </div>
                </div>
            </div>
            {{-- Informasi Statistik Rumah Tangga --}}
            <div class="row mt-1">
                <div class="col-md-6">
                     <div class="mb-2">
                        <small class="text-muted d-block">Jumlah Anggota Rumah Tangga:</small>
                        <span class="text-dark">{{ $item->jart }} orang</span>
                    </div>
                     <div class="mb-2">
                        <small class="text-muted d-block">Jumlah ART Bekerja:</small>
                        <span class="text-dark">{{ $item->jart_ab }} orang</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Pendapatan Rata-Rata RT/Bulan:</small>
                        <span class="text-dark">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</span>
                    </div>
                </div>
                @if($item->ttd_pendata)
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">TTD Pendata (Pengirim):</small>
                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee; padding: 2px; background-color: #fff;" class="img-thumbnail">
                </div>
                @endif
            </div>

            <hr class="my-4">

            {{-- SEKSI 2: DATA ANGGOTA KELUARGA --}}
            <h5 class="text-info mb-3"><i class="fas fa-users mr-2"></i>Data Anggota Keluarga</h5>
            <div class="table-responsive">
                {{-- Tabel Anggota Keluarga tetap sama seperti sebelumnya --}}
                <table class="table table-bordered table-hover table-sm" style="font-size: 0.9rem;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 25%;">Nama Lengkap</th>
                            <th style="width: 18%;">NIK</th>
                            <th style="width: 10%;" class="text-center">Kelamin</th>
                            <th style="width: 12%;">Hub. KRT</th>
                            <th>Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th style="width: 12%;" class="text-center">Sts. Kawin</th>
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
            <h5 class="text-success mb-3"><i class="fas fa-tasks mr-2"></i>Tindakan Validasi</h5>
            <div class="row">
                <div class="col-md-8 col-lg-7"> {{-- Lebar form disesuaikan lagi --}}
                    {{-- Form Validasi tetap sama seperti sebelumnya --}}
                    <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
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