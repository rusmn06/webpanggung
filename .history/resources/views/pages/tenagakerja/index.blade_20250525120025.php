@extends('layouts.main')

@section('title', 'Dasbor Kuesioner Tenaga Kerja')

@push('styles')
    {{-- <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
    <style>
        .stat-card {
            transition: transform .2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .submission-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Kuisioner Tenaga Kerja Saya</h1>
        <a href="{{ route('dashboard') }}" {{-- Pastikan ini route ke dashboard utama user --}}
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard Utama
        </a>
    </div>

    <div class="mb-4">
        <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-icon-split btn-lg"> {{-- Pastikan route tkw.step1 benar --}}
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Isi Kuesioner Baru</span>
        </a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengajuan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengajuan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Validasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanPending }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tervalidasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDisetujui }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDitolak }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-4">
        <h2 class="h4 mb-0 text-gray-800">Riwayat Pengajuan Kuesioner Anda</h2>
    </div>

    @if($items->isEmpty())
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                <p class="lead text-gray-700">Anda belum memiliki data pengajuan.</p>
                <p>Silakan mulai dengan mengisi kuesioner baru.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($items as $item)
                <div class="col-xl-4 col-lg-6 mb-4">
                    <div class="card submission-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pengajuan #RT-{{ $item->id }}</h6>
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-2">{{ $statusText }}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-1"><small class="text-muted">Tgl. Pengajuan:</small><br>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                            <p class="card-text mb-1"><small class="text-muted">Responden:</small><br>{{ $item->nama_responden }}</p>
                            <p class="card-text"><small class="text-muted">Lokasi:</small><br>{{ $item->desa }}, {{ $item->kecamatan }}</p>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="#" {{-- Pastikan nama route ini benar --}}
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye fa-sm"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($items->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $items->links() }}
        </div>
        @endif
    @endif

    {{-- Placeholder untuk Chart jika ingin ditambahkan nanti --}}
    {{--
    @if(!$items->isEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Visualisasi Status Pengajuan Anda</h6>
                </div>
                <div class="card-body">
                    <p class="text-center">Chart akan tampil di sini.</p>
                    <canvas id="userSubmissionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
    --}}

@endsection

@push('scripts')
    {{-- Jika ingin menggunakan Chart.js untuk visualisasi status --}}
    {{-- <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script> --}}
    {{--
    <script>
    $(document).ready(function() {
        // Logika untuk membuat chart jika ada data $items
        @if(!$items->isEmpty())
            // const ctx = document.getElementById('userSubmissionsChart').getContext('2d');
            // const userSubmissionsChart = new Chart(ctx, {
            //     type: 'doughnut', // atau 'pie' atau 'bar'
            //     data: {
            //         labels: ['Pending', 'Disetujui', 'Ditolak', 'Lainnya'],
            //         datasets: [{
            //             label: 'Status Pengajuan',
            //             data: [
            //                 {{ $pengajuanPending }},
            //                 {{ $pengajuanDisetujui }},
            //                 {{ $pengajuanDitolak }},
            //                 {{ $totalPengajuan - ($pengajuanPending + $pengajuanDisetujui + $pengajuanDitolak) }}
            //             ],
            //             backgroundColor: [
            //                 'rgba(255, 193, 7, 0.7)', // Kuning untuk Pending
            //                 'rgba(40, 167, 69, 0.7)', // Hijau untuk Disetujui
            //                 'rgba(220, 53, 69, 0.7)', // Merah untuk Ditolak
            //                 'rgba(108, 117, 125, 0.7)' // Abu-abu untuk Lainnya
            //             ],
            //             borderColor: [
            //                 'rgba(255, 193, 7, 1)',
            //                 'rgba(40, 167, 69, 1)',
            //                 'rgba(220, 53, 69, 1)',
            //                 'rgba(108, 117, 125, 1)'
            //             ],
            //             borderWidth: 1
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         // Opsi chart lainnya
            //     }
            // });
        @endif
    });
    </script>
    --}}
@endpush