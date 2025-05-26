@extends('layouts.main')

@section('title', 'Detail Validasi Rumah Tangga #RT-' . $item->id)

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800 mb-0">Validasi Rumah Tangga #RT-{{ $item->id }} (Responden: {{ $item->nama_responden }})</h1>
        {{-- Pastikan nama route ini benar: admin.tkw.index --}}
        <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-gray-700"></i> Kembali ke Daftar Validasi
        </a>
    </div>

    {{-- Baris Pertama: Data Rumah Tangga & Form Validasi Admin --}}
    <div class="row">
        {{-- Kiri: Kartu Data Rumah Tangga --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Informasi Rumah Tangga</h6>
                </div>
                <div class="card-body">
                    {{-- Isi data rumah tangga seperti versi sebelumnya --}}
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Provinsi:</strong><br>{{ $item->provinsi }}</p>
                            <p class="mb-2"><strong>Kabupaten:</strong><br>{{ $item->kabupaten }}</p>
                            <p class="mb-2"><strong>Kecamatan:</strong><br>{{ $item->kecamatan }}</p>
                            <p class="mb-2"><strong>Desa/Kelurahan:</strong><br>{{ $item->desa }}</p>
                            <p class="mb-0"><strong>RT/RW:</strong><br>{{ $item->rt }}/{{ $item->rw }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nama Pendata:</strong><br>{{ $item->nama_pendata }}</p>
                            <p class="mb-2"><strong>Nama Responden:</strong><br>{{ $item->nama_responden }}</p>
                            <p class="mb-2"><strong>Tgl. Pengajuan:</strong><br>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</p>
                            <p class="mb-0"><strong>Status Saat Ini:</strong><br>
                                @php
                                    $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                    $badgeClass = 'badge-light text-dark';
                                    if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                    if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                    if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                            </p>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jumlah Anggota Rumah Tangga:</strong> {{ $item->jart }}</p>
                            <p class="mb-2"><strong>Jml. ART Bekerja:</strong> {{ $item->jart_ab }}</p>
                            <p class="mb-0"><strong>Pendapatan RT/Bulan:</strong> {{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</p>
                        </div>
                        @if($item->ttd_pendata)
                        <div class="col-md-6">
                            <p class="mb-1"><strong>TTD Pendata:</strong></p>
                            <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Kartu Form Validasi Admin --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 font-weight-bold">Form Tindakan Validasi</h6>
                </div>
                <div class="card-body">
                    {{-- Isi form validasi seperti versi sebelumnya --}}
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
                            <div id="preview-ttd" class="mt-2 text-center">
                                @if($item->admin_ttd_pendata)
                                    <small class="text-muted d-block mb-1">TTD Tersimpan:</small>
                                    <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 100px; border: 1px solid #ddd; padding: 2px;" class="img-thumbnail">
                                @endif
                            </div>
                        </div>

                        @if($item->status_validasi == 'pending')
                            <div class="pt-2">
                                <button type="submit" class="btn btn-success btn-sm btn-icon-split w-100 mb-2">
                                    <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                    <span class="text">Setujui Pengajuan Ini</span>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn-icon-split w-100" data-toggle="modal" data-target="#rejectModal">
                                     <span class="icon text-white-50"><i class="fas fa-times"></i></span>
                                     <span class="text">Tolak Pengajuan Ini</span>
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
            <div class="card shadow-sm"> {{-- Kartu independen 3 --}}
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm" style="font-size: 0.9rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Urut</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIK</th>
                                    <th>Kelamin</th>
                                    <th>Hub. KRT</th>
                                    <th>Pendidikan</th>
                                    <th>Pekerjaan</th>
                                    <th>Status Kawin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->anggotaKeluarga as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $anggota->nuk }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>'{{ $anggota->nik }}</td>
                                        <td>{{ $anggota->kelamin_text }}</td>
                                        <td>{{ $anggota->hdkrt_text }}</td>
                                        <td>{{ $anggota->pendidikan_terakhir_text }}</td>
                                        <td>{{ $anggota->status_pekerjaan_text }}
                                            @if($anggota->status_pekerjaan == '1') <small class="d-block text-muted">({{ $anggota->jenis_pekerjaan_text }})</small>
                                            @endif
                                        </td>
                                        <td>{{ $anggota->status_perkawinan_text }}</td>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menolak data rumah tangga #{{ $item->id }} ini? Tindakan ini akan mengubah status menjadi 'Ditolak'.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                {{-- Pastikan nama route ini benar: admin.tkw.reject --}}
                <form action="{{ route('admin.tkw.reject', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Tolak Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Script Preview Gambar (sama seperti sebelumnya) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputTtd = document.getElementById('admin_ttd_pendata');
    const previewTtd = document.getElementById('preview-ttd');

    if(inputTtd && previewTtd) {
        inputTtd.addEventListener('change', function () {
            const file = this.files[0];
            const existingImage = previewTtd.querySelector('img');
            if (existingImage) {
                existingImage.remove(); // Hapus preview lama jika ada
            }

            if (file) {
                const allowedTypes = ['image/png', 'image/jpeg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('File harus berformat JPG atau PNG.');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('Ukuran file maksimal 2MB.');
                    this.value = '';
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
            }
        });
    }
});
</script>
@endpush
@endsection