@extends('layouts.main')

@section('title', 'Form Step 1')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Informasi Form</h1>
    </div>

    <!-- Card Form -->
    <div class="card shadow-sm border-0 mb-4">
        
        <div class="card-body">
            <form action="{{ route('tkw.step1') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi"
                            value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                            class="form-control @error('provinsi') is-invalid @enderror">
                        @error('provinsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="kabupaten">Kabupaten</label>
                        <input type="text" name="kabupaten" id="kabupaten"
                            value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                            class="form-control @error('kabupaten') is-invalid @enderror">
                        @error('kabupaten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="kecamatan">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan"
                            value="{{ old('kecamatan', $data['kecamatan'] ?? '') }}"
                            class="form-control @error('kecamatan') is-invalid @enderror">
                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="desa">Desa</label>
                        <input type="text" name="desa" id="desa"
                            value="{{ old('desa', $data['desa'] ?? '') }}"
                            class="form-control @error('desa') is-invalid @enderror">
                        @error('desa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="rt">RT (000)</label>
                        <input type="text" name="rt" id="rt"
                            value="{{ old('rt', $data['rt'] ?? '') }}"
                            class="form-control @error('rt') is-invalid @enderror">
                        @error('rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="rw">RW (000)</label>
                        <input type="text" name="rw" id="rw"
                            value="{{ old('rw', $data['rw'] ?? '') }}"
                            class="form-control @error('rw') is-invalid @enderror">
                        @error('rw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="tgl_pembuatan">Tanggal Pembuatan</label>
                        <input type="date" name="tgl_pembuatan" id="tgl_pembuatan"
                            value="{{ old('tgl_pembuatan', $data['tgl_pembuatan'] ?? '') }}"
                            class="form-control @error('tgl_pembuatan') is-invalid @enderror">
                        @error('tgl_pembuatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_pendata">Nama Pendata</label>
                        <input type="text" name="nama_pendata" id="nama_pendata"
                            value="{{ old('nama_pendata', $data['nama_pendata'] ?? '') }}"
                            class="form-control @error('nama_pendata') is-invalid @enderror">
                        @error('nama_pendata')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_responden">Nama Responden</label>
                        <input type="text" name="nama_responden" id="nama_responden"
                            value="{{ old('nama_responden', $data['nama_responden'] ?? '') }}"
                            class="form-control @error('nama_responden') is-invalid @enderror">
                        @error('nama_responden')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Next <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
