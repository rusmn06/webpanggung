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
            /* Tetap sebagai block untuk flow baris */
        }
        .submission-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border-radius: 0.5rem; /* Sedikit rounded corner untuk estetika */
        }
        .submission-card .card-header, .submission-card .card-footer {
            background-color: #f8f9fc; /* Warna netral untuk header/footer kartu */
        }
        .submission-card .card-body {
            flex-grow: 1; /* Pastikan body kartu mengisi ruang */
        }
        .submission-card .card-title-search {
            font-size: 1.1rem; /* Sedikit perbesar judul kartu */
        }
        .submission-card .card-text-label {
            font-size: 0.75rem; /* Kecilkan label "Responden:", "Lokasi:", dll. */
            color: #858796; /* Warna abu-abu untuk label */
            display: block; /* Agar teks utama di bawahnya */
            margin-bottom: 0.1rem;
        }
         .submission-card .card-text-value {
            font-size: 0.9rem; /* Ukuran standar untuk nilai */
            color: #5a5c69;
            line-height: 1.4;
        }
        /* Style untuk search input agar tidak terlalu lebar di desktop */
        #searchRiwayatInputContainer {
            max-width: 350px; /* Atau persentase, misal: 40% */
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

    {{-- Riwayat Pengajuan & Search Input Sejajar --}}
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 mt-4">
    <h2 class="h4 mb-2 mb-sm-0 text-gray-800">Riwayat Pengajuan Kuesioner Anda</h2>

    @if(!$items->isEmpty())
    <div id="searchRiwayatInputContainer">
        <input type="text" id="searchRiwayatInput" class="form-control form-control-sm shadow-sm" placeholder="🔍 Cari berdasarkan nama, lokasi...">
    </div>
    @endif
</div>


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
                <div class="col-xl-4 col-lg-6 mb-4 submission-card-wrapper">
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
                            {{-- Urutan Info Diubah & Style Font Disesuaikan --}}
                            <p class="card-text mb-2">
                                <span class="card-text-label">Responden:</span>
                                <span class="card-respondent-search font-weight-bold card-text-value">{{ $item->nama_responden }}</span>
                            </p>
                            <p class="card-text mb-2">
                                <span class="card-text-label">Lokasi:</span>
                                <span class="card-location-search card-text-value">{{ $item->desa }}, {{ $item->kecamatan }}</span>
                            </p>
                            <p class="card-text mb-0"> {{-- mb-0 untuk baris terakhir di card-body --}}
                                <span class="card-text-label">Tanggal Pengajuan:</span>
                                <span class="card-date-search card-text-value">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMM YY, HH:mm') }}</span> {{-- Format tanggal lebih ringkas --}}
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
            Tidak ada pengajuan yang cocok dengan kriteria pencarian Anda.
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
    const riwayatContainer = document.getElementById('riwayatPengajuanContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');

    // Hanya jalankan skrip pencarian jika elemennya ada
    if (searchInput && riwayatContainer) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const cards = riwayatContainer.getElementsByClassName('submission-card-wrapper');
            let visibleCount = 0;

            for (let i = 0; i < cards.length; i++) {
                const cardWrapper = cards[i];
                const card = cardWrapper.querySelector('.submission-card'); // Target elemen kartu sebenarnya
                if (!card) continue;

                const cardTitle = (card.querySelector('.card-title-search')?.textContent || '').toLowerCase();
                const cardStatus = (card.querySelector('.card-status-search')?.textContent || '').toLowerCase();
                // const cardDate = (card.querySelector('.card-date-search')?.textContent || '').toLowerCase(); // Kurang relevan untuk search umum
                const cardRespondent = (card.querySelector('.card-respondent-search')?.textContent || '').toLowerCase();
                const cardLocation = (card.querySelector('.card-location-search')?.textContent || '').toLowerCase();
                
                // ID diambil dari judul, contoh: "Pengajuan #RT-123" -> "rt-123"
                const idPart = cardTitle.split('#')[1] ? cardTitle.split('#')[1].toLowerCase() : '';

                const searchableText = `${cardTitle} ${cardStatus} ${cardRespondent} ${cardLocation} ${idPart}`;

                if (searchableText.includes(searchTerm)) {
                    cardWrapper.style.display = '';
                    visibleCount++;
                } else {
                    cardWrapper.style.display = 'none';
                }
            }

            if (noResultsMessage) {
                if (visibleCount === 0 && searchTerm !== '' && cards.length > 0) {
                    noResultsMessage.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'none';
                }
            }
        });
    }
});
</script>
@endpush