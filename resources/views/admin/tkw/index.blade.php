@extends('layouts.main')

@section('title', 'Validasi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi</h1>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Responden</th>
                <th>Penginput</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
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

    <!-- pagination dengan Bootstrap -->
    <div class="d-flex justify-content-center">
        {{ $items->links() }}
    </div>
@endsection
