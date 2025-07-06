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
    .signature-box { border: 2px dashed #d1d3e2; border-radius: .35rem; padding: 1.5rem; text-align: center; display: flex; flex-direction: column; justify-content: center; background-color: #f8f9fc; height: 85%; }
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
<form action="{{ route('admin.tkw.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
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
    {{-- Revisi dengan Nama Kolom yang Benar --}}
    @elseif($item->status_validasi === 'rejected')
    <div class="alert alert-danger mb-4 shadow-sm">
        <h5 class="alert-heading font-weight-bold"><i class="fas fa-undo mr-2"></i>Pengajuan Ditolak</h5>
        <p>Pengajuan ini ditolak oleh admin. Silakan periksa dan perbaiki data di bawah ini, lalu ajukan kembali.</p>
        {{-- Menggunakan nama kolom yang benar dari database: 'admin_catatan' --}}
        @if($item->admin_catatan)
            <hr>
            <p class="mb-0"><strong>Catatan dari Admin:</strong> <em>{{ $item->admin_catatan }}</em></p>
        @endif
    </div>
    @endif

    {{-- Fieldset hanya menonaktifkan form jika status 'validated' --}}
    <fieldset @if($item->status_validasi === 'validated') disabled @endif>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <h1 class="h3 text-gray-800 mb-0">
                    Edit Pengajuan Ke-{{ $item->user_sequence_number ?? '??' }}
                </h1>
                @php
                    $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                    $badgeClass = 'badge-light text-dark border';
                    if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                    if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                    if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
                @endphp
                <span class="badge {{ $badgeClass }} p-2 ml-3" style="font-size: 0.9rem;">{{ $statusText }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Pengajuan & Lokasi</h6></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="provinsi">Provinsi</label><input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $item->provinsi) }}" class="form-control @error('provinsi') is-invalid @enderror">@error('provinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4 mb-3"><label for="kabupaten">Kabupaten</label><input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $item->kabupaten) }}" class="form-control @error('kabupaten') is-invalid @enderror">@error('kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4 mb-3"><label for="kecamatan">Kecamatan</label><input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', $item->kecamatan) }}" class="form-control @error('kecamatan') is-invalid @enderror">@error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4 mb-3"><label for="desa">Desa/Kelurahan</label><input type="text" name="desa" id="desa" value="{{ old('desa', $item->desa) }}" class="form-control @error('desa') is-invalid @enderror">@error('desa')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-2 mb-3"><label for="rt">RT</label><input type="number" name="rt" id="rt" value="{{ old('rt', $item->rt) }}" class="form-control @error('rt') is-invalid @enderror" min="0">@error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-2 mb-3"><label for="rw">RW</label><input type="number" name="rw" id="rw" value="{{ old('rw', $item->rw) }}" class="form-control @error('rw') is-invalid @enderror" min="0">@error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4 mb-3"><label for="tgl_pembuatan">Tgl. Pembuatan</label><input type="date" name="tgl_pembuatan" id="tgl_pembuatan" value="{{ old('tgl_pembuatan', \Carbon\Carbon::parse($item->tgl_pembuatan)->format('Y-m-d')) }}" class="form-control @error('tgl_pembuatan') is-invalid @enderror">@error('tgl_pembuatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-6 mb-md-0"><label for="nama_pendata">Nama Pendata</label><input type="text" name="nama_pendata" id="nama_pendata" value="{{ old('nama_pendata', $item->nama_pendata) }}" class="form-control @error('nama_pendata') is-invalid @enderror" readonly>@error('nama_pendata')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-6 mb-md-0"><label for="nama_responden">Nama Responden</label><input type="text" name="nama_responden" id="nama_responden" value="{{ old('nama_responden', $item->nama_responden) }}" class="form-control @error('nama_responden') is-invalid @enderror">@error('nama_responden')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100" id="recap-card">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Rekapitulasi (Otomatis)</h6></div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-8">Jumlah Anggota</dt><dd class="col-sm-4"><input type="number" id="recap_jart" class="form-control form-control-sm" readonly></dd>
                            <dt class="col-sm-8">Anggota Bekerja</dt><dd class="col-sm-4"><input type="number" id="recap_jart_ab" class="form-control form-control-sm" readonly></dd>
                            <dt class="col-sm-8">Tdk/Belum Bekerja</dt><dd class="col-sm-4"><input type="number" id="recap_jart_tb" class="form-control form-control-sm" readonly></dd>
                            <dt class="col-sm-8">Masih Sekolah</dt><dd class="col-sm-4"><input type="number" id="recap_jart_ms" class="form-control form-control-sm" readonly></dd>
                        </dl>
                        <hr class="my-2">
                        <label for="jpr2rtp" class="d-block mb-2">Pendapatan Rata-Rata RT</label>
                        <select name="jpr2rtp" id="jpr2rtp" class="form-control @error('jpr2rtp') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="5" @if(old('jpr2rtp', $item->jpr2rtp) == '5') selected @endif>Tidak Berpenghasilan</option>
                            <option value="1" @if(old('jpr2rtp', $item->jpr2rtp) == '1') selected @endif>&gt; 500 ribu</option>
                            <option value="2" @if(old('jpr2rtp', $item->jpr2rtp) == '2') selected @endif>&gt; 1 juta</option>
                            <option value="3" @if(old('jpr2rtp', $item->jpr2rtp) == '3') selected @endif>&gt; 2 juta</option>
                            <option value="4" @if(old('jpr2rtp', $item->jpr2rtp) == '4') selected @endif>&gt; 4 juta</option>
                        </select>
                        @error('jpr2rtp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
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
                            <div class="form-group col-md-6"><label>Nama Lengkap</label><input type="text" name="nama[]" class="form-control @error('nama.'.$index) is-invalid @enderror" value="{{ old('nama.'.$index, optional($anggota)->nama) }}">@error('nama.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-6"><label>NIK</label><input type="text" name="nik[]" class="form-control @error('nik.'.$index) is-invalid @enderror" value="{{ old('nik.'.$index, optional($anggota)->nik) }}" placeholder="16 Digit Angka">@error('nik.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3"><label>Jenis Kelamin</label><select name="kelamin[]" class="form-control @error('kelamin.'.$index) is-invalid @enderror"><option value="">Pilih...</option><option value="1" @if(old('kelamin.'.$index, optional($anggota)->kelamin) == '1') selected @endif>Laki-laki</option><option value="2" @if(old('kelamin.'.$index, optional($anggota)->kelamin) == '2') selected @endif>Perempuan</option></select>@error('kelamin.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>Hub. KRT</label><select name="hdkrt[]" class="form-control @error('hdkrt.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Kepala Keluarga','2'=>'Istri / Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua / Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('hdkrt.'.$index, optional($anggota)->hdkrt) == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('hdkrt.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>Hub. KK</label><select name="hdkk[]" class="form-control @error('hdkk.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Kepala Keluarga','2'=>'Istri / Suami','3'=>'Anak','4'=>'Menantu','5'=>'Cucu','6'=>'Orang Tua / Mertua','7'=>'Pembantu','8'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('hdkk.'.$index, optional($anggota)->hdkk) == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('hdkk.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-3"><label>No. Urut</label><input type="number" name="nuk[]" min="1" max="99" class="form-control @error('nuk.'.$index) is-invalid @enderror" value="{{ old('nuk.'.$index, optional($anggota)->nuk ?? ($index + 1)) }}">@error('nuk.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div><hr>
                        <div class="form-row">
                            <div class="form-group col-md-4"><label>Status Perkawinan</label><select name="status_perkawinan[]" class="form-control @error('status_perkawinan.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Belum Kawin','2'=>'Kawin / Nikah','3'=>'Cerai Hidup','4'=>'Cerai Mati'] as $key => $value)<option value="{{$key}}" @if(old('status_perkawinan.'.$index, optional($anggota)->status_perkawinan) == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('status_perkawinan.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-4"><label>Pendidikan Terakhir</label><select name="pendidikan_terakhir[]" class="form-control @error('pendidikan_terakhir.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Tidak / Belum Sekolah','2'=>'Tamat SD','3'=>'Tamat SMP','4'=>'Tamat SMA','5'=>'Tamat PT','6'=>'Tidak Sekolah'] as $key => $value)<option value="{{$key}}" @if(old('pendidikan_terakhir.'.$index, optional($anggota)->pendidikan_terakhir) == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('pendidikan_terakhir.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                            <div class="form-group col-md-4"><label>Status Pekerjaan</label><select name="status_pekerjaan[]" class="form-control recap-trigger @error('status_pekerjaan.'.$index) is-invalid @enderror"><option value="">Pilih...</option>@foreach(['1'=>'Bekerja','2'=>'Ibu Rumah Tangga','3'=>'Bersekolah','4'=>'Tidak / Belum Bekerja','5'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('status_pekerjaan.'.$index, optional($anggota)->status_pekerjaan) == $key) selected @endif>{{$value}}</option>@endforeach</select>@error('status_pekerjaan.'.$index)<div class="invalid-feedback">{{$message}}</div>@enderror</div>
                        </div>
                         <div class="form-row">
                            <div class="form-group col-md-4"><label>Jenis Pekerjaan</label><select name="jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'PNS/TNI/POLRI','2'=>'Karyawan/Honorer','3'=>'Wiraswasta','4'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('jenis_pekerjaan.'.$index, optional($anggota)->jenis_pekerjaan) == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                            <div class="form-group col-md-4"><label>Sub-Jenis Pekerjaan</label><select name="sub_jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'Aparatur','2'=>'Profesional','3'=>'Tenaga Harian','4'=>'Wira Usaha','5'=>'Lainnya'] as $key => $value)<option value="{{$key}}" @if(old('sub_jenis_pekerjaan.'.$index, optional($anggota)->sub_jenis_pekerjaan) == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                            <div class="form-group col-md-4"><label>Pendapatan per Bulan</label><select name="pendapatan_per_bulan[]" class="form-control"><option value="">Pilih...</option>@foreach(['1'=>'> 500 ribu','2'=>'> 1 juta','3'=>'> 2 juta','4'=>'> 4 juta','5'=>'Tdk Berpenghasilan'] as $key => $value)<option value="{{$key}}" @if(old('pendapatan_per_bulan.'.$index, optional($anggota)->pendapatan_per_bulan) == $key) selected @endif>{{$value}}</option>@endforeach</select></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
            <medium class="text-muted pr-3">
                Tambahkan semua anggota yang biasanya tinggal dan makan di rumah tangga ini.
            </medium>
            <button type="button" id="add-member-btn" class="btn btn-success flex-shrink-0">
                <i class="fas fa-plus mr-2"></i>Tambah Anggota
            </button>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4 w-100">
            <h1 class="h3 mb-0 text-gray-800">Verifikasi & Tanda Tangan</h1>
        </div>
        <div class="card shadow-sm mb-4 w-100">
            <div class="card-body">
                <div class="row align-items-stretch">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h6 class="font-weight-bold text-primary">Informasi Pendata</h6><hr class="mt-2">
                        <div class="form-group"><label for="verif_nama_pendata">Nama Lengkap Pendata</label><input type="text" name="verif_nama_pendata" id="verif_nama_pendata" value="{{ old('verif_nama_pendata', $item->verif_nama_pendata) }}" class="form-control @error('verif_nama_pendata') is-invalid @enderror" readonly></div>
                        <div class="form-group mb-md-0"><label for="verif_tgl_pembuatan">Tanggal Pengajuan</label><input type="date" name="verif_tgl_pembuatan" id="verif_tgl_pembuatan" value="{{ old('verif_tgl_pembuatan', \Carbon\Carbon::parse($item->verif_tgl_pembuatan)->format('Y-m-d')) }}" class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror"></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary">Tanda Tangan Pendata</h6><hr class="mt-2">
                        <div class="signature-box">
                            <div id="signature-preview" class="mb-2 d-flex align-items-center justify-content-center" style="flex-grow: 1;">
                                @if($item->ttd_pendata)
                                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="Tanda Tangan Saat Ini">
                                @else
                                    <p class="text-muted small">Belum ada tanda tangan</p>
                                @endif
                            </div>
                            <div class="text-center"><label for="ttd_pendata" class="btn btn-sm btn-outline-primary"><i class="fas fa-upload mr-2"></i> Ganti Tanda Tangan (Opsional)</label><input type="file" name="ttd_pendata" id="ttd_pendata" class="d-none @error('ttd_pendata') is-invalid @enderror" accept="image/png, image/jpeg">@error('ttd_pendata')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <hr class="w-100">
    <div class="w-100 d-flex justify-content-between align-items-center my-4">
        {{-- Bagian Kiri: Tombol Kembali --}}
        <div>
            {{-- Kode yang Benar (dengan parameter RT) --}}
<a href="{{ route('admin.tkw.rtshow', ['rt' => $item->rt]) }}" class="btn btn-outline-secondary">Kembali ke Riwayat</a>
        </div>

        {{-- Tombol ini sekarang memanggil modal --}}
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal" @if($item->status_validasi === 'validated') disabled @endif>
            <i class="fas fa-save mr-2"></i>Update Data & Kirim Ulang
        </button>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Konfirmasi Perubahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyimpan perubahan ini? Mohon pastikan semua data yang Anda masukkan sudah benar.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="confirm-update-btn" class="btn btn-primary">Ya, Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="member-template" style="display: none;">
    <div class="member-form card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Anggota Keluarga #REPLACE_INDEX</h6>
            <button type="button" class="btn btn-danger btn-sm remove-member-btn"><i class="fas fa-trash"></i></button>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6"><label>Nama Lengkap</label><input type="text" name="nama[]" class="form-control"></div>
                <div class="form-group col-md-6"><label>NIK</label><input type="text" name="nik[]" class="form-control" placeholder="16 Digit Angka"></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label>Jenis Kelamin</label><select name="kelamin[]" class="form-control"><option value="">Pilih...</option><option value="1">Laki-laki</option><option value="2">Perempuan</option></select></div>
                <div class="form-group col-md-3"><label>Hub. KRT</label><select name="hdkrt[]" class="form-control"><option value="">Pilih...</option><option value="1">Kepala Keluarga</option><option value="2">Istri / Suami</option><option value="3">Anak</option><option value="4">Menantu</option><option value="5">Cucu</option><option value="6">Orang Tua / Mertua</option><option value="7">Pembantu</option><option value="8">Lainnya</option></select></div>
                <div class="form-group col-md-3"><label>Hub. KK</label><select name="hdkk[]" class="form-control"><option value="">Pilih...</option><option value="1">Kepala Keluarga</option><option value="2">Istri / Suami</option><option value="3">Anak</option><option value="4">Menantu</option><option value="5">Cucu</option><option value="6">Orang Tua / Mertua</option><option value="7">Pembantu</option><option value="8">Lainnya</option></select></div>
                <div class="form-group col-md-3"><label>No. Urut</label><input type="number" name="nuk[]" min="1" max="99" class="form-control"></div>
            </div><hr>
            <div class="form-row">
                <div class="form-group col-md-4"><label>Status Perkawinan</label><select name="status_perkawinan[]" class="form-control"><option value="">Pilih...</option><option value="1">Belum Kawin</option><option value="2">Kawin / Nikah</option><option value="3">Cerai Hidup</option><option value="4">Cerai Mati</option></select></div>
                <div class="form-group col-md-4"><label>Pendidikan Terakhir</label><select name="pendidikan_terakhir[]" class="form-control"><option value="">Pilih...</option><option value="1">Tidak / Belum Sekolah</option><option value="2">Tamat SD</option><option value="3">Tamat SMP</option><option value="4">Tamat SMA</option><option value="5">Tamat PT</option><option value="6">Tidak Sekolah</option></select></div>
                <div class="form-group col-md-4"><label>Status Pekerjaan</label><select name="status_pekerjaan[]" class="form-control recap-trigger"><option value="">Pilih...</option><option value="1">Bekerja</option><option value="2">Ibu Rumah Tangga</option><option value="3">Bersekolah</option><option value="4">Tidak / Belum Bekerja</option><option value="5">Lainnya</option></select></div>
            </div>
             <div class="form-row">
                <div class="form-group col-md-4"><label>Jenis Pekerjaan</label><select name="jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option><option value="1">PNS/TNI/POLRI</option><option value="2">Karyawan/Honorer</option><option value="3">Wiraswasta</option><option value="4">Lainnya</option></select></div>
                <div class="form-group col-md-4"><label>Sub-Jenis Pekerjaan</label><select name="sub_jenis_pekerjaan[]" class="form-control"><option value="">Pilih...</option><option value="1">Aparatur</option><option value="2">Profesional</option><option value="3">Tenaga Harian</option><option value="4">Wira Usaha</option><option value="5">Lainnya</option></select></div>
                <div class="form-group col-md-4"><label>Pendapatan per Bulan</label><select name="pendapatan_per_bulan[]" class="form-control"><option value="">Pilih...</option><option value="1">> 500 ribu</option><option value="2">> 1 juta</option><option value="3">> 2 juta</option><option value="4">> 4 juta</option><option value="5">Tdk Berpenghasilan</option></select></div>
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
    const templateHtml = document.getElementById('member-template').innerHTML;
    const ttdInput = document.getElementById('ttd_pendata');
    const signaturePreview = document.getElementById('signature-preview');
    
    function updateAllMemberNumbers() {
        const forms = container.querySelectorAll('.member-form');
        forms.forEach((form, index) => {
            const header = form.querySelector('h6');
            if(header) {
                header.textContent = `Anggota Keluarga #${index + 1}`;
            }
            const nukInput = form.querySelector('input[name="nuk[]"]');
            if (nukInput && !nukInput.value) { // Hanya isi jika kosong
                nukInput.value = index + 1;
            }
            // Tombol hapus hanya bisa untuk anggota kedua dan seterusnya
            const removeBtn = form.querySelector('.remove-member-btn');
            if(removeBtn) removeBtn.style.display = forms.length > 1 ? 'block' : 'none';
        });
    }

    function addMemberForm() {
        const memberCount = container.querySelectorAll('.member-form').length;
        let newFormHtml = templateHtml.replace(/REPLACE_INDEX/g, memberCount + 1);
        
        const div = document.createElement('div');
        div.innerHTML = newFormHtml;
        container.appendChild(div.firstElementChild);
        
        updateAllMemberNumbers();
        updateRecap();
    }

    function updateRecap() {
        const memberForms = container.querySelectorAll('.member-form');
        let working = 0, notWorking = 0, schooling = 0;
        memberForms.forEach(form => {
            const status = form.querySelector('select[name="status_pekerjaan[]"]').value;
            if (status === '1') working++;
            else if (status === '3') schooling++;
            else if (['2', '4', '5'].includes(status) && status !== '') notWorking++;
        });
        document.getElementById('recap_jart').value = memberForms.length;
        document.getElementById('recap_jart_ab').value = working;
        document.getElementById('recap_jart_tb').value = notWorking;
        document.getElementById('recap_jart_ms').value = schooling;
    }

    // Event listener untuk tombol 'Tambah Anggota'
    addButton.addEventListener('click', addMemberForm);

    // Event listener untuk hapus, validasi NIK, dan update rekap di dalam kontainer
    container.addEventListener('click', e => {
        if (e.target.closest('.remove-member-btn')) {
            e.preventDefault();
            e.target.closest('.member-form').remove();
            updateAllMemberNumbers();
            updateRecap();
        }
    });

    container.addEventListener('input', e => {
        if (e.target.matches('input[name="nik[]"]')) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 16);
        }
    });

    container.addEventListener('change', e => { 
        if (e.target.matches('.recap-trigger')) {
            updateRecap();
        }
    });

    // Event listener untuk preview tanda tangan
    ttdInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('signature-preview');
        previewContainer.innerHTML = ''; // Kosongkan preview
        if(!file) { 
            // Jika batal memilih, tampilkan kembali TTD lama jika ada
            const oldSignature = '{{ $item->ttd_pendata ? asset('storage/ttd/pendata/'.$item->ttd_pendata) : '' }}';
            if(oldSignature) {
                const img = document.createElement('img');
                img.src = oldSignature;
                previewContainer.appendChild(img);
            } else {
                previewContainer.innerHTML = '<p class="text-muted small">Belum ada tanda tangan</p>';
            }
            return; 
        }
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });

    const confirmButton = document.getElementById('confirm-update-btn');
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            document.getElementById('main-form').submit();
        });
    }

    // Jalankan fungsi ini saat halaman pertama kali dimuat
    updateAllMemberNumbers();
    updateRecap();
});
</script>
@endpush