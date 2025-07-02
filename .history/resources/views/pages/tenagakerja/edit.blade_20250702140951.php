@extends('layouts.main')

@section('title', 'Edit Pengajuan Ke-' . $item->user_sequence_number)

@push('styles')
{{-- Style sama persis dengan halaman create --}}
<style>
    .card-body { padding: 1.25rem; }
    .card-header { padding: 0.75rem 1.25rem; }
    #recap-card dt, #recap-card dd { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    #recap-card dt { font-weight: 500; }
    #recap-card dd input { background-color: #eaecf4 !important; border: none; font-size: 1rem; text-align: right; }
    .signature-box { border: 2px dashed #d1d3e2; border-radius: .35rem; padding: 1.5rem; text-align: center; display: flex; flex-direction: column; justify-content: center; background-color: #f8f9fc; height: 100%; }
    #signature-preview img { max-height: 120px; max-width: 100%; border-radius: .25rem; margin-bottom: 1rem; }
    .alert-info ul { margin-bottom: 0; padding-left: 20px; font-size: 0.85rem; }
    .is-invalid ~ .invalid-feedback { display: block; }
    fieldset[disabled] .form-control, fieldset[disabled] .custom-select {
        background-color: #eaecf4;
        opacity: 1;
    }
</style>
@endpush

@section('content')
<form action="{{ route('tenagakerja.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
    @csrf
    @method('PUT')

    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Gagal Memperbarui Data!</h5>
        <p>Terdapat kesalahan pada isian Anda. Silakan periksa kembali semua kolom yang ditandai merah di bawah ini.</p>
    </div>
    @endif
    
    @if (session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif
    
    {{-- KOTAK PERINGATAN DINAMIS BERDASARKAN STATUS --}}
    @if($item->status_validasi === 'validated')
    <div class="alert alert-success mb-4 shadow-sm">
        <h5 class="alert-heading font-weight-bold"><i class="fas fa-lock mr-2"></i>Data Terkunci</h5>
        <p class="mb-0">Pengajuan ini telah <strong>Disetujui</strong> dan tidak dapat diubah lagi. Untuk permintaan perubahan, silakan hubungi administrator.</p>
    </div>
    @elseif($item->status_validasi === 'rejected')
    <div class="alert alert-danger mb-4 shadow-sm">
        <h5 class="alert-heading font-weight-bold"><i class="fas fa-undo mr-2"></i>Pengajuan Ditolak</h5>
        <p class="mb-0">Pengajuan ini ditolak oleh admin. Silakan periksa dan perbaiki data di bawah ini, lalu ajukan kembali dengan menekan tombol "Update Data".</p>
        @if($item->admin_catatan_validasi)
            <hr>
            <p class="mb-0"><strong>Catatan dari Admin:</strong> <em>{{ $item->admin_catatan_validasi }}</em></p>
        @endif
    </div>
    @endif

    {{-- Fieldset hanya menonaktifkan form jika status 'validated' --}}
    <fieldset @if($item->status_validasi === 'validated') disabled @endif>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Informasi & Rekapitulasi</h1>
        </div>
        <div class="row">
            <div class="col-lg-8 mb-4">
                {{-- ... (Isi Kartu Informasi Pengajuan, sama seperti sebelumnya) ... --}}
            </div>
            <div class="col-lg-4 mb-4">
                {{-- ... (Isi Kartu Rekapitulasi, sama seperti sebelumnya) ... --}}
            </div>
        </div>
        
        <div class="d-sm-flex align-items-center justify-content-between my-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Anggota Keluarga</h1>
        </div>
        <div id="members-container" class="w-100">
            @foreach (old('nama', $item->anggotaKeluarga) as $index => $data)
                @php
                    $anggota = is_object($data) ? $data : ($item->anggotaKeluarga[$index] ?? null);
                @endphp
                <div class="member-form card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Anggota Keluarga #{{ $index + 1 }}</h6>
                        <button type="button" class="btn btn-danger btn-sm remove-member-btn"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Nama Lengkap</label><input type="text" name="nama[]" class="form-control @error('nama.'.$index) is-invalid @enderror" value="{{ old('nama.'.$index, $anggota->nama ?? '') }}">@error('nama.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-6"><label>NIK</label><input type="text" name="nik[]" class="form-control @error('nik.'.$index) is-invalid @enderror" value="{{ old('nik.'.$index, $anggota->nik ?? '') }}" placeholder="16 Digit Angka">@error('nik.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3"><label>Jenis Kelamin</label><select name="kelamin[]" class="form-control @error('kelamin.'.$index) is-invalid @enderror"><option value="">Pilih...</option><option value="1" @if(old('kelamin.'.$index, $anggota->kelamin ?? '') == '1') selected @endif>Laki-laki</option><option value="2" @if(old('kelamin.'.$index, $anggota->kelamin ?? '') == '2') selected @endif>Perempuan</option></select>@error('kelamin.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>Hub. KRT</label><select name="hdkrt[]" class="form-control @error('hdkrt.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Kepala Keluarga','2'=>'Istri / Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua / Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('hdkrt.'.$index, $anggota->hdkrt ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('hdkrt.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>Hub. KK</label><select name="hdkk[]" class="form-control @error('hdkk.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Kepala Keluarga','2'=>'Istri / Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua / Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('hdkk.'.$index, $anggota->hdkk ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('hdkk.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>No. Urut</label><input type="number" name="nuk[]" min="1" max="99" class="form-control @error('nuk.'.$index) is-invalid @enderror" value="{{ old('nuk.'.$index, $anggota->nuk ?? ($index + 1)) }}">@error('nuk.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div><hr>
                        <div class="form-row">
                            <div class="form-group col-md-4"><label>Status Perkawinan</label><select name="status_perkawinan[]" class="form-control @error('status_perkawinan.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Belum Kawin','2'=>'Kawin / Nikah','3'=>'Cerai Hidup','4'=>'Cerai Mati'] as $key => $value)<option value="{{$key}}" @if(old('status_perkawinan.'.$index, $anggota->status_perkawinan ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('status_perkawinan.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-4"><label>Pendidikan Terakhir</label><select name="pendidikan_terakhir[]" class="form-control @error('pendidikan_terakhir.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Tidak / Belum Sekolah','2'=>'Tamat SD','3'=>'Tamat SMP','4'=>'Tamat SMA','5'=>'Tamat PT','6'=>'Tidak Sekolah'] as $key => $value)<option value="{{$key}}" @if(old('pendidikan_terakhir.'.$index, $anggota->pendidikan_terakhir ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('pendidikan_terakhir.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-4"><label>Status Pekerjaan</label><select name="status_pekerjaan[]" class="form-control recap-trigger @error('status_pekerjaan.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Bekerja','2'=>'Ibu Rumah Tangga','3'=>'Bersekolah','4'=>'Tidak / Belum Bekerja','5'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('status_pekerjaan.'.$index, $anggota->status_pekerjaan ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('status_pekerjaan.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div>
                         <div class="form-row">
                            <div class="form-group col-md-4"><label>Jenis Pekerjaan</label><select name="jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'PNS/TNI/POLRI','2'=>'Karyawan/Honorer','3'=>'Wiraswasta','4'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('jenis_pekerjaan.'.$index, $anggota->jenis_pekerjaan ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                            <div class="form-group col-md-4"><label>Sub-Jenis Pekerjaan</label><select name="sub_jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'Aparatur','2'=>'Profesional','3'=>'Tenaga Harian','4'=>'Wira Usaha','5'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('sub_jenis_pekerjaan.'.$index, $anggota->sub_jenis_pekerjaan ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                            <div class="form-group col-md-4"><label>Pendapatan per Bulan</label><select name="pendapatan_per_bulan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'> 500 ribu','2'=>'> 1 juta','3'=>'> 2 juta','4'=>'> 4 juta','5'=>'Tdk Berpenghasilan'] as $key => $value)<option value="{{$key}}" @if(old('pendapatan_per_bulan.'.$index, $anggota->pendapatan_per_bulan ?? '') == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr class="w-100">
        <div class="w-100 d-flex justify-content-end align-items-center mb-4">
            <button type="button" id="add-member-btn" class="btn btn-success flex-shrink-0"><i class="fas fa-plus mr-2"></i>Tambah Anggota</button>
        </div>

        {{-- ... (Bagian Verifikasi & Tanda Tangan, sama seperti sebelumnya) ... --}}
    </fieldset>

    <hr class="w-100">
    <div class="w-100 d-flex justify-content-end my-4">
        <a href="{{ route('tenagakerja.show', $item->id) }}" class="btn btn-secondary mr-3">Batal</a>
        <button type="submit" class="btn btn-primary btn-lg" @if($item->status_validasi === 'validated') disabled @endif>
            <i class="fas fa-save mr-2"></i>Update Data Pengajuan
        </button>
    </div>
</form>

{{-- Template anggota baru tidak berubah --}}
<div id="member-template" style="display: none;">
    {{-- ... Kode template sama persis seperti di create.blade.php ... --}}
</div>
@endsection

@push('scripts')
{{-- Script juga bisa disalin dari create.blade.php, karena fungsinya sama --}}
<script>
    // ... JavaScript untuk menambah/menghapus anggota, update rekap, dan preview TTD ...
</script>
@endpush