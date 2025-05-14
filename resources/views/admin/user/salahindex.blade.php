@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Manajemen Akun User</h1>
<a href="{{ route('admin.akun.create') }}" class="btn btn-primary mb-3">+ Tambah User</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('admin.akun.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('admin.akun.destroy', $user->id) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus user ini?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}
@endsection
