@extends('layouts.main')

@section('title', 'Detail Pengajuan Rumah Tangga #RT-' . $item->id)

@push('styles')
<style>
    .info-list-condensed dt, .info-list-condensed dd {
        padding-top: .35rem; padding-bottom: .35rem; margin-bottom: 0.25rem;
        font-size: 0.875rem; line-height: 1.4;
    }
    .info-list-condensed dt { font-weight: 600; color: #5a5c69; }
    .info-list-condensed dd { word-break: break-word; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">
                Detail Pengajuan Saya Ke-{{ $item->user_sequence_number }}
                <small class="text-muted" style="font-size: 0.8rem; font-weight:normal;">(ID Sistem: RT-{{ $item->id }})</small>
            </h6>
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary">
                 <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
            </a>
        </div>
        <div class="card-body">
            @if(session('success_message_body') && !session('show_success_modal'))
                <div class="alert alert-success">{{ session('success_message_body') }}</div>
            @endif

            {{-- Informasi Rumah Tangga --}}
            <h5 class="text-dark mb-3">Informasi Rumah Tangga</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="info-list-condensed">
                        <dt>Provinsi</dt><dd>{{ $item->provinsi }}</dd>
                        <dt>Kabupaten</dt><dd>{{ $item->kabupaten }}</dd>
                        <dt>Kecamatan</dt><dd>{{ $item->kecamatan }}</dd>
                        <dt>Desa/Kel.</dt><dd>{{ $item->desa }}</dd>
                        <dt>RT/RW</dt><dd>{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="info-list-condensed">
                        <dt>Nama Responden</dt><dd class="font-weight-bold">{{ $item->nama_responden }}</dd>
                        <dt>Nama Pendata</dt><dd>{{ $item->nama_pendata }}</dd>
                        <dt>Tgl. Diajukan</dt><dd>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                        <dt>Status Pengajuan</dt>
                        <dd>
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
                </div>
            </div>
             @if($item->ttd_pendata)
                <div class="mt-2">
                    <p class="mb-1"><small class="text-muted">TTD Pengaju:</small></p>
                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                </div>
            @endif

            <hr class="my-3">
            {{-- Daftar Anggota Keluarga --}}
            <h5 class="text-dark mb-3">Data Anggota Keluarga</h5>
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
                            <tr> <td colspan="8" class="text-center">Tidak ada data anggota keluarga.</td> </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Informasi Validasi oleh Admin (jika sudah divalidasi/ditolak) --}}
            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
            <hr class="my-3">
            <h5 class="text-dark mb-3">Informasi Hasil Validasi Admin</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="info-list-condensed">
                        <dt class="col-sm-4">Tgl. Validasi</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') }}</dd>
                        <dt class="col-sm-4">Pejabat</dt><dd class="col-sm-8">{{ $item->admin_nama_kepaladusun ?? '-' }}</dd>
                    </dl>
                </div>
                @if($item->admin_ttd_pendata)
                <div class="col-md-6">
                    <p class="mb-1"><small class="text-muted">TTD Pejabat:</small></p>
                    <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Bootstrap Modal untuk Notifikasi Sukses --}}
@if(session('show_success_modal'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success_message_title', 'Berhasil!') }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <p style="font-size: 1.1rem;">{{ session('success_message_body', 'Data Anda telah berhasil diproses.') }}</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list-alt"></i> Lihat Riwayat Pengajuan@extends('layouts.main')

@section('title', 'Detail Kuisioner - NAMA KRT')

@push('styles')
{{-- Tambahkan style kustom jika diperlukan --}}
<style>
    .card-body .dl-horizontal dt {
        float: left;
        width: 160px; /* Sesuaikan lebar label */
        overflow: hidden;
        clear: left;
        text-align: right;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: bold;
        margin-right: 15px; /* Jarak antara label dan data */
    }
    .card-body .dl-horizontal dd {
        margin-left: 175px; /* Sesuaikan agar pas dengan dt + margin */
        margin-bottom: 0.5rem;
    }
    .rekap-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }
    .rekap-item:last-child {
        border-bottom: none;
    }
    .signature-area {
        border: 1px dashed #ccc;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f9f9f9;
        margin-top: 10px;
        text-align: center;
    }
    .table th {
        vertical-align: middle;
        text-align: center;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Kuisioner - NAMA KRT</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Garis Pemisah --}}
    <hr>

    {{-- Area Konten Utama --}}
    <div class="row">

        {{-- KOLOM KIRI --}}
        <div class="col-lg-7">

            {{-- Kartu: Keterangan Tempat --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Keterangan Tempat</h6>
                </div>
                <div class="card-body">
                    <dl class="dl-horizontal">
                        <dt>Nama KRT:</dt>
                        <dd>XXXX</dd>

                        <dt>Nama Responden:</dt>
                        <dd>YYYY</dd>

                        <dt>Provinsi:</dt>
                        <dd>...</dd>

                        <dt>Kabupaten:</dt>
                        <dd>...</dd>

                        <dt>Kecamatan:</dt>
                        <dd>...</dd>

                        <dt>Desa:</dt>
                        <dd>...</dd>

                        <dt>RT/RW:</dt>
                        <dd>...</dd>
                    </dl>
                </div>
            </div>

            {{-- Kartu: Rekapitulasi --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi</h6>
                </div>
                <div class="card-body">
                   <div class="rekap-item">
                       <span>Jumlah ART</span>
                       <span><span class="badge bg-info text-white">4</span> <i class="fas fa-users ms-2"></i></span>
                   </div>
                   <div class="rekap-item">
                       <span>Jumlah Bekerja</span>
                       <span><span class="badge bg-success text-white">2</span> <i class="fas fa-briefcase ms-2"></i></span>
                   </div>
                    <div class="rekap-item">
                       <span>Tidak Sekolah</span>
                       <span><span class="badge bg-warning text-dark">1</span> <i class="fas fa-school ms-2"></i></span>
                   </div>
                   <div class="rekap-item">
                       <span>Pendapatan</span>
                       <span><span class="badge bg-secondary text-white">>500rb</span> <i class="fas fa-money-bill-wave ms-2"></i></span>
                   </div>
                </div>
            </div>

            {{-- Kartu: TTD KRT/Responden --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tanda Tangan KRT / Responden</h6>
                </div>
                <div class="card-body text-center">
                    <div class="signature-area">
                        [Area Tanda Tangan]
                    </div>
                     <p class="mt-2 mb-0"><strong>( NAMA KRT / RESPONDEN )</strong></p>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-lg-5">

            {{-- Kartu: Anggota Rumah Tangga --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Anggota Rumah Tangga</h6>
                </div>
                <div class="card-body p-0"> {{-- p-0 untuk menghilangkan padding agar tabel pas --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Hub KRT</th>
                                    <th>JK</th>
                                    <th>Umur</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Contoh Baris Data (Nanti diganti dengan loop data) --}}
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Anggota Satu</td>
                                    <td>32xxxxxxxxxxxx01</td>
                                    <td>Kepala RT</td>
                                    <td class="text-center">L</td>
                                    <td class="text-center">35</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Anggota Dua</td>
                                    <td>32xxxxxxxxxxxx02</td>
                                    <td>Istri</td>
                                    <td class="text-center">P</td>
                                    <td class="text-center">32</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Anggota Tiga</td>
                                    <td>32xxxxxxxxxxxx03</td>
                                    <td>Anak</td>
                                    <td class="text-center">L</td>
                                    <td class="text-center">10</td>
                                </tr>
                                 {{-- ... Baris data lainnya ... --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kartu: Petugas & TTD --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Petugas & Tanda Tangan</h6>
                </div>
                <div class="card-body">
                    <dl class="dl-horizontal">
                        <dt>Pendata:</dt>
                        <dd>A</dd>
                        <dt>Tgl. Data:</dt>
                        <dd>...</dd>
                    </dl>
                    <div class="signature-area mb-3">
                        [TTD Pendata]
                    </div>
                    <hr>
                     <dl class="dl-horizontal">
                        <dt>Pemeriksa:</dt>
                        <dd>B</dd>
                        <dt>Tgl. Periksa:</dt>
                        <dd>...</dd>
                    </dl>
                    <div class="signature-area">
                        [TTD Pemeriksa]
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- End Row --}}

</div> {{-- End Container-fluid --}}
@endsection

@push('scripts')
{{-- Tambahkan script JS jika diperlukan --}}
<script>
    // Contoh: Jika kamu ingin menambahkan interaktivitas di masa depan
    $(document).ready(function() {
        console.log("Halaman Detail Kuisioner siap!");
    });
</script>
@endpush
                </a>
                <a href="{{ route('tkw.step1') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Ajukan Data Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
    $(document).ready(function(){
        $('#successModal').modal('show');
    });
</script>
@endif
@endpush