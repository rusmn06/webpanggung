<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class UserTenagaKerjaController extends Controller
{
     /**
     * Menampilkan dashboard Kuesioner Tenaga Kerja untuk user,
     * termasuk statistik dan daftar pengajuan yang sudah mereka buat.
     * Ini akan menjadi halaman yang diakses dari menu "Kuesioner Tenaga Kerja".
     */
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

    // Method show($id) tetap sama seperti sebelumnya untuk menampilkan detail
    public function show($id)
    {
        $userId = Auth::id();
        $item = RumahTangga::with('anggotaKeluarga') // Eager load anggota keluarga
                           ->where('user_id', $userId) // Keamanan: Hanya data milik user
                           ->findOrFail($id);

        // View ini adalah halaman detail pengajuan, misal 'pages.tenagakerja.submission_detail'
        // atau 'pages.tenagakerja.show_detail' seperti yang Anda sebutkan
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
