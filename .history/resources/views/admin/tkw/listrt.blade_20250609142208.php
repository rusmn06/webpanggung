@extends('layouts.app') {{-- Pastikan ini nama layout utama Anda --}}

@section('title', 'Pilih RT')

@push('styles')
<style>
    /* ... CSS Kustom Anda yang sudah bagus tidak perlu diubah ... */
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
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 text-muted" id="rt-info">
                    {{-- Teks ini akan diisi oleh JavaScript --}}
                </div>
                <div class="col-md-6">
                    <input type="text" id="rtSearch" class="form-control form-control-sm" placeholder="Cari RT (misal: 001, 023)...">
                </div>
            </div>
            <hr class="mt-0">

            {{-- Container untuk kartu-kartu RT --}}
            <div class="row" id="rt-card-container">
                {{-- Kita tetap menggunakan @for loop dari kode asli Anda --}}
                @for ($i = 1; $i <= 24; $i++)
                    @php
                        $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                        $totalRT = $rumahTanggaCounts[$i] ?? 0;
                        $totalAnggota = $anggotaCounts[$i] ?? 0;
                    @endphp
                    {{-- Kita tambahkan class 'rt-card-item' untuk target JavaScript --}}
                    <div class="col-lg-4 col-md-6 mb-4 rt-card-item">
                        <a href="{{ route('admin.tkw.showrt', ['rt' => $i]) }}" class="rt-link">
                            <div class="card rt-card-simple h-100 shadow-sm">
                                <div class="d-flex align-items-stretch h-100">
                                    <div class="icon-area">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="text-area">
                                        {{-- Kita tambahkan data-rt-number untuk memudahkan pencarian --}}
                                        <h6 data-rt-number="{{ $rtNumber }}">RT {{ $rtNumber }}</h6>
                                        <span class="btn btn-outline-primary btn-sm">Lihat Data</span>
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

            <nav>
                <ul class="pagination justify-content-center" id="rt-pagination">
                    {{-- Tombol pagination akan dibuat oleh JavaScript --}}
                </ul>
            </nav>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Script untuk fungsi pagination dan pencarian client-side --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsPerPage = 9;
        const rtCards = document.querySelectorAll('.rt-card-item');
        const paginationContainer = document.getElementById('rt-pagination');
        const infoContainer = document.getElementById('rt-info');
        const searchInput = document.getElementById('rtSearch');
        const totalPages = Math.ceil(rtCards.length / itemsPerPage);
        let currentPage = 1;

        function showPage(page) {
            currentPage = page;
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Tampilkan kartu untuk halaman yang aktif, sembunyikan yang lain
            rtCards.forEach((card, index) => {
                card.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
            });

            // Update info halaman
            const firstItem = startIndex + 1;
            const lastItem = Math.min(endIndex, rtCards.length);
            infoContainer.textContent = `Menampilkan ${firstItem} sampai ${lastItem} dari ${rtCards.length} total RT.`;

            // Update tombol pagination
            document.querySelectorAll('.page-item').forEach(item => {
                item.classList.remove('active');
            });
            const activePageLink = paginationContainer.querySelector(`[data-page="${page}"]`);
            if(activePageLink) {
                activePageLink.parentElement.classList.add('active');
            }
        }

        function setupPagination() {
            paginationContainer.innerHTML = ''; // Kosongkan pagination
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = 'page-item';
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                a.dataset.page = i;
                li.appendChild(a);
                paginationContainer.appendChild(li);

                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    showPage(i);
                });
            }
        }

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();

            if (searchTerm.length > 0) {
                // Sembunyikan pagination dan info saat mencari
                paginationContainer.style.display = 'none';
                infoContainer.textContent = '';
            } else {
                // Tampilkan kembali pagination dan info saat pencarian kosong
                paginationContainer.style.display = '';
                showPage(1); // Kembali ke halaman 1
            }
            
            // Filter kartu berdasarkan pencarian
            rtCards.forEach(card => {
                const rtNumber = card.querySelector('[data-rt-number]').dataset.rtNumber;
                if (rtNumber.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Inisialisasi tampilan awal
        setupPagination();
        showPage(1);
    });
</script>
@endpush