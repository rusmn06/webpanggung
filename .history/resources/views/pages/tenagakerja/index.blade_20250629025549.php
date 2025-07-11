@extends('layouts.main')

@section('title', 'Kuisioner Tenaga Kerja')

@push('styles')
    <style>
        .stat-card {
            transition: transform .2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .submission-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0;
        }
        .submission-card .card-header, .submission-card .card-footer {
            background-color: #f8f9fc;
        }
        .submission-card .card-body {
            flex-grow: 1;
        }
        .submission-card .card-title-search {
            font-size: 1.05rem;
        }
        .submission-card .card-text-label {
            font-size: 0.75rem;
            color: #858796;
            display: block;
            margin-bottom: 0.1rem;
        }
         .submission-card .card-text-value {
            font-size: 0.9rem;
            color: #5a5c69;
            line-height: 1.4;
        }
        #searchRiwayatInputOuterContainer {
            max-width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kuisioner Tenaga Kerja Saya</h1>
        <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-icon-split btn-md shadow-sm">
            <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span>
            <span class="text">Isi Kuesioner Baru</span>
        </a>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="row">
        {{-- Kartu Statistik Total --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengajuan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengajuan }}</div>
                        </div><div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kartu Statistik Pending --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Validasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanPending }}</div>
                        </div><div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kartu Statistik Tervalidasi --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tervalidasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDisetujui }}</div>
                        </div><div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kartu Statistik Ditolak --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengajuanDitolak }}</div>
                        </div><div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Pengajuan Kuesioner Tenaga Kerja Anda</h1>
    </div>

    {{-- Kartu untuk Riwayat Pengajuan --}}
    <div class="card shadow mb-4">   
        <div class="card-body">
            @if(!$items->isEmpty())
            <div class="row mb-3" id="searchRiwayatInputOuterContainer">
                <div class="col-md-6 col-lg-6">
                    <form action="{{ route('tenagakerja.index') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" id="searchRiwayatInput" name="search" class="form-control" placeholder="Cari pengajuan..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-lg-6 d-flex align-items-center">
                    <small class="form-text text-muted mt-2 mt-md-0">
                        Tips: Anda bisa mencari berdasarkan ID Pengajuan (misal: Pengajuan-1/2/3), Nama Responden, Asal Daerah(Desa/Kecamatan), atau Status.
                    </small>
                </div>
            </div>
            @endif

            @if($items->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                    <p class="lead text-gray-700">Anda belum memiliki data pengajuan.</p>
                    <p>Silakan mulai dengan mengisi kuesioner baru.</p>
                </div>
            @else
                <div class="row" id="riwayatPengajuanContainer">
                    @foreach($items as $item)
                        <div class="col-xl-3 col-lg-6 mb-4 submission-card-wrapper">
                            <div class="card submission-card shadow-sm h-100">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="m-0 font-weight-bold text-primary card-title-search" style="line-height: 1.2;">
                                            Pengajuan Ke-{{ $item->user_sequence_number ?? '?' }}
                                        </h6>
                                        <small class="text-muted d-block card-date-search" style="font-size: 0.78rem; font-weight:normal; line-height: 1.2;">
                                            {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}
                                        </small>
                                    </div>
                                    @php
                                        $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                        $badgeClass = 'badge-light text-dark';
                                        if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                        if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                        if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} py-2 px-2 card-status-search" style="font-size: 0.82rem;">{{ $statusText }}</span>
                                </div>
                                <div class="card-body px-3 py-3">
                                    <div class="row mb-2">
                                        <div class="col-7 pr-1">
                                            <span class="card-text-label">Responden:</span>
                                            <span class="card-respondent-search font-weight-bold card-text-value d-block">{{ $item->nama_responden }}</span>
                                        </div>
                                        <div class="col-5 pl-1">
                                            <span class="card-text-label">RT/RW:</span>
                                            <span class="card-rtrw-search card-text-value d-block">{{ $item->rt }}/{{ $item->rw }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <span class="card-text-label">Asal Daerah:</span>
                                        <span class="card-location-search card-text-value d-block">
                                            Desa {{ $item->desa }}, Kec. {{ $item->kecamatan }}, Kab. {{ $item->kabupaten }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1;">Pendata:</small>
                                        <span class="card-pendata-search" style="font-size: 0.9rem; color: #5a5c69;">{{ $item->nama_pendata }}</span>
                                    </div>
                                    <a href="{{ route('tenagakerja.show', $item->id) }}"
                                    class="btn btn-info btn-sm btn-icon-split">
                                        <span class="icon text-white-50" style="padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <span class="text" style="padding: 0.25rem 0.5rem;">Detail</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="noResultsMessage" class="alert alert-warning mt-3" style="display: none;">
                    Tidak ada pengajuan yang cocok dengan kriteria pencarian Anda.
                </div>

                @if($items->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
@endsection
