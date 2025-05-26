{{-- resources/views/pages/tenagakerja/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Status Pengajuan Tenaga Kerja Anda') {{-- Judul lebih spesifik --}}

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Pengajuan Tenaga Kerja Anda</h1>
        {{-- Tombol ini mungkin lebih cocok kembali ke halaman menu Kuesioner atau Dashboard User --}}
        {{-- Asumsi ada route 'user.submission.tkw.menu' untuk halaman dengan tombol "ISI DATA" & "LIHAT DATA" --}}
        <a href="{{ route('user.submission.tkw.menu') }}" {{-- SESUAIKAN NAMA ROUTE INI --}}
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu Kuesioner
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Rumah Tangga Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>ID Pengajuan</th>
                            <th>Nama Responden (Kepala RT)</th>
                            <th>Desa/Kelurahan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status Validasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item) {{-- $item sekarang adalah objek RumahTangga --}}
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>RT-{{ $item->id }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                                <td>
                                    {{-- Menggunakan accessor status_validasi_text dari Model RumahTangga --}}
                                    @php
                                        $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi); // Fallback jika accessor belum ada
                                        $badgeClass = 'badge-secondary'; // Default
                                        if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark'; // Tambah text-dark agar terbaca
                                        if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                        if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} p-2">{{ $statusText }}</span>
                                </td>
                                <td>
                                    {{-- Tombol untuk melihat detail lengkap pengajuan --}}
                                    {{-- Pastikan nama route ini sesuai dengan definisi di web.php --}}
                                    <a href="{{ route('user.submission.tkw.show', $item->id) }}"
                                       class="btn btn-sm btn-info" title="Lihat Detail Pengajuan">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    {{-- Contoh tombol edit jika statusnya memungkinkan --}}
                                    @if(in_array($item->status_validasi, ['pending', 'rejected']))
                                        {{-- <a href="{{ route('user.tkwizard.step1', ['edit_id' => $item->id]) }}" --}}
                                        {{-- class="btn btn-sm btn-warning mt-1 mt-lg-0" title="Edit Pengajuan"> --}}
                                        {{-- <i class="fas fa-edit"></i> Edit --}}
                                        {{-- </a> --}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Anda belum memiliki data pengajuan. <br>
                                    {{-- Arahkan ke halaman awal wizard (step1) --}}
                                    {{-- Pastikan nama route ini sesuai dengan definisi di web.php --}}
                                    <a href="{{ route('user.tkwizard.step1') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Buat Pengajuan Baru
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Tampilkan link pagination jika menggunakan pagination Laravel --}}
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
            $('#dataTable').DataTable({
                // Jika menggunakan pagination dari Laravel, matikan pagination DataTables
                // agar tidak ada dua kontrol pagination.
                "paging":   {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "info":     {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages() ? 'false' : 'true' }},
                "searching": true, // Biarkan pencarian DataTables aktif
                "order": [[ 4, "desc" ]] // Contoh: urutkan berdasarkan kolom Tanggal Pengajuan (indeks ke-4) secara descending
            });
        });
    </script>
@endpush