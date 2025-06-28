@extends('layouts.main')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
<style>
    /* Style yang sudah ada dan beberapa tambahan */
    .card-detail-section .card-title { color: #4e73df !important; }
    .info-list-split dt, .info-list-split dd { padding-top: 0.5rem; padding-bottom: 0.5rem; margin-bottom: 0; }
    .info-list-split .row { border-bottom: 1px solid #eaecf4; }
    .info-list-split .row:last-child { border-bottom: none; }
    .info-list-split dt { font-weight: 500; color: #5a5c69; }
    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid #eaecf4; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: #5a5c69; display: flex; align-items: center; }
    .summary-item .label .fa-fw { margin-right: 0.75rem; color: #858796; }
    .summary-item .value { font-weight: bold; font-size: 1.1rem; color: #36b9cc; }
    .form-label { font-size: 0.875rem; font-weight: 500; }
    .comment-tag { cursor: pointer; user-select: none; border: 1px solid #d1d3e2; }
    .comment-tag:hover { background-color: #e9ecef; }
    #signature-preview img { max-height: 80px; border: 1px solid #ddd; border-radius: .25rem; margin-top: 10px; }
    .table-anggota-keluarga th, .table-anggota-keluarga td { /* style tabel tetap sama */ }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- ... (kode judul halaman Anda, tidak berubah) ... --}}
    </div>

    {{-- Alert jika ada pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- ============================================= --}}
        {{-- KOLOM KIRI: DIBAGI MENJADI 3 KARTU INFORMASI --}}
        {{-- ============================================= --}}
        <div class="col-lg-7">
            {{-- KARTU 1: INFORMASI PENDATAAN --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Informasi Pendataan</h6></div>
                <div class="card-body py-2 px-3">
                    <dl class="info-list-split mb-0">
                        <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                    </dl>
                </div>
            </div>

            {{-- KARTU 2: INFORMASI LOKASI --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Informasi Lokasi</h6></div>
                <div class="card-body py-2 px-3">
                     <dl class="info-list-split mb-0">
                        <div class="row"><dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kabupaten/Kota</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $item->desa }}</dd></div>
                        <div class="row"><dt class="col-sm-4">RT / RW</dt><dd class="col-sm-8">{{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} / {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}</dd></div>
                    </dl>
                </div>
            </div>

            {{-- KARTU 3: RINGKASAN RUMAH TANGGA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Ringkasan Rumah Tangga</h6></div>
                <div class="card-body px-4 py-2">
                    <div class="summary-item"><span class="label"><i class="fas fa-users fa-fw"></i>Total Anggota</span> <span class="value">{{ $item->jart }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-briefcase fa-fw"></i>Anggota Bekerja</span> <span class="value">{{ $item->jart_ab }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-home fa-fw"></i>Anggota Tdk Bekerja</span> <span class="value">{{ $item->jart_tb }}</span></div>
                    <div class="summary-item"><span class="label"><i class="fas fa-user-graduate fa-fw"></i>Anggota Masih Sekolah</span> <span class="value">{{ $item->jart_ms }}</span></div>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- KOLOM KANAN: FORMULIR VERIFIKASI YANG BARU --}}
        {{-- ============================================= --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Tindakan Verifikasi</h6></div>
                <div class="card-body">
                    <form id="verification-form" action="{{ route('admin.tkw.process', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Verifikasi <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mr-4">
                                    <input type="radio" id="status_validated" name="status" value="validated" class="custom-control-input" @if(old('status', $item->status_validasi) == 'validated') checked @endif>
                                    <label class="custom-control-label" for="status_validated">Setujui (Validated)</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="status_rejected" name="status" value="rejected" class="custom-control-input" @if(old('status', $item->status_validasi) == 'rejected') checked @endif>
                                    <label class="custom-control-label" for="status_rejected">Tolak (Rejected)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                             <label for="admin_tgl_validasi" class="form-label">Tanggal Verifikasi <span class="text-danger">*</span></label>
                            <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi" value="{{ old('admin_tgl_validasi', now()->toDateString()) }}" class="form-control @error('admin_tgl_validasi') is-invalid @enderror" required>
                            @error('admin_tgl_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_nama_kepaladusun" class="form-label">Nama Verifikator <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun" value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun ?? Auth::user()->name) }}" class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror" required>
                            @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_ttd_pendata" class="form-label">Upload TTD Verifikator <span class="text-danger">*</span></label>
                            <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg" class="form-control-file @error('admin_ttd_pendata') is-invalid @enderror">
                            <div id="signature-preview" class="mt-2"></div> {{-- Wadah untuk preview TTD --}}
                            @error('admin_ttd_pendata') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_catatan" class="form-label">Komentar / Alasan Penolakan</label>
                            <textarea name="admin_catatan" id="admin_catatan" rows="3" class="form-control @error('admin_catatan') is-invalid @enderror" placeholder="Wajib diisi jika menolak pengajuan...">{{ old('admin_catatan', $item->admin_catatan) }}</textarea>
                            @error('admin_catatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4" id="comment-tags-container">
                            <label class="form-label d-block mb-1"><small>Tambahkan Tag Cepat:</small></label>
                            <span class="badge badge-pill badge-light comment-tag" data-tag="Kesalahan input data.">Salah Input</span>
                            <span class="badge badge-pill badge-light comment-tag" data-tag="Data tidak valid.">Data Tidak Valid</span>
                            <span class="badge badge-pill badge-light comment-tag" data-tag="Informasi kurang lengkap.">Kurang Lengkap</span>
                        </div>
                        
                        <div class="mt-auto d-flex justify-content-end">
                            <button type="button" id="submit-verification-btn" class="btn btn-primary btn-block">
                                <i class="fas fa-check-circle mr-2"></i> Proses Verifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Anggota Keluarga (TIDAK DIHILANGKAN) --}}
    <div class="card shadow-sm card-detail-section mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            {{-- ... (seluruh isi kartu rincian anggota keluarga Anda tetap di sini, termasuk tabelnya) ... --}}
        </div>
    </div>
</div>

{{-- Modal Konfirmasi (tidak berubah) --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    {{-- ... (seluruh kode modal konfirmasi Anda tetap di sini) ... --}}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Elemen-elemen Penting ===
    const signatureInput = document.getElementById('admin_ttd_pendata');
    const signaturePreview = document.getElementById('signature-preview');
    // ... (semua elemen lain dari script sebelumnya) ...
    const form = document.getElementById('verification-form');
    const radioValidated = document.getElementById('status_validated');
    const radioRejected = document.getElementById('status_rejected');
    const commentTextarea = document.getElementById('admin_catatan');
    const commentTagsContainer = document.getElementById('comment-tags-container');
    const commentTags = document.querySelectorAll('.comment-tag');
    const submitBtn = document.getElementById('submit-verification-btn');
    const modal = $('#confirmationModal');
    // ... dan seterusnya

    // === LOGIKA BARU UNTUK PREVIEW TANDA TANGAN ===
    if (signatureInput) {
        signatureInput.addEventListener('change', function(event) {
            signaturePreview.innerHTML = ''; // Kosongkan preview lama
            const file = event.target.files[0];
            if (file) {
                // Validasi tipe file
                if (!['image/png', 'image/jpeg'].includes(file.type)) {
                    alert('Format file harus PNG atau JPG.');
                    signatureInput.value = ''; // Reset input file
                    return;
                }
                // Validasi ukuran file (misal: max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    signatureInput.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    signaturePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // === SISA SCRIPT LAMA (TIDAK BERUBAH) ===
    function toggleCommentState() {
        // ... (fungsi toggleCommentState Anda yang sudah ada) ...
    }
    // ... (semua event listener lainnya yang sudah ada) ...
});
</script>
@endpush