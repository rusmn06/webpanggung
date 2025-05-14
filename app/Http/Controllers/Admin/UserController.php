<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Hanya list user-role saja
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255|unique:users,username',
            'password'              => 'required|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'user';

        User::create($data);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User baru sukses dibuat.');
    }

    public function edit($id)
    {
        $user = User::where('role','user')->findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
    $user = User::where('role','user')->findOrFail($id);

    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,'.$id.',id',
        'password' => 'nullable|min:6|confirmed',
    ]);

    if ($request->filled('password')) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()
        ->route('admin.user.index')
        ->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        // Prevent self‐delete tanpa harus pakai $user->id
        if (auth()->id() === (int) $id) {
            return back()->with('error', 'Gak bisa hapus akun sendiri.');
        }

        $user = User::where('role','user')->findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
