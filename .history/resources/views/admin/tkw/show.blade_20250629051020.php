@extends('layouts.main')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
<style>
    /* Card Info Kiri */
    .info-list-split dt, .info-list-split dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; }
    .info-list-split .row { border-bottom: 1px solid #eaecf4; }
    .info-list-split .row:last-child { border-bottom: none; }
    .info-list-split dt { font-weight: 500; color: #5a5c69; }

    /* Card Ringkasan Kiri */
    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid #eaecf4; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: #5a5c69; display: flex; align-items: center; }
    .summary-item .label .fa-fw { margin-right: 0.75rem; color: #858796; font-size: 1.1em; }
    .summary-item .value { font-weight: bold; font-size: 1.1rem; color: #36b9cc; }
    
    /* Form Kanan */
    .form-label { font-size: 0.875rem; font-weight: 500; }
    .comment-tag { cursor: pointer; user-select: none; border: 1px solid #d1d3e2; margin-right: 5px; margin-bottom: 5px; display: inline-block; }
    .comment-tag:hover { background-color: #e9ecef; }
    #signature-preview img { max-height: 80px; border: 1px solid #ddd; border-radius: .25rem; margin-top: 10px; }
    
    /* Tabel Anggota Keluarga */
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; margin-bottom: 0; padding-left: 20px; }
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
        <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Alert untuk notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- ============================================= --}}
        {{-- KOLOM KIRI: DIBAGI MENJADI 3 KARTU INFORMASI --}}
        {{-- ============================================= --}}
        <div class="col-lg-7">
            {{-- KARTU 1: INFORMASI PENDATAAN --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Pendataan</h6></div>
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
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Lokasi</h6></div>
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
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Ringkasan Rumah Tangga</h6></div>
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
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tindakan Verifikasi</h6></div>
                <div class="card-body">
                    <form id="verification-form" action="{{ route('admin.tkw.process', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Verifikasi <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mr-4">
                                    <input type="radio" id="status_validated" name="status" value="validated" class="custom-control-input" @if(old('status', $item->status_validasi) == 'validated' || $item->status_validasi == 'pending') checked @endif>
                                    <label class="custom-control-label" for="status_validated">Setujui (Validated)</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="status_rejected" name="status" value="rejected" class="custom-control-input" @if(old('status') == 'rejected') checked @endif>
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
                            <div id="signature-preview"></div> {{-- Wadah untuk preview TTD --}}
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

    {{-- Tabel Anggota Keluarga --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Rincian Anggota Keluarga</h6></div>
        <div class="card-body">
            <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                <ul class="mb-0 pl-3">
                    <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga, <strong>NUK:</strong> Nomor Urut Anggota, <strong>HDKK:</strong> Hubungan Dengan Kepala Keluarga, <strong>J. Kelamin:</strong> Jenis Kelamin, <strong>Sts. Kawin:</strong> Status Perkawinan, <strong>Sts. Kerja:</strong> Status Pekerjaan, <strong>Jns. Kerja:</strong> Jenis Pekerjaan, <strong>Sub Jns. Kerja:</strong> Sub Jenis Pekerjaan, <strong>Pddk. Akhir:</strong> Pendidikan Terakhir, <strong>Pendapatan/bln:</strong> Pendapatan per Bulan</li>
                </ul>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th><th>Nama</th><th>NIK</th><th>Hub. KRT</th><th>NUK</th><th>HDKK</th><th>J. Kelamin</th><th>Sts. Kawin</th><th>Sts. Kerja</th><th>Jns. Kerja</th><th>Sub Jns. Kerja</th><th>Pddk. Akhir</th><th>Pendapatan/bln</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td class="text-center font-weight-bold">{{ $anggota->nik }}</td>
                                <td class="text-center">{{ $anggota->hdkrt_text }}</td>
                                <td class="text-center">{{ $anggota->nuk ?? '-' }}</td>
                                <td class="text-center">{{ $anggota->hdkk_text ?? '-' }}</td>
                                <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                                <td class="text-center">{{ $anggota->status_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->sub_jenis_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->pendidikan_terakhir_text }}</td>
                                <td class="text-right">{{ $anggota->pendapatan_per_bulan_text }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="13" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Verifikasi --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Tindakan</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body">
                <p>Anda akan memproses pengajuan dari <strong>{{ $item->nama_responden }}</strong> dengan detail sebagai berikut:</p>
                <dl>
                    <dt>Status Baru:</dt>
                    <dd><span id="modal-status" class="font-weight-bold"></span></dd>
                    <div id="modal-comment-section" style="display: none;">
                        <dt>Komentar:</dt>
                        <dd><blockquote class="mb-0" style="font-size: 0.9rem;"><i>"<span id="modal-comment"></span>"</i></blockquote></dd>
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
    const signatureInput = document.getElementById('admin_ttd_pendata');
    const signaturePreview = document.getElementById('signature-preview');
    const submitBtn = document.getElementById('submit-verification-btn');
    
    const modal = $('#confirmationModal'); // jQuery object for Bootstrap modal
    const modalStatus = document.getElementById('modal-status');
    const modalCommentSection = document.getElementById('modal-comment-section');
    const modalComment = document.getElementById('modal-comment');
    const confirmSubmitBtn = document.getElementById('confirm-submit');

    // === 1. LOGIKA UNTUK RADIO BUTTON SETUJU/TOLAK ===
    function toggleCommentState() {
        if (radioRejected.checked) {
            commentTextarea.disabled = false;
            commentTextarea.required = true;
            commentTagsContainer.style.display = 'block';
        } else {
            commentTextarea.disabled = true;
            commentTextarea.required = false;
            // commentTextarea.value = ''; // Opsional: hapus komentar jika memilih 'Setujui'
            commentTagsContainer.style.display = 'none';
        }
    }

    // === 2. LOGIKA UNTUK PREVIEW TANDA TANGAN ===
    if (signatureInput) {
        signatureInput.addEventListener('change', function(event) {
            signaturePreview.innerHTML = ''; // Kosongkan preview lama
            const file = event.target.files[0];
            if (file) {
                if (!['image/png', 'image/jpeg'].includes(file.type)) {
                    alert('Format file harus PNG atau JPG.');
                    signatureInput.value = ''; return;
                }
                if (file.size > 2 * 1024 * 1024) { // Max 2MB
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    signatureInput.value = ''; return;
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

    // === 3. LOGIKA UNTUK TOMBOL TAG KOMENTAR ===
    commentTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const tagText = this.getAttribute('data-tag');
            commentTextarea.value += (commentTextarea.value.trim() ? ' ' : '') + tagText;
            commentTextarea.focus();
        });
    });

    // === 4. LOGIKA UNTUK MODAL KONFIRMASI ===
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            if (!radioValidated.checked && !radioRejected.checked) {
                alert('Silakan pilih status "Setujui" atau "Tolak" terlebih dahulu.'); return;
            }
            if (!form.checkValidity()) {
                form.reportValidity(); return;
            }
            
            if (radioValidated.checked) {
                modalStatus.textContent = 'DISETUJUI (VALIDATED)';
                modalStatus.className = 'font-weight-bold text-success';
                modalCommentSection.style.display = 'none';
            } else {
                modalStatus.textContent = 'DITOLAK (REJECTED)';
                modalStatus.className = 'font-weight-bold text-danger';
                if (commentTextarea.value.trim()) {
                    modalComment.textContent = commentTextarea.value;
                    modalCommentSection.style.display = 'block';
                } else {
                     modalCommentSection.style.display = 'none';
                }
            }
            modal.modal('show');
        });
    }

    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
            form.submit();
        });
    }

    toggleCommentState();
    if (radioValidated) radioValidated.addEventListener('change', toggleCommentState);
    if (radioRejected) radioRejected.addEventListener('change', toggleCommentState);
});
</script>
@endpush