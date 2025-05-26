{{-- Ini adalah resources/views/admin/tkw/index.blade.php ATAU resources/views/admin/validation/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Daftar Validasi Tenaga Kerja') {{-- Judul bisa disesuaikan --}}

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- Judul halaman. Tombol "Tambah" tidak relevan di sini karena data datang dari user --}}
        <h1 class="h3 mb-0 text-gray-800">Validasi Pengajuan Tenaga Kerja (Pending)</h1>
        {{-- Jika ada aksi global, bisa ditambahkan di sini, misal filter lanjutan atau export --}}
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengajuan Menunggu Validasi</h6>
        </div>
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
                {{-- Mengganti ID tabel agar unik jika ada beberapa DataTables --}}
                <table id="validasiTable" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>ID Pengajuan</th>
                            <th>Nama Responden</th>
                            <th>Desa</th>
                            <th>Pendata</th>
                            <th>Tgl. Pengajuan</th>
                            <th style="width: 80px;">Aksi</th> {{-- Lebar kolom aksi disesuaikan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item) {{-- $items adalah koleksi RumahTangga --}}
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>RT-{{ $item->id }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ $item->nama_pendata }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD MMM YYYY') }}</td>
                                <td class="text-center">
                                    {{-- Tombol aksi dibuat mirip dengan Manajemen Akun (ikon) --}}
                                    <a href="{{ route('admin.tkw.show', $item->id) }}" {{-- Pastikan nama route ini benar --}}
                                       class="btn btn-sm btn-primary" title="Lihat & Validasi Data">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- Jika ada aksi lain seperti 'Prioritaskan' atau 'Tahan', bisa ditambahkan di sini --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data yang menunggu validasi saat ini.</td>
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
            $('#validasiTable').DataTable({ // Target ID tabel yang baru
                "paging": {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }}, // Sesuaikan dengan pagination Laravel
                "info":   {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "searching": true,
                "order": [[ 5, "asc" ]], // Contoh: urutkan berdasarkan Tgl. Pengajuan (kolom ke-6, indeks 5)
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Kolom "Aksi" (indeks 6) tidak bisa diurutkan
                ]
            });
        });
    </script>
@endpush