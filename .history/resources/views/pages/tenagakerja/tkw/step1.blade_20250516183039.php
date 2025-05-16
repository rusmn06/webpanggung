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
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        
        .card-header h4 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            border: 1px solid #e3e6f0;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border: none;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(78, 115, 223, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(to right, var(--secondary-color), #6b6d7d);
            border: none;
            box-shadow: 0 4px 6px rgba(133, 135, 150, 0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(to right, #6b6d7d, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(133, 135, 150, 0.4);
        }
        
        .form-section {
            position: relative;
            padding: 1.5rem;
            border-radius: 10px;
            background-color: rgba(78, 115, 223, 0.03);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 2rem;
        }
        
        .section-title {
            position: absolute;
            top: -15px;
            left: 20px;
            background-color: white;
            padding: 0 15px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .scroll-to-top {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }
        
        .scroll-to-top:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-3px);
        }
        
        .toast-success {
            background-color: var(--success-color);
            color: white;
            border-radius: 10px;
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
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }
        
        .step-name {
            font-size: 0.85rem;
            font-weight: 600;
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
        
        .input-icon-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #b7b9cc;
        }
        
        .input-with-icon {
            padding-left: 45px;
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: none;
        }
        
        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        /* Card hover effect */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7);
            }
            
            70% {
                box-shadow: 0 0 0 10px rgba(78, 115, 223, 0);
            }
            
            100% {
                box-shadow: 0 0 0 0 rgba(78, 115, 223, 0);
            }
        }
        
        .highlight-section:hover {
            animation: pulse 1.5s infinite;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                <div class="container-fluid py-4 fade-in">
                    
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

                    <div class="card shadow rounded mb-5 highlight-section">
                        <div class="card-header py-4 bg-gradient-primary">
                            <h4 class="m-0 font-weight-bold text-white">
                                <i class="fas fa-file-alt mr-2"></i> Informasi Form
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tkw.step1') }}" method="POST">
                                @csrf
                                
                                <div class="form-section mb-4">
                                    <div class="section-title">Lokasi</div>
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <label for="provinsi">
                                                <i class="fas fa-map-marker-alt mr-1 text-primary"></i> Provinsi
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="provinsi" id="provinsi"
                                                    value="{{ old('provinsi', $data['provinsi'] ?? '') }}"
                                                    class="form-control @error('provinsi') is-invalid @enderror"
                                                    placeholder="Masukkan nama provinsi">
                                                @error('provinsi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="kabupaten">
                                                <i class="fas fa-city mr-1 text-primary"></i> Kabupaten
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="kabupaten" id="kabupaten"
                                                    value="{{ old('kabupaten', $data['kabupaten'] ?? '') }}"
                                                    class="form-control @error('kabupaten') is-invalid @enderror"
                                                    placeholder="Masukkan nama kabupaten">
                                                @error('kabupaten')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="kecamatan">
                                                <i class="fas fa-landmark mr-1 text-primary"></i> Kecamatan
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="kecamatan" id="kecamatan"
                                                    value="{{ old('kecamatan', $data['kecamatan'] ?? '') }}"
                                                    class="form-control @error('kecamatan') is-invalid @enderror"
                                                    placeholder="Masukkan nama kecamatan">
                                                @error('kecamatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="desa">
                                                <i class="fas fa-home mr-1 text-primary"></i> Desa
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="desa" id="desa"
                                                    value="{{ old('desa', $data['desa'] ?? '') }}"
                                                    class="form-control @error('desa') is-invalid @enderror"
                                                    placeholder="Masukkan nama desa">
                                                @error('desa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="rt">
                                                <i class="fas fa-map-pin mr-1 text-primary"></i> RT
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="rt" id="rt"
                                                    value="{{ old('rt', $data['rt'] ?? '') }}"
                                                    class="form-control @error('rt') is-invalid @enderror"
                                                    placeholder="000">
                                                @error('rt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="rw">
                                                <i class="fas fa-map-pin mr-1 text-primary"></i> RW
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="rw" id="rw"
                                                    value="{{ old('rw', $data['rw'] ?? '') }}"
                                                    class="form-control @error('rw') is-invalid @enderror"
                                                    placeholder="000">
                                                @error('rw')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <div class="section-title">Informasi Pendataan</div>
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <label for="tgl_pembuatan">
                                                <i class="fas fa-calendar-alt mr-1 text-primary"></i> Tanggal Pembuatan
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="date" name="tgl_pembuatan" id="tgl_pembuatan"
                                                    value="{{ old('tgl_pembuatan', $data['tgl_pembuatan'] ?? '') }}"
                                                    class="form-control @error('tgl_pembuatan') is-invalid @enderror">
                                                @error('tgl_pembuatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="nama_pendata">
                                                <i class="fas fa-user-edit mr-1 text-primary"></i> Nama Pendata
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="nama_pendata" id="nama_pendata"
                                                    value="{{ old('nama_pendata', $data['nama_pendata'] ?? '') }}"
                                                    class="form-control @error('nama_pendata') is-invalid @enderror"
                                                    placeholder="Masukkan nama pendata">
                                                @error('nama_pendata')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="nama_responden">
                                                <i class="fas fa-user mr-1 text-primary"></i> Nama Responden
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <input type="text" name="nama_responden" id="nama_responden"
                                                    value="{{ old('nama_responden', $data['nama_responden'] ?? '') }}"
                                                    class="form-control @error('nama_responden') is-invalid @enderror"
                                                    placeholder="Masukkan nama responden">
                                                @error('nama_responden')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary animate__animated animate__pulse animate__infinite">
                                        Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
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