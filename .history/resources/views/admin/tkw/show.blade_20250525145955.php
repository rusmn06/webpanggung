@extends('layouts.main')

@section('title', 'Detail Validasi Rumah Tangga #RT-' . $item->id)

@section('content')
<div class="container-fluid px-0"> {{-- Menggunakan px-0 seperti yang Anda coba sebelumnya --}}
    <div class="card shadow-sm"> {{-- Satu kartu utama untuk membungkus semua --}}
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Validasi Rumah Tangga #RT-{{ $item->id }} (Responden: {{ $item->nama_responden }})</h6>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kiri: Data Rumah Tangga (Responden) DIBUAT LEBIH PADAT --}}
                <div class="col-lg-7 mb-4"> {{-- Dibuat sedikit lebih lebar dari form validasi --}}
                    <div class="card h-100 shadow-none border"> {{-- Kartu internal tanpa shadow, hanya border --}}
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Informasi Rumah Tangga & Responden</h6>
                        </div>
                        <div class="card-body" style="font-size: 0.9rem;"> {{-- Ukuran font sedikit dikecilkan untuk kepadatan --}}
                            {{-- Menggunakan dua kolom internal untuk data RT --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><span class="text-muted">Provinsi:</span> {{ $item->provinsi }}</p>
                                    <p class="mb-1"><span class="text-muted">Kabupaten:</span> {{ $item->kabupaten }}</p>
                                    <p class="mb-1"><span class="text-muted">Kecamatan:</span> {{ $item->kecamatan }}</p>
                                    <p class="mb-1"><span class="text-muted">Desa/Kel:</span> {{ $item->desa }}</p>
                                    <p class="mb-2"><span class="text-muted">RT/RW:</span> {{ $item->rt }}/{{ $item->rw }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><span class="text-muted">Pendata:</span> {{ $item->nama_pendata }}</p>
                                    <p class="mb-1"><span class="text-muted">Responden:</span> <span class="font-weight-bold">{{ $item->nama_responden }}</span></p>
                                    <p class="mb-1"><span class="text-muted">Tgl. Dibuat:</span> {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD MMM YYYY') }}</p>
                                    <p class="mb-2"><span class="text-muted">Status:</span>
                                        @php
                                            $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                            $badgeClass = 'badge-light text-dark';
                                            if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                            if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                            if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                             {{-- Informasi Statistik RT --}}
                            <p class="mb-1"><span class="text-muted">Jml. Anggota RT:</span> {{ $item->jart }} orang</p>
                            <p class="mb-1"><span class="text-muted">Jml. ART Bekerja:</span> {{ $item->jart_ab }} orang</p>
                            <p class="mb-1"><span class="text-muted">Pendapatan RT/Bln:</span> {{ $item->jpr2rtp_text ?? $item->jpr2rtp }}</p>

                            @if($item->ttd_pendata)
                                <hr class="my-2">
                                <p class="mb-1"><span class="text-muted">TTD Pendata:</span></p>
                                <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kanan: Kartu Form Validasi Admin --}}
                <div class="col-lg-5 mb-4">
                    <div class="card h-100 shadow-none border"> {{-- Kartu internal tanpa shadow, hanya border --}}
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Form Tindakan Validasi</h6>
                        </div>
                        <div class="card-body">
                            {{-- Isi form validasi tetap sama --}}
                            <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="admin_tgl_validasi" class="form-label">Tgl. Validasi <span class="text-danger">*</span></label>
                                    <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi"
                                        value="{{ old('admin_tgl_validasi', $item->admin_tgl_validasi ?? now()->toDateString()) }}"
                                        class="form-control form-control-sm @error('admin_tgl_validasi') is-invalid @enderror"
                                        {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                                    @error('admin_tgl_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admin_nama_kepaladusun" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                                    <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                                        value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}"
                                        class="form-control form-control-sm @error('admin_nama_kepaladusun') is-invalid @enderror"
                                        {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                                    @error('admin_nama_kepaladusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admin_ttd_pendata" class="form-label">TTD Pejabat (Opsional)</label>
                                    <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/png, image/jpeg"
                                        class="form-control form-control-sm @error('admin_ttd_pendata') is-invalid @enderror"
                                        {{ $item->status_validasi != 'pending' ? 'disabled' : '' }}>
                                    @error('admin_ttd_pendata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div id="preview-ttd" class="mt-2 text-center" style="min-height: 80px; border: 1px dashed #ddd; padding: 5px; background-color: #fdfdfd;">
                                        @if($item->admin_ttd_pendata)
                                            <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 70px;" class="img-thumbnail">
                                        @else
                                            <small class="text-muted d-block mt-1">Preview TTD.</small>
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
                </div>
            </div> {{-- End Row Pertama (Data RT & Form Validasi) --}}

            {{-- Kartu Data Anggota Keluarga --}}
            <div class="card shadow-none border mt-4"> {{-- Kartu internal tanpa shadow, hanya border --}}
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Data Anggota Keluarga</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" style="font-size: 0.875rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">No.</th>
                                    <th style="width: 20%;">Nama Lengkap</th>
                                    <th style="width: 15%;">NIK</th>
                                    <th style="width: 10%;" class="text-center">Kelamin</th>
                                    <th style="width: 15%;">Hub. KRT</th>
                                    <th style="width: 15%;">Pendidikan</th>
                                    <th>Pekerjaan</th>
                                    <th style="width: 10%;" class="text-center">Sts. Kawin</th>
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
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data anggota keluarga.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> {{-- End Card Body Utama --}}
    </div> {{-- End Card Utama --}}
</div> {{-- End Container-fluid (atau wrapper lain dari layout Anda) --}}

{{-- Modal Konfirmasi Tolak --}}
{{-- ... (kode modal tetap sama) ... --}}

@push('scripts')
{{-- Script Preview Gambar (tetap sama) --}}
<script>
// ... (Script preview gambar tetap sama) ...
</script>
@endpush
@endsection