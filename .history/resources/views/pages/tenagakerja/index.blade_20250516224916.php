@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@push('styles')
    <!-- Page-specific styles -->
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Kuesioner Tenaga Kerja</h1>
    </div>

    <!-- Cards Section -->
    <div class="row">
        <!-- Card: Isi Data -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="text-sm font-weight-bold text-primary text-uppercase mb-2">
                        Isi Data Kuesioner
                    </div>
                    <p class="text-muted mb-3">Masukkan data tenaga kerja baru secara bertahap.</p>
                    <a href="/tenagakerja/step-1" class="btn btn-primary btn-sm mt-auto">
                        <i class="fas fa-edit mr-1"></i> Mulai Input
                    </a>
                </div>
            </div>
        </div>

        <!-- Card: Lihat Data -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="text-sm font-weight-bold text-success text-uppercase mb-2">
                        Lihat Data RT
                    </div>
                    <p class="text-muted mb-3">Tampilkan daftar data yang sudah dimasukkan berdasarkan RT.</p>
                    <a href="/tenagakerja/listrt" class="btn btn-success btn-sm mt-auto">
                        <i class="fas fa-table mr-1"></i> Lihat Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Bar -->
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Distribusi Data</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-bar-demo.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script>
@endpush
