@extends('layouts.app') {{-- Pastikan ini nama layout utama Anda --}}

@section('title', 'Pilih RT')

@push('styles')
<style>
    /* ... CSS kustom Anda yang sudah bagus tidak perlu diubah ... */
    .rt-link { text-decoration: none !important; color: inherit; }
    .rt-link:hover { text-decoration: none !important; color: inherit; }
    .rt-card-simple { background-color: #fff; border: 1px solid #e3e6f0; border-radius: .35rem; transition: transform 0.15s ease, box-shadow 0.15s ease, border-left-color 0.15s ease; border-left: 4px solid #dddfeb; overflow: hidden; }
    .rt-card-simple:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.10)!important; border-left-color: #4e73df; }
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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pilih RT untuk Melihat Data</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6 text-muted">
                    Menampilkan {{ $rts->firstItem() }} sampai {{ $rts->lastItem() }} dari {{ $rts->total() }} total RT.
                </div>
                <div class="col-md-6">
                    <input type="text" id="rtSearch" class="form-control form-control-sm" placeholder="Cari RT (misal: 001, 015)...">
                </div>
            </div>

            <div class="row" id="rt-card-container">
                {{-- PERUBAHAN: Menggunakan @foreach untuk data paginasi, bukan @for --}}
                @forelse ($rts as $rt)
                    <div class="col-lg-4 col-md-6 mb-4 rt-card-item">
                        <a href="{{ route('admin.tkw.showrt', ['rt' => $rt['rt_number']]) }}" class="rt-link">
                            <div class="card rt-card-simple h-100 shadow-sm">
                                <div class="d-flex align-items-stretch h-100">
                                    <div class="icon-area">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="text-area">
                                        <h6 data-rt-number="{{ str_pad($rt['rt_number'], 3, '0', STR_PAD_LEFT) }}">RT {{ str_pad($rt['rt_number'], 3, '0', STR_PAD_LEFT) }}</h6>
                                        <span class="btn btn-outline-primary btn-sm">
                                           Lihat Data
                                        </span>
                                        <div class="rt-preview-data mt-2">
                                            <small>Jumlah Responden: <strong>{{ $rt['total_responden'] }}</strong></small>
                                            <small>Jumlah Orang Terdaftar: <strong>{{ $rt['total_anggota'] }}</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p>Tidak ada data RT yang dapat ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            @if ($rts->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $rts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
{{-- Script untuk fungsi pencarian client-side --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('rtSearch');
        const cardContainer = document.getElementById('rt-card-container');
        const rtCards = cardContainer.querySelectorAll('.rt-card-item');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            rtCards.forEach(function(card) {
                const rtNumberElement = card.querySelector('[data-rt-number]');
                if (rtNumberElement) {
                    const rtNumber = rtNumberElement.dataset.rtNumber;
                    if (rtNumber.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endpush