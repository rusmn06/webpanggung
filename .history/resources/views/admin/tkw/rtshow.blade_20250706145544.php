@extends('layouts.main') {{-- Pastikan ini nama layout utama Anda --}}

@section('title', 'Data Responden RT ' . str_pad($rt, 3, '0', STR_PAD_LEFT))

@push('styles')
<style>
    /* Mengambil gaya kartu dari halaman riwayat pengajuan Anda */
    .submission-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        border-radius: 0.5rem;
        border: 1px solid #e3e6f0;
    }
    .submission-card .card-header, .submission-card .card-footer {
        background-color: #f8f9fc;
    }
    .submission-card .card-body {
        flex-grow: 1;
    }
    .anggota-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .anggota-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #eaecf4;
        font-size: 0.9rem;
    }
    .anggota-list li:last-child {
        border-bottom: none;
    }
    .anggota-list li .nik {
        font-family: 'Courier New', Courier, monospace;
        color: #858796;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')

    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Responden - RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}</h1>
        <a href="{{ route('admin.tkw.listrt') }}"
           class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Pilihan RT
        </a>
    </div>

    {{-- Perulangan utama sekarang untuk setiap Rumah Tangga, bukan setiap anggota --}}
    <div class="row">
        @forelse($rumahTaggas as $rumahTangga)
            <div class="col-lg-6 mb-4">
                <div class="card submission-card shadow-sm h-100">
                    
                    {{-- HEADER KARTU: Menampilkan nama responden dan status pengajuan --}}
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary" style="line-height: 1.2;">
                            Responden: {{ $rumahTangga->nama_responden }}
                            <small class="text-muted">
                                Tgl. Pengajuan: {{ \Carbon\Carbon::parse($rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY') }}
                            </small>
                        </h6>
                        @php
                            $status = $rumahTangga->status_validasi ?? 'N/A';
                            $badgeClass = 'secondary';
                            if ($status === 'pending') $badgeClass = 'warning';
                            if ($status === 'validated') $badgeClass = 'success';
                            if ($status === 'rejected') $badgeClass = 'danger';
                        @endphp
                        <span class="badge badge-{{ $badgeClass }} py-2 px-2">{{ ucfirst($status) }}</span>
                    </div>

                    {{-- BODY KARTU: Menampilkan daftar anggota keluarga di dalam rumah tangga ini --}}
                    <div class="card-body p-0">
                        @if ($rumahTangga->anggotaKeluarga->isNotEmpty())
                            <ul class="anggota-list">
                                @foreach ($rumahTangga->anggotaKeluarga as $anggota)
                                    <li>
                                        <span><i class="fas fa-user fa-fw text-gray-400 mr-2"></i>{{ $anggota->nama }}</span>
                                        <span class="nik">{{ $anggota->nik }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-3 text-center text-muted">
                                Tidak ada data anggota keluarga.
                            </div>
                        @endif
                    </div>
                    
                    {{-- FOOTER KARTU: Menampilkan info pendata dan tombol aksi --}}
                    <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1;">Pendata:</small>
                            <span style="font-size: 0.9rem; color: #5a5c69;">{{ $rumahTangga->nama_pendata }}</span>
                        </div>

                        <div class="d-flex" style="gap: 0.5rem;">
                            <a href="{{ route('admin.tkw.detail', $rumahTangga->id) }}" class="btn btn-info btn-sm btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span class="text">Detail</span>
                            </a>
                            <a href="{{ route('admin.tkw.edit', $rumahTangga->id) }}" class="btn btn-warning btn-sm btn-icon-split">
                                {{-- VARIABEL $data->id diperbaiki menjadi $rumahTangga->id --}}
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Edit</span>
                            </a>
                            <form action="{{ route('admin.tkw.destroy', $rumahTangga->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                                    <span class="text">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                        <p class="lead text-gray-700">Tidak ada data pengajuan untuk RT ini.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

@endsection

@push('scripts')
<script>

    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();

            // Tampilkan pop-up SweetAlert2
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                // Jika pengguna mengklik tombol "Ya, hapus!"
                if (result.isConfirmed) {
                    // Lanjutkan pengiriman form asli
                    e.target.submit();
                }
            });
        }
    });
</script>
@endpush