{{-- resources/views/admin/akun/index.blade.php --}}
@extends('layouts.main') {{-- pastikan layout admin udah include @stack('styles') & @stack('scripts') --}}

@section('title', 'Manajemen Akun User')

@push('styles')
    <!-- DataTables CSS -->
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Akun User</h1>
        <a href="{{ route('admin.user.create') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Tambah Akun
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Akun User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="akunTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->username ?? $u->email }}</td>
                                <td><span class="badge badge-info">{{ strtoupper($u->role) }}</span></td>
                                <td>
                                    <a href="{{ route('admin.user.edit', $u->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.user.destroy', $u->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin mau hapus akun {{ $u->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination jika diperlukan --}}
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#akunTable').DataTable({
                // contoh custom: disable ordering di kolom aksi
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ]
            });
        });
    </script>
@endpush
