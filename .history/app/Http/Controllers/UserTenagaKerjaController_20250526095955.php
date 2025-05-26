<?php

namespace App\Http\Controllers; // Namespace Anda saat ini

use App\Models\RumahTangga;
// use App\Models\TenagaKerja; // Tidak digunakan lagi untuk fitur ini
use App\Models\AnggotaKeluarga; // Diperlukan jika show() mengambil relasi secara eksplisit
use Illuminate\Http\Request;    // Tambahkan jika belum ada
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

    /**
     * Menampilkan detail satu pengajuan Rumah Tangga spesifik milik pengguna.
     * Dipanggil setelah submit form step 4 (untuk pop-up) atau dari tombol "Lihat Detail".
     */
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

    // Method listrt() yang lama tidak lagi mengirim data dan bisa dihapus jika fungsinya sudah dicakup oleh index()
    // public function listrt()
    // {
    //     return view('pages.tenagakerja.listrt');
    // }

    // Method rtview(int $rt) tidak lagi digunakan untuk alur ini
    // public function rtview(int $rt) { ... }
}