<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SID Panggung - Digital Information System">
    <meta name="author" content="SID Panggung Team">

    <title>SID Panggung - Dashboard</title>

    <!-- Custom fonts -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #3a56b1;
            --secondary-color: #858796;
            --accent-color: #36b9cc;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        
        .card-header h4 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.3rem;
        }
        
        .card-body {
            padding: 1.75rem;
        }
        
        .form-control {
            border-radius: 6px;
            padding: 0.75rem 1rem;
            border: 1px solid #e3e6f0;
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        label {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
        }
        
        .btn {
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            box-shadow: 0 3px 5px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 5px 10px rgba(78, 115, 223, 0.4);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            box-shadow: 0 3px 5px rgba(133, 135, 150, 0.3);
        }
        
        .btn-secondary:hover {
            background: #6b6d7d;
            box-shadow: 0 5px 10px rgba(133, 135, 150, 0.4);
        }
        
        .form-section {
            position: relative;
            padding: 1.5rem;
            border-radius: 6px;
            background-color: #f8f9fc;
            border-top: 2px solid var(--primary-color);
            margin-bottom: 2rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            position: absolute;
            top: -12px;
            left: 20px;
            background-color: white;
            padding: 0 15px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .scroll-to-top {
            background: var(--primary-color);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(78, 115, 223, 0.4);
        }
        
        .toast-success {
            background-color: var(--success-color);
            color: white;
            border-radius: 6px;
        }
        
        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .progress-step {
            flex: 1;
            text-align: center;
            padding: 15px;
            position: relative;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #858796;
            transition: all 0.3s ease;
        }
        
        .active-step .step-number {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 3px 10px rgba(78, 115, 223, 0.4);
        }
        
        .step-name {
            font-size: 1rem;
            font-weight: 700;
            color: #858796;
        }
        
        .active-step .step-name {
            color: var(--primary-color);
        }
        
        .step-bar {
            position: absolute;
            top: 34px;
            height: 3px;
            width: 100%;
            background-color: #e3e6f0;
            left: 50%;
            z-index: -1;
        }
        
        .progress-step:last-child .step-bar {
            display: none;
        }
        
        .active-step .step-bar {
            background-color: var(--primary-color);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: none;
        }
        
        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }
        
        .form-row {
            margin-right: -10px;
            margin-left: -10px;
        }
        
        .form-row > .col,
        .form-row > [class*="col-"] {
            padding-right: 10px;
            padding-left: 10px;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (min-width: 992px) {
            .custom-container {
                width: 90%;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .card-body {
                padding: 2rem;
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

                <!-- Begin Page Content -->
                <div class="container-fluid py-4 fade-in custom-container">
                    
                    <!-- Progress Indicator -->
                    <div class="progress-indicator mb-4">
                        <div class="progress-step active-step">
                            <div class="step-number">1</div>
                            <div class="step-name">Informasi Form</div>
                            <div class="step-bar"></div>
                        </div>
                        <div class="progress-step">
                            <div class="step-number">2</div>
                            <div class="step-name">Data Personal</div>
                            <div class="step-bar"></div>
                        </div>
                        <div class="progress-step">
                            <div class="step-number">3</div>
                            <div class="step-name">Informasi Kontak</div>
                            <div class="step-bar"></div>
                        </div>
                        <div class="progress-step">
                            <div class="step-number">4</div>
                            <div class="step-name">Konfirmasi</div>
                        </div>
                    </div>

                    <div class="card shadow mb-5">
                        <div class="card-header py-3 bg-primary">
                            <h4 class="m-0 font-weight-bold text-white">
                                Informasi Form
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tkw.step1') }}" method="POST">
                                @csrf
                                
                                <div class="form-section mb-4">
                                    <div class="section-title">Lokasi</div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <label for="provinsi">
                                                Provinsi
                                            </label>
                                            <input type="text" name="provinsi" id="provinsi"
                                                value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                                                class="form-control @error('provinsi') is-invalid @enderror"
                                                placeholder="Masukkan nama provinsi">
                                            @error('provinsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <label for="kabupaten">
                                                Kabupaten
                                            </label>
                                            <input type="text" name="kabupaten" id="kabupaten"
                                                value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                                                class="form-control @error('kabupaten') is-invalid @enderror"
                                                placeholder="Masukkan nama kabupaten">
                                            @error('kabupaten')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <label for="kecamatan">
                                                Kecamatan
                                            </label>
                                            <input type="text" name="kecamatan" id="kecamatan"
                                                value="{{ old('kecamatan', $data['kecamatan'] ?? '') }}"
                                                class="form-control @error('kecamatan') is-invalid @enderror"
                                                placeholder="Masukkan nama kecamatan">
                                            @error('kecamatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <label for="desa">
                                                Desa
                                            </label>
                                            <input type="text" name="desa" id="desa"
                                
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
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-white font-weight-bold" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
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

    <!-- Success Modal -->
    @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title font-weight-bold" id="successModalLabel">
                        <i class="fas fa-check-circle mr-2"></i> Sukses!
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <p class="lead text-center mb-0">{{ session('success') }}</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast for success notification -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast toast-success hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
            <div class="toast-header">
                <strong class="mr-auto text-success"><i class="fas fa-check-circle mr-1"></i> Sukses</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Data berhasil disimpan.
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

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-format RT and RW fields to have leading zeros
            $("#rt, #rw").on("blur", function() {
                let value = $(this).val();
                if (value !== "") {
                    value = ("000" + value).slice(-3);
                    $(this).val(value);
                }
            });
            
            // Set today's date as default for date fields if empty
            if (!$("#tgl_pembuatan").val()) {
                const today = new Date();
                const formattedDate = today.toISOString().substr(0, 10);
                $("#tgl_pembuatan").val(formattedDate);
            }
            
            // Form validation
            $("form").submit(function(e) {
                let isValid = true;
                
                $("input[required]").each(function() {
                    if ($(this).val() === "") {
                        $(this).addClass("is-invalid");
                        isValid = false;
                    } else {
                        $(this).removeClass("is-invalid");
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });
            
            // Focus animation for input fields
            $(".form-control").focus(function() {
                $(this).parent().addClass("animate__animated animate__pulse");
            }).blur(function() {
                $(this).parent().removeClass("animate__animated animate__pulse");
            });
        });
    </script>

    {{-- Script untuk memanggil modal saat ada flash success --}}
    @if(session('success'))
    <script>
    $(function(){
        $('#successModal').modal('show');
    });
    </script>
    @endif

</body>

</html>