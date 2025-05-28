@extends('layouts.main')

@section('title', 'Validasi & Data RT Tenaga Kerja')

@push('styles')
    {{-- DataTables CSS --}}
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    {{-- Custom CSS untuk Halaman Ini --}}
    <style>
        /* Gaya Akordeon */
        .rt-accordion-item .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0; /* Hapus padding default */
        }
        .rt-accordion-button {
            color: #5a5c69;
            text-decoration: none !important;
            padding: 1rem 1.25rem;
            width: 100%;
            display: block; /* Agar full width */
            text-align: left; /* Pastikan teks rata kiri */
            border: none; /* Hapus border default button */
            background: none; /* Hapus background default button */
        }
        .rt-accordion-button:hover {
            background-color: #f8f9fc;
            color: #4e73df;
            text-decoration: none !important;
        }
        .rt-accordion-button.collapsed .indicator-icon {
            transform: rotate(0deg);
            transition: transform 0.2s ease-in-out;
            color: #858796; /* Warna ikon saat tertutup */
        }
        .rt-accordion-button:not(.collapsed) .indicator-icon {
            transform: rotate(180deg);
            transition: transform 0.2s ease-in-out;
            color: #4e73df; /* Warna ikon saat terbuka */
        }
        .rt-accordion-button:not(.collapsed) {
            color: #4e73df;
            background-color: #f8f9fc;
            box-shadow: none;
            font-weight: bold; /* Tebalkan teks saat terbuka */
        }
        .rt-accordion-button:focus,
        .rt-accordion-button:active {
            box-shadow: none; /* Hilangkan outline aneh saat fokus/klik */
            outline: none;
        }
        .rt-accordion-collapse .card-body {
            background-color: #fcfdff;
            border-top: 1px solid #e3e6f0;
            padding: 1rem; /* Beri padding */
        }
        .rt-summary .badge {
            font-size: 0.75em;
            font-weight: 500;
            padding: 0.4em 0.6em;
        }
        .btn-link:hover, .btn-link:focus, .btn-link:active {
            text-decoration: none;
        }

        /* Lain-lain */
        #rt-section.hidden {
            display: none;
        }
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            font-size: 1.2rem;
            color: #858796;
        }
        .loading-spinner .fas {
            margin-right: 10px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        /* Perbaikan kecil agar ikon validasi table sejajar */
        #validasiTable .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
        }
    </style>
@endpush

@section('content')

    {{-- ================================================= --}}
    {{-- BAGIAN 1: TABEL VALIDASI (Pending)             --}}
    {{-- ================================================= --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi Pengajuan Tenaga Kerja (Pending)</h1>
        <button id="toggle-rt-view" class="btn btn-info shadow-sm">
            <i class="fas fa-chevron-down fa-sm"></i> Tampilkan Data per RT
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="validasiTable" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Responden</th>
                            <th>Pendata</th>
                            <th>Desa/Kelurahan</th>
                            <th>Tgl. Pengajuan</th>
                            <th style="width: 80px;">Aksi</th>
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
                                    <a href="{{ route('admin.tkw.show', $item->id) }}"
                                       class="btn btn-sm btn-primary" title="Lihat & Validasi Data">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data yang menunggu validasi saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- BAGIAN 2: DAFTAR RT (Layout Akordeon Baru)     --}}
    {{-- ================================================= --}}
    <div id="rt-section" class="mb-4 hidden"> {{-- Awalnya tersembunyi --}}
         <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
            <h1 class="h3 mb-0 text-gray-800">Ringkasan Data per RT</h1>
        </div>

        {{-- Container untuk Akordeon --}}
        <div class="accordion" id="rtAccordion">
            @for ($i = 1; $i <= 24; $i++)
                @php
                    $rtNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                    $totalRT = $rumahTanggaCounts[$i] ?? 0;
                    $totalAnggota = $anggotaCounts[$i] ?? 0;
                @endphp
                <div class="card rt-accordion-item shadow-sm mb-2">
                    {{-- Header yang bisa diklik --}}
                    <div class="card-header p-0" id="heading-rt-{{ $i }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left rt-accordion-button collapsed"
                                    type="button"
                                    data-rt="{{ $i }}"
                                    data-toggle="collapse"
                                    data-target="#collapse-rt-{{ $i }}"
                                    aria-expanded="false"
                                    aria-controls="collapse-rt-{{ $i }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                                        <strong>RT {{ $rtNumber }}</strong>
                                    </span>
                                    <span class="rt-summary">
                                        <span class="badge badge-light mr-1">Responden: {{ $totalRT }}</span>
                                        <span class="badge badge-light">Anggota: {{ $totalAnggota }}</span>
                                        <i class="fas fa-chevron-down ml-3 indicator-icon"></i>
                                    </span>
                                </div>
                            </button>
                        </h2>
                    </div>

                    {{-- Konten yang bisa expand/collapse --}}
                    <div id="collapse-rt-{{ $i }}" class="collapse rt-accordion-collapse"
                         aria-labelledby="heading-rt-{{ $i }}"
                         data-parent="#rtAccordion"> {{-- data-parent agar hanya 1 yg terbuka --}}
                        <div class="card-body">
                            {{-- Area Konten Detail --}}
                            <div class="rt-detail-content-area p-1">
                                 {{-- Konten akan dimuat di sini oleh AJAX --}}
                                 <div class="text-center p-5 text-muted">Klik header di atas untuk memuat data RT...</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

@endsection

@push('scripts')
    {{-- jQuery & Bootstrap JS (Pastikan sudah ada di layouts.main atau load di sini) --}}
    {{-- <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

    {{-- DataTables JS --}}
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    {{-- Custom JS untuk Halaman Ini --}}
    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTables untuk tabel Validasi
            $('#validasiTable').DataTable({
                "paging": {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "info":   {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "searching": true,
                "order": [[ 4, "asc" ]],
                "columnDefs": [ { "orderable": false, "targets": 5 } ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Data tidak ditemukan",
                    "infoEmpty": "Tidak ada data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                     "paginate": { "first": "Awal", "last": "Akhir", "next": "Berikutnya", "previous": "Sebelumnya" },
                }
            });

            // 2. Fungsi untuk Tampilkan/Sembunyikan Seluruh Daftar RT
            $('#toggle-rt-view').on('click', function() {
                const rtSection = $('#rt-section');
                const icon = $(this).find('i');

                rtSection.slideToggle(function() {
                    if (rtSection.is(':hidden')) {
                        icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        // Saat disembunyikan, tutup semua accordion
                        $('.rt-accordion-collapse').collapse('hide');
                    } else {
                        icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    }
                });
            });

            // 3. Fungsi untuk Klik Tombol Akordeon (AJAX Loading)
            $('.rt-accordion-button').on('click', function(e) {
                e.preventDefault();

                const button = $(this);
                const rtNumber = button.data('rt');
                const targetCollapse = $(button.data('target'));
                const detailContentArea = targetCollapse.find('.rt-detail-content-area');
                
                // Cek apakah akan membuka (sedang tertutup)
                const willOpen = button.hasClass('collapsed');

                // Hanya load AJAX jika akan membuka DAN belum ada tabelnya
                if (willOpen && detailContentArea.find('table').length === 0) {
                    detailContentArea.html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Memuat data RT ' + String(rtNumber).padStart(3, '0') + '...</div>');

                    const url = `{{ route('admin.tkw.getrtdata', ['rt' => ':rt']) }}`.replace(':rt', rtNumber);

                    $.ajax({
                        url: url, type: 'GET',
                        success: function(response) {
                            detailContentArea.html(response);
                            const tableId = '#rtDetailTable-' + rtNumber;

                            // Inisialisasi DataTables dengan ID unik & pagination
                            $(tableId).DataTable({
                                "searching": true,
                                "paging": true,
                                "pageLength": 10,
                                "lengthChange": false,
                                "info": true,
                                "order": [[ 1, "asc" ]], // Urutkan berdasarkan Nama KK
                                "language": { "search": "Cari:", /* ... bahasa lainnya ... */ }
                            });
                        },
                        error: function() {
                            detailContentArea.html('<div class="text-danger p-3 text-center">Gagal memuat data. Silakan coba lagi.</div>');
                        }
                    });
                }

                // Biarkan Bootstrap menangani buka/tutup
                // Tapi kita perlu handle ikon secara manual karena event Bootstrap
                // bisa sedikit tricky. Kita akan gunakan event 'shown.bs.collapse' & 'hidden.bs.collapse'
            });

            // 4. Update Ikon saat Akordeon Buka/Tutup (Menggunakan event Bootstrap)
            $('.rt-accordion-collapse').on('show.bs.collapse', function () {
                // Cari button yang terhubung dan update ikonnya
                const button = $(`[data-target="#${this.id}"]`);
                button.find('.indicator-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                button.removeClass('collapsed').attr('aria-expanded', 'true');
            }).on('hide.bs.collapse', function () {
                // Cari button yang terhubung dan update ikonnya
                const button = $(`[data-target="#${this.id}"]`);
                button.find('.indicator-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                button.addClass('collapsed').attr('aria-expanded', 'false');
            });

        }); // Akhir dari $(document).ready
    </script>
@endpush