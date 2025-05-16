@extends('layouts.main')

@section('title', 'SID Panggung - Dashboard')

@push('styles')
    <!-- You can add page-specific styles here -->
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kuesioner Tenaga Kerja</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a> --}}
    </div>

    <!-- Content Row -->
    <!-- Action Cards Only -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Isi Kuesioner</h3>
                <p class="text-sm text-gray-500">Lengkapi data tenaga kerja</p>
            </div>
        </div>
        <a href="/tenagakerja/step-1"
            class="inline-block mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">Isi Sekarang</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M3 4a2 2 0 012-2h14a2 2 0 012 2v16l-4-4H5a2 2 0 01-2-2V4z"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Lihat Data</h3>
                <p class="text-sm text-gray-500">Statistik tenaga kerja terbaru</p>
            </div>
        </div>
        <a href="/tenagakerja/listrt"
            class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Lihat Statistik</a>
    </div>
</div>
    

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-8">
            <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('template/js/demo/chart-bar-demo.js') }}"></script>
    <script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script>
@endpush
