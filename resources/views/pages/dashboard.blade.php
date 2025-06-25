@extends('layouts.main')

@section('title', 'Dashboard - SID Panggung')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard (USER)</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
    class="fas fa-download fa-sm text-white-50"></i> Hubungi Developer</a>
</div>

@if(auth()->user()->role == 'user')
<!-- <div class="row">
    {{-- Card 1 --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Data Kuisioner Tenaga Kerja</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($grandTotal) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

{{-- Grafik Section --}}
<div class="row">

    <div class="col-xl-12 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data 24 RT</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2"><i class="fas fa-circle text-primary"></i> Direct</span>
                    <span class="mr-2"><i class="fas fa-circle text-success"></i> Social</span>
                    <span class="mr-2"><i class="fas fa-circle text-info"></i> Referral</span>
                </div>
            </div>
        </div>
    </div> --}}

</div>
@endif

@endsection

@push('scripts')
    <!-- Core Plugins -->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Custom Bar Chart (v2 syntax) -->
    <script>
        const rtLabels = @json($rtCounts->keys()->toArray());
        const rtData   = @json($rtCounts->values()->toArray());

        const ctx = document.getElementById('myBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rtLabels,
                datasets: [{
                    label: 'Jumlah Data per RT',
                    data: rtData,
                    backgroundColor: '#4e73df',
                    hoverBackgroundColor: '#2e59d9',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    maxBarThickness: 25
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontColor: '#858796'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,                 //  increment per satu unit
                            callback: function(value) {  // hanya integer
                                return Number.isInteger(value) ? value : '';
                            },
                            fontColor: '#858796'
                        },
                        gridLines: {
                            color: 'rgba(234,236,244,1)',
                            zeroLineColor: 'rgba(234,236,244,1)',
                            drawBorder: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {                      // v2 syntax
                    backgroundColor: 'rgba(255,255,255,0.9)',
                    titleFontColor: '#6e707e',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1
                }
            }
        });
    </script>
@endpush

