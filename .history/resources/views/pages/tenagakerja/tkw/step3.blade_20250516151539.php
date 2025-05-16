<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SID Panggung - Rekapitulasi</title>

    <!-- Custom fonts for this template-->  
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Rekapitulasi</h1>
                    </div>

                    <!-- Form Step 3 -->
                    <form action="{{ route('tkw.step3') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <label for="jart">Jumlah Anggota Rumah Tangga</label>
                                <input type="number" name="jart" id="jart" min="0" max="99"
                                    value="{{ old('jart', $data['jart'] ?? '') }}"
                                    class="form-control @error('jart') is-invalid @enderror">
                                @error('jart')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="jart_ab">Jumlah Anggota Rumah Tangga Aktif Bekerja</label>
                                <input type="number" name="jart_ab" id="jart_ab" min="0" max="99"
                                    value="{{ old('jart_ab', $data['jart_ab'] ?? '') }}"
                                    class="form-control @error('jart_ab') is-invalid @enderror">
                                @error('jart_ab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="jart_tb">Jumlah Anggota Rumah Tangga Tidak / Belum Bekerja</label>
                                <input type="number" name="jart_tb" id="jart_tb" min="0" max="99"
                                    value="{{ old('jart_tb', $data['jart_tb'] ?? '') }}"
                                    class="form-control @error('jart_tb') is-invalid @enderror">
                                @error('jart_tb')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="jart_ms">Jumlah Anggota Rumah Tangga MS</label>
                                <input type="number" name="jart_ms" id="jart_ms" min="0" max="99"
                                    value="{{ old('jart_ms', $data['jart_ms'] ?? '') }}"
                                    class="form-control @error('jart_ms') is-invalid @enderror">
                                @error('jart_ms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <label for="jpr2rtp">JPR2RTP</label>
                                <select name="jpr2rtp" id="jpr2rtp"
                                    class="form-control @error('jpr2rtp') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @for($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ old('jpr2rtp', $data['jpr2rtp'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('jpr2rtp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
