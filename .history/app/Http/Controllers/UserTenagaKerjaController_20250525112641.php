<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class UserTenagaKerjaController extends Controller
{
    
       /**
     * Menampilkan dashboard Kuesioner Tenaga Kerja untuk user,
     * termasuk daftar pengajuan yang sudah mereka buat.
     */
    public function index()
    {
        $userId = Auth::id();
        $pengajuanUser = RumahTangga::where('user_id', $userId)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(5); // Tampilkan 5 per halaman, bisa disesuaikan

        // Variabel $items yang akan dikirim ke view berisi pengajuanUser
        return view('pages.tenagakerja.index', ['items' => $pengajuanUser]);
    }

    /**
     * Menampilkan detail satu pengajuan Rumah Tangga milik user.
     * Ini method yang sudah kita siapkan sebelumnya.
     */
    public function show($id) // Kita akan butuh route untuk ini
    {
        $userId = Auth::id();
        $item = RumahTangga::with('anggotaKeluarga')
                           ->where('user_id', $userId)
                           ->findOrFail($id);

        // View ini adalah halaman detail pengajuan
        return view('pages.tenagakerja.show_detail', compact('item')); // Anda perlu membuat view ini
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
