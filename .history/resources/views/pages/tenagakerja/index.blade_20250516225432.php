@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Dashboard Kuesioner Tenaga Kerja</h1>
    </div>

    <!-- Kartu Kontainer -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                {{-- Kolom kiri: Aksi --}}
                <div class="col-md-4">
                    <div class="mb-4">
                        <h6 class="text-uppercase text-primary font-weight-bold mb-3">Aksi Cepat</h6>

                        <div class="card bg-primary text-white mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-1">Isi Data</h6>
                                        <small>Mulai pendataan tenaga kerja</small>
                                    </div>
                                    <i class="fas fa-edit fa-2x"></i>
                                </div>
                                <a href="/tenagakerja/step-1" class="btn btn-light btn-sm mt-3">
                                    Mulai Pendataan
                                </a>
                            </div>
                        </div>

                        <div class="card bg-success text-white mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="card-title mb-1">Lihat Data</h6>
                                        <small>Data yang sudah terkumpul</small>
                                    </div>
                                    <i class="fas fa-database fa-2x"></i>
                                </div>
                                <a href="/tenagakerja/listrt" class="btn btn-light btn-sm mt-3">
                                    Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan: Grafik --}}
                <div class="col-md-8">
                    <h6 class="text-uppercase text-primary font-weight-bold mb-3">Statistik Pendataan</h6>
                    <div class="chart-bar">
                        <canvas id="myBarChart" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-bar-demo.js') }}"></script>
@endpush
