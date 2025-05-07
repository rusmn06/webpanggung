<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SID Panggung - Verifikasi & Validasi</title>

    <!-- Custom fonts for this template-->  
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .signature-pad { border: 1px solid #ced4da; border-radius: .25rem; }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">
        @include('layouts.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.navbar')
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Verifikasi</h1>
                    </div>
                    <form action="{{ route('tkw.step4') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="verif_tgl_pembuatan">Tanggal Verifikasi</label>
                                <input type="date" name="verif_tgl_pembuatan" id="verif_tgl_pembuatan"
                                    value="{{ old('verif_tgl_pembuatan', $data['verif_tgl_pembuatan'] ?? '') }}"
                                    class="form-control @error('verif_tgl_pembuatan') is-invalid @enderror">
                                @error('verif_tgl_pembuatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="verif_nama_pendata">Nama Pendata</label>
                                <input type="text" name="verif_nama_pendata" id="verif_nama_pendata"
                                    value="{{ old('verif_nama_pendata', $data['verif_nama_pendata'] ?? '') }}"
                                    class="form-control @error('verif_nama_pendata') is-invalid @enderror">
                                @error('verif_nama_pendata')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-4">
                                <label for="ttd_file">Upload Tanda Tangan (PNG | Max:2MB)</label>
                                <input type="file" name="ttd_pendata" id="ttd_pendata"
                                    accept="image/png"
                                    class="form-control @error('ttd_pendata') is-invalid @enderror">
                                @error('ttd_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="admin_nama_kepaladusun">Nama Kepala Dusun</label>
                                <input type="text" name="admin_nama_kepaladusun" id="admin_nama_kepaladusun"
                                    value="{{ old('admin_nama_kepaladusun', $data['admin_nama_kepaladusun'] ?? '') }}"
                                    class="form-control @error('admin_nama_kepaladusun') is-invalid @enderror">
                                @error('admin_nama_kepaladusun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Complete</button>
                        </div>
                    </form>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Scripts -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('ttd_file');
    const preview = document.getElementById('preview-ttd');

    input.addEventListener('change', () => {
        const file = input.files[0];
        preview.innerHTML = ''; // clear preview

        if (!file) return;

        // Validate file type
        if (file.type !== 'image/png') {
            alert('File harus berformat PNG.');
            input.value = ''; // reset input
            return;
        }

        // Validate size (2MB = 2 * 1024 * 1024)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = ''; // reset input
            return;
        }

        // Preview PNG
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
</body>

</html>
