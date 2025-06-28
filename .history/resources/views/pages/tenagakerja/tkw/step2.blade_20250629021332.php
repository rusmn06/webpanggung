{{-- resources/views/pages/tenagakerja/tkw/step2.blade.php --}}
@extends('layouts.main')

@section('title', 'Form Step 2')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Identitas Anggota Keluarga</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tkw.step2') }}" method="POST">
                @csrf
                <div id="members-container">

                    @php
                        $members = old('nama') ? old('nama') : (isset($data['nama']) && is_array($data['nama']) ? $data['nama'] : ['']);
                        if (empty($members)) $members = [''];
                    @endphp

                    @foreach ($members as $index => $nama_value)
                        <div class="member-form card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Anggota Keluarga #{{ $index + 1 }}</h6>
                                @if ($index > 0)
                                    <button type="button" class="btn btn-danger btn-sm remove-member-btn">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>
                            <div class="card-body">
                                {{-- NAMA, NIK, KELAMIN --}}
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nama_{{ $index }}">Nama Lengkap</label>
                                        <input type="text" name="nama[]"
                                            class="form-control @error('nama.'.$index) is-invalid @enderror"
                                            id="nama_{{ $index }}" value="{{ old('nama.'.$index, $data['nama'][$index] ?? '') }}">
                                        @error('nama.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nik_{{ $index }}">NIK</label>
                                        <input type="number" name="nik[]" maxlength="16"
                                            class="form-control @error('nik.'.$index) is-invalid @enderror"
                                            id="nik_{{ $index }}" value="{{ old('nik.'.$index, $data['nik'][$index] ?? '') }}">
                                        @error('nik.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="kelamin_{{ $index }}">Jenis Kelamin</label>
                                        <select name="kelamin[]" class="form-control @error('kelamin.'.$index) is-invalid @enderror" id="kelamin_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('kelamin.'.$index, $data['kelamin'][$index] ?? '') == 1 ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="2" {{ old('kelamin.'.$index, $data['kelamin'][$index] ?? '') == 2 ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('kelamin.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- HDKRT, HDKK, NUK, STATUS KAWIN --}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="hdkrt_{{ $index }}">Hubungan Dengan Kepala Rumah Tangga</label>
                                        <select name="hdkrt[]" class="form-control @error('hdkrt.'.$index) is-invalid @enderror" id="hdkrt_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
                                            <option value="2" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
                                            <option value="3" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
                                            <option value="4" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
                                            <option value="5" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
                                            <option value="6" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
                                            <option value="7" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
                                            <option value="8" {{ old('hdkrt.'.$index, $data['hdkrt'][$index] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('hdkrt.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hdkk_{{ $index }}">Hubungan Dengan Kepala Keluarga</label>
                                        <select name="hdkk[]" class="form-control @error('hdkk.'.$index) is-invalid @enderror" id="hdkk_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
                                            <option value="2" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
                                            <option value="3" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
                                            <option value="4" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
                                            <option value="5" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
                                            <option value="6" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
                                            <option value="7" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
                                            <option value="8" {{ old('hdkk.'.$index, $data['hdkk'][$index] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('hdkk.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="nuk_{{ $index }}">No. Urut Anggota</label>
                                        <input type="number" name="nuk[]" id="nuk_{{ $index }}" min="1" max="99"
                                            value="{{ old('nuk.'.$index, $data['nuk'][$index] ?? ($index + 1)) }}"
                                            class="form-control @error('nuk.'.$index) is-invalid @enderror">
                                        @error('nuk.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="status_perkawinan_{{ $index }}">Status Perkawinan</label>
                                        <select name="status_perkawinan[]" class="form-control @error('status_perkawinan.'.$index) is-invalid @enderror" id="status_perkawinan_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('status_perkawinan.'.$index, $data['status_perkawinan'][$index] ?? '') == 1 ? 'selected' : '' }}>Belum Kawin</option>
                                            <option value="2" {{ old('status_perkawinan.'.$index, $data['status_perkawinan'][$index] ?? '') == 2 ? 'selected' : '' }}>Kawin / Nikah</option>
                                            <option value="3" {{ old('status_perkawinan.'.$index, $data['status_perkawinan'][$index] ?? '') == 3 ? 'selected' : '' }}>Cerai Hidup</option>
                                            <option value="4" {{ old('status_perkawinan.'.$index, $data['status_perkawinan'][$index] ?? '') == 4 ? 'selected' : '' }}>Cerai Mati</option>
                                        </select>
                                        @error('status_perkawinan.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- STATUS PEKERJAAN, PENDIDIKAN, JENIS KERJA, SUB JENIS KERJA --}}
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="status_pekerjaan_{{ $index }}">Status Pekerjaan</label>
                                        <select name="status_pekerjaan[]" class="form-control @error('status_pekerjaan.'.$index) is-invalid @enderror" id="status_pekerjaan_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('status_pekerjaan.'.$index, $data['status_pekerjaan'][$index] ?? '') == 1 ? 'selected' : '' }}>Bekerja</option>
                                            <option value="2" {{ old('status_pekerjaan.'.$index, $data['status_pekerjaan'][$index] ?? '') == 2 ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                            <option value="3" {{ old('status_pekerjaan.'.$index, $data['status_pekerjaan'][$index] ?? '') == 3 ? 'selected' : '' }}>Bersekolah</option>
                                            <option value="4" {{ old('status_pekerjaan.'.$index, $data['status_pekerjaan'][$index] ?? '') == 4 ? 'selected' : '' }}>Tidak / Belum Bekerja</option>
                                            <option value="5" {{ old('status_pekerjaan.'.$index, $data['status_pekerjaan'][$index] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('status_pekerjaan.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="pendidikan_terakhir_{{ $index }}">Pendidikan Terakhir</label>
                                        <select name="pendidikan_terakhir[]" class="form-control @error('pendidikan_terakhir.'.$index) is-invalid @enderror" id="pendidikan_terakhir_{{ $index }}">
                                           <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 1 ? 'selected' : '' }}>Tidak / Belum Sekolah</option>
                                            <option value="2" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 2 ? 'selected' : '' }}>Tamat SD Sederajat</option>
                                            <option value="3" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 3 ? 'selected' : '' }}>Tamat SMP Sederajat</option>
                                            <option value="4" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 4 ? 'selected' : '' }}>Tamat SMA Sederajat</option>
                                            <option value="5" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 5 ? 'selected' : '' }}>Tamat Perguruan Tinggi(Diploma, S1, S2 S3)</option>
                                            <option value="6" {{ old('pendidikan_terakhir.'.$index, $data['pendidikan_terakhir'][$index] ?? '') == 6 ? 'selected' : '' }}>Tidak Pernah Sekolah</option>
                                        </select>
                                        @error('pendidikan_terakhir.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="jenis_pekerjaan_{{ $index }}">Jenis Pekerjaan</label>
                                        <select name="jenis_pekerjaan[]" class="form-control @error('jenis_pekerjaan.'.$index) is-invalid @enderror" id="jenis_pekerjaan_{{ $index }}">
                                           <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('jenis_pekerjaan.'.$index, $data['jenis_pekerjaan'][$index] ?? '') == 1 ? 'selected' : '' }}>PNS, TNI dan POLRI</option>
                                            <option value="2" {{ old('jenis_pekerjaan.'.$index, $data['jenis_pekerjaan'][$index] ?? '') == 2 ? 'selected' : '' }}>Karyawan, Honorer</option>
                                            <option value="3" {{ old('jenis_pekerjaan.'.$index, $data['jenis_pekerjaan'][$index] ?? '') == 3 ? 'selected' : '' }}>Wiraswasta</option>
                                            <option value="4" {{ old('jenis_pekerjaan.'.$index, $data['jenis_pekerjaan'][$index] ?? '') == 4 ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('jenis_pekerjaan.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sub_jenis_pekerjaan_{{ $index }}">Sub-Jenis Pekerjaan</label>
                                        <select name="sub_jenis_pekerjaan[]" class="form-control @error('sub_jenis_pekerjaan.'.$index) is-invalid @enderror" id="sub_jenis_pekerjaan_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('sub_jenis_pekerjaan.'.$index, $data['sub_jenis_pekerjaan'][$index] ?? '') == 1 ? 'selected' : '' }}>Aparatur Pemerintah / Negara</option>
                                            <option value="2" {{ old('sub_jenis_pekerjaan.'.$index, $data['sub_jenis_pekerjaan'][$index] ?? '') == 2 ? 'selected' : '' }}>Tenaga Ahli / Profesional</option>
                                            <option value="3" {{ old('sub_jenis_pekerjaan.'.$index, $data['sub_jenis_pekerjaan'][$index] ?? '') == 3 ? 'selected' : '' }}>Tenaga Kerja Harian</option>
                                            <option value="4" {{ old('sub_jenis_pekerjaan.'.$index, $data['sub_jenis_pekerjaan'][$index] ?? '') == 4 ? 'selected' : '' }}>Pengusaha / Wira Usaha</option>
                                            <option value="5" {{ old('sub_jenis_pekerjaan.'.$index, $data['sub_jenis_pekerjaan'][$index] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('sub_jenis_pekerjaan.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- PENDAPATAN --}}
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="pendapatan_per_bulan_{{ $index }}">Pendapatan per Bulan</label>
                                        <select name="pendapatan_per_bulan[]" class="form-control @error('pendapatan_per_bulan.'.$index) is-invalid @enderror" id="pendapatan_per_bulan_{{ $index }}">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('pendapatan_per_bulan.'.$index, $data['pendapatan_per_bulan'][$index] ?? '') == 1 ? 'selected' : '' }}>Di atas 500 ribu</option>
                                            <option value="2" {{ old('pendapatan_per_bulan.'.$index, $data['pendapatan_per_bulan'][$index] ?? '') == 2 ? 'selected' : '' }}>Di atas 1 juta</option>
                                            <option value="3" {{ old('pendapatan_per_bulan.'.$index, $data['pendapatan_per_bulan'][$index] ?? '') == 3 ? 'selected' : '' }}>Di atas 2 juta</option>
                                            <option value="4" {{ old('pendapatan_per_bulan.'.$index, $data['pendapatan_per_bulan'][$index] ?? '') == 4 ? 'selected' : '' }}>Di atas 4 juta</option>
                                            <option value="5" {{ old('pendapatan_per_bulan.'.$index, $data['pendapatan_per_bulan'][$index] ?? '') == 5 ? 'selected' : '' }}>Tidak Berpenghasilan</option>
                                        </select>
                                        @error('pendapatan_per_bulan.'.$index)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div> {{-- End #members-container --}}

                <button type="button" id="add-member-btn" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Tambah Anggota
                </button>

                <hr>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('tkw.step1') }}" class="btn btn-outline-secondary btn-back">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-outline-primary btn-next">
                        Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden Template for New Member Form --}}
    <div id="member-template" style="display: none;">
        <div class="member-form card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Anggota Keluarga #</h6>
                <button type="button" class="btn btn-danger btn-sm remove-member-btn">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
            <div class="card-body">
                {{-- NAMA, NIK, KELAMIN --}}
                 <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama[]" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>NIK</label>
                        <input type="text" name="nik[]" maxlength="16" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Jenis Kelamin</label>
                        <select name="kelamin[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Laki-laki</option>
                            <option value="2">Perempuan</option>
                        </select>
                    </div>
                </div>
                {{-- HDKRT, HDKK, NUK, STATUS KAWIN --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Hubungan Dengan Kepala Rumah Tangga</label>
                        <select name="hdkrt[]" class="form-control">
                           <option value="">-- Pilih --</option>
                           <option value="1">Kepala Keluarga</option>
                           <option value="2">Istri / Suami</option>
                           <option value="3">Anak</option>
                           <option value="4">Menantu</option>
                           <option value="5">Cucu</option>
                           <option value="6">Orang Tua / Mertua</option>
                           <option value="7">Pembantu</option>
                           <option value="8">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Hubungan Dengan Kepala Keluarga</label>
                        <select name="hdkk[]" class="form-control">
                           <option value="">-- Pilih --</option>
                           <option value="1">Kepala Keluarga</option>
                           <option value="2">Istri / Suami</option>
                           <option value="3">Anak</option>
                           <option value="4">Menantu</option>
                           <option value="5">Cucu</option>
                           <option value="6">Orang Tua / Mertua</option>
                           <option value="7">Pembantu</option>
                           <option value="8">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>No. Urut Anggota</label>
                        <input type="number" name="nuk[]" min="1" max="99" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Status Perkawinan</label>
                        <select name="status_perkawinan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Belum Kawin</option>
                            <option value="2">Kawin / Nikah</option>
                            <option value="3">Cerai Hidup</option>
                            <option value="4">Cerai Mati</option>
                        </select>
                    </div>
                </div>
                {{-- STATUS PEKERJAAN, PENDIDIKAN, JENIS KERJA, SUB JENIS KERJA --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Status Pekerjaan</label>
                        <select name="status_pekerjaan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Bekerja</option>
                            <option value="2">Ibu Rumah Tangga</option>
                            <option value="3">Bersekolah</option>
                            <option value="4">Tidak / Belum Bekerja</option>
                            <option value="5">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Tidak / Belum Sekolah</option>
                            <option value="2">Tamat SD Sederajat</option>
                            <option value="3">Tamat SMP Sederajat</option>
                            <option value="4">Tamat SMA Sederajat</option>
                            <option value="5">Tamat Perguruan Tinggi</option>
                            <option value="6">Tidak Pernah Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jenis Pekerjaan</label>
                        <select name="jenis_pekerjaan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">PNS, TNI dan POLRI</option>
                            <option value="2">Karyawan, Honorer</option>
                            <option value="3">Wiraswasta</option>
                            <option value="4">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sub-Jenis Pekerjaan</label>
                        <select name="sub_jenis_pekerjaan[]" class="form-control">
                           <option value="">-- Pilih --</option>
                           <option value="1">Aparatur Pemerintah / Negara</option>
                           <option value="2">Tenaga Ahli / Profesional</option>
                           <option value="3">Tenaga Kerja Harian</option>
                           <option value="4">Pengusaha / Wira Usaha</option>
                           <option value="5">Lainnya</option>
                        </select>
                    </div>
                </div>
                {{-- PENDAPATAN --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Pendapatan per Bulan</label>
                        <select name="pendapatan_per_bulan[]" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Di atas 500 ribu</option>
                            <option value="2">Di atas 1 juta</option>
                            <option value="3">Di atas 2 juta</option>
                            <option value="4">Di atas 4 juta</option>
                            <option value="5">Tidak Berpenghasilan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('members-container');
    const addButton = document.getElementById('add-member-btn');
    const template = document.getElementById('member-template').firstElementChild.cloneNode(true);

    // --- FUNGSI UNTUK VALIDASI INPUT NIK (BARU) ---
    function enforceNikMaxLength(event) {
        if (event.target.matches('input[name="nik[]"]')) {
            const input = event.target;
            const maxLength = 16; // Panjang maksimal NIK

            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    }

    if (container) {
        container.addEventListener('input', enforceNikMaxLength);
    }
    
    function updateMemberNumbers() {
        const forms = container.querySelectorAll('.member-form');
        forms.forEach((form, index) => {
            const header = form.querySelector('h6');
            header.textContent = `Anggota Keluarga #${index + 1}`;
            
            const nukInput = form.querySelector('input[name="nuk[]"]');
            if (nukInput && !nukInput.value) {
                nukInput.value = index + 1;
            }

            form.querySelectorAll('label, input, select').forEach(el => {
                if(el.hasAttribute('for')) {
                    el.setAttribute('for', el.getAttribute('for').split('_')[0] + '_' + index);
                }
                if(el.hasAttribute('id')) {
                    el.setAttribute('id', el.getAttribute('id').split('_')[0] + '_' + index);
                }
            });
        });
    }

    if (addButton) {
        addButton.addEventListener('click', function () {
            const newForm = template.cloneNode(true);
            newForm.querySelectorAll('input, select').forEach(el => {
                if (el.type !== 'button' && el.type !== 'submit') {
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                    else el.value = '';
                }
            });
            container.appendChild(newForm);
            updateMemberNumbers();
        });
    }

    if (container) {
        container.addEventListener('click', function (e) {
            if (e.target && (e.target.classList.contains('remove-member-btn') || e.target.closest('.remove-member-btn'))) {
                e.preventDefault(); 
                const formToRemove = e.target.closest('.member-form');
                formToRemove.remove();
                updateMemberNumbers();
            }
        });
    }

    updateMemberNumbers();
});
</script>
@endpush