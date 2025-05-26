<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserTenagaKerjaController;
use App\Http\Controllers\TenagaKerjaWizardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TenagaKerjaVerifController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\JamsosWizardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Halaman depan (home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Hanya untuk tamu (guest): login & register
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});

// Logout (harus sudah auth)
Route::post('/logout', [LoginController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'role:admin'])
     ->group(function () {
         // Dashboard admin
         Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

         // Verifikasi tenaga kerja
         Route::prefix('tenagakerja')
              ->name('tkw.')
              ->group(function () {
                  Route::get('/',               [TenagaKerjaVerifController::class, 'index'])->name('index');
                  Route::get('{id}',            [TenagaKerjaVerifController::class, 'show'])->name('show');
                  Route::post('{id}/approve',   [TenagaKerjaVerifController::class, 'approve'])->name('approve');
                  Route::post('{id}/reject',    [TenagaKerjaVerifController::class, 'reject'])->name('reject');
              });

         // User
         Route::prefix('user')
              ->name('user.')
              ->group(function () {
                  Route::get('/',            [UserController::class, 'index'])->name('index');
                  Route::get('/create',      [UserController::class, 'create'])->name('create');
                  Route::post('/',           [UserController::class, 'store'])->name('store');
                  Route::get('/{id}/edit',   [UserController::class, 'edit'])->name('edit');
                  Route::put('/{id}',        [UserController::class, 'update'])->name('update');
                  Route::delete('/{id}',     [UserController::class, 'destroy'])->name('destroy');
             });
     });


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user|admin'])->group(function () {
         // Dashboard user
         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

         // Menu Tenaga Kerja (user-facing)
         Route::get('/tenagakerja',           [UserTenagaKerjaController::class, 'index'])->name('tenagakerja.index');
         Route::get('/tenagakerja/listrt',    [UserTenagaKerjaController::class, 'listrt'])->name('tenagakerja.listrt');
         for ($i = 1; $i <= 24; $i++) {
             Route::get("/tenagakerja/listrt/{$i}", [UserTenagaKerjaController::class, 'rtview'])
                  ->name("tenagakerja.listrt.{$i}")
                  ->defaults('rt', $i);
         }

         Route::prefix('kuesioner-tenaga-kerja')->name('tenagakerja.')->group(function () {
        // Halaman utama/dashboard fitur "Kuesioner Tenaga Kerja"
        // yang menampilkan statistik dan daftar pengajuan user.
        // Ini akan memanggil UserTenagaKerjaController@index
        Route::get('/', [UserTenagaKerjaController::class, 'index'])->name('index');

        // Halaman untuk menampilkan DETAIL SATU PENGAJUAN spesifik milik user.
        // Tombol "Lihat Detail" dari halaman index akan mengarah ke sini.
        // Wizard postStep4 juga akan redirect ke sini.
        // Ini akan memanggil UserTenagaKerjaController@show
        Route::get('/{id}', [UserTenagaKerjaController::class, 'show'])->name('show');
    });

         // Wizard Tenaga Kerja
         Route::prefix('tenagakerja')
              ->name('tkw.')
              ->group(function () {
                  Route::get('step-1', [TenagaKerjaWizardController::class, 'showStep1'])->name('step1');
                  Route::post('step-1',[TenagaKerjaWizardController::class, 'postStep1']);
                  Route::get('step-2', [TenagaKerjaWizardController::class, 'showStep2'])->name('step2');
                  Route::post('step-2',[TenagaKerjaWizardController::class, 'postStep2']);
                  Route::get('step-3', [TenagaKerjaWizardController::class, 'showStep3'])->name('step3');
                  Route::post('step-3',[TenagaKerjaWizardController::class, 'postStep3']);
                  Route::get('step-4', [TenagaKerjaWizardController::class, 'showStep4'])->name('step4');
                  Route::post('step-4',[TenagaKerjaWizardController::class, 'postStep4']);
              });

         // Menu Jamsos
         Route::get('/jamsos', function () {
             return view('pages.jamsos.index');
         });

         // (Buka kembali wizard Jamsos bila diperlukan)
         // Route::prefix('jamsos')->name('jss.')->group(…);

         // Menu Difabel Rentan
         Route::get('/difabelrentan', function () {
             return view('pages.difabelrentan.index');
         });
     });


Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
             ->name('profile.show');
    
    // Settings (misal: form ubah profil/password)
    Route::get('/profile/settings', [ProfileController::class, 'settings'])
             ->name('profile.settings');
    Route::post('/profile/settings', [ProfileController::class, 'updateSettings'])
             ->name('profile.settings.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
             ->name('profile.avatar.update');
});     