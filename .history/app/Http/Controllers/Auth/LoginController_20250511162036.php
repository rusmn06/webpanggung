<?php

// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required'],
    ]);

    $user = User::where('username', $credentials['username'])->first();

    if (!$user) {
        return back()->withErrors([
            'username' => 'Username tidak ditemukan.',
        ])->onlyInput('username');
    }

    if (!Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors([
            'password' => 'Password salah.',
        ])->onlyInput('username'); // input yang dipertahankan hanya username
    }

    Auth::login($user);
    $request->session()->regenerate();

    if ($user->role === 'admin') {
        return redirect()->intended('admin/dashboard');
    }

    return redirect()->intended('/dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}