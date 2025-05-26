@extends('layouts.main')

@section('title', 'Validasi Rumah Tangga')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            {{-- Tampilkan ID Rumah Tangga dan Nama Responden --}}
            <h1 class="h3 text-gray-800 mb-0">Validasi Rumah Tangga #{{ $item->id }} ({{ $item->nama_responden }})</h1>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kiri: Data Rumah Tangga --}}
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            Data Rumah Tangga
                        </div>
                        <div class="card-body">
                            <p><strong>Provinsi:</strong> {{ $item->provinsi }}</p>
                            <p><strong>Kabupaten:</strong> {{ $item->kabupaten }}</p>
                            <p><strong>Kecamatan:</strong> {{ $item->kecamatan }}</p>
                            <p><strong>Desa:</strong> {{ $item->desa }}</p>
                            <p><strong>RT/RW:</strong> {{ $item->rt }}/{{ $item->rw }}</p>
                            <p><strong>Tgl. Pengajuan:</strong> {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</p>
                            <p><strong>Nama Pendata:</strong> {{ $item->nama_pendata }}</p>
                            <p><strong>Nama Responden:</strong> {{ $item->nama_responden }}</p>
                            <hr>
                            <p><strong>Jumlah Anggota RT:</strong> {{ $item->jart }}</p>
                            <p><strong>Jumlah ART Bekerja:</strong> {{ $item->jart_ab }}</p>
                            <p><strong>JPR2RTP:</strong> {{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</p> {{-- Pakai Accessor --}}
                            <p><strong>Status Saat Ini:</strong> {{ $item->status_validasi_text ?? $item->status_validasi }}</p> {{-- Pakai Accessor --}}
                            {{-- Tampilkan TTD Pendata jika ada --}}
                            @if($item->ttd_pendata)
                                <p><strong>TTD Pendata:</strong></p>
                                <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 100px;" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kanan: Form Validasi --}}
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            Form Validasi Admin
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="admin_tgl_validasi" class="form-label">Tanggal Validasi</label>
                                    <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi"
                                        value="{{ old('admin_tgl_validasi', $item->admin_tgl_validasi ?? now()->toDateString()) }}" {{-- Default tanggal hari ini --}}
                                        class="form-control @error('admin_tgl_validasi') is-invalid @enderror">
                                    @error('admin_tgl_validasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admin_nama_kepaladusun" class="form-label">Nama Kepala Dusun/Pejabat</label>
                                    <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                                        value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}"
                                        class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                                    @error('admin_nama_kepaladusun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admin_ttd_pendata" class="form-label">Upload TTD Pejabat (JPG/PNG &lt;2MB)</label>
                                    <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg"
                                        class="form-control @error('admin_ttd_pendata') is-invalid @enderror">
                                    @error('admin_ttd_pendata')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="preview-ttd" class="mt-3">
                                        @if($item->admin_ttd_pendata)
                                            <small class="text-muted d-block mb-2">TTD Tersimpan:</small>
                                            <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 120px;" class="img-thumbnail mb-2">
                                        @endif
                                    </div>
                                </div>

                                {{-- Hanya tampilkan tombol jika status masih pending --}}
                                @if($item->status_validasi == 'pending')
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                             <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-info">Data ini sudah {{ $item->status_validasi_text }}.</div>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
            </div> {{-- End Row --}}

            {{-- Tambahkan bagian baru untuk Anggota Keluarga --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            Data Anggota Keluarga
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th>Kelamin</th>
                                            <th>Hub. KRT</th>
                                            <th>Pendidikan</th>
                                            <th>Pekerjaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Lakukan Looping di sini! --}}
                                        @forelse ($item->anggotaKeluarga as $anggota)
                                            <tr>
                                                <td>{{ $anggota->nuk }}</td>
                                                <td>{{ $anggota->nama }}</td>
                                                <td>{{ $anggota->nik }}</td>
                                                <td>{{ $anggota->kelamin_text }}</td> {{-- Pakai Accessor --}}
                                                <td>{{ $anggota->hdkrt_text }}</td> {{-- Pakai Accessor --}}
                                                <td>{{ $anggota->pendidikan_terakhir_text }}</td> {{-- Pakai Accessor --}}
                                                <td>{{ $anggota->jenis_pekerjaan_text }}</td> {{-- Pakai Accessor --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data anggota keluarga.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Row Anggota --}}

        </div> {{-- End Card Body Utama --}}
    </div> {{-- End Card Utama --}}
</div> {{-- End Container --}}

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
                Apakah Anda yakin ingin menolak data rumah tangga ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.tkw.reject', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Tolak Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script Preview Gambar (Bisa dipindah ke file JS terpisah) --}}
<script>
    // ... (Script preview gambar bisa tetap sama seperti yang kamu punya) ...
</script>
@endsection

@push('scripts')
    {{-- Jika belum ada jQuery & Bootstrap JS, tambahkan di layout utama --}}
    {{-- <script src=".../jquery.min.js"></script> --}}
    {{-- <script src=".../bootstrap.bundle.min.js"></script> --}}
@endpush