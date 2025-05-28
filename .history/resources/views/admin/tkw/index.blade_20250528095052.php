@extends('layouts.main')

@section('title', 'Daftar Validasi Tenaga Kerja') {{-- Judul bisa disesuaikan --}}

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi Pengajuan Tenaga Kerja (Pending)</h1>
        {{-- TOMBOL BARU DITAMBAHKAN DI SINI --}}
        <a href="{{ route('admin.tkw.listrt') }}"
        class="btn btn-info shadow-sm d-none d-sm-inline-block">
            <i class="fas fa-th-large fa-sm text-white-50"></i> Lihat Data per RT
        </a>
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
                            <th>Desa/Kelurahan</th> {{-- Diubah --}}
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
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD MMM YY') }}</td> {{-- Format YY agar lebih ringkas --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.tkw.show', $item->id) }}"
                                       class="btn btn-sm btn-primary" title="Lihat & Validasi Data">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data yang menunggu validasi saat ini.</td> {{-- Colspan diubah menjadi 6 --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Tampilkan pagination jika ada --}}
            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
              <div class="mt-3 d-flex justify-content-center">
                  {{ $items->links() }}
              </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#validasiTable').DataTable({
                // Menonaktifkan paging & info bawaan DataTables jika menggunakan pagination Laravel
                "paging": {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "info":   {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "searching": true, // Tetap aktifkan pencarian
                "order": [[ 4, "asc" ]], // Urutkan berdasarkan Tgl. Pengajuan (index 4)
                "columnDefs": [
                    { "orderable": false, "targets": 5 } // Nonaktifkan sorting untuk kolom Aksi (index 5)
                ],
                "language": { // Menambahkan bahasa Indonesia (opsional)
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first":      "Pertama",
                        "last":       "Terakhir",
                        "next":       "Berikutnya",
                        "previous":   "Sebelumnya"
                    },
                }
            });
        });
    </script>
@endpush