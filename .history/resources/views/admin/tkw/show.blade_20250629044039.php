@extends('layouts.main')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
{{-- Style tidak berubah --}}
<style>
    .card-detail-section .card-title { color: #4e73df !important; }
    .info-list-lined dt, .info-list-lined dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; border-bottom: 1px solid #eaecf4; display: flex; align-items: center; }
    .info-list-lined > .row:last-of-type > dt, .info-list-lined > .row:last-of-type > dd { border-bottom: none; }
    .info-list-lined dt { font-weight: 500; color: #5a5c69; }
    .info-list-lined dd { font-weight: 400; }
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { height: 80px; width: 100%; object-fit: contain; border: 1px solid #eee; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .form-label { font-size: 0.875rem; font-weight: 500; }
    .comment-tag { cursor: pointer; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
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
    </div>

    {{-- BARIS UTAMA: Info Pengajuan (Kiri) & Form Verifikasi (Kanan) --}}
    <div class="row">
        {{-- Kolom Kiri: Informasi Detail Pengajuan --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm card-detail-section h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6></div>
                <div class="card-body py-2 px-3">
                    <dl class="dl-horizontal info-list-lined mb-0">
                        <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                        <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                        {{-- LOKASI LENGKAP --}}
                        <div class="row">
                            <dt class="col-sm-4">Lokasi</dt>
                            <dd class="col-sm-8">
                                RT {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} / RW {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}, Desa/Kel. {{ $item->desa }}, <br>
                                Kec. {{ $item->kecamatan }}, Kab. {{ $item->kabupaten }}, Prov. {{ $item->provinsi }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Formulir Verifikasi Baru --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold card-title">Tindakan Verifikasi</h6></div>
                <div class="card-body">
                    {{-- Form utama kita, sekarang menjadi satu --}}
                    <form id="verification-form" action="{{ route('admin.tkw.process', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- 1. Pilihan Status (Radio Button) --}}
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Verifikasi <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mr-4">
                                    <input type="radio" id="status_validated" name="status" value="validated" class="custom-control-input" {{ old('status', $item->status_validasi) == 'validated' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status_validated">Setujui (Validated)</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="status_rejected" name="status" value="rejected" class="custom-control-input" {{ old('status', $item->status_validasi) == 'rejected' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status_rejected">Tolak (Rejected)</label>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Input Verifikator --}}
                         <div class="mb-3">
                            <label for="admin_nama_kepaladusun" class="form-label">Nama Verifikator <span class="text-danger">*</span></label>
                            <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun" value="{{ old('admin_nama_kepaladusun', Auth::user()->name) }}" class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror" required>
                            @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="admin_ttd_pendata" class="form-label">Upload TTD Verifikator <small>(Opsional)</small></label>
                            <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg" class="form-control-file @error('admin_ttd_pendata') is-invalid @enderror">
                            @error('admin_ttd_pendata') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- 3. Komentar Penolakan --}}
                        <div class="mb-3">
                            <label for="admin_catatan" class="form-label">Komentar / Alasan Penolakan</label>
                            <textarea name="admin_catatan" id="admin_catatan" rows="3" class="form-control @error('admin_catatan') is-invalid @enderror" placeholder="Wajib diisi jika menolak pengajuan..." disabled>{{ old('admin_catatan', $item->admin_catatan) }}</textarea>
                            @error('admin_catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 4. Tag Komentar Cepat --}}
                        <div class="mb-4" id="comment-tags-container" style="display: none;">
                            <label class="form-label d-block mb-1"><small>Tambahkan Tag Cepat:</small></label>
                            <span class="badge badge-pill badge-light border comment-tag" data-tag="Kesalahan input data. ">Salah Input</span>
                            <span class="badge badge-pill badge-light border comment-tag" data-tag="Data tidak valid. ">Data Tidak Valid</span>
                            <span class="badge badge-pill badge-light border comment-tag" data-tag="Informasi tidak lengkap. ">Kurang Lengkap</span>
                        </div>
                        
                        {{-- 5. Tombol Aksi --}}
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

    {{-- Tabel Anggota Keluarga (Tidak Berubah) --}}
    <div class="card shadow-sm card-detail-section mb-4">
        {{-- ... (seluruh isi kartu rincian anggota keluarga Anda tetap di sini) ... --}}
    </div>
</div>


{{-- Modal Konfirmasi Verifikasi (BARU) --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Anda akan memproses pengajuan dari <strong>{{ $item->nama_responden }}</strong> dengan detail sebagai berikut:</p>
                <dl>
                    <dt>Status Baru:</dt>
                    <dd><span id="modal-status" class="font-weight-bold"></span></dd>
                    
                    <div id="modal-comment-section" style="display: none;">
                        <dt>Komentar:</dt>
                        <dd><em id="modal-comment"></em></dd>
                    </div>
                </dl>
                <p class="mb-0">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="confirm-submit" class="btn btn-primary">Ya, Kirim Verifikasi</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Elemen-elemen Penting ===
    const form = document.getElementById('verification-form');
    const radioValidated = document.getElementById('status_validated');
    const radioRejected = document.getElementById('status_rejected');
    const commentTextarea = document.getElementById('admin_catatan');
    const commentTagsContainer = document.getElementById('comment-tags-container');
    const commentTags = document.querySelectorAll('.comment-tag');
    const submitBtn = document.getElementById('submit-verification-btn');
    
    const modal = $('#confirmationModal'); // jQuery object for Bootstrap modal
    const modalStatus = document.getElementById('modal-status');
    const modalCommentSection = document.getElementById('modal-comment-section');
    const modalComment = document.getElementById('modal-comment');
    const confirmSubmitBtn = document.getElementById('confirm-submit');

    // === Fungsi untuk Mengatur Status Komentar ===
    function toggleCommentState() {
        if (radioRejected.checked) {
            commentTextarea.disabled = false;
            commentTextarea.required = true;
            commentTagsContainer.style.display = 'block';
        } else {
            commentTextarea.disabled = true;
            commentTextarea.required = false;
            commentTagsContainer.style.display = 'none';
        }
    }

    // === Event Listeners ===

    // 1. Jalankan fungsi saat halaman dimuat untuk set status awal
    toggleCommentState();

    // 2. Listener untuk perubahan pada radio button
    if (radioValidated) radioValidated.addEventListener('change', toggleCommentState);
    if (radioRejected) radioRejected.addEventListener('change', toggleCommentState);

    // 3. Listener untuk klik pada tag komentar
    commentTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const currentComment = commentTextarea.value;
            const tagText = this.getAttribute('data-tag');
            // Tambahkan spasi jika komentar sudah ada isinya
            commentTextarea.value += (currentComment ? ' ' : '') + tagText;
            commentTextarea.focus();
        });
    });

    // 4. Listener untuk tombol "Proses Verifikasi" -> Membuka Modal
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            // Cek apakah salah satu radio button sudah dipilih
            if (!radioValidated.checked && !radioRejected.checked) {
                alert('Silakan pilih status "Setujui" atau "Tolak" terlebih dahulu.');
                return;
            }
            
            // Isi konten modal berdasarkan pilihan
            if (radioValidated.checked) {
                modalStatus.textContent = 'DISETUJUI (VALIDATED)';
                modalStatus.className = 'font-weight-bold text-success';
                modalCommentSection.style.display = 'none';
            } else { // radioRejected.checked
                modalStatus.textContent = 'DITOLAK (REJECTED)';
                modalStatus.className = 'font-weight-bold text-danger';
                if (commentTextarea.value.trim()) {
                    modalComment.textContent = commentTextarea.value;
                    modalCommentSection.style.display = 'block';
                } else {
                     modalCommentSection.style.display = 'none';
                }
            }
            
            // Tampilkan modal
            modal.modal('show');
        });
    }

    // 5. Listener untuk tombol konfirmasi final di dalam modal -> Submit Form
    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', function() {
            form.submit();
        });
    }
});
</script>
@endpush