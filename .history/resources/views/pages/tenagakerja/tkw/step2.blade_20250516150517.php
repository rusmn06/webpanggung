<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SID Panggung - Identitas Responden</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Identitas Responden</h1>
                    </div>

                    <!-- Form Step 2 -->
                    <form action="{{ route('tkw.step2') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $data['nama'] ?? '') }}"
                                    class="form-control @error('nama') is-invalid @enderror">
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="nik">NIK</label>
                                <input type="text" name="nik" id="nik"
                                    value="{{ old('nik', $data['nik'] ?? '') }}"
                                    class="form-control @error('nik') is-invalid @enderror">
                                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 mb-3">
    <label for="hdkrt">Hubungan Dengan Kepala Rumah Tangga</label>
    <select name="hdkrt" id="hdkrt"
        class="form-control @error('hdkrt') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('hdkrt', $data['hdkrt'] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
        <option value="2" {{ old('hdkrt', $data['hdkrt'] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
        <option value="3" {{ old('hdkrt', $data['hdkrt'] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
        <option value="4" {{ old('hdkrt', $data['hdkrt'] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
        <option value="5" {{ old('hdkrt', $data['hdkrt'] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
        <option value="6" {{ old('hdkrt', $data['hdkrt'] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
        <option value="7" {{ old('hdkrt', $data['hdkrt'] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
        <option value="8" {{ old('hdkrt', $data['hdkrt'] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('hdkrt')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>


                            <div class="col-lg-3 mb-3">
                                <label for="nuk">Nomor Urut Keluarga</label>
                                <select name="nuk" id="nuk"
                                    class="form-control @error('nuk') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @for($i = 1; $i <= 2; $i++)
                                        <option value="{{ $i }}" {{ old('nuk', $data['nuk'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('nuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-lg-3 mb-3">
    <label for="hdkk">Hubungan Dengan Kepala Keluarga</label>
    <select name="hdkk" id="hdkk"
        class="form-control @error('hdkk') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('hdkk', $data['hdkk'] ?? '') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
        <option value="2" {{ old('hdkk', $data['hdkk'] ?? '') == 2 ? 'selected' : '' }}>Istri / Suami</option>
        <option value="3" {{ old('hdkk', $data['hdkk'] ?? '') == 3 ? 'selected' : '' }}>Anak</option>
        <option value="4" {{ old('hdkk', $data['hdkk'] ?? '') == 4 ? 'selected' : '' }}>Menantu</option>
        <option value="5" {{ old('hdkk', $data['hdkk'] ?? '') == 5 ? 'selected' : '' }}>Cucu</option>
        <option value="6" {{ old('hdkk', $data['hdkk'] ?? '') == 6 ? 'selected' : '' }}>Orang Tua / Mertua</option>
        <option value="7" {{ old('hdkk', $data['hdkk'] ?? '') == 7 ? 'selected' : '' }}>Pembantu</option>
        <option value="8" {{ old('hdkk', $data['hdkk'] ?? '') == 8 ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('hdkk')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>


                            <div class="col-lg-3 mb-3">
                                <label for="kelamin">Jenis Kelamin</label>
                                <select name="kelamin" id="kelamin"
                                    class="form-control @error('kelamin') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('kelamin', $data['kelamin'] ?? '') == 1 ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="2" {{ old('kelamin', $data['kelamin'] ?? '') == 2 ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
    <label for="status_perkawinan">Status Perkawinan</label>
    <select name="status_perkawinan" id="status_perkawinan"
        class="form-control @error('status_perkawinan') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 1 ? 'selected' : '' }}>Belum Kawin</option>
        <option value="2" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 2 ? 'selected' : '' }}>Kawin / Nikah</option>
        <option value="3" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 3 ? 'selected' : '' }}>Cerai Hidup</option>
        <option value="4" {{ old('status_perkawinan', $data['status_perkawinan'] ?? '') == 4 ? 'selected' : '' }}>Cerai Mati</option>
    </select>
    @error('status_perkawinan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>


                           <div class="col-lg-4 mb-3">
    <label for="status_pekerjaan">Status Pekerjaan</label>
    <select name="status_pekerjaan" id="status_pekerjaan"
        class="form-control @error('status_pekerjaan') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>Bekerja</option>
        <option value="2" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Ibu Rumah Tangga</option>
        <option value="3" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Bersekolah</option>
        <option value="4" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Tidak / Belum Bekerja</option>
        <option value="5" {{ old('status_pekerjaan', $data['status_pekerjaan'] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('status_pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>


                            <div class="col-lg-4 mb-3">
    <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
    <select name="jenis_pekerjaan" id="jenis_pekerjaan"
        class="form-control @error('jenis_pekerjaan') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>PNS, TNI dan POLRI</option>
        <option value="2" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Karyawan, Honorer</option>
        <option value="3" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Wiraswasta</option>
        <option value="4" {{ old('jenis_pekerjaan', $data['jenis_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('jenis_pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
    <label for="sub_jenis_pekerjaan">Sub-Jenis Pekerjaan</label>
    <select name="sub_jenis_pekerjaan" id="sub_jenis_pekerjaan"
        class="form-control @error('sub_jenis_pekerjaan') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="1" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 1 ? 'selected' : '' }}>Aparatur Pemerintah / Negara</option>
        <option value="2" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 2 ? 'selected' : '' }}>Tenaga Ahli / Profesional</option>
        <option value="3" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 3 ? 'selected' : '' }}>Tenaga Kerja Harian</option>
        <option value="4" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 4 ? 'selected' : '' }}>Pengusaha / Wira Usaha</option>
        <option value="5" {{ old('sub_jenis_pekerjaan', $data['sub_jenis_pekerjaan'] ?? '') == 5 ? 'selected' : '' }}>Lainnya</option>
    </select>
    @error('sub_jenis_pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>


                            <div class="col-lg-4 mb-3">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                    class="form-control @error('pendidikan_terakhir') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('pendidikan_terakhir', $data['pendidikan_terakhir'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('pendidikan_terakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="pendapatan_per_bulan">Pendapatan per Bulan</label>
                                <select name="pendapatan_per_bulan" id="pendapatan_per_bulan"
                                    class="form-control @error('pendapatan_per_bulan') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('pendapatan_per_bulan', $data['pendapatan_per_bulan'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('pendapatan_per_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
