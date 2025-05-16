@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Dashboard Kuesioner Tenaga Kerja</h1>
    </div>

    <!-- Card Aksi Cepat -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="text-uppercase text-primary font-weight-bold mb-4">Aksi Cepat</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-primary text-white shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title mb-2">Isi Data</h5>
                                <p class="card-text">Mulai mengisi kuesioner tenaga kerja.</p>
                            </div>
                            <a href="/tenagakerja/step-1" class="btn btn-light btn-sm mt-3">
                                Mulai Pendataan
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card bg-success text-white shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title mb-2">Lihat Data</h5>
                                <p class="card-text">Lihat data yang telah terkumpul dari responden.</p>
                            </div>
                            <a href="/tenagakerja/listrt" class="btn btn-light btn-sm mt-3">
                                Lihat Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Statistik -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="text-uppercase text-primary font-weight-bold mb-4">Statistik Pendataan</h6>
            <div class="chart-bar">
                <canvas id="myBarChart" height="100"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-bar-demo.js') }}"></script>
@endpush
