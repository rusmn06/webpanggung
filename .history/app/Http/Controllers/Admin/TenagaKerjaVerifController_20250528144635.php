<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
                Storage::disk('public')->delete('ttd/' . $item->admin_ttd_pendata);
            }
            $path = $request->file('admin_ttd_pendata')->store('ttd/admin', 'public');
            $data['admin_ttd_pendata'] = basename($path);
        }

        // Set status dan ID admin
        $data['status_validasi'] = 'validated';
        // $data['admin_id']        = Auth::id(); // Pastikan ada admin_id jika perlu nanti

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
        $item = RumahTangga::findOrFail($id);
        $item->update(['status_validasi' => 'rejected']);

        return redirect()->route('admin.tkw.index')
                         ->with('warning', 'Data rumah tangga #'.$id.' ditolak.');
    }

    public function listRtPage()
    {
        // Hitung jumlah RumahTangga (Responden) per RT
        $rumahTanggaCounts = RumahTangga::select('rt', DB::raw('count(*) as total_rt'))
            ->whereBetween('rt', [1, 24])
            ->groupBy('rt')
            ->pluck('total_rt', 'rt'); // Hasil: [1 => 10, 2 => 15, ...]

        // Hitung jumlah AnggotaKeluarga per RT
        $anggotaCounts = AnggotaKeluarga::select('fm_rumah_tangga.rt', DB::raw('count(fm_anggota_keluarga.id) as total_anggota'))
            ->join('fm_rumah_tangga', 'fm_anggota_keluarga.rumah_tangga_id', '=', 'fm_rumah_tangga.id')
            ->whereBetween('fm_rumah_tangga.rt', [1, 24])
            ->groupBy('fm_rumah_tangga.rt')
            ->pluck('total_anggota', 'rt'); // Hasil: [1 => 50, 2 => 65, ...]

        // Kirim data counts ke view
        return view('admin.tkw.listrt', compact('rumahTanggaCounts', 'anggotaCounts'));
    }

    public function showRtData($rt)
    {
        // 1. Validasi nomor RT
        if ($rt < 1 || $rt > 24) {
            abort(404, 'Nomor RT tidak valid.');
        }

        // 2. Ambil data AnggotaKeluarga menggunakan model baru
        $items = AnggotaKeluarga::whereHas('rumahTangga', function ($query) use ($rt) {
            $query->where('rt', $rt);
        })
        ->with('rumahTangga')
        ->orderBy('nama', 'asc')
        ->paginate(15);

        // 3. Tampilkan view, kirim data & nomor RT
        return view('admin.tkw.rtshow', compact('items', 'rt'));
    }

    // ==========================================================
    // == METHOD BARU UNTUK HALAMAN GABUNGAN & AJAX ==
    // ==========================================================

    /**
     * Menampilkan halaman dashboard gabungan (Validasi & List RT).
     */
    public function dashboardTkw()
    {
        // Ambil data untuk tabel validasi (seperti di index())
        $items = RumahTangga::where('status_validasi', 'pending')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15); // Kita tetap pakai paginate untuk $items

        // Ambil data untuk kartu RT (seperti di listRtPage())
        $rumahTanggaCounts = RumahTangga::select('rt', DB::raw('count(*) as total_rt'))
            ->whereBetween('rt', [1, 24])
            ->groupBy('rt')
            ->pluck('total_rt', 'rt');

        $anggotaCounts = AnggotaKeluarga::select('fm_rumah_tangga.rt', DB::raw('count(fm_anggota_keluarga.id) as total_anggota'))
            ->join('fm_rumah_tangga', 'fm_anggota_keluarga.rumah_tangga_id', '=', 'fm_rumah_tangga.id')
            ->whereBetween('fm_rumah_tangga.rt', [1, 24])
            ->groupBy('fm_rumah_tangga.rt')
            ->pluck('total_anggota', 'rt');

        // Kirim semua data ke view gabungan yang kita buat sebelumnya
        return view('admin.tkw.dashboard_tkw', compact('items', 'rumahTanggaCounts', 'anggotaCounts'));
    }

    /**
     * Mengambil data detail RT (Rumah Tangga & Anggota) untuk AJAX.
     * Mengembalikan partial view (hanya tabel HTML).
     */
    public function getRtData(Request $request, $rt)
    {
        // Validasi RT
        if ($rt < 1 || $rt > 24) {
            // Jika request AJAX, kirim error JSON, jika tidak, abort
            return $request->ajax() ?
                   response()->json(['error' => 'Nomor RT tidak valid.'], 404) :
                   abort(404);
        }

        // Ambil data RumahTangga untuk RT ini
        // Kita ambil RumahTangga agar bisa menampilkan info per KK
        $rtData = RumahTangga::where('rt', $rt)
                               ->withCount('anggotaKeluarga') // Hitung anggota per RT
                               ->orderBy('nama_responden', 'asc')
                               ->get(); // Gunakan get() karena kita akan buat tabel sendiri

        // Hanya proses jika ini adalah request AJAX
        if ($request->ajax()) {
            // Render partial view (yang akan kita buat) dan kirim sebagai response HTML
            return view('admin.tkw._rt_detail_table', compact('rtData', 'rt'))->render();
        }

        // Jika bukan AJAX, larang akses
        abort(403, 'Akses langsung tidak diizinkan.');
    }
}