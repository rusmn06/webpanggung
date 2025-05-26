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

    {{-- Baris Pertama: Data Rumah Tangga & Form Validasi Admin --}}
    <div class="row">
        {{-- Kiri: Kartu Data Rumah Tangga --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Informasi Rumah Tangga</h6>
                </div>
                <div class="card-body">
                    {{-- Menggunakan struktur dl-dt-dd untuk kerapian --}}
                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-md-4">Provinsi</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->provinsi }}</dd>

                        <dt class="col-sm-5 col-md-4">Kabupaten</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->kabupaten }}</dd>

                        <dt class="col-sm-5 col-md-4">Kecamatan</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->kecamatan }}</dd>

                        <dt class="col-sm-5 col-md-4">Desa/Kelurahan</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->desa }}</dd>

                        <dt class="col-sm-5 col-md-4">RT/RW</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->rt }}/{{ $item->rw }}</dd>

                        <dt class="col-sm-5 col-md-4">Nama Pendata</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->nama_pendata }}</dd>

                        <dt class="col-sm-5 col-md-4">Nama Responden</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->nama_responden }}</dd>

                        <dt class="col-sm-5 col-md-4">Tgl. Pengajuan</dt>
                        <dd class="col-sm-7 col-md-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd>

                        <dt class="col-sm-5 col-md-4">Status Saat Ini</dt>
                        <dd class="col-sm-7 col-md-8">
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
                    <hr class="my-3">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-md-4">Jml. Anggota RT</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->jart }}</dd>

                        <dt class="col-sm-5 col-md-4">Jml. ART Bekerja</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->jart_ab }}</p>

                        <dt class="col-sm-5 col-md-4">Pendapatan RT/Bulan</dt>
                        <dd class="col-sm-7 col-md-8">{{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</dd>

                        @if($item->ttd_pendata)
                        <dt class="col-sm-5 col-md-4 mt-2">TTD Pendata</dt>
                        <dd class="col-sm-7 col-md-8 mt-2">
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                        </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Kanan: Kartu Form Validasi Admin --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Form Tindakan Validasi</h6>
                </div>
                <div class="card-body">
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
                            {{-- Preview TTD --}}
                            <div id="preview-ttd" class="mt-2 text-center" style="min-height: 100px;">
                                @if($item->admin_ttd_pendata)
                                    <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 100px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                                @else
                                    <small class="text-muted d-block mt-3">Preview TTD akan muncul di sini jika ada file yang dipilih.</small>
                                @endif
                            </div>
                        </div>

                        @if($item->status_validasi == 'pending')
                            <div class="d-flex justify-content-end pt-2"> {{-- Tombol ke kanan --}}
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
        </div>
    </div>

    {{-- Baris Kedua: Kartu Data Anggota Keluarga --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" style="font-size: 0.9rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">No.</th> {{-- style width untuk No --}}
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
                                        <td class="text-center">{{ $loop->iteration }}</td> {{-- Menggunakan $loop->iteration untuk nomor --}}
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
                </div>
            </div>
        </div>
    </div>

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
{{-- Script Preview Gambar --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputTtd = document.getElementById('admin_ttd_pendata');
    const previewTtd = document.getElementById('preview-ttd');

    if(inputTtd && previewTtd) {
        inputTtd.addEventListener('change', function () {
            const file = this.files[0];
            // Bersihkan semua isi previewTtd sebelum menambahkan gambar baru atau placeholder
            previewTtd.innerHTML = '';

            if (file) {
                const allowedTypes = ['image/png', 'image/jpeg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('File harus berformat JPG atau PNG.');
                    this.value = '';
                    // Tambahkan kembali placeholder jika input dikosongkan karena error
                    if (!previewTtd.querySelector('img') && !previewTtd.querySelector('small')) {
                         const placeholderText = document.createElement('small');
                         placeholderText.classList.add('text-muted', 'd-block', 'mt-3');
                         placeholderText.textContent = 'Preview TTD akan muncul di sini jika ada file yang dipilih.';
                         previewTtd.appendChild(placeholderText);
                    }
                    return;
                }
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('Ukuran file maksimal 2MB.');
                    this.value = '';
                    // Tambahkan kembali placeholder
                     if (!previewTtd.querySelector('img') && !previewTtd.querySelector('small')) {
                         const placeholderText = document.createElement('small');
                         placeholderText.classList.add('text-muted', 'd-block', 'mt-3');
                         placeholderText.textContent = 'Preview TTD akan muncul di sini jika ada file yang dipilih.';
                         previewTtd.appendChild(placeholderText);
                    }
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
                 // Jika tidak ada file DIPILIH (misal dibatalkan), tampilkan placeholder jika tidak ada TTD tersimpan
                if (!previewTtd.querySelector('img[src*="storage"]') && !previewTtd.querySelector('small')) {
                     const placeholderText = document.createElement('small');
                     placeholderText.classList.add('text-muted', 'd-block', 'mt-3');
                     placeholderText.textContent = 'Preview TTD akan muncul di sini jika ada file yang dipilih.';
                     previewTtd.appendChild(placeholderText);
                }
            }
        });
        // Tambahkan placeholder awal jika tidak ada TTD tersimpan
        if (!previewTtd.querySelector('img') && !previewTtd.querySelector('small')) {
             const placeholderText = document.createElement('small');
             placeholderText.classList.add('text-muted', 'd-block', 'mt-3');
             placeholderText.textContent = 'Preview TTD akan muncul di sini jika ada file yang dipilih.';
             previewTtd.appendChild(placeholderText);
        }
    }
});
</script>
@endpush
@endsection