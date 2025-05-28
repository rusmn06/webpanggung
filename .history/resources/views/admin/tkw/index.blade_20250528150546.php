@extends('layouts.main')

@section('title', 'Validasi & Data RT Tenaga Kerja') {{-- Judul diubah --}}

@push('styles')
    {{-- Gabungkan CSS dari kedua file --}}
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        /* CSS untuk Kartu RT */
        .rt-link { text-decoration: none !important; color: inherit; }
        .rt-link:hover { text-decoration: none !important; color: inherit; }
        .rt-card-simple { background-color: #fff; border: 1px solid #e3e6f0; border-radius: .35rem; transition: transform 0.15s ease, box-shadow 0.15s ease, border-left-color 0.15s ease; border-left: 4px solid #dddfeb; overflow: hidden; cursor: pointer; }
        .rt-card-simple:hover, .rt-card-simple.active { transform: translateY(-4px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.10)!important; border-left-color: #4e73df; }
        .rt-card-simple .icon-area { background-color: #f8f9fc; padding: 1rem; display: flex; align-items: center; justify-content: center; transition: background-color 0.15s ease; min-width: 70px; }
        .rt-card-simple:hover .icon-area, .rt-card-simple.active .icon-area { background-color: #eaf0fc; }
        .rt-card-simple .icon-area i { font-size: 1.75rem; color: #b7b9cc; transition: color 0.15s ease; }
        .rt-card-simple:hover .icon-area i, .rt-card-simple.active .icon-area i { color: #4e73df; }
        .rt-card-simple .text-area { padding: 0.75rem 1rem; display: flex; flex-direction: column; justify-content: center; flex-grow: 1; background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.03) 1px, transparent 0); background-size: 7px 7px; }
        .rt-card-simple .text-area h6 { font-weight: 700; margin-bottom: 0.35rem; color: #5a5c69; font-size: 1rem; }
        .rt-card-simple .text-area .btn { padding: 0.15rem 0.5rem; font-size: 0.75rem; align-self: flex-start; margin-bottom: 0.5rem; pointer-events: none; }
        .rt-preview-data small { line-height: 1.4; font-size: 0.7rem; display: block; color: #858796; }

        /* Tambahan CSS */
        .hidden { display: none; }
        #rt-detail-container .card { margin-top: 20px; }
        .loading-spinner { display: flex; justify-content: center; align-items: center; padding: 50px; font-size: 1.5rem; color: #858796; }
        .loading-spinner .fas { margin-right: 10px; }
        #rt-section.hidden { display: none; } /* Untuk toggle */
    </style>
@endpush

@section('content')
    {{-- ================================================= --}}
    {{-- BAGIAN 1: TABEL VALIDASI (Dari file index asli) --}}
    {{-- ================================================= --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi Pengajuan Tenaga Kerja (Pending)</h1>
        {{-- Tombol 'Lihat Data per RT' kita ubah jadi tombol toggle --}}
        <button id="toggle-rt-view" class="btn btn-info shadow-sm">
            <i class="fas fa-th-large fa-sm"></i> Tampilkan/Sembunyikan Data per RT
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }} <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }} <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="validasiTable" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th> <th>Nama Responden</th> <th>Pendata</th> <th>Desa/Kelurahan</th> <th>Tgl. Pengajuan</th> <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->nama_pendata }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD MMM YY') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.tkw.show', $item->id) }}" class="btn btn-sm btn-primary" title="Lihat & Validasi Data">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Tidak ada data yang menunggu validasi saat ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
                <div class="mt-3 d-flex justify-content-center"> {{ $items->links() }} </div>
            @endif
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- BAGIAN 2: DAFTAR RT (Dari file listrt)         --}}
    {{-- ================================================= --}}
    {{-- Kita beri class 'hidden' agar awalnya tersembunyi, tapi bisa diatur --}}
    <div id="rt-section" class="card shadow mb-4 hidden">
         <div class="card-header py-3">
             <h6 class="m-0 font-weight-bold text-primary">Pilih RT untuk Melihat Ringkasan Data</h6>
         </div>
        <div class="card-body">
            <div class="row">
                @for ($i = 1; $i <= 24; $i++)
                    @php
                        $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                        $totalRT = $rumahTanggaCounts[$i] ?? 0;
                        $totalAnggota = $anggotaCounts[$i] ?? 0;
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="rt-card-simple h-100 shadow-sm" data-rt="{{ $i }}">
                            <div class="d-flex align-items-stretch h-100">
                                <div class="icon-area"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="text-area">
                                    <h6>RT {{ $rtNumber }}</h6>
                                    <span class="btn btn-outline-primary btn-sm">Klik untuk Lihat Detail</span>
                                    <div class="rt-preview-data">
                                        <small>Jumlah Responden: <strong>{{ $totalRT }}</strong></small>
                                        <small>Jumlah Orang Yang terdaftar: <strong>{{ $totalAnggota }}</strong></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- BAGIAN 3: TEMPAT UNTUK MENAMPILKAN DETAIL RT    --}}
    {{-- ================================================= --}}
    <div id="rt-detail-container" class="mb-4">
        {{-- Detail tabel akan dimuat di sini oleh JavaScript --}}
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTables untuk tabel Validasi
            $('#validasiTable').DataTable({
                "paging": {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "info":   {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "searching": true, "order": [[ 4, "asc" ]], "columnDefs": [ { "orderable": false, "targets": 5 } ],
                "language": { "search": "Cari:", "zeroRecords": "Data tidak ditemukan", /* ... bahasa lainnya ... */ }
            });

            // 2. Fungsi untuk Tampilkan/Sembunyikan Daftar RT (Dropdown)
            $('#toggle-rt-view').on('click', function() {
                $('#rt-section').toggleClass('hidden').slideToggle(); // Gunakan slideToggle
                // Sembunyikan juga detail jika sedang terbuka
                $('#rt-detail-container').slideUp().html('');
                $('.rt-card-simple').removeClass('active');
            });

            // 3. Fungsi untuk Klik Kartu RT (Menggunakan AJAX)
            $('.rt-card-simple').on('click', function() {
                const rtNumber = $(this).data('rt');
                const detailContainer = $('#rt-detail-container');
                const clickedCard = $(this);
                const rtSection = $('#rt-section'); // Ambil bagian kartu RT

                $('.rt-card-simple').removeClass('active');
                clickedCard.addClass('active');

                detailContainer.html(`
                    <div class="card shadow"><div class="card-body loading-spinner">
                       <i class="fas fa-spinner fa-spin"></i> Memuat data untuk RT ${String(rtNumber).padStart(3, '0')}...
                    </div></div>`).slideDown(); // Gunakan slideDownA

                const url = `{{ route('admin.tkw.getrtdata', ['rt' => ':rt']) }}`.replace(':rt', rtNumber);

                $.ajax({
                    url: url, type: 'GET',
                    success: function(response) {
                        detailContainer.html(response);
                        // Hancurkan datatable lama jika ada, lalu inisialisasi yang baru
                        if ($.fn.DataTable.isDataTable('#rtDetailTable')) {
                           $('#rtDetailTable').DataTable().destroy();
                        }
                        $('#rtDetailTable').DataTable({
                           "searching": true, "paging": true, "info": true,
                           "language": { "search": "Cari:", /* ... bahasa lainnya ... */ }
                        });
                        // Scroll ke tabel detail (jika perlu)
                         $('html, body').animate({
                             scrollTop: detailContainer.offset().top - 70
                         }, 500);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        detailContainer.html(`<div class="card shadow"><div class="card-body text-center text-danger">Gagal memuat data: ${errorThrown}</div></div>`);
                    }
                });
            });
        });
    </script>
@endpush