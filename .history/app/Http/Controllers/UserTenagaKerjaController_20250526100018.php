<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;

class UserTenagaKerjaController extends Controller
{
    
    public function index()
    {
        $userId = Auth::id();

        // Query dasar untuk pengajuan milik user
        $userSubmissionsQuery = RumahTangga::where('user_id', $userId);

        // Ambil data untuk statistik
        $totalPengajuan = (clone $userSubmissionsQuery)->count();
        $pengajuanPending = (clone $userSubmissionsQuery)->where('status_validasi', 'pending')->count();
        $pengajuanDisetujui = (clone $userSubmissionsQuery)->where('status_validasi', 'validated')->count();
        $pengajuanDitolak = (clone $userSubmissionsQuery)->where('status_validasi', 'rejected')->count();

        // Ambil daftar pengajuan dengan pagination
        $items = $userSubmissionsQuery->orderBy('created_at', 'desc')->paginate(6); // Mungkin 6 item (2 baris @ 3 kartu)

        return view('pages.tenagakerja.index', compact(
            'items',
            'totalPengajuan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // Method show($id) tetap sama seperti sebelumnya untuk menampilkan detail
    public function show($id)
    {
        $userId = Auth::id();
        $item = RumahTangga::with('anggotaKeluarga')
                           ->where('user_id', $userId)
                           ->findOrFail($id);
        return view('pages.tenagakerja.show_detail', compact('item')); // Ganti dengan path view detail Anda
    }

    public function listrt()
    {
        // Kalau perlu, ambil data spesifik RT:
        // $rts = TenagaKerja::where('user_id',Auth::id())->get();
        //$items = TenagaKerja::orderBy('created_at','desc')->paginate(15);
        return view('pages.tenagakerja.listrt');
    }
    
    public function rtview(int $rt)
    {
        if ($rt < 1 || $rt > 24) {
            abort(404);
        }

        $items = TenagaKerja::where('rt', $rt)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("pages.tenagakerja.listrt.{$rt}", compact('items', 'rt'));
    }
}
