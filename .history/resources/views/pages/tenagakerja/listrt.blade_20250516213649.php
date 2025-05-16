@extends('layouts.main')

@section('title', 'List RT')

@push('styles')
    <!-- Tambahkan styles khusus halaman jika perlu -->
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">List RT</h1>
        <a href="/tenagakerja" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">Kembali</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Looping Cards -->
        @for ($i = 1; $i <= 24; $i++)
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">RT {{ $i }}</h5>
                        <a href="/tenagakerja/listrt/{{ $i }}" class="btn btn-primary btn-sm">Lihat Data</a>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endsection

@push('scripts')
    <!-- Tambahkan script khusus halaman jika perlu -->
@endpush
