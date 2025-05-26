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

            {{-- SEKSI 1: INFORMASI RUMAH TANGGA --}}
            <h5 class="text-primary mb-3">Informasi Rumah Tangga</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">Provinsi</dt>
                        <dd class="col-sm-8">{{ $item->provinsi }}</dd>

                        <dt class="col-sm-4 text-muted">Kabupaten</dt>
                        <dd class="col-sm-8">{{ $item->kabupaten }}</dd>

                        <dt class="col-sm-4 text-muted">Kecamatan</dt>
                        <dd class="col-sm-8">{{ $item->kecamatan }}</dd>

                        <dt class="col-sm-4 text-muted">Desa/Kelurahan</dt>
                        <dd class="col-sm-8">{{ $item->desa }}</dd>

                        <dt class="col-sm-4 text-muted">RT/RW</dt>
                        <dd class="col-sm-8">{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">Nama Pendata</dt>
                        <dd class="col-sm-8">{{ $item->nama_pendata }}</dd>

                        <dt class="col-sm-4 text-muted">Nama Responden</dt>
                        <dd class="col-sm-8">{{ $item->nama_responden }}</dd>

                        <dt class="col-sm-4 text-muted">Tgl. Pengajuan</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd>

                        <dt class="col-sm-4 text-muted">Status Saat Ini</dt>
                        <dd class="col-sm-8">
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
            <div class="row mt-2">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">Jml. Anggota RT</dt>
                        <dd class="col-sm-8">{{ $item->jart }}</dd>

                        <dt class="col-sm-4 text-muted">Jml. ART Bekerja</dt>
                        <dd class="col-sm-8">{{ $item->jart_ab }}</dd>

                        <dt class="col-sm-4 text-muted">Pendapatan RT</dt>
                        <dd class="col-sm-8">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>
                    </dl>
                </div>
                @if($item->ttd_pendata)
                <div class="col-md-6">
                    <p class="mb-1"><small class="text-muted">TTD Pendata:</small></p>
                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                </div>
                @endif
            </div>

            <hr class="my-4">

            {{-- SEKSI 2: DATA ANGGOTA KELUARGA --}}
            <h5 class="text-info mb-3">Data Anggota Keluarga</h5>
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
                            <th style="width: 10%;" class="text-center">Status Kawin</th>
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
            <h5 class="text-success mb-3">Tindakan Validasi</h5>
            <div class="row">
                <div class="col-md-8 col-lg-6"> {{-- Batasi lebar form agar tidak terlalu panjang --}}
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
                            <div id="preview-ttd" class="mt-2 text-center" style="min-height: 100px; border: 1px dashed #ddd; padding: 10px;">
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