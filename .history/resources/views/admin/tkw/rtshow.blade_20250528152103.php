{{-- views/admin/tkw/rt_show.blade.php --}}
@extends('layouts.main')

@section('title', 'Data Tenaga Kerja RT ' . str_pad($rt, 3, '0', STR_PAD_LEFT))

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Anggota Keluarga - RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}</h1>
        <a href="{{ route('admin.tkw.listrt') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Pilihan RT
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Anggota</th>
                            <th>NIK</th>
                            <th>Status Validasi RT</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>
                                    {{-- Mengambil status dari relasi RumahTangga --}}
                                    @php $status = $item->rumahTangga->status_validasi ?? 'N/A'; @endphp
                                    @if($status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($status === 'validated')
                                        <span class="badge badge-success">Divalidasi</span>
                                    @elseif($status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Link ke detail RumahTangga --}}
                                    <a href="{{ route('admin.tkw.show', $item->rumah_tangga_id) }}" class="btn btn-info btn-sm" title="Lihat Detail Rumah Tangga">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Diubah menjadi 4 --}}
                                <td colspan="4" class="text-center">Tidak ada data anggota keluarga untuk RT ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- TAMPILKAN LINK PAGINATION DI SINI --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $('#dataTable').DataTable({
                "paging":   false,
                "info":     false,
                "searching": true
            });
        });
    </script>
@endpush