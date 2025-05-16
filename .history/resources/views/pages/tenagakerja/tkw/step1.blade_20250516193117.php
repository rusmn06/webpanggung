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
    
    <!-- Custom additional styles -->
    <style>
        /* Full-content layout styles */
        #content {
            width: 100%;
            padding: 0;
        }
        
        .container-fluid {
            padding: 0;
            margin: 0;
            max-width: 100%;
            height: 100%;
        }
        
        .content-section {
            background-color: #f8f9fc;
            min-height: calc(100vh - 4.375rem - 80px); /* Account for topbar and footer */
            padding: 1.5rem;
        }
        
        .header-section {
            background-color: #4e73df;
            color: white;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0;
        }
        
        .form-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .small-input {
            max-width: 120px;
        }
        
        /* Customize sidebar for full-width layout */
        .sidebar {
            position: fixed;
            height: 100vh;
            z-index: 1;
        }
        
        .sidebar .nav-item .nav-link {
            padding: 0.75rem 1rem;
        }
        
        /* Adjust content wrapper when sidebar is toggled */
        .sidebar.toggled ~ #content-wrapper {
            margin-left: 6.5rem;
            width: calc(100% - 6.5rem);
        }
        
        #content-wrapper {
            margin-left: 14rem;
            width: calc(100% - 14rem);
            transition: margin-left 0.25s ease-in-out, width 0.25s ease-in-out;
        }
        
        /* Footer styles */
        footer.sticky-footer {
            padding: 1rem;
            margin-top: 0;
        }
        
        /* Form specific styles */
        .form-label {
            font-weight: 600;
            color: #5a5c69;
        }
        
        .btn-action {
            padding: 0.375rem 1.5rem;
        }
        
        /* Remove unnecessary padding/margins */
        .row {
            margin-right: 0;
            margin-left: 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar.toggled ~ #content-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
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

                <!-- Begin Page Content - Full Width Layout -->
                <div class="container-fluid">
                    <!-- Header Section -->
                    <div class="header-section">
                        <h4 class="m-0 font-weight-bold">Informasi Form</h4>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="content-section">
                        <!-- Form Section -->
                        <div class="form-section">
                            <form action="{{ route('tkw.step1') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- First row - Location information (4 columns) -->
                                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                                        <label for="provinsi" class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi" id="provinsi"
                                            value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                                            class="form-control @error('provinsi') is-invalid @enderror">
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                                        <label for="kabupaten" class="form-label">Kabupaten</label>
                                        <input type="text" name="kabupaten" id="kabupaten"
                                            value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                                            class="form-control @error('kabupaten') is-invalid @enderror">
                                        @error('kabupaten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                                        <label for="kecamatan" class="form-label">Kecamatan</label>
                                        <input type="text" name="kecamatan" id="kecamatan"
                                            value="{{ old('kecamatan', $data['kecamatan'] ?? '') }}"
                                            class="form-control @error('kecamatan') is-invalid @enderror">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                                        <label for="desa" class="form-label">Desa</label>
                                        <input type="text" name="desa" id="desa"
                                            value="{{ old('desa', $data['desa'] ?? '') }}"
                                            class="form-control @error('desa') is-invalid @enderror">
                                        @error('desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Second row - RT/RW and date -->
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 mb-3">
                                        <label for="rt" class="form-label">RT (000)</label>
                                        <input type="text" name="rt" id="rt"
                                            value="{{ old('rt', $data['rt'] ?? '') }}"
                                            class="form-control small-input @error('rt') is-invalid @enderror">
                                        @error('rt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 mb-3">
                                        <label for="rw" class="form-label">RW (000)</label>
                                        <input type="text" name="rw" id="rw"
                                            value="{{ old('rw', $data['rw'] ?? '') }}"
                                            class="form-control small-input @error('rw') is-invalid @enderror">
                                        @error('rw')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 mb-3">
                                        <label for="tgl_pembuatan" class="form-label">Tanggal Pembuatan</label>
                                        <input type="date" name="tgl_pembuatan" id="tgl_pembuatan"
                                            value="{{ old('tgl_pembuatan', $data['tgl_pembuatan'] ?? '') }}"
                                            class="form-control @error('tgl_pembuatan') is-invalid @enderror">
                                        @error('tgl_pembuatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Third row - Nama Pendata & Responden -->
                                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                                        <label for="nama_pendata" class="form-label">Nama Pendata</label>
                                        <input type="text" name="nama_pendata" id="nama_pendata"
                                            value="{{ old('nama_pendata', $data['nama_pendata'] ?? '') }}"
                                            class="form-control @error('nama_pendata') is-invalid @enderror">
                                        @error('nama_pendata')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                                        <label for="nama_responden" class="form-label">Nama Responden</label>
                                        <input type="text" name="nama_responden" id="nama_responden"
                                            value="{{ old('nama_responden', $data['nama_responden'] ?? '') }}"
                                            class="form-control @error('nama_responden') is-invalid @enderror">
                                        @error('nama_responden')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-action">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-action">
                                        Next <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Page Content -->

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

    <!-- Script untuk optimized sidebar -->
    <script>
        $(document).ready(function() {
            // Auto-toggle sidebar for smaller screens
            function adjustSidebar() {
                if ($(window).width() < 992) {
                    $("body").addClass("sidebar-toggled");
                    $(".sidebar").addClass("toggled");
                } else {
                    if ($(".sidebar").hasClass("toggled")) {
                        // Only un-toggle if it was previously toggled by this function
                        if (sessionStorage.getItem("sidebarState") !== "manual") {
                            $("body").removeClass("sidebar-toggled");
                            $(".sidebar").removeClass("toggled");
                        }
                    }
                }
            }
            
            // Run on page load
            adjustSidebar();
            
            // Run on window resize
            $(window).resize(function() {
                adjustSidebar();
            });
            
            // Track manual sidebar toggles
            $("#sidebarToggle, #sidebarToggleTop").click(function() {
                sessionStorage.setItem("sidebarState", "manual");
            });
        });
    </script>

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