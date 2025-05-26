@extends('layouts.main')

@section('title', 'Kuesioner Tenaga Kerja Saya')

@push('styles')
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Kuesioner Tenaga Kerja Saya</h1>
        <a href="{{ route('dashboard') }}"
           class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <a href="{{ route('tkw.step1') }}" class="btn btn-primary btn-lg btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Isi Data Kuesioner Baru</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pengajuan Kuesioner Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>ID Pengajuan</th>
                            <th>Nama Responden (Kepala RT)</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item) {{-- $items adalah RumahTangga --}}
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>RT-{{ $item->id }}</td>
                                <td>{{ $item->nama_responden }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMM YYYY, HH:mm') }}</td>
                                <td>
                                    @php
                                        $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                        $badgeClass = 'badge-light text-dark'; // Default
                                        if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                        if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                        // KOREKSI TYPO DARI '.' MENJADI '->'
                                        if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} p-2">{{ $statusText }}</span>
                                </td>
                                <td>
                                    {{-- Tombol Lihat Detail --}}
                                    <a href="{{ route('tenagakerja.show', $item->id) }}" {{-- NAMA ROUTE BARU UNTUK DETAIL --}}
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="mb-2">Anda belum memiliki data pengajuan.</p>
                                    <i class="fas fa-folder-open fa-3x text-gray-400 mb-2"></i>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Kolom Chart (Opsional, Frontend Saja Dulu) --}}
    @if($items->isEmpty())
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-uppercase text-primary font-weight-bold mb-4">Statistik Pendataan Anda</h6>
                        <p>Belum ada data untuk ditampilkan dalam statistik.</p>
                        {{-- Kamu bisa tambahkan gambar placeholder atau ilustrasi di sini --}}
                        <i class="fas fa-chart-bar fa-4x text-gray-300 mt-3"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
      {{-- Jika ada data, kamu bisa tampilkan chart di sini jika mau --}}
      {{-- Contoh:
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase text-primary font-weight-bold mb-4">Statistik Pendataan</h6>
                        <div class="chart-bar">
                            <canvas id="myUserBarChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      --}}
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    {{-- Jika ingin pakai Chart.js --}}
    {{-- <script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('template/js/demo/chart-bar-demo.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging":   {{ $items->hasPages() ? 'false' : 'true' }},
                "info":     {{ $items->hasPages() ? 'false' : 'true' }},
                "searching": true,
                "order": [[ 3, "desc" ]] // Urutkan berdasarkan Tanggal Pengajuan (kolom ke-4)
            });

            // Jika ingin implementasi chart sederhana untuk user (contoh)
            // if (document.getElementById("myUserBarChart")) {
            //   // Logika untuk chart user, bisa ambil data dari $items jika perlu diolah di JS
            // }
        });
    </script>
@endpush