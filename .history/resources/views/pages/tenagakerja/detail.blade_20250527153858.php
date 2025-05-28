@extends('layouts.main')

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