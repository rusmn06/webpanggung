@extends('layouts.main')

@section('title', 'Form Step 4 - Verifikasi & Validasi')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Verifikasi & Validasi</h1>
    </div>

    <!-- Card Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('tkw.step4') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="verif_nama_pendata">Nama Pendata</label>
                        <input type="text" name="verif_nama_pendata" id="verif_nama_pendata"
                            value="{{ old('verif_nama_pendata', $data['verif_nama_pendata'] ?? '') }}"
                            class="form-control @error('verif_nama_pendata') is-invalid @enderror">
                        @error('verif_nama_pendata')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="admin_nama_kepaladusun">Nama Kepala Dusun</label>
                        <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                            value="{{ old('admin_nama_kepaladusun', $data['admin_nama_kepaladusun'] ?? '') }}"
                            class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                        @error('admin_nama_kepaladusun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="verif_tgl_pembuatan">Tanggal Verifikasi</label>
                        <input type="date" name="verif_tgl_pembuatan" id="verif_tgl_pembuatan"
                            value="{{ old('verif_tgl_pembuatan', $data['verif_tgl_pembuatan'] ?? '') }}"
                            class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror">
                        @error('verif_tgl_pembuatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="ttd_pendata">Upload Tanda Tangan (PNG | Max: 2MB)</label>
                        <input type="file" name="ttd_pendata" id="ttd_pendata" accept="image/png"
                            class="form-control @error('ttd_pendata') is-invalid @enderror">
                        @error('ttd_pendata')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="preview-ttd" class="mt-3"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('tkw.step3') }}" class="tn btn-outline-secondary btn-back">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Next <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Signature Pad Preview Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('ttd_pendata');
        const preview = document.getElementById('preview-ttd');

        input.addEventListener('change', () => {
            const file = input.files[0];
            preview.innerHTML = ''; // clear preview

            if (!file) return;

            if (file.type !== 'image/png') {
                alert('File harus berformat PNG.');
                input.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview Tanda Tangan';
                img.style.maxWidth = '300px';
                img.classList.add('img-thumbnail');
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
    </script>
@endsection
