@extends('layouts.main')

@section('title', 'Pilih RT')

@push('styles')
<style>
    .rt-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-left: 4px solid #4e73df;
        cursor: pointer;
    }
    .rt-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border-left-color: #2e59d9;
    }
    .rt-card .card-body .rt-icon {
        font-size: 2.5rem;
        color: #dddfeb;
        margin-bottom: 0.75rem;
        transition: color 0.2s ease-in-out;
    }
    .rt-card:hover .card-body .rt-icon {
        color: #4e73df;
    }
    .rt-card .card-body h5 {
        font-weight: 700;
        color: #3a3b45;
    }
    .rt-card .card-body .btn {
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pilih RT untuk Melihat Data</h1>
        <a href="{{ route('admin.tkw.index') }}"
           class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar Validasi
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Rukun Tetangga (RT)</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @for ($i = 1; $i <= 24; $i++)
                    @php
                        $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                    @endphp
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                        <a href="{{ route('admin.tkw.showrt', ['rt' => $i]) }}" style="text-decoration: none;">
                            <div class="card shadow-sm h-100 rt-card">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <i class="fas fa-home rt-icon"></i>
                                    <h5 class="card-title mb-2">RT {{ $rtNumber }}</h5>
                                    <span class="btn btn-primary btn-sm mt-auto">
                                       Lihat Data
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection