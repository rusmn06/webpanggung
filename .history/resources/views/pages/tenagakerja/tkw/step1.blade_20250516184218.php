<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SID Panggung - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
            <div class="container-fluid py-4">

                <div class="card shadow rounded mb-5" style="margin: auto; width: 100%; max-width: 95%;">
                    <div class="card-header py-3 bg-primary">
                        <h4 class="m-0 font-weight-bold text-white">Informasi Form</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tkw.step1') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="provinsi">Provinsi</label>
                                    <input type="text" name="provinsi" id="provinsi"
                                        value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                                        class="form-control @error('provinsi') is-invalid @enderror">
                                    @error('provinsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="kabupaten">Kabupaten</label>
                                    <input type="text" name="kabupaten" id="kabupaten"
                                        value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                                        class="form-control @error('kabupaten') is-invalid @enderror">
                                    @error('kabupaten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
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

            </div>
            <!-- End of Page Content -->





            <!-- Footer -->
            @include('layouts.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap untuk flash success -->
    @if(session('success'))
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-success" id="exampleModalLabel">Sukses!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            {{ session('success') }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
    </div>
    @endif

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    {{-- Script untuk memanggil modal saat ada flash success --}}
    @if(session('success'))
    <script>
    $(function(){
        $('#exampleModal').modal('show');
    });
    </script>
    @endif

</body>

</html>