@extends('layouts.main')

@section('title', 'Verifikasi Pengajuan: ' . $item->nama_responden)

@push('styles')
<style>
    /* Style yang disesuaikan untuk konsistensi */
    .info-list-split dt, .info-list-split dd { padding-top: 0.6rem; padding-bottom: 0.6rem; margin-bottom: 0; }
    .info-list-split .row { border-bottom: 1px solid #eaecf4; }
    .info-list-split .row:last-child { border-bottom: none; }
    .info-list-split dt { font-weight: 500; color: #5a5c69; }
    
    /* Style BARU untuk sub-header di dalam kartu */
    .sub-header-info {
        background-color: #f8f9fc;
        padding: 0.5rem 0.75rem;
        margin-top: 1rem;
        margin-bottom: 0;
        font-weight: bold;
        font-size: 0.9rem;
        color: #5a5c69;
        border-top: 1px solid #eaecf4;
        border-bottom: 1px solid #eaecf4;
    }
    #detail-card .card-body > .sub-header-info:first-of-type {
        margin-top: 0;
        border-top: none;
    }
    
    /* Sisa style tetap sama */
    .form-label { font-size: 0.875rem; font-weight: 500; }
    .comment-tag { cursor: pointer; user-select: none; border: 1px solid #d1d3e2; margin-right: 5px; margin-bottom: 5px; display: inline-block; }
    .comment-tag:hover { background-color: #e9ecef; }
    #signature-preview img { max-height: 80px; border: 1px solid #ddd; border-radius: .25rem; margin-top: 10px; }
    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; white-space: normal; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; margin-bottom: 0; padding-left: 20px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- ... (kode judul halaman tidak berubah) ... --}}
    </div>

    {{-- Alert untuk notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri: Informasi Detail Pengajuan --}}
        <div class="col-lg-7">
            {{-- KARTU 1: GABUNGAN INFORMASI PENDATAAN & LOKASI dengan style sub-header baru --}}
            <div class="card shadow-sm mb-4" id="detail-card">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Detail Pendataan & Lokasi</h6></div>
                <div class="card-body p-0"> {{-- p-0 agar sub-header menempel --}}
                    <div class="sub-header-info">Informasi Pengajuan</div>
                    <div class="px-3">
                        <dl class="info-list-split mb-0">
                            <div class="row"><dt class="col-sm-4">Nama Responden</dt><dd class="col-sm-8">{{ $item->nama_responden }}</dd></div>
                            <div class="row"><dt class="col-sm-4">Nama Pendata</dt><dd class="col-sm-8">{{ $item->nama_pendata }}</dd></div>
                            <div class="row"><dt class="col-sm-4">Tgl. Pengajuan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd></div>
                        </dl>
                    </div>

                    <div class="sub-header-info">Lokasi</div>
                    <div class="px-3">
                        <dl class="info-list-split mb-0">
                            <div class="row"><dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd></div>
                            <div class="row"><dt class="col-sm-4">Kabupaten/Kota</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd></div>
                            <div class="row"><dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd></div>
                            <div class="row"><dt class="col-sm-4">Desa/Kelurahan</dt><dd class="col-sm-8">{{ $item->desa }}</dd></div>
                            <div class="row"><dt class="col-sm-4">RT / RW</dt><dd class="col-sm-8">{{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }} / {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}</dd></div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- ... (Kartu Ringkasan Rumah Tangga tidak berubah) ... --}}
        </div>

        {{-- Kolom Kanan: Formulir Verifikasi --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tindakan Verifikasi</h6></div>
                <div class="card-body">
                    <form id="verification-form" action="{{ route('admin.tkw.process', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Bagian Pilihan Status (Tidak Berubah) --}}
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

                        {{-- Bagian input yang bisa disable --}}
                        <div id="approval-fields">
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
                                <div id="signature-preview" class="mt-2"></div>
                                @error('admin_ttd_pendata') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <small id="rejection-footnote" class="form-text text-muted mb-3" style="display: none;">Tanggal, Nama, dan TTD tidak diperlukan untuk menolak pengajuan.</small>
                        
                        {{-- Bagian Komentar --}}
                        <div class="mb-3">
                            <label for="admin_catatan" class="form-label">Komentar / Alasan Penolakan</label>
                            <textarea name="admin_catatan" id="admin_catatan" rows="3" class="form-control @error('admin_catatan') is-invalid @enderror" placeholder="Wajib diisi jika menolak pengajuan..."></textarea>
                            <small id="approval-footnote" class="form-text text-muted" style="display: none;">Komentar tidak diperlukan jika menyetujui pengajuan.</small>
                            @error('admin_catatan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- ... (Tag Komentar dan Tombol Proses tidak berubah) ... --}}
                        
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
                {{-- PETUNJUK TABEL DENGAN STYLE BARU YANG LEBIH MUDAH DIBACA --}}
                <ul class="mb-0 pl-3">
                    <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga</li>
                    <li><strong>NUK:</strong> Nomor Urut Anggota dalam Keluarga</li>
                    <li><strong>HDKK:</strong> Hubungan Dengan Kepala Keluarga</li>
                    <li><strong>J. Kelamin:</strong> Jenis Kelamin</li>
                    <li><strong>Sts. Kawin:</strong> Status Perkawinan</li>
                    <li><strong>Sts. Kerja:</strong> Status Pekerjaan</li>
                    <li><strong>Jns. Kerja:</strong> Jenis Pekerjaan</li>
                    <li><strong>Sub Jns. Kerja:</strong> Sub Jenis Pekerjaan</li>
                    <li><strong>Pddk. Akhir:</strong> Pendidikan Terakhir</li>
                    <li><strong>Pendapatan/bln:</strong> Pendapatan per Bulan</li>
                </ul>
            </div>
             {{-- ... (Isi tabel anggota keluarga Anda tetap di sini) ... --}}
        </div>
    </div>
</div>

{{-- ... (Modal Konfirmasi Anda tetap di sini) ... --}}
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Elemen-elemen Penting ===
    const radioValidated = document.getElementById('status_validated');
    const radioRejected = document.getElementById('status_rejected');
    const commentTextarea = document.getElementById('admin_catatan');
    const approvalFieldsDiv = document.getElementById('approval-fields');
    const approvalInputs = approvalFieldsDiv.querySelectorAll('input');
    const approvalFootnote = document.getElementById('approval-footnote');
    const rejectionFootnote = document.getElementById('rejection-footnote');
    // ... sisa elemen lainnya ...

    // === Fungsi untuk Mengatur Status Form ===
    function updateFormState() {
        if (radioRejected.checked) {
            // Logika saat "Tolak" dipilih
            commentTextarea.disabled = false;
            commentTextarea.required = true;
            approvalInputs.forEach(input => {
                input.disabled = true;
                input.required = false; // Hapus required agar form bisa submit
            });
            approvalFootnote.style.display = 'none';
            rejectionFootnote.style.display = 'block';
        } else { // Asumsi "Setujui" dipilih
            // Logika saat "Setujui" dipilih
            commentTextarea.disabled = true;
            commentTextarea.required = false;
            commentTextarea.value = ''; // Kosongkan komentar
            approvalInputs.forEach(input => {
                input.disabled = false;
                // Kembalikan 'required' ke input yang memang wajib
                if (input.name !== 'admin_ttd_pendata') { // TTD tidak wajib di HTML5
                    input.required = true;
                }
            });
            approvalFootnote.style.display = 'block';
            rejectionFootnote.style.display = 'none';
        }
    }

    // === Event Listeners ===
    
    // Jalankan fungsi saat halaman dimuat
    updateFormState();

    // Tambahkan listener untuk perubahan pada radio button
    radioValidated.addEventListener('change', updateFormState);
    radioRejected.addEventListener('change', updateFormState);

    // ... sisa semua script lainnya (preview TTD, tag, modal) tetap sama ...
});
</script>
@endpush