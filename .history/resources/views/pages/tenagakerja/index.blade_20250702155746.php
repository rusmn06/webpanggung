@extends('layouts.main')

@section('title', 'Kuisioner Tenaga Kerja')

@push('styles')
    {{-- Style Anda tidak berubah, ini sudah bagus --}}
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

        .btn-square {
            width: 2rem;  /* Mengatur lebar tombol */
            height: 2rem; /* Mengatur tinggi tombol */
            padding: 0;     /* Menghapus padding internal */
            display: inline-flex;
            align-items: center;    /* Center vertikal untuk ikon */
            justify-content: center;/* Center horizontal untuk ikon */
        }
    </style>
@endpush

@section('content')
    {{-- Bagian Header dan Tombol Isi Kuesioner (Tidak Berubah) --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kuisioner Tenaga Kerja Saya</h1>
        <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-icon-split btn-md shadow-sm">
            <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span>
            <span class="text">Isi Kuesioner Baru</span>
        </a>
    </div>

    {{-- Bagian Statistik Ringkas (Tidak Berubah) --}}
    <div class="row">
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

    <div class="card shadow mb-4">
        <div class="card-body">

            @if($totalPengajuan > 0)
            <div class="row mb-4" id="searchRiwayatInputOuterContainer">
                <div class="col-lg-8">
                    {{-- Formulir berisi Filter dan Pencarian --}}
                    <form action="{{ route('tenagakerja.index') }}" method="GET" class="d-flex align-items-center" id="filter-search-form">
                        
                        {{-- BAGIAN FILTER STATUS --}}
                        <div class="input-group input-group-sm mr-2" style="max-width: 220px;">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="statusFilter"><i class="fas fa-filter"></i></label>
                            </div>
                            <select class="custom-select" id="statusFilter" name="status">
                                <option value="">Semua Status</option>
                                <option value="validated" @if(request('status') == 'validated') selected @endif>Disetujui</option>
                                <option value="pending" @if(request('status') == 'pending') selected @endif>Pending</option>
                                <option value="rejected" @if(request('status') == 'rejected') selected @endif>Ditolak</option>
                            </select>
                        </div>

                        {{-- BAGIAN PENCARIAN TEKS --}}
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, daerah..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" aria-label="Cari">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request()->has('search') || request()->has('status'))
                                <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-secondary" aria-label="Reset Filter dan Pencarian">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-lg-4 d-flex align-items-center">
                    <small class="form-text text-muted mt-2 mt-lg-0">
                        Gunakan filter atau cari berdasarkan kata kunci.
                    </small>
                </div>
            </div>
            @endif

            @if($items->isEmpty())
                @if(request()->has('search') && request()->get('search') !== '')
                    <div class="alert alert-warning text-center">
                        Tidak ada pengajuan yang cocok dengan kata kunci <strong>"{{ request('search') }}"</strong>.
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                        <p class="lead text-gray-700">Anda belum memiliki data pengajuan.</p>
                        <p>Silakan mulai dengan mengisi kuesioner baru.</p>
                    </div>
                @endif
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
                                            Desa {{ $item->desa }}, Kec. {{ $item->kecamatan }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                    <!-- Bagian kiri: Pendata -->
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1;">
                                            Pendata:
                                        </small>
                                        <span class="card-pendata-search" style="font-size: 0.9rem; color: #5a5c69;">
                                            {{ $item->nama_pendata }}
                                        </span>
                                    </div>

                                    <!-- Bagian kanan: Tombol Detail + Edit -->
                                    <div class="d-flex gap-1"> {{-- Jarak antar tombol diperkecil --}}
                                        <a href="{{ route('tenagakerja.show', $item->id) }}" class="btn btn-info btn-square" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('tenagakerja.edit', $item->id) }}" class="btn btn-warning btn-square @if($item->status_validasi === 'validated') disabled @endif" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($items->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $items->appends(request()->query())->links() }}
                </div>
                @endif
            @endif
            
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            // Otomatis kirim form saat nilai filter diubah
            document.getElementById('filter-search-form').submit();
        });
    }
});
</script>
@endpush