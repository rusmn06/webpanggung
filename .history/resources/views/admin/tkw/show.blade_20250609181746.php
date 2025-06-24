@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
{{-- Semua style yang sudah kita setujui sebelumnya --}}
<style>
    .card-detail-section .card-title { color: #4e73df !important; }
    .info-list-lined dt, .info-list-lined dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; border-bottom: 1px solid #eaecf4; display: flex; align-items: center; }
    .info-list-lined > .row:last-of-type > dt, .info-list-lined > .row:last-of-type > dd { border-bottom: none; }
    .info-list-lined dt { font-weight: 500; color: #5a5c69; }
    .info-list-lined dd { font-weight: 400; }
    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid #eaecf4; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: #5a5c69; }
    .summary-item .value { font-weight: bold; font-size: 1.1rem; color: #36b9cc; }
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .table-anggota-keluarga th.th-nik { width: 190px; }
    .table-anggota-keluarga td.td-nik { font-family: 'Courier New', Courier, monospace; letter-spacing: 1.5px; white-space: nowrap; text-align: center; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; margin-bottom: 0; padding-left: 20px; }
    .verification-title { font-size: 0.9rem; font-weight: bold; color: #495057; margin-bottom: 5px; text-transform: uppercase; }
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { height: 80px; width: 100%; object-fit: contain; border: 1px solid #eee; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .form-label { font-size: 0.875rem; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Aksi (Sama seperti detail.blade.php) --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">Verifikasi Pengajuan</h1>
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

    {{-- BARIS UTAMA: Info Pengajuan & Ringkasan (Sama seperti detail.blade.php) --}}
    <div class="row">
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
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Ringkasan Rumah Tangga</h6></div>
                <div class="card-body">
                    <div class="summary-item"><span class="label"><i class="fas fa-users fa-fw mr-2 text-gray-400"></i>Total Anggota</span><span class="value">{{ $item->anggotaKeluarga->count() }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-briefcase fa-fw mr-2 text-gray-400"></i>Anggota Bekerja</span><span class="value">{{ $item->anggotaKeluarga->where('status_pekerjaan', 1)->count() }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-home fa-fw mr-2 text-gray-400"></i>Anggota Tidak Bekerja</span><span class="value">{{ $item->anggotaKeluarga->where('status_pekerjaan', '!=', 1)->count() }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Anggota Keluarga (Sama seperti detail.blade.php) --}}
    <div class="card shadow-sm card-detail-section mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            @include('admin.tkw.partials.detail_table', ['item' => $item])
        </div>
    </div>

    {{-- ====================================================================== --}}
    {{-- == PERUBAHAN UTAMA: Kartu Verifikasi sekarang menjadi sebuah Form     == --}}
    {{-- ====================================================================== --}}
    <div class="card shadow-sm card-detail-section">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Tindakan Verifikasi dan Validasi</h6></div>
        <div class="card-body">
            <form action="{{ route('admin.tkw.approve', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- KOLOM KIRI: TTD PENDATA (Hanya Tampilan) --}}
                    <div class="col-md-6 mb-4 mb-md-0">
                        <p class="verification-title text-center">DISERAHKAN OLEH PENDATA</p>
                        <div class="text-center mb-2">Tgl/Bulan/Tahun: <strong>{{ $item->tgl_pembuatan ? \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD / MM / YYYY') : '- / - / ----' }}</strong></div>
                        <div class="signature-box">
                            @if($item->ttd_pendata)
                                <img src="{{ asset('storage/' . $item->ttd_pendata) }}" alt="TTD Pendata">
                                <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata' }} )</p>
                            @else
                                <p class="text-muted my-5"><em>Belum ada TTD Pendata</em></p>
                                <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata' }} )</p>
                            @endif
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FORM VERIFIKASI ADMIN --}}
                    <div class="col-md-6">
                        <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                        {{-- Input Tanggal Validasi --}}
                        <div class="form-group">
                            <label for="admin_tgl_validasi" class="form-label">Tanggal Validasi <span class="text-danger">*</span></label>
                            <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi" value="{{ old('admin_tgl_validasi', now()->toDateString()) }}" class="form-control @error('admin_tgl_validasi') is-invalid @enderror">
                            @error('admin_tgl_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- Input Nama Kepala Dusun --}}
                        <div class="form-group">
                            <label for="admin_nama_kepaladusun" class="form-label">Nama Kepala Dusun/Verifikator <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun" value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}" placeholder="Masukkan nama verifikator" class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                            @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- Input Upload TTD --}}
                        <div class="form-group">
                            <label for="admin_ttd_pendata" class="form-label">Upload TTD Verifikator</label>
                            <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" class="form-control-file @error('admin_ttd_pendata') is-invalid @enderror">
                            @error('admin_ttd_pendata') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        {{-- Preview TTD --}}
                        <div id="preview-ttd" class="text-center mt-2">
                            @if($item->admin_ttd_pendata)
                                <small class="text-muted d-block">TTD Tersimpan:</small>
                                <img src="{{ asset('storage/' . $item->admin_ttd_pendata) }}" class="img-thumbnail" style="max-height: 80px;" alt="TTD Tersimpan">
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Tombol Aksi Form --}}
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#rejectModal"><i class="fas fa-times"></i> Tolak</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Setujui & Validasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body">Apakah Anda yakin ingin menolak pengajuan dari <strong>{{ $item->nama_responden }}</strong>? Tindakan ini akan mengubah status menjadi 'Ditolak'.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.tkw.reject', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Tolak Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk Preview Gambar TTD --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('admin_ttd_pendata');
    const previewContainer = document.getElementById('preview-ttd');

    if (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            previewContainer.innerHTML = ''; // Bersihkan preview lama

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxHeight = '80px';
                    img.classList.add('img-thumbnail');
                    
                    const newPreviewTitle = document.createElement('small');
                    newPreviewTitle.className = 'text-muted d-block';
                    newPreviewTitle.textContent = 'Preview TTD Baru:';
                    
                    previewContainer.appendChild(newPreviewTitle);
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush