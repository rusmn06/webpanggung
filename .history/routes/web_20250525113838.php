<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController; // Untuk User Dashboard
use App\Http\Controllers\UserTenagaKerjaController; // Untuk User melihat data & menu kuesioner
use App\Http\Controllers\TenagaKerjaWizardController; // Untuk form wizard
use App\Http\Controllers\Admin\AdminController; // Nama AdminController di routes Anda, mungkin ini AdminDashboardController?
use App\Http\Controllers\Admin\TenagaKerjaVerifController;
use App\Http\Controllers\Admin\UserController as AdminUserController; // Alias untuk menghindari konflik jika ada UserController di root
use App\Http\Controllers\JamsosWizardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes (Tidak ada perubahan di sini, biarkan seperti kode Anda)
|--------------------------------------------------------------------------
*/
// ... (kode Anda untuk public & auth routes) ...
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes (Tidak ada perubahan di sini, biarkan seperti kode Anda)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'role:admin'])
     ->group(function () {
         // Dashboard admin
         // Jika AdminController adalah Dashboard khusus Admin, namanya sudah pas.
         Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

         // Verifikasi tenaga kerja
         Route::prefix('tenagakerja') // Ini bisa jadi 'validasi-tkw' agar beda dengan user
              ->name('tkw.')
              ->group(function () {
                  Route::get('/',               [TenagaKerjaVerifController::class, 'index'])->name('index');
                  Route::get('{id}',            [TenagaKerjaVerifController::class, 'show'])->name('show');
                  Route::post('{id}/approve',   [TenagaKerjaVerifController::class, 'approve'])->name('approve');
                  Route::post('{id}/reject',    [TenagaKerjaVerifController::class, 'reject'])->name('reject');
              });

         // User Management oleh Admin
         Route::prefix('user') // Ini bisa jadi 'manajemen-pengguna'
              ->name('user.')
              ->controller(AdminUserController::class) // Menggunakan AdminUserController
              ->group(function () {
                  Route::get('/',           'index')->name('index');
                  Route::get('/create',     'create')->name('create');
                  Route::post('/',          'store')->name('store');
                  Route::get('/{user}/edit','edit')->name('edit');
                  Route::put('/{user}',     'update')->name('update');
                  Route::delete('/{user}',  'destroy')->name('destroy');
              });
     });


/*
|--------------------------------------------------------------------------
| User Routes (BAGIAN YANG DIMODIFIKASI)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () { // Middleware 'role:user|admin' bisa di-apply di sini atau per grup

    // Dashboard user (menggunakan DashboardController dari root)
    // Jika 'DashboardController' ini memang untuk user, maka 'role:user|admin' sudah pas.
    // Jika ingin dashboard user benar-benar terpisah, Anda bisa buat User\DashboardController.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Fitur Kuesioner Tenaga Kerja untuk User ---
    Route::prefix('kuesioner-tenaga-kerja')->name('user.kuesioner.tkw.')->group(function () {
        // Halaman menu utama fitur "Kuesioner Tenaga Kerja" (ISI DATA / LIHAT DATA)
        // Menggunakan UserTenagaKerjaController@menuKuesioner (buat method ini jika belum ada)
        Route::get('/', [UserTenagaKerjaController::class, 'menuKuesioner'])->name('menu');

        // Halaman untuk menampilkan DAFTAR PENGAJUAN MILIK USER SAJA
        // Tombol "LIHAT DATA" dari menuKuesioner akan mengarah ke sini.
        // Menggunakan UserTenagaKerjaController@listMySubmissions (buat method ini)
        Route::get('/riwayat-saya', [UserTenagaKerjaController::class, 'listMySubmissions'])->name('listMySubmissions');

        // Halaman untuk menampilkan DETAIL SATU PENGAJUAN spesifik milik user
        // Menggunakan UserTenagaKerjaController@showMySubmission (buat method ini)
        Route::get('/riwayat-saya/{id}', [UserTenagaKerjaController::class, 'showMySubmission'])->name('showMySubmission');
    });
    // --- Akhir Fitur Kuesioner Tenaga Kerja untuk User ---


    // --- Wizard Pengisian Form Tenaga Kerja ---
    // Diberi prefix yang berbeda agar tidak konflik
    Route::prefix('isi-form-tenagakerja')->name('tkw.')->group(function () {
        // Menggunakan TenagaKerjaWizardController dari root (sesuai struktur Anda saat ini)
        Route::get('step-1', [TenagaKerjaWizardController::class, 'showStep1'])->name('step1');
        Route::post('step-1',[TenagaKerjaWizardController::class, 'postStep1']);
        Route::get('step-2', [TenagaKerjaWizardController::class, 'showStep2'])->name('step2');
        Route::post('step-2',[TenagaKerjaWizardController::class, 'postStep2']);
        Route::get('step-3', [TenagaKerjaWizardController::class, 'showStep3'])->name('step3');
        Route::post('step-3',[TenagaKerjaWizardController::class, 'postStep3']);
        Route::get('step-4', [TenagaKerjaWizardController::class, 'showStep4'])->name('step4');
        Route::post('step-4',[TenagaKerjaWizardController::class, 'postStep4']);
    });
    // --- Akhir Wizard Pengisian Form Tenaga Kerja ---


    // Menu Jamsos
    Route::get('/jamsos', function () {
        return view('pages.jamsos.index'); // Sesuaikan path view jika perlu
    })->name('jamsos.index');


    // Menu Difabel Rentan
    Route::get('/difabelrentan', function () {
        return view('pages.difabelrentan.index'); // Sesuaikan path view jika perlu
    })->name('difabelrentan.index');


    // Profile Routes (tetap)
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings', 'updateSettings')->name('settings.update');
        Route::post('/avatar', 'updateAvatar')->name('avatar.update');
    });
});