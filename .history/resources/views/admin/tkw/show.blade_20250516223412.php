@extends('layouts.main')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Validasi Tenaga Kerja #{{ $item->id }}</h1>
    <a href="{{ route('admin.tkw.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
  </div>

  <div class="row">
    {{-- Kiri: Data Responden --}}
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          Data Responden
        </div>
        <div class="card-body">
          <p><strong>Provinsi:</strong> {{ $item->provinsi }}</p>
          <p><strong>Kabupaten:</strong> {{ $item->kabupaten }}</p>
          <p><strong>Kecamatan:</strong> {{ $item->kecamatan }}</p>
          <p><strong>Desa:</strong> {{ $item->desa }}</p>
          <p><strong>RT/RW:</strong> {{ $item->rt_rw }}</p>
          <p><strong>Tgl. Pembuatan:</strong> {{ $item->tgl_pembuatan }}</p>
          <p><strong>Nama Pendata:</strong> {{ $item->nama_pendata }}</p>
          <p><strong>Nama Responden:</strong> {{ $item->nama_responden }}</p>
          <hr>
          <p><strong>Nama:</strong> {{ $item->nama }}</p>
          <p><strong>NIK:</strong> {{ $item->nik }}</p>
          <p><strong>Hubungan Dengan Kepala Rumah Tangga:</strong> {{ $item->hdkrt }}</p>
          <p><strong>Nomor Urut Keluarga:</strong> {{ $item->nuk }}</p>
          <p><strong>:</strong> {{ $item->hdkk }}</p>
          <p><strong>Kelamin:</strong> {{ $item->kelamin == '1' ? 'Laki-laki' : 'Perempuan' }}</p>
          <hr>
          <p><strong>JART:</strong> {{ $item->jart }}</p>
          <p><strong>JART_AB:</strong> {{ $item->jart_ab }}</p>
          <p><strong>JPR2RTP:</strong> {{ $item->jpr2rtp }}</p>
        </div>
      </div>
    </div>

    {{-- Kanan: Form Validasi --}}
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">
          Form Validasi Admin
        </div>
        <div class="card-body">
          <form action="{{ route('admin.tkw.approve', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="admin_tgl_validasi" class="form-label">Tanggal Validasi</label>
              <input type="date" name="admin_tgl_validasi" id="admin_tgl_validasi"
                     value="{{ old('admin_tgl_validasi', $item->admin_tgl_validasi) }}"
                     class="form-control @error('admin_tgl_validasi') is-invalid @enderror">
              @error('admin_tgl_validasi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="admin_nama_kepaladusun" class="form-label">Nama Kepala Dusun</label>
              <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                     value="{{ old('admin_nama_kepaladusun', $item->admin_nama_kepaladusun) }}"
                     class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
              @error('admin_nama_kepaladusun')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="admin_ttd_pendata" class="form-label">Upload TTD (jpg/png, &lt;2MB)</label>
              <input type="file" name="admin_ttd_pendata" id="admin_ttd_pendata" accept="image/*"
                     class="form-control @error('admin_ttd_pendata') is-invalid @enderror">
              @error('admin_ttd_pendata')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror

              @if($item->admin_ttd_pendata)
                <small class="text-muted d-block mt-2">
                  TTD lama:<br>
                  <img src="{{ asset('storage/'.$item->admin_ttd_pendata) }}" style="max-height: 120px;">
                </small>
              @endif
            </div>

            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-success">Setujui</button>
              <button type="submit" formaction="{{ route('admin.tkw.reject', ['id' => $item->id]) }}" class="btn btn-danger">Tolak</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
