@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="h4 text-gray-800 mb-0">Dashboard Kuesioner Tenaga Kerja</h1>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Kolom kiri: Aksi --}}
                <div class="col-md-4">
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-primary text-uppercase mb-2">Aksi</h6>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Isi Data Baru</span>
                                <a href="/tenagakerja/step-1" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Mulai
                                </a>
                            </div>
                            <small class="text-muted">Input data tenaga kerja rumah tangga.</small>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Lihat Data RT</span>
                                <a href="/tenagakerja/listrt" class="btn btn-success btn-sm">
                                    <i class="fas fa-table mr-1"></i> Lihat
                                </a>
                            </div>
                            <small class="text-muted">Tinjau data yang telah diinput berdasarkan RT.</small>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan: Grafik --}}
                <div class="col-md-8">
                    <h6 class="font-weight-bold text-primary text-uppercase mb-3">Statistik</h6>
                    <div class="chart-bar">
                        <canvas id="myBarChart" height="180"></canvas>
                    </div>
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
