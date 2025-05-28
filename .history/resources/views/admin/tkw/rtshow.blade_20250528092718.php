{{-- views/admin/tkw/rt_show.blade.php --}}
@extends('layouts.main')

{{-- Judul dinamis berdasarkan $rt --}}
@section('title', 'Data Tenaga Kerja RT ' . str_pad($rt, 3, '0', STR_PAD_LEFT))

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- Judul dinamis --}}
        <h1 class="h3 mb-0 text-gray-800">Data Tenaga Kerja - RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}</h1>
        {{-- Tombol kembali ke halaman pemilihan RT --}}
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
                            <th>Nama Responden</th> {{-- Sesuaikan kolom jika perlu --}}
                            <th>NIK</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th> {{-- Mungkin perlu tombol show/detail? --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                {{-- Sesuaikan $item->nama dengan $item->nama_responden atau yang sesuai --}}
                                <td>{{ $item->nama_responden ?? $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>
                                    @if($item->status_validasi === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status_validasi === 'validated')
                                        <span class="badge badge-success">Divalidasi</span>
                                    @elseif($item->status_validasi === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($item->status_validasi) }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Link ke halaman show validasi --}}
                                    <a href="{{ route('admin.tkw.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-search"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data untuk RT ini.</td>
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
        $(function() {
            $('#dataTable').DataTable({
                "paging":   false, // Nonaktifkan paging DataTables, pakai Laravel
                "info":     false, // Nonaktifkan info DataTables, pakai Laravel
                "searching": true // Aktifkan pencarian
            });
        });
    </script>
@endpush