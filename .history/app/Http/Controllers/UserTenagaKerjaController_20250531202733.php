<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Exports\TenagaKerjaExport; // Class Export yang kita buat
use Maatwebsite\Excel\Facades\Excel; // Facade untuk Maatwebsite\Excel
use Illuminate\Support\Facades\Auth; // Sudah ada di controller Anda

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

    // Tambahan: Hitung nomor urut pengajuan untuk user ini
    $userAllSubmissionIds = RumahTangga::where('user_id', $userId)
                                       ->orderBy('created_at', 'asc')
                                       ->pluck('id')
                                       ->toArray();

    $itemSequenceIndex = array_search($item->id, $userAllSubmissionIds);

    // nomor urut ke objek $item untuk dikirim ke view
    if ($itemSequenceIndex !== false) {
        $item->user_sequence_number = $itemSequenceIndex + 1;
    } else {
        $item->user_sequence_number = '?';
    }

    return view('pages.tenagakerja.detail', compact('item'));
    }

// --- TAMBAHKAN METHOD BARU DI SINI ---
    public function exportExcel($id)
    {
        $userId = Auth::id();
        $rumahTangga = RumahTangga::with('anggotaKeluarga') // Pastikan anggota keluarga juga diambil
                                ->where('user_id', $userId) // Sesuaikan dengan logika di method show Anda
                                ->findOrFail($id);

        // Tentukan nama file saat diunduh
        // Anda bisa menambahkan tanggal atau ID unik jika perlu
        $timestamp = now()->format('Ymd_His');
        $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

        // Panggil Export Class dan trigger download
        return Excel::download(new TenagaKerjaExport($rumahTangga), $namaFile);
    }
    // --- AKHIR METHOD BARU ---

}
