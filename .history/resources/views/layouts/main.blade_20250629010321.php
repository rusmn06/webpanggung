<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Fonts & Icons -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,900" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.navbar')
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Scroll to Top-->
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

    <!-- JS -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        /**
         * Fungsi ini akan memvalidasi input secara langsung saat pengguna mengetik.
         * Ia mencari input dengan atribut data-type="string-only" atau data-type="numeric-only".
         */
        function liveValidateInput(event) {
            const input = event.target;

            // Validasi untuk yang hanya boleh huruf dan spasi
            if (input.matches('[data-type="string-only"]')) {
                // Hapus semua karakter yang bukan huruf (besar/kecil) atau spasi
                input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
            }

            // Validasi untuk yang hanya boleh angka (seperti NIK)
            if (input.matches('[data-type="numeric-only"]')) {
                // Hapus semua karakter yang bukan angka
                input.value = input.value.replace(/[^0-9]/g, '');
            }
        }

        // Terapkan event listener ke seluruh dokumen.
        // Ini akan berfungsi untuk elemen yang sudah ada maupun yang baru ditambahkan (seperti di step 2)
        document.addEventListener('input', liveValidateInput);
    });
    </script>
    @stack('scripts')
</body>
</html>
