<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
    // Ambil data counts seperti sebelumnya
    $rumahTanggaCounts = RumahTangga::where('status_validasi', 'validated')
        ->selectRaw('rt, count(*) as total')
        ->groupBy('rt')
        ->pluck('total', 'rt');

    $anggotaCounts = AnggotaKeluarga::whereHas('rumahTangga', function ($query) {
        $query->where('status_validasi', 'validated');
    })
    ->selectRaw('rumah_tanggas.rt, count(*) as total')
    ->join('rumah_tanggas', 'anggota_keluargas.rumah_tangga_id', '=', 'rumah_tanggas.id')
    ->groupBy('rumah_tanggas.rt')
    ->pluck('total', 'rt');

    // 1. Buat koleksi data untuk semua 24 RT
    $allRtData = new Collection();
    for ($i = 1; $i <= 24; $i++) {
        $allRtData->push([
            'rt_number' => $i,
            'total_responden' => $rumahTanggaCounts[$i] ?? 0,
            'total_anggota' => $anggotaCounts[$i] ?? 0,
        ]);
    }

    // 2. Buat Paginator secara manual
    $perPage = 9; // Menampilkan 9 RT per halaman
    $currentPage = Paginator::resolveCurrentPage('page');
    $currentPageItems = $allRtData->slice(($currentPage - 1) * $perPage, $perPage);
    
    $paginatedItems = new LengthAwarePaginator(
        $currentPageItems,
        $allRtData->count(),
        $perPage,
        $currentPage,
        ['path' => Paginator::resolveCurrentPath()]
    );

    // 3. Kirim data yang sudah di-paginasi ke view
    return view('admin.tkw.listrt', [
        'rts' => $paginatedItems
    ]);
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
}