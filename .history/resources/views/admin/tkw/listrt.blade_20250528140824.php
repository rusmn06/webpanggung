@extends('layouts.main')

@section('title', 'Pilih RT')

@push('styles')
<style>
    /* Menghapus dekorasi link default */
    .rt-link {
        text-decoration: none !important;
        color: inherit; /* Mewarisi warna teks induk */
    }
    .rt-link:hover {
        text-decoration: none !important;
        color: inherit;
    }

    /* Styling untuk kartu RT baru */
    .rt-card-simple {
        background-color: #fff;
        border: 1px solid #e3e6f0;
        border-radius: .35rem;
        transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out, border-left-color 0.15s ease-in-out;
        border-left: 4px solid #dddfeb; /* Border kiri abu-abu */
        overflow: hidden; /* Pastikan konten tidak keluar */
    }

    /* Efek Hover */
    .rt-card-simple:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.10)!important;
        border-left-color: #4e73df; /* Border kiri jadi biru saat hover */
    }

    /* Styling Ikon */
    .rt-card-simple .icon-area {
        background-color: #f8f9fc; /* Latar belakang ikon sedikit abu-abu */
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.15s ease-in-out;
    }
    .rt-card-simple:hover .icon-area {
        background-color: #e3e6f0;
    }
    .rt-card-simple .icon-area i {
        font-size: 1.75rem; /* Ukuran ikon disesuaikan */
        color: #858796; /* Warna ikon abu-abu */
        transition: color 0.15s ease-in-out;
    }
    .rt-card-simple:hover .icon-area i {
        color: #4e73df; /* Warna ikon jadi biru saat hover */
    }

    /* Styling Teks & Tombol */
    .rt-card-simple .text-area {
        padding: 0.75rem 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }
    .rt-card-simple .text-area h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #5a5c69;
    }
    .rt-card-simple .text-area .btn {
        padding: 0.15rem 0.5rem;
        font-size: 0.75rem;
        align-self: flex-start;
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

        <div class="card-body">
            <div class="row">
                @for ($i = 1; $i <= 24; $i++)
                    @php
                        $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                    @endphp
                    {{-- Menggunakan col-lg-4 (3 kolom di layar besar), col-md-6 (2 kolom di medium) --}}
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('admin.tkw.showrt', ['rt' => $i]) }}" class="rt-link">
                            {{-- KARTU BARU --}}
                            <div class="card rt-card-simple h-100 shadow-sm">
                                <div class="d-flex align-items-stretch h-100"> {{-- Pakai d-flex --}}

                                    {{-- Area Kiri: Ikon --}}
                                    <div class="icon-area">
                                        <i class="fas fa-map-marker-alt"></i> {{-- Ganti ikon jika perlu --}}
                                    </div>

                                    {{-- Area Kanan: Teks & Tombol --}}
                                    <div class="text-area">
                                        <h6>RT {{ $rtNumber }}</h6>
                                        <span class="btn btn-outline-primary btn-sm">
                                           Lihat Data
                                        </span>
                                    </div>

                                </div>
                            </div>
                            {{-- AKHIR KARTU BARU --}}
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection