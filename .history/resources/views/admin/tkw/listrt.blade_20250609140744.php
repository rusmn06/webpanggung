@extends('layouts.app') {{-- Pastikan ini adalah nama file layout utama Anda --}}

@section('title', 'Pilih RT')

@push('styles')
<style>
    /* CSS Kustom Anda yang sudah bagus tidak diubah */
    .rt-link {
        text-decoration: none !important;
        color: inherit;
    }
    .rt-link:hover {
        text-decoration: none !important;
        color: inherit;
    }
    .rt-card-simple {
        background-color: #fff;
        border: 1px solid #e3e6f0;
        border-radius: .35rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-left-color 0.15s ease;
        border-left: 4px solid #dddfeb;
        overflow: hidden;
    }
    .rt-card-simple:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.10)!important;
        border-left-color: #4e73df;
    }
    /* ... sisa CSS Anda tidak diubah ... */
    .rt-card-simple .icon-area { background-color: #f8f9fc; padding: 1rem; display: flex; align-items: center; justify-content: center; transition: background-color 0.15s ease; min-width: 70px; }
    .rt-card-simple:hover .icon-area { background-color: #eaf0fc; }
    .rt-card-simple .icon-area i { font-size: 1.75rem; color: #b7b9cc; transition: color 0.15s ease; }
    .rt-card-simple:hover .icon-area i { color: #4e73df; }
    .rt-card-simple .text-area { padding: 0.75rem 1rem; display: flex; flex-direction: column; justify-content: center; flex-grow: 1; background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.03) 1px, transparent 0); background-size: 7px 7px; }
    .rt-card-simple .text-area h6 { font-weight: 700; margin-bottom: 0.35rem; color: #5a5c69; font-size: 1rem; }
    .rt-card-simple .text-area .btn { padding: 0.15rem 0.5rem; font-size: 0.75rem; align-self: flex-start; margin-bottom: 0.5rem; }
    .rt-preview-data small { line-height: 1.4; font-size: 0.7rem; display: block; color: #858796; }
</style>
@endpush

@section('content')
    <div class="card shadow mb-4">
        
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih RT untuk Melihat Data</h6>
        </div>

        <div class="card-body">
            <div class="row">
                @for ($i = 1; $i <= 24; $i++)
                    @php
                        $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                        $totalRT = $rumahTanggaCounts[$i] ?? 0;
                        $totalAnggota = $anggotaCounts[$i] ?? 0;
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('admin.tkw.showrt', ['rt' => $i]) }}" class="rt-link">
                            <div class="card rt-card-simple h-100 shadow-sm">
                                <div class="d-flex align-items-stretch h-100">

                                    <div class="icon-area">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>

                                    <div class="text-area">
                                        <h6>RT {{ $rtNumber }}</h6>
                                        <span class="btn btn-outline-primary btn-sm">
                                           Lihat Data
                                        </span>
                                        <div class="rt-preview-data mt-2">
                                            <small>Jumlah Responden: <strong>{{ $totalRT }}</strong></small>
                                            <small>Jumlah Orang Terdaftar: <strong>{{ $totalAnggota }}</strong></small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection