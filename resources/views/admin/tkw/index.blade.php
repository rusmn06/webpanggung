{{-- resources/views/pages/tenagakerja/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Validasi')

@push('styles')
    <!-- DataTables CSS -->
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi</h1>
    </div>

    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Responden</th>
                            <th>Penginput</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $items->firstItem() + $loop->index }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->verif_nama_pendata }}</td>
                                <td>
                                    <a href="{{ route('admin.tkw.show', $item->id) }}"
                                       class="btn btn-sm btn-primary">
                                        Lihat &amp; Validasi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $('#dataTable').DataTable({
                // Opsi tambahan (optional):
                // "ordering": false, // non‐urutkan kolom
                // "paging": true,    // pakai paging client‐side
            });
        });
    </script>
@endpush