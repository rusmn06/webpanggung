@extends('layouts.main')

@section('title', 'Dasbor Kuesioner Tenaga Kerja')

@push('styles')
    <style>
        .stat-card {
            transition: transform .2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .submission-card-wrapper {
            /* Untuk animasi atau transisi jika diinginkan nanti */
        }
        .submission-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0; /* Tambah border halus */
        }
        .submission-card .card-header, .submission-card .card-footer {
            background-color: #f8f9fc;
        }
        .submission-card .card-body {
            flex-grow: 1;
        }
        .submission-card .card-title-search {
            font-size: 1.05rem; /* Sedikit disesuaikan */
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
        .filter-container .form-control-sm {
            font-size: 0.8rem; /* Kecilkan sedikit input filter */
        }
    </style>
@endpush

@section('content')
    {{-- Baris Judul Utama dan Tombol Aksi --}}
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

    {{-- Judul Riwayat Pengajuan --}}
    <div class="mb-3 mt-4">
        <h2 class="h4 text-gray-800 d-inline-block">Riwayat Pengajuan Kuesioner Anda</h2>
    </div>

    {{-- Filter dan Pencarian --}}
    @if(!$items->isEmpty())
    <div class="card shadow-sm mb-4">
        <div class="card-body filter-container">
            <div class="row">
                <div class="col-md-5 mb-2 mb-md-0">
                    <input type="text" id="searchRiwayatInput" class="form-control form-control-sm" placeholder="Cari berdasarkan ID, Responden, Lokasi, Status...">
                    <small class="form-text text-muted">
                        Ketik kata kunci untuk memfilter daftar pengajuan.
                    </small>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <input type="date" id="startDateFilter" class="form-control form-control-sm" title="Dari Tanggal">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <input type="date" id="endDateFilter" class="form-control form-control-sm" title="Sampai Tanggal">
                </div>
                <div class="col-md-1 d-flex align-items-end mb-2 mb-md-0">
                    <button id="resetFilterBtn" class="btn btn-sm btn-outline-secondary w-100" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    @if($items->isEmpty())
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                <p class="lead text-gray-700">Anda belum memiliki data pengajuan.</p>
                <p>Silakan mulai dengan mengisi kuesioner baru.</p>
            </div>
        </div>
    @else
        <div class="row" id="riwayatPengajuanContainer">
            @foreach($items as $item)
                {{-- Menambahkan data-date untuk filter tanggal JS --}}
                <div class="col-xl-4 col-lg-6 mb-4 submission-card-wrapper" data-date="{{ $item->tgl_pembuatan->toDateString() }}">
                    <div class="card submission-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary card-title-search">Pengajuan #RT-{{ $item->id }}</h6>
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} py-1 px-2 card-status-search" style="font-size: 0.8rem;">{{ $statusText }}</span>
                        </div>
                        <div class="card-body px-3 py-2">
                            <p class="card-text mb-2">
                                <span class="card-text-label">Responden:</span>
                                <span class="card-respondent-search font-weight-bold card-text-value">{{ $item->nama_responden }}</span>
                            </p>
                            <p class="card-text mb-2">
                                <span class="card-text-label">Lokasi:</span>
                                <span class="card-location-search card-text-value">{{ $item->desa }}, {{ $item->kecamatan }}</span>
                            </p>
                            <p class="card-text mb-0">
                                <span class="card-text-label">Tanggal Pengajuan:</span>
                                <span class="card-date-search card-text-value">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMM YY, HH:mm') }}</span>
                            </p>
                        </div>
                        <div class="card-footer bg-light text-right py-2 px-3">
                            <a href="#"
                               class="btn btn-info btn-sm btn-icon-split">
                                <span class="icon text-white-50" style="padding: 0.25rem 0.5rem;">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="text" style="padding: 0.25rem 0.5rem;">Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="noResultsMessage" class="alert alert-warning mt-3" style="display: none;">
            Tidak ada pengajuan yang cocok dengan kriteria pencarian dan filter Anda.
        </div>

        @if($items->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $items->links() }}
        </div>
        @endif
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRiwayatInput');
    const startDateFilter = document.getElementById('startDateFilter');
    const endDateFilter = document.getElementById('endDateFilter');
    const resetFilterBtn = document.getElementById('resetFilterBtn');
    const riwayatContainer = document.getElementById('riwayatPengajuanContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');

    function filterCards() {
        if (!riwayatContainer) return; // Keluar jika container tidak ada

        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : "";
        const startDate = startDateFilter ? startDateFilter.value : "";
        const endDate = endDateFilter ? endDateFilter.value : "";
        const cards = riwayatContainer.getElementsByClassName('submission-card-wrapper');
        let visibleCount = 0;

        for (let i = 0; i < cards.length; i++) {
            const cardWrapper = cards[i];
            const card = cardWrapper.querySelector('.submission-card');
            if (!card) continue;

            const cardTitle = (card.querySelector('.card-title-search')?.textContent || '').toLowerCase();
            const cardStatus = (card.querySelector('.card-status-search')?.textContent || '').toLowerCase();
            const cardRespondent = (card.querySelector('.card-respondent-search')?.textContent || '').toLowerCase();
            const cardLocation = (card.querySelector('.card-location-search')?.textContent || '').toLowerCase();
            const cardDataDate = cardWrapper.dataset.date; // YYYY-MM-DD dari data-attribute

            const idPart = cardTitle.split('#')[1] ? cardTitle.split('#')[1].toLowerCase() : '';
            const searchableText = `${cardTitle} ${cardStatus} ${cardRespondent} ${cardLocation} ${idPart}`;

            let textMatch = true;
            if (searchTerm) {
                textMatch = searchableText.includes(searchTerm);
            }

            let dateMatch = true;
            if (cardDataDate) {
                if (startDate && cardDataDate < startDate) {
                    dateMatch = false;
                }
                if (endDate && cardDataDate > endDate) {
                    dateMatch = false;
                }
            } else if (startDate || endDate) { // Jika kartu tidak punya data tanggal, tapi filter tanggal ada
                dateMatch = false;
            }


            if (textMatch && dateMatch) {
                cardWrapper.style.display = '';
                visibleCount++;
            } else {
                cardWrapper.style.display = 'none';
            }
        }

        if (noResultsMessage) {
            if (visibleCount === 0 && (searchTerm !== '' || startDate !== '' || endDate !== '') && cards.length > 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', filterCards);
    }
    if (startDateFilter) {
        startDateFilter.addEventListener('change', filterCards);
    }
    if (endDateFilter) {
        endDateFilter.addEventListener('change', filterCards);
    }

    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            if(searchInput) searchInput.value = '';
            if(startDateFilter) startDateFilter.value = '';
            if(endDateFilter) endDateFilter.value = '';
            filterCards(); // Terapkan filter (akan menampilkan semua karena input kosong)
        });
    }

    // Filter awal saat halaman dimuat (jika ada nilai default di filter)
    // filterCards(); // Mungkin tidak perlu jika filter awalnya kosong
});
</script>
@endpush