@extends('layouts.main')

@section('title', 'Detail Pengajuan Rumah Tangga #RT-' . $item->id)

@push('styles')
<style>
    .info-list-condensed dt, .info-list-condensed dd {
        padding-top: .35rem; padding-bottom: .35rem; margin-bottom: 0;
        font-size: 0.875rem; line-height: 1.4;
    }
    .info-list-condensed dt { font-weight: 400; color: #5a5c69; }
    .info-list-condensed dd { word-break: break-word; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Detail Pengajuan #RT-{{ $item->id }}</h6>
            {{-- Tombol kembali ke daftar pengajuan user --}}
            {{-- Pastikan route 'tenagakerja.listMySubmissions' sudah benar --}}
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary">
                 <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
            </a>
        </div>
        <div class="card-body">
            {{-- Tampilkan pesan sukses umum jika ada (opsional karena ada modal) --}}
            @if(session('success_message_body') && !session('show_success_modal'))
                <div class="alert alert-success">{{ session('success_message_body') }}</div>
            @endif

            {{-- Informasi Rumah Tangga --}}
            <h5 class="text-dark mb-3">Informasi Rumah Tangga</h5>
            {{-- ... (Salin bagian detail informasi rumah tangga dari admin.tkw.show.blade.php, hilangkan bagian yang tidak perlu dilihat user) ... --}}
            <div class="row">
                <div class="col-md-6">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-4">Provinsi</dt><dd class="col-sm-8">{{ $item->provinsi }}</dd>
                        <dt class="col-sm-4">Kabupaten</dt><dd class="col-sm-8">{{ $item->kabupaten }}</dd>
                        <dt class="col-sm-4">Kecamatan</dt><dd class="col-sm-8">{{ $item->kecamatan }}</dd>
                        <dt class="col-sm-4">Desa/Kel.</dt><dd class="col-sm-8">{{ $item->desa }}</dd>
                        <dt class="col-sm-4">RT/RW</dt><dd class="col-sm-8">{{ $item->rt }}/{{ $item->rw }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-4">Responden</dt><dd class="col-sm-8 font-weight-bold">{{ $item->nama_responden }}</dd>
                        <dt class="col-sm-4">Tgl. Diajukan</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            @php
                                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                                $badgeClass = 'badge-light text-dark';
                                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} p-1">{{ $statusText }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
             @if($item->ttd_pendata)
                <div class="mt-2">
                    <small class="text-muted d-block mb-1">TTD Pengaju:</small>
                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                </div>
            @endif


            <hr class="my-3">
            {{-- Daftar Anggota Keluarga --}}
            <h5 class="text-dark mb-3">Data Anggota Keluarga</h5>
            {{-- ... (Salin tabel anggota keluarga dari admin.tkw.show.blade.php) ... --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" style="font-size: 0.875rem;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 20%;">Nama Lengkap</th>
                            <th style="width: 15%;">NIK</th>
                            <th style="width: 10%;" class="text-center">Kelamin</th>
                            <th style="width: 15%;">Hub. KRT</th>
                            <th style="width: 15%;">Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th style="width: 10%;" class="text-center">Sts. Kawin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->anggotaKeluarga as $anggota)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td>'{{ $anggota->nik }}</td>
                                <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                <td>{{ $anggota->hdkrt_text }}</td>
                                <td>{{ $anggota->pendidikan_terakhir_text }}</td>
                                <td>{{ $anggota->status_pekerjaan_text }}
                                    @if($anggota->status_pekerjaan == '1')
                                        <small class="d-block text-muted">({{ $anggota->jenis_pekerjaan_text }})</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                            </tr>
                        @empty
                            <tr> <td colspan="8" class="text-center">Tidak ada data anggota keluarga.</td> </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Informasi Validasi oleh Admin (jika sudah divalidasi/ditolak) --}}
            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
            <hr class="my-3">
            <h5 class="text-dark mb-3">Informasi Hasil Validasi</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row info-list-condensed">
                        <dt class="col-sm-4">Tgl. Validasi</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('D MMMM YYYY') }}</dd>
                        <dt class="col-sm-4">Pejabat</dt><dd class="col-sm-8">{{ $item->admin_nama_kepaladusun ?? '-' }}</dd>
                    </dl>
                </div>
                @if($item->admin_ttd_pendata)
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">TTD Pejabat:</small>
                    <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" style="max-height: 70px; border: 1px solid #eee;" class="img-thumbnail">
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Bootstrap Modal untuk Notifikasi Sukses --}}
@if(session('show_success_modal'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"> {{-- Tambah data-backdrop & data-keyboard --}}
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success_message_title', 'Berhasil!') }}
                </h5>
                {{-- Tombol close modal bisa dihilangkan jika ingin user memilih aksi --}}
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="text-white">&times;</span></button> --}}
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p style="font-size: 1.1rem;">{{ session('success_message_body', 'Data Anda telah berhasil diproses.') }}</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center"> {{-- Tombol di tengah --}}
                <a href="{{ route('tenagakerja.listMySubmissions') }}" class="btn btn-outline-primary"> {{-- SESUAIKAN NAMA ROUTE --}}
                    <i class="fas fa-list-alt"></i> Lihat Riwayat Pengajuan
                </a>
                <a href="{{ route('tkw.step1') }}" class="btn btn-primary"> {{-- SESUAIKAN NAMA ROUTE --}}
                    <i class="fas fa-plus-circle"></i> Ajukan Data Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
    $(document).ready(function(){
        $('#successModal').modal('show');
    });
</script>
@endif
@endpush