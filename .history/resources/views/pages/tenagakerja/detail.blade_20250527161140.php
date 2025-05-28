@extends('layouts.main')

@section('title', 'Validasi Rumah Tangga - ' . $item->nama_responden . ' (RT-' . $item->id . ')')

@push('styles')
<style>
    /* Styling untuk informasi yang lebih padat di dalam bagian Informasi Rumah Tangga */
    .info-block {
        margin-bottom: 0.6rem; /* Jarak antar item info */
        line-height: 1.4;
        font-size: 0.875rem; /* Ukuran font dasar untuk info blok */
    }
    .info-block .info-label {
        font-size: 0.8rem; /* Label sedikit lebih kecil */
        color: #858796;   /* text-muted */
        display: block; /* Label di atas nilai */
    }
    .info-block .info-value {
        color: #5a5c69;    /* Warna teks standar untuk nilai */
        word-break: break-word;
    }
    .card-body hr.my-3 {
        margin-top: 1.25rem !important;
        margin-bottom: 1.25rem !important;
    }
    .sub-section-title {
        font-size: 1.1rem; /* Ukuran untuk sub-judul seperti Informasi Rumah Tangga */
        color: #4e73df; /* text-primary */
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0"> {{-- px-0 jika ingin menghilangkan padding default --}}

    {{-- KARTU UTAMA UNTUK SELURUH KONTEN VALIDASI --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">
                Validasi Pengajuan: <span class="text-primary">{{ $item->nama_responden }}</span> (ID Sistem: RT-{{ $item->id }})
            </h6>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
            </a>
        </div>
        <div class="card-body">

            {{-- SEKSI 1: INFORMASI RUMAH TANGGA --}}
            <h5 class="sub-section-title">Informasi Rumah Tangga</h5>
            <div class="row">
                {{-- Kolom 1 Info --}}
                <div class="col-lg-4 col-md-6">
                    <div class="info-block">
                        <span class="info-label">Provinsi</span>
                        <span class="info-value">{{ $item->provinsi }}</span>
                    </div>
                    <div class="info-block">
                        <span class="info-label">Kabupaten</span>
                        <span class="info-value">{{ $item->kabupaten }}</span>
                    </div>
                    <div class="info-block">
                        <span class="info-label">Kecamatan</span>
                        <span class="info-value">{{ $item->kecamatan }}</span>
                    </div>
                </div>
                {{-- Kolom 2 Info --}}
                <div class="col-lg-4 col-md-6">
                    <div class="info-block">
                        <span class="info-label">Desa/Kelurahan</span>
                        <span class="info-value">{{ $item->desa }}</span>
                    </div>
                    <div class="info-block">
                        <span class="info-label">RT/RW</span>
                        <span class="info-value">{{ $item->rt }}/{{ $item->rw }}</span>
                    </div>
                    <div class="info-block">
                        <span class="info-label">Nama Pendata</span>
                        <span class="info-value">{{ $item->nama_pendata }}</span>
                    </div>
                </div>
                {{-- Kolom 3 Info (Statistik & Status) --}}
                <div class="col-lg-4 col-md-12"> {{-- Di layar md, kolom ini akan di bawah --}}
                    <div class="info-block">
                        <span class="info-label">Tgl. Pengajuan</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    <div class="info-block">
                        <span class="info-label">Status Saat Ini</span>
                        <span class="info-value">
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark border';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-4 col-md-6">
                    <div class="info-block">
                        <span class="info-label">Jml. Anggota RT</span>
                        <span class="info-value">{{ $item->jart }} orang</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-block">
                        <span class="info-label">Jml. ART Bekerja</span>
                        <span class="info-value">{{ $item->jart_ab }} orang</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-block">
                        <span class="info-label">Pendapatan RT/Bln</span>
                        <span class="info-value">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</span>
                    </div>
                </div>
                 @if($item->ttd_pendata)
                <div class="col-lg-12 mt-2">
                    <div class="info-block">
                        <span class="info-label">TTD Pendata (Pengirim):</span>
                        <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee; padding: 2px; background-color: #fff;" class="img-thumbnail info-value">
                    </div>
                </div>
                @endif
            </div>

            <hr class="my-3">

            {{-- SEKSI 2: DATA ANGGOTA KELUARGA --}}
            <h5 class="sub-section-title">Data Anggota Keluarga</h5>
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
            <h5 class="sub-section-title">Tindakan Validasi</h5>
            <div class="row">
                <div class="col-md-8 col-lg-7"> {{-- Lebar form tetap dibatasi --}}
                    <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- ... Isi form validasi (tidak ada perubahan di sini dari versi Anda sebelumnya) ... --}}
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
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menolak data rumah tangga #{{ $item->id }} ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.tkw.reject', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Ya, Tolak Data</button>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
{{-- Script Preview Gambar (jika belum ada di layout utama atau file JS terpisah) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputTtd = document.getElementById('admin_ttd_pendata');
    const previewTtd = document.getElementById('preview-ttd');

    if(inputTtd && previewTtd) {
        inputTtd.addEventListener('change', function () {
            const file = this.files[0];
            const existingImage = previewTtd.querySelector('img');
            if (existingImage) {
                existingImage.remove();
            }
            const placeholder = previewTtd.querySelector('small');
            if(placeholder) placeholder.style.display = 'none';


            if (file) {
                const allowedTypes = ['image/png', 'image/jpeg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('File harus berformat JPG atau PNG.');
                    this.value = '';
                    if(placeholder) placeholder.style.display = 'block';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('Ukuran file maksimal 2MB.');
                    this.value = '';
                    if(placeholder) placeholder.style.display = 'block';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxHeight = '100px';
                    img.style.border = '1px solid #ddd';
                    img.style.padding = '2px';
                    img.classList.add('img-thumbnail', 'mt-1');
                    previewTtd.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                if(placeholder && !previewTtd.querySelector('img[src*="storage"]')) { // Tampilkan placeholder hanya jika tidak ada TTD tersimpan DAN tidak ada file dipilih
                    placeholder.style.display = 'block';
                } else if (!previewTtd.querySelector('img')) { // Jika tidak ada gambar sama sekali (baik dari storage atau preview baru)
                    if(placeholder) placeholder.style.display = 'block';
                }
            }
        });
         // Inisialisasi placeholder jika tidak ada TTD tersimpan saat halaman dimuat
        if (!previewTtd.querySelector('img')) {
            const placeholder = previewTtd.querySelector('small');
            if(placeholder) placeholder.style.display = 'block';
        } else {
            const placeholder = previewTtd.querySelector('small');
            if(placeholder) placeholder.style.display = 'none';
        }
    }
});
</script>
@endpush
@endsection