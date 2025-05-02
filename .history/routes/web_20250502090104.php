<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Public route untuk halaman awal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes (login & register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes — prioritas utama
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Other admin routes...
});

// User routes — prioritas utama
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Menu Tenaga Kerja
Route::get('/tenagakerja', function () {
    return view('pages.tenagakerja.index');
});
Route::get('/tenagakerja/listrt', function () {
    return view('pages.tenagakerja.listrt');
});
for ($i = 1; $i <= 24; $i++) {
    Route::get("/tenagakerja/listrt/{$i}", function () use ($i) {
        return view("pages.tenagakerja.listrt.{$i}");
    });
}

// Menu Jaminan Sosial
Route::get('/jamsos', function () {
    return view('pages.jamsos.index');
});

// Menu Difabel Rentan
Route::get('/difabelrentan', function () {
    return view('pages.difabelrentan.index');
});

// Testing
// Route::get('/test-middleware', function () {
//     return 'Role middleware Berhasil';
// })->middleware(['auth', 'role:user']);

// Route::get('/test-admin', function () {
//     return 'Direct test Berhasil';
// })->middleware('role:admin');

// Route::get('/test-user', function () {
//     return 'Direct test Berhasil';
// })->middleware('role:user');
