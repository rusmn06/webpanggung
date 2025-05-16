@extends('layouts.main')

@section('title', 'List RT')

@push('styles')
    <!-- Tambahkan styles khusus halaman jika perlu -->
@endpush

@section('content')
    <!-- Page Heading -->
    {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">List RT</h1>
        <a href="/tenagakerja" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div> --}}

    <!-- Card Container -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pilih RT untuk melihat data</h6>
            <a href="/tenagakerja" class="btn btn-sm btn-secondary shadow-sm">
                Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                @for ($i = 1; $i <= 24; $i++)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h6 class="card-title mb-2">RT {{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</h6>
                                <a href="/tenagakerja/listrt/{{ $i }}" class="btn btn-outline-primary btn-sm w-100">
                                    Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Tambahkan script khusus halaman jika perlu -->
@endpush
