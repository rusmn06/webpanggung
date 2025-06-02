<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Exports\TenagaKerjaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory; // Perlu ini
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Perlu ini
use Carbon\Carbon; // Perlu ini


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

     // Di dalam UserTenagaKerjaController.php
    public function exportExcel($id)
{
    // ... (kode ambil $rumahTangga dan $templatePath) ...
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        // $sheet = $spreadsheet->getSheet(0); // Bahkan ini bisa dikomen dulu untuk tes paling dasar

        // ======== SEMUA BAGIAN PENGISIAN DATA DIKOMEN DULU ========
        // $fillStringPerChar = function(...) { ... };
        // $fillNumberPerDigit = function(...) { ... };
        // $fillStringPerChar($sheet, $rumahTangga->provinsi, ...);
        // ... dan seterusnya semua setCellValue ...
        // ======== AKHIR BAGIAN YANG DIKOMEN ========

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $timestamp = now()->format('Ymd_His');
        $namaFile = 'Data_Kosong_Debug_' . $timestamp . '.xlsx'; // Nama file debug

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $namaFile .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;

    } catch (\Exception $e) { // Tangkap semua jenis exception
        // Untuk debugging, tampilkan error langsung jika bisa, atau log
        // dd('Error caught:', $e->getMessage(), $e->getFile(), $e->getLine()); 
        // Jika dd() tidak bekerja sebelum header, log adalah cara terbaik
        \Illuminate\Support\Facades\Log::error("Export Excel Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
