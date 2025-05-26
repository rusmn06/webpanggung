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
        .submission-card-wrapper { Wrapper untuk setiap kartu agar mudah di-filter
            /* display: block; default */
        }
        .submission-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        /* Tambahkan style untuk input search jika perlu */
        #searchRiwayatInput {
            /* background-image: url('data:image/svg+xml;...'); /* ikon search */
            /* background-position: 10px 12px; */
            /* background-repeat: no-repeat; */
            /* padding-left: 40px; */
        }
    </style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 text-gray-800">Kuisioner Tenaga Kerja Saya</h1>
        <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-icon-split btn-md">
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
        {{-- Kartu Statistik Lainnya (Pending, Tervalidasi, Ditolak) --}}
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

    {{-- Riwayat Pengajuan --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-4">
        <h2 class="h4 mb-0 text-gray-800">Riwayat Pengajuan Kuesioner Anda</h2>
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
        {{-- Input untuk Pencarian --}}
        <div class="mb-3">
            <input type="text" id="searchRiwayatInput" class="form-control" placeholder="Cari pengajuan (ID, Responden, Lokasi, Status)...">
        </div>

        <div class="row" id="riwayatPengajuanContainer">
            @foreach($items as $item)
                {{-- Kita bungkus kartu dengan div agar mudah di-show/hide oleh JS --}}
                <div class="col-xl-4 col-lg-6 mb-4 submission-card-wrapper">
                    <div class="card submission-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary card-title-search">Pengajuan {{ $item->id }}</h6>
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-2 card-status-search">{{ $statusText }}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-1"><small class="text-muted">Tgl. Pengajuan:</small><br><span class="card-date-search">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY, HH:mm') }}</span></p>
                            <p class="card-text mb-1"><small class="text-muted">Responden:</small><br><span class="card-respondent-search">{{ $item->nama_responden }}</span></p>
                            <p class="card-text"><small class="text-muted">Lokasi:</small><br><span class="card-location-search">{{ $item->desa }}, {{ $item->kecamatan }}</span></p>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            {{-- Pastikan nama route 'tenagakerja.show' sudah benar dan mengarah ke UserTenagaKerjaController@show --}}
                            {{-- <a href="{{ route('tenagakerja.show', $item->id) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye fa-sm"></i> Lihat Detail
                            </a> --}}
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
{{-- Script untuk pencarian kartu --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRiwayatInput');
    const riwayatContainer = document.getElementById('riwayatPengajuanContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');

    if (searchInput && riwayatContainer) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = riwayatContainer.getElementsByClassName('submission-card-wrapper');
            let visibleCount = 0;

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const cardTitle = (card.querySelector('.card-title-search')?.textContent || '').toLowerCase();
                const cardStatus = (card.querySelector('.card-status-search')?.textContent || '').toLowerCase();
                const cardDate = (card.querySelector('.card-date-search')?.textContent || '').toLowerCase();
                const cardRespondent = (card.querySelector('.card-respondent-search')?.textContent || '').toLowerCase();
                const cardLocation = (card.querySelector('.card-location-search')?.textContent || '').toLowerCase();
                
                const searchableText = `${cardTitle} ${cardStatus} ${cardDate} ${cardRespondent} ${cardLocation} rt-${card.querySelector('.card-title-search')?.textContent.split('-')[1] || ''}`;

                if (searchableText.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            }

            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        });
    }
});
</script>
@endpush