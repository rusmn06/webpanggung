@extends('layouts.main')

@section('title', 'Form Step 3')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rekapitulasi</h1>
    </div>

    <!-- Card Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('tkw.step3') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="jart">Jumlah Anggota Rumah Tangga</label>
                        <input type="number" name="jart" id="jart" min="0" max="99"
                            value="{{ old('jart', $data['jart'] ?? '') }}"
                            class="form-control @error('jart') is-invalid @enderror">
                        @error('jart')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="jart_ab">Jumlah Anggota Rumah Tangga Aktif Bekerja</label>
                        <input type="number" name="jart_ab" id="jart_ab" min="0" max="99"
                            value="{{ old('jart_ab', $data['jart_ab'] ?? '') }}"
                            class="form-control @error('jart_ab') is-invalid @enderror">
                        @error('jart_ab')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="jart_tb">Jumlah Anggota Rumah Tangga Tidak / Belum Bekerja</label>
                        <input type="number" name="jart_tb" id="jart_tb" min="0" max="99"
                            value="{{ old('jart_tb', $data['jart_tb'] ?? '') }}"
                            class="form-control @error('jart_tb') is-invalid @enderror">
                        @error('jart_tb')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="jart_ms">Jumlah Anggota Rumah Tangga Masih Sekolah</label>
                        <input type="number" name="jart_ms" id="jart_ms" min="0" max="99"
                            value="{{ old('jart_ms', $data['jart_ms'] ?? '') }}"
                            class="form-control @error('jart_ms') is-invalid @enderror">
                        @error('jart_ms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="jpr2rtp">Jumlah Pendapatan Rata-Rata Rumah Tangga Per Bulan</label>
                        <select name="jpr2rtp" id="jpr2rtp"
                            class="form-control @error('jpr2rtp') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="0" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == 0 ? 'selected' : '' }}>
                                0 - Rumah tangga tidak memiliki penghasilan
                            </option>
                            <option value="1" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == 1 ? 'selected' : '' }}>
                                1 - Jumlah pendapatan rumah tangga di atas 500 ribu
                            </option>
                            <option value="2" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == 2 ? 'selected' : '' }}>
                                2 - Jumlah pendapatan rumah tangga di atas 1 juta
                            </option>
                            <option value="3" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == 3 ? 'selected' : '' }}>
                                3 - Jumlah pendapatan rumah tangga di atas 2 juta
                            </option>
                            <option value="4" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == 4 ? 'selected' : '' }}>
                                4 - Jumlah pendapatan rumah tangga di atas 4 juta
                            </option>
                        </select>
                        @error('jpr2rtp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('tkw.step1') }}" class="btn btn-success">
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
