@extends('layouts.main')

{{-- Judul halaman diubah menjadi mode edit --}}
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
    {{-- Form diubah untuk mengarah ke route 'update' dengan method PUT --}}
    <form action="{{ route('tenagakerja.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
        @csrf
        @method('PUT')

        <div class="row justify-content-center">
            <div class="col-xl-11">

                @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <h5 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Gagal Memperbarui Data!</h5>
                    <p>Terdapat kesalahan pada isian Anda. Silakan periksa kembali semua kolom yang ditandai merah di bawah ini.</p>
                </div>
                @endif
                
                @if (session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif
                
                {{-- KOTAK PERINGATAN JIKA DATA TERKUNCI --}}
                @if(in_array($item->status_validasi, ['validated', 'rejected']))
                <div class="alert alert-warning mb-4 shadow-sm">
                    <h5 class="alert-heading font-weight-bold"><i class="fas fa-lock mr-2"></i>Data Terkunci</h5>
                    <p class="mb-0">Pengajuan ini telah <strong>{{ $item->status_validasi_text }}</strong> dan tidak dapat diubah lagi. Untuk permintaan perubahan, silakan hubungi administrator.</p>
                </div>
                @endif

                {{-- Fieldset untuk menonaktifkan semua form jika data terkunci --}}
                <fieldset @if(in_array($item->status_validasi, ['validated', 'rejected'])) disabled @endif>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Informasi & Rekapitulasi</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Pengajuan & Lokasi</h6></div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- Semua 'value' diisi dengan data dari $item --}}
                                        <div class="col-md-4 mb-3"><label for="provinsi">Provinsi</label><input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $item->provinsi) }}" class="form-control @error('provinsi') is-invalid @enderror">@error('provinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-4 mb-3"><label for="kabupaten">Kabupaten</label><input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $item->kabupaten) }}" class="form-control @error('kabupaten') is-invalid @enderror">@error('kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-4 mb-3"><label for="kecamatan">Kecamatan</label><input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', $item->kecamatan) }}" class="form-control @error('kecamatan') is-invalid @enderror">@error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-4 mb-3"><label for="desa">Desa/Kelurahan</label><input type="text" name="desa" id="desa" value="{{ old('desa', $item->desa) }}" class="form-control @error('desa') is-invalid @enderror">@error('desa')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-2 mb-3"><label for="rt">RT</label><input type="number" name="rt" id="rt" value="{{ old('rt', $item->rt) }}" class="form-control @error('rt') is-invalid @enderror" min="0">@error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-2 mb-3"><label for="rw">RW</label><input type="number" name="rw" id="rw" value="{{ old('rw', $item->rw) }}" class="form-control @error('rw') is-invalid @enderror" min="0">@error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                        <div class="col-md-4 mb-3"><label for="tgl_pembuatan">Tgl. Pembuatan</label><input type="date" name="tgl_pembuatan" id="tgl_pembuatan" value="{{ old('tgl_pembuatan', $item->tgl_pembuatan) }}" class="form-control @error('tgl_pembuatan') is-invalid @enderror">@error('tgl_pembuatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
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
                        {{-- Anggota keluarga yang sudah ada akan dirender di sini oleh Blade --}}
                        @foreach (old('nama', $item->anggotaKeluarga->pluck('nama')) as $index)
                            @php
                                // Ambil data anggota dari $item jika tidak ada old input
                                $anggota = $item->anggotaKeluarga[$index] ?? null;
                            @endphp
                            <div class="member-form card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Anggota Keluarga #{{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-danger btn-sm remove-member-btn"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body">
                                     <div class="form-row">
                                        {{-- Mengisi value untuk setiap anggota --}}
                                        <div class="form-group col-md-6"><label>Nama Lengkap</label><input type="text" name="nama[]" class="form-control" value="{{ old('nama.'.$index, $anggota->nama ?? '') }}"></div>
                                        <div class="form-group col-md-6"><label>NIK</label><input type="text" name="nik[]" class="form-control" value="{{ old('nik.'.$index, $anggota->nik ?? '') }}"></div>
                                    </div>
                                    {{-- ... Lakukan hal yang sama untuk semua field anggota ... --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="w-100">
                    <div class="w-100 d-flex justify-content-end align-items-center mb-4">
                        <button type="button" id="add-member-btn" class="btn btn-success flex-shrink-0"><i class="fas fa-plus mr-2"></i>Tambah Anggota</button>
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
                                    <div class="form-group mb-md-0"><label for="verif_tgl_pembuatan">Tanggal Pengajuan</label><input type="date" name="verif_tgl_pembuatan" id="verif_tgl_pembuatan" value="{{ old('verif_tgl_pembuatan', $item->verif_tgl_pembuatan) }}" class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror"></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-primary">Tanda Tangan Pendata</h6><hr class="mt-2">
                                    <div class="signature-box">
                                        <div id="signature-preview" class="mb-2 d-flex align-items-center justify-content-center" style="flex-grow: 1;">
                                            {{-- Menampilkan TTD yang sudah ada --}}
                                            @if($item->ttd_pendata)
                                                <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="Tanda Tangan Saat Ini">
                                            @else
                                                <p class="text-muted small">Belum ada tanda tangan</p>
                                            @endif
                                        </div>
                                        {{-- Label diubah menjadi 'Ganti' --}}
                                        <div class="text-center"><label for="ttd_pendata" class="btn btn-sm btn-outline-primary"><i class="fas fa-upload mr-2"></i> Ganti Tanda Tangan (Opsional)</label><input type="file" name="ttd_pendata" id="ttd_pendata" class="d-none @error('ttd_pendata') is-invalid @enderror" accept="image/png, image/jpeg">@error('ttd_pendata')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset> {{-- Akhir dari fieldset --}}

                <hr class="w-100">
                <div class="w-100 d-flex justify-content-end my-4">
                    <a href="{{ route('tenagakerja.show', $item->id) }}" class="btn btn-secondary mr-3">Batal</a>
                    {{-- Tombol simpan dinonaktifkan jika data terkunci --}}
                    <button type="submit" class="btn btn-primary btn-lg" @if(in_array($item->status_validasi, ['validated', 'rejected'])) disabled @endif>
                        <i class="fas fa-save mr-2"></i>Update Data Pengajuan
                    </button>
                </div>
                
            </div>
        </div>
    </form>
    
    {{-- Template untuk menambah anggota baru, disembunyikan --}}
    <div id="member-template" style="display: none;">
        {{-- Kode template anggota sama persis seperti di halaman create --}}
    </div>
@endsection

@push('scripts')
<script>
// JavaScript di halaman edit lebih sederhana karena tidak perlu localStorage
document.addEventListener('DOMContentLoaded', function () {
    // ... (kode untuk update rekapitulasi, tambah/hapus anggota, dan preview TTD sama seperti sebelumnya)
    // ... Anda bisa salin dari file create.blade.php
});
</script>
@endpush