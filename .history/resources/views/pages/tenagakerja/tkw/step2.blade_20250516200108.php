{{-- resources/views/pages/tenagakerja/step2.blade.php --}}
@extends('layouts.main')

@section('title', 'Identitas Responden')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Identitas Responden</h1>
    </div>

    <!-- Form Step 2 -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tkw.step2') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama', $data['nama'] ?? '') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" class="form-control" id="nik" value="{{ old('nik', $data['nik'] ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="hdkrt">Hubungan Dengan Kepala Rumah Tangga</label>
                        <select name="hdkrt" class="form-control" id="hdkrt">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('hdkrt', $data['hdkrt'] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
                            <option value="2" {{ old('hdkrt', $data['hdkrt'] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
                            <option value="3" {{ old('hdkrt', $data['hdkrt'] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
                            <option value="4" {{ old('hdkrt', $data['hdkrt'] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
                            <option value="5" {{ old('hdkrt', $data['hdkrt'] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
                            <option value="6" {{ old('hdkrt', $data['hdkrt'] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
                            <option value="7" {{ old('hdkrt', $data['hdkrt'] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
                            <option value="8" {{ old('hdkrt', $data['hdkrt'] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="nuk">Nomor Urut Keluarga</label>
                        <select name="nuk" class="form-control" id="nuk">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('nuk', $data['nuk'] ?? '') == 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ old('nuk', $data['nuk'] ?? '') == 2 ? 'selected' : '' }}>2</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="hdkk">Hubungan Dengan Kepala Keluarga</label>
                        <select name="hdkk" class="form-control" id="hdkk">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('hdkk', $data['hdkk'] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
                            <option value="2" {{ old('hdkk', $data['hdkk'] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
                            <option value="3" {{ old('hdkk', $data['hdkk'] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
                            <option value="4" {{ old('hdkk', $data['hdkk'] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
                            <option value="5" {{ old('hdkk', $data['hdkk'] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
                            <option value="6" {{ old('hdkk', $data['hdkk'] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
                            <option value="7" {{ old('hdkk', $data['hdkk'] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
                            <option value="8" {{ old('hdkk', $data['hdkk'] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status_perkawinan">Status Perkawinan</label>
                        <select name="status_perkawinan" class="form-control" id="status_perkawinan">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 1 ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="2" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 2 ? 'selected' : '' }}>Kawin / Nikah</option>
                            <option value="3" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 3 ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="4" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 4 ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="status_pekerjaan">Status Pekerjaan</label>
                        <select name="status_pekerjaan" class="form-control" id="status_pekerjaan">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>Bekerja</option>
                            <option value="2" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                            <option value="3" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Bersekolah</option>
                            <option value="4" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Tidak / Belum Bekerja</option>
                            <option value="5" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                        <select name="jenis_pekerjaan" class="form-control" id="jenis_pekerjaan">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>PNS, TNI dan POLRI</option>
                            <option value="2" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Karyawan, Honorer</option>
                            <option value="3" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Wiraswasta</option>
                            <option value="4" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sub_jenis_pekerjaan">Sub-Jenis Pekerjaan</label>
                        <select name="sub_jenis_pekerjaan" class="form-control" id="sub_jenis_pekerjaan">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>Aparatur Pemerintah / Negara</option>
                            <option value="2" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Tenaga Ahli / Profesional</option>
                            <option value="3" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Tenaga Kerja Harian</option>
                            <option value="4" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Pengusaha / Wira Usaha</option>
                            <option value="5" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-control" id="pendidikan_terakhir">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 1 ? 'selected' : '' }}>Tidak / Belum Sekolah</option>
                            <option value="2" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 2 ? 'selected' : '' }}>Tamat SD Sederajat</option>
                            <option value="3" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 3 ? 'selected' : '' }}>Tamat SMP Sederajat</option>
                            <option value="4" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 4 ? 'selected' : '' }}>Tamat SMA Sederajat</option>
                            <option value="5" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 5 ? 'selected' : '' }}>Tamat Perguruan Tinggi (Diploma, S1, S2, S3)</option>
                            <option value="6" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == 6 ? 'selected' : '' }}>Tidak Pernah Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="pendapatan_per_bulan">Pendapatan per Bulan</label>
                        <select name="pendapatan_per_bulan" class="form-control" id="pendapatan_per_bulan">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == 1 ? 'selected' : '' }}>Di atas 500 ribu</option>
                            <option value="2" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == 2 ? 'selected' : '' }}>Di atas 1 juta</option>
                            <option value="3" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == 3 ? 'selected' : '' }}>Di atas 2 juta</option>
                            <option value="4" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == 4 ? 'selected' : '' }}>Di atas 4 juta</option>
                            <option value="5" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == 5 ? 'selected' : '' }}>Tidak Berpenghasilan</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
@endsection
