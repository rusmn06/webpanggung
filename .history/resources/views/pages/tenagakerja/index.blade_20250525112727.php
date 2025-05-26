@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Dashboard Kuesioner Tenaga Kerja</h1>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Aksi -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-primary font-weight-bold mb-4">Aksi Cepat</h6>

                    <div class="mb-3">
                        <div class="card bg-primary text-white shadow-sm">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title">Isi Data</h5>
                                    <p class="card-text">Mulai mengisi kuesioner tenaga kerja.</p>
                                </div>
                                <a href="/tenagakerja/step-1" class="btn btn-light btn-sm mt-2">Mulai Pendataan</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card bg-success text-white shadow-sm">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title">Lihat Data</h5>
                                    <p class="card-text">Lihat data yang telah dikumpulkan.</p>
                                </div>
                                <a href="/tenagakerja/listrt" class="btn btn-light btn-sm mt-2">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-primary font-weight-bold mb-4">Statistik Pendataan</h6>
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
@endpush
