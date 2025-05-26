<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class UserTenagaKerjaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userSubmissionsQuery = RumahTangga::where('user_id', $userId);

        $totalPengajuan = (clone $userSubmissionsQuery)->count();
        $pengajuanPending = (clone $userSubmissionsQuery)->where('status_validasi', 'pending')->count();
        $pengajuanDisetujui = (clone $userSubmissionsQuery)->where('status_validasi', 'validated')->count();
        $pengajuanDitolak = (clone $userSubmissionsQuery)->where('status_validasi', 'rejected')->count();

        $items = $userSubmissionsQuery->orderBy('created_at', 'desc')->paginate(6);

        // View ini adalah 'pages.tenagakerja.index' yang menampilkan statistik dan daftar kartu
        return view('pages.tenagakerja.index', compact(
            'items',
            'totalPengajuan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $item = RumahTangga::with('anggotaKeluarga')
                           ->where('user_id', $userId)
                           ->findOrFail($id);
        return view('pages.tenagakerja.show_detail', compact('item'));
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
