@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
{{-- Menggunakan style yang sama dengan halaman detail untuk konsistensi --}}
<style>
    .card-detail-section .card-title { color: #4e73df !important; }
    .info-list-lined dt, .info-list-lined dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; border-bottom: 1px solid #eaecf4; display: flex; align-items: center; }
    .info-list-lined > .row:last-of-type > dt, .info-list-lined > .row:last-of-type > dd { border-bottom: none; }
    .info-list-lined dt { font-weight: 500; color: #5a5c69; }
    .info-list-lined dd { font-weight: 400; }
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .table-anggota-keluarga th.th-nik { width: 190px; }
    .table-anggota-keluarga td.td-nik { font-family: 'Courier New', Courier, monospace; letter-spacing: 1.5px; white-space: nowrap; text-align: center; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; margin-bottom: 0; padding-left: 20px; }
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { height: 80px; width: 100%; object-fit: contain; border: 1px solid #eee; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .verification-title { font-size: 0.9rem; font-weight: bold; color: #495057; margin-bottom: 5px; text-transform: uppercase; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Aksi --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">Verifikasi Pengajuan</h1>
            {{-- Badge Status --}}
            @php
                $status = $item->status_validasi;
                $badgeClass = 'secondary';
                if ($status === 'pending') $badgeClass = 'warning';
                if ($status === 'validated') $badgeClass = 'success';
                if ($status === 'rejected') $badgeClass = 'danger';
            @endphp
            <span class="badge badge-{{ $badgeClass }} p-2 ml-3" style="font-size: 0.9rem;">{{ ucfirst($status) }}</span>
        </div>
        <div>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar Validasi
            </a>
        </div>
    </div>

    {{-- BARIS UTAMA: Info Pengajuan (Kiri) & Form Verifikasi (Kanan) --}}
    <div class="row">
        {{-- KOLOM KIRI: Informasi Pengajuan (Read-Only) --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm card-detail-section h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6></div>
                <div class="card-body py-2 px-3">
                    <dl class="dl-horizontal info-list-lined mb-0">
                        <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Lokasi</dt><dd class="col-sm-8">RT {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} / RW {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}, {{ $item->desa }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Formulir Tindakan Verifikasi --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Formulir Tindakan Verifikasi</h6></div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted small">Silakan isi form di bawah ini untuk menyetujui data. Untuk menolak, cukup tekan tombol "Tolak".</p>
                    
                    {{-- Form untuk Approve --}}
                    <form action="{{ route('admin.tkw.approve', $item->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-grow-1">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_tgl_validasi" class="form-label">Tanggal Validasi <span class="text-danger">*</span></label>
                            <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi" value="{{ old('admin_tgl_validasi', now()->toDateString()) }}" class="form-control @error('admin_tgl_validasi') is-invalid @enderror">
                            @error('admin_tgl_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_nama_kepaladusun" class="form-label">Nama Kepala Dusun/Verifikator <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun" value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}" class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                            @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_ttd_pendata" class="form-label">Upload TTD Verifikator <small>(Opsional)</small></label>
                            <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg" class="form-control @error('admin_ttd_pendata') is-invalid @enderror">
                            @error('admin_ttd_pendata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        {{-- Tombol Aksi diletakkan di bagian bawah kartu --}}
                        <div class="mt-auto d-flex justify-content-end">
                            {{-- Tombol Tolak (menggunakan form terpisah) --}}
                            <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#rejectModal">Tolak</button>
                            {{-- Tombol Setujui --}}
                            <button type="submit" class="btn btn-success">Setujui & Validasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Rincian Anggota Keluarga (dari referensi) --}}
    <div class="card shadow-sm card-detail-section mb-4">
        {{-- (Isi kartu ini sama persis dengan kode dari jawaban saya sebelumnya) --}}
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                <ul class="mb-0">
                    {{-- Daftar Petunjuk --}}
                    <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga</li>
                    <li><strong>NUK:</strong> Nomor Urut Anggota dalam Keluarga</li>
                    <li><strong>HDKK:</strong> Hubungan Dengan Kepala Keluarga</li>
                    <li>dan seterusnya...</li>
                </ul>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                    {{-- Seluruh isi tabel anggota keluarga --}}
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menolak pengajuan dari responden <strong>{{ $item->nama_responden }}</strong>? Tindakan ini tidak dapat diurungkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                {{-- Form untuk action 'reject' --}}
                <form action="{{ route('admin.tkw.reject', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Tolak Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection