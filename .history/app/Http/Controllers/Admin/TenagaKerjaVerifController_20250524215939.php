<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class TenagaKerjaVerifController extends Controller
{
    // list semua yg pending
    public function index()
    {
        $items = TenagaKerja::where('status_validasi', 'pending')
            ->paginate(15);

        return view('admin.tkw.index', compact('items'));
    }

    // Detail & form validasi
    public function show($id)
    {
        $item = TenagaKerja::findOrFail($id);
        return view('admin.tkw.show', compact('item'));
    }

    // Simpan validasi (approve)
    public function approve(Request $request, $id)
    {
        $item = TenagaKerja::findOrFail($id);
        $data = $request->validate([
            'admin_tgl_validasi'     => 'required|date',
            'admin_nama_kepaladusun'  => 'required|string|max:255',
            'admin_ttd_pendata'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('admin_ttd_pendata')) {
            $path = $request->file('admin_ttd_pendata')->store('ttd', 'public');
            $data['admin_ttd_pendata'] = basename($path);
        }

        $data['status_validasi'] = 'validated';
        $data['admin_id']         = Auth::id();

        $item->update($data);

        return redirect()
            ->route('admin.tkw.index')
            ->with('success', 'Data berhasil divalidasi!');
    }

    // Tandai reject
    public function reject($id)
    {
        TenagaKerja::where('id',$id)
            ->update(['status_validasi'=>'rejected']);

        return redirect()->route('admin.tkw.index')
                         ->with('warning','Data ditolak.');
    }
}