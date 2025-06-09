@extends('layouts.main') {{-- Pastikan ini nama layout utama Anda --}}

@section('title', 'Detail Responden: ' . $item->nama_responden)

@push('styles')
{{-- Mengambil sebagian style dari referensi Anda --}}
<style>
    .signature-box { border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem; }
    .signature-box img { max-height: 80px; max-width: 100%; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .info-list-condensed dt { font-weight: normal; color: #5a5c69; }
    .info-list-condensed dd { font-weight: 500; }
    .card-detail-section .card-title { color: #4e73df !important; }

    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid #eaecf4; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item .label { color: #5a5c69; }
    .summary-item .value { font-weight: bold; font-size: 1.1rem; color: #36b9cc; }

    .table-anggota-keluarga th { vertical-align: middle !important; text-align: center; font-size: 0.78rem; padding: 0.5rem 0.3rem; }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .table-anggota-keluarga td.td-nik { font-family: monospace; letter-spacing: 1px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Detail Pengajuan</h1>
            <p class="mb-0 text-muted">Responden: {{ $item->nama_responden }}</p>
        </div>
        {{-- Tombol kembali sekarang mengarah ke halaman RT spesifik --}}
        <a href="{{ route('admin.tkw.showrt', ['rt' => $item->rt]) }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar RT {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}
        </a>
    </div>

    {{-- BARIS UTAMA: Info Pengajuan (Kiri) & Ringkasan (Kanan) --}}
    <div class="row">
        {{-- KOLOM KIRI: Informasi Pengajuan --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm card-detail-section h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6>
                </div>
                <div class="card-body">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-4">Nama Pendata</dt>
                        <dd class="col-sm-8">{{ $item->nama_pendata }}</dd>
                        <dt class="col-sm-4">Tgl. Pengajuan</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}</dd>
                        <dt class="col-sm-4">Lokasi</dt>
                        <dd class="col-sm-8">RT {{ $item->rt }} / RW {{ $item->rw }}, Desa {{ $item->desa }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Kartu Ringkasan (Pengganti Catatan & Ekspor) --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold card-title">Ringkasan Rumah Tangga</h6>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="label"><i class="fas fa-users fa-fw mr-2 text-gray-400"></i>Total Anggota Keluarga</span>
                        <span class="value">{{ $item->anggotaKeluarga->count() }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label"><i class="fas fa-briefcase fa-fw mr-2 text-gray-400"></i>Anggota Bekerja</span>
                        <span class="value">{{ $item->anggotaKeluarga->where('status_pekerjaan', 1)->count() }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label"><i class="fas fa-book-reader fa-fw mr-2 text-gray-400"></i>Tidak Bekerja / Sekolah</span>
                        <span class="value">{{ $item->anggotaKeluarga->whereIn('status_pekerjaan', [3, 4])->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Anggota Keluarga --}}
    <div class="card shadow-sm card-detail-section mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold card-title">Data Anggota Keluarga</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Hub. KRT</th>
                            <th>J. Kelamin</th>
                            <th>Sts. Kerja</th>
                            <th>Pddk. Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td class="text-center td-nik">{{ $anggota->nik }}</td>
                                <td class="text-center">{{ $anggota->hdkrt_text }}</td>
                                <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                <td class="text-center">{{ $anggota->status_pekerjaan_text }}</td>
                                <td class="text-center">{{ $anggota->pendidikan_terakhir_text }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection