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
        // Ambil semua data Rumah Tangga untuk RT yang dipilih,
        // dan langsung muat (eager load) relasi anggotaKeluarga untuk setiap rumah tangga.
        // Ini jauh lebih efisien daripada query yang sebelumnya.
        $rumahTaggas = RumahTangga::where('rt', $rt)
                                    ->with('anggotaKeluarga') // Eager load anggota
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // Kirim data yang sudah dikelompokkan dan nomor RT ke view
        return view('admin.tkw.rtshow', [
            'rumahTaggas' => $rumahTaggas,
            'rt' => $rt,
        ]);
    }

    public function showHouseholdDetail($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);

        return view('admin.tkw.detail', compact('item'));
    }


    public function exportExcel($id)
    {
    $rumahTangga = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
    $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx');
}