<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return view('profile.show', compact('user'));
    }

    public function settings(Request $request)
    {
        $user = $request->user();
        return view('profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('profile.settings')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Hapus avatar lama kalau ada
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/'.$user->avatar);
        }

        // Simpan file baru
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = basename($path);
        $user->save();

        return back()->with('success', 'Avatar berhasil diperbarui.');
    }
}
