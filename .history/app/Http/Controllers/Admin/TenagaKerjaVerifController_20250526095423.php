<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RumahTangga;
use Illuminate\Support\Facades\Storage;

class TenagaKerjaVerifController extends Controller
{
    /**
     * Menampilkan daftar Rumah Tangga yang statusnya 'pending'.
     */
    public function index()
    {
        $items = RumahTangga::where('status_validasi', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        return view('admin.tkw.index', compact('items'));
    }

    /**
     * Menampilkan detail Rumah Tangga dan anggotanya untuk divalidasi.
     */
    public function show($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        return view('admin.tkw.show', compact('item'));
    }

    /**
     * Memproses persetujuan (approve) data Rumah Tangga.
     */
    public function approve(Request $request, $id)
    {
        $item = RumahTangga::findOrFail($id);

        $data = $request->validate([
            'admin_tgl_validasi'     => 'required|date',
            'admin_nama_kepaladusun' => 'required|string|max:100',
            'admin_ttd_pendata'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload TTD Admin
        if ($request->hasFile('admin_ttd_pendata')) {
            if ($item->admin_ttd_pendata) {
                Storage::disk('public')->delete('ttd/' . $item->admin_ttd_pendata); // Sesuaikan path jika perlu
            }
            $path = $request->file('admin_ttd_pendata')->store('ttd/admin', 'public'); // Simpan ke folder admin
            $data['admin_ttd_pendata'] = basename($path);
        }

        // Set status dan ID admin
        $data['status_validasi'] = 'validated';
        // $data['admin_id']        = Auth::id(); // Pastikan kamu punya kolom admin_id jika perlu

        // Update data RumahTangga
        $item->update($data);

        return redirect()
            ->route('admin.tkw.index')
            ->with('success', 'Data rumah tangga #'.$id.' berhasil divalidasi!');
    }

    /**
     * Menandai data Rumah Tangga sebagai ditolak (reject).
     */
    public function reject($id)
    {
        $item = RumahTangga::findOrFail($id); // Ambil RumahTangga
        $item->update(['status_validasi' => 'rejected']); // Update statusnya

        return redirect()->route('admin.tkw.index')
                         ->with('warning', 'Data rumah tangga #'.$id.' ditolak.');
    }
}