@extends('layouts.main')

@section('title', 'Daftar Validasi')

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi (Pending)</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID RT</th> {{-- ID Rumah Tangga --}}
                            <th>Nama Responden</th> {{-- Nama Responden --}}
                            <th>Desa</th> {{-- Desa --}}
                            <th>Pendata</th> {{-- Pendata --}}
                            <th>Tgl. Pengajuan</th> {{-- Tanggal Pengajuan --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item) {{-- $items sekarang adalah RumahTangga --}}
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ $item->nama_pendata }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD MMM YYYY') }}</td>
                                <td>
                                    <a href="{{ route('admin.tkw.show', $item->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat &amp; Validasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data yang menunggu validasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- Tampilkan pagination jika ada --}}
                @if($items->count() > 0)
                  {{ $items->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": false, // Matikan paging DataTables jika pakai paging Laravel
                "info": false,   // Matikan info DataTables jika pakai paging Laravel
                "searching": true // Biarkan searching aktif
            });
        });
    </script>
@endpush