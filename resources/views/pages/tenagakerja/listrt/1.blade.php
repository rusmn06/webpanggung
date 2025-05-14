{{-- resources/views/pages/tenagakerja/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Status Tenaga Kerja Anda')

@push('styles')
    <!-- DataTables CSS -->
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Tenaga Kerja Anda</h1>
        <a href="{{ route('tenagakerja.index') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            Kembali
        </a>
    </div>

    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Status Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>
                                    @if($item->status_validasi === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status_validasi === 'validated')
                                        <span class="badge badge-success">Divalidasi</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada form yang Anda buat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            $('#dataTable').DataTable();
        });
    </script>
@endpush