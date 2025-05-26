<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RumahTangga; // <-- Ganti ke RumahTangga
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan untuk handle TTD lama

class TenagaKerjaVerifController extends Controller
{
    /**
     * Menampilkan daftar Rumah Tangga yang statusnya 'pending'.
     */
    public function index()
    {
        // Ambil data RumahTangga, bukan TenagaKerja
        $items = RumahTangga::where('status_validasi', 'pending')
                            ->orderBy('created_at', 'desc') // Urutkan berdasarkan terbaru
                            ->paginate(15);

        // Ganti view jika perlu, atau pastikan view index bisa handle $items (RumahTangga)
        return view('admin.tkw.index', compact('items'));
    }

    /**
     * Menampilkan detail Rumah Tangga dan anggotanya untuk divalidasi.
     */
    public function show($id)
    {
        // Ambil RumahTangga DENGAN anggotanya (Eager Loading)
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        // Ganti view jika perlu, atau pastikan view show bisa handle $item (RumahTangga)
        return view('admin.tkw.show', compact('item'));
    }

    /**
     * Memproses persetujuan (approve) data Rumah Tangga.
     */
    public function approve(Request $request, $id)
    {
        $item = RumahTangga::findOrFail($id); // Ambil RumahTangga

        // Validasi input admin
        $data = $request->validate([
            'admin_tgl_validasi'     => 'required|date',
            'admin_nama_kepaladusun' => 'required|string|max:100', // Ganti max:100
            'admin_ttd_pendata'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload TTD Admin
        if ($request->hasFile('admin_ttd_pendata')) {
            // Hapus TTD lama jika ada
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