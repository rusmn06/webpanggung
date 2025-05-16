@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h1 class="h5 m-0 text-primary font-weight-bold">
                <i class="fas fa-user-check"></i> Validasi Tenaga Kerja #{{ $item->id }}
            </h1>
            <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- LEFT: Data Responden --}}
                <div class="col-lg-6 mb-4">
                    <div class="card border-left-info shadow h-100">
                        <div class="card-header bg-info text-white font-weight-bold">
                            <i class="fas fa-address-card"></i> Data Responden
                        </div>
                        <div class="card-body small">
                            <div class="mb-2"><strong>Provinsi:</strong> {{ $item->provinsi }}</div>
                            <div class="mb-2"><strong>Kabupaten:</strong> {{ $item->kabupaten }}</div>
                            <div class="mb-2"><strong>Kecamatan:</strong> {{ $item->kecamatan }}</div>
                            <div class="mb-2"><strong>Desa:</strong> {{ $item->desa }}</div>
                            <div class="mb-2"><strong>RT/RW:</strong> {{ $item->rt_rw }}</div>
                            <div class="mb-2"><strong>Tgl. Pembuatan:</strong> {{ $item->tgl_pembuatan }}</div>
                            <div class="mb-2"><strong>Nama Pendata:</strong> {{ $item->nama_pendata }}</div>
                            <div class="mb-2"><strong>Nama Responden:</strong> {{ $item->nama_responden }}</div>
                            <hr>
                            <div class="mb-2"><strong>Nama:</strong> {{ $item->nama }}</div>
                            <div class="mb-2"><strong>NIK:</strong> {{ $item->nik }}</div>
                            <div class="mb-2"><strong>HDKRT:</strong> {{ $item->hdkrt }}</div>
                            <div class="mb-2"><strong>NUK:</strong> {{ $item->nuk }}</div>
                            <div class="mb-2"><strong>HDKK:</strong> {{ $item->hdkk }}</div>
                            <div class="mb-2"><strong>Kelamin:</strong> {{ $item->kelamin == '1' ? 'Laki-laki' : 'Perempuan' }}</div>
                            <hr>
                            <div class="mb-2"><strong>JART:</strong> {{ $item->jart }}</div>
                            <div class="mb-2"><strong>JART_AB:</strong> {{ $item->jart_ab }}</div>
                            <div class="mb-2"><strong>JPR2RTP:</strong> {{ $item->jpr2rtp }}</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Form Validasi --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100 border-left-success">
                        <div class="card-header bg-success text-white font-weight-bold">
                            <i class="fas fa-edit"></i> Form Validasi Admin
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="admin_tgl_validasi">Tanggal Validasi</label>
                                    <input type="date"
                                           name="admin_tgl_validasi"
                                           id="admin_tgl_validasi"
                                           value="{{ old('admin_tgl_validasi', $item->admin_tgl_validasi) }}"
                                           class="form-control @error('admin_tgl_validasi') is-invalid @enderror">
                                    @error('admin_tgl_validasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="admin_nama_kepaladusun">Nama Kepala Dusun</label>
                                    <input type="text"
                                           name="admin_nama_kepaladusun"
                                           id="admin_nama_kepaladusun"
                                           value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}"
                                           class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                                    @error('admin_nama_kepaladusun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="admin_ttd_pendata">Upload TTD (jpg/png, <2MB)</label>
                                    <input type="file"
                                           name="admin_ttd_pendata"
                                           id="admin_ttd_pendata"
                                           accept="image/*"
                                           class="form-control @error('admin_ttd_pendata') is-invalid @enderror">
                                    @error('admin_ttd_pendata')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if($item->admin_ttd_pendata)
                                        <small class="text-muted d-block mt-2">
                                            TTD lama:<br>
                                            <img src="{{ asset('storage/'.$item->admin_ttd_pendata) }}"
                                                 style="max-height:120px;">
                                        </small>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success shadow-sm">
                                        <i class="fas fa-check-circle"></i> Setujui
                                    </button>
                                    <button type="submit"
                                            formaction="{{ route('admin.tkw.reject', ['id' => $item->id]) }}"
                                            class="btn btn-danger shadow-sm">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div> {{-- End row --}}
        </div> {{-- End card-body --}}
    </div> {{-- End card --}}
</div>
@endsection
