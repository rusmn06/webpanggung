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
        $userId = Auth::id();
        $rumahTangga = RumahTangga::with('anggotaKeluarga')
                                    ->where('user_id', $userId)
                                    ->findOrFail($id);

        $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx');

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0);

            // ... (semua logika $sheet->setCellValue Anda untuk data utama dan anggota keluarga) ...
            // Contoh:
            // $sheet->setCellValue('A4', 1); 
            // $sheet->setCellValue('B4', $rumahTangga->nama_responden);
            // dst...
            // (Pastikan semua $sheet->setCellValue dari kode TenagaKerjaExport sebelumnya dipindahkan ke sini,
            //  menggunakan variabel $sheet dan $rumahTangga / $anggota dengan benar)

            // Mengisi Data Utama
            $sheet->setCellValue('A4', 1);
            $sheet->setCellValue('B4', $rumahTangga->nama_responden);
            $sheet->setCellValue('C4', $rumahTangga->nama_pendata);
            $sheet->setCellValue('D4', \Carbon\Carbon::parse($rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY')); // Menggunakan YYYY untuk tahun penuh
            $sheet->setCellValue('E4', $rumahTangga->provinsi);
            $sheet->setCellValue('F4', $rumahTangga->kabupaten);
            $sheet->setCellValue('G4', $rumahTangga->kecamatan);
            $sheet->setCellValue('H4', $rumahTangga->desa);
            $sheet->setCellValue('I4', $rumahTangga->rt);
            $sheet->setCellValue('J4', $rumahTangga->rw);
            $sheet->setCellValue('K4', $rumahTangga->jart);
            $sheet->setCellValue('L4', $rumahTangga->jart_ab);
            $sheet->setCellValue('M4', $rumahTangga->jart_tb);
            $sheet->setCellValue('N4', $rumahTangga->jart_ms);
            $sheet->setCellValue('O4', $rumahTangga->jpr2rtp_text);
            $sheet->setCellValue('P4', $rumahTangga->status_validasi_text);
            if ($rumahTangga->admin_tgl_validasi) {
                $sheet->setCellValue('Q4', \Carbon\Carbon::parse($rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
                $sheet->setCellValue('R4', $rumahTangga->admin_nama_kepaladusun ?? '-');
            } else {
                $sheet->setCellValue('Q4', '-');
                $sheet->setCellValue('R4', '-');
            }
            $sheet->setCellValue('S4', 'RT-' . $rumahTangga->id);

            // Mengisi Data Anggota Keluarga
            $startRowAnggota = 7;
            $anggotaCounter = 0;
            if ($rumahTangga->anggotaKeluarga && $rumahTangga->anggotaKeluarga->count() > 0) {
                foreach ($rumahTangga->anggotaKeluarga as $anggota) {
                    $currentRow = $startRowAnggota + $anggotaCounter;
                    $sheet->setCellValue('A' . $currentRow, $anggotaCounter + 1);
                    $sheet->setCellValue('B' . $currentRow, $anggota->nama);
                    $sheet->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('D' . $currentRow, $anggota->kelamin_text);
                    $sheet->setCellValue('E' . $currentRow, $anggota->hdkrt_text);
                    $sheet->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text);
                    $sheet->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text);
                    $sheet->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text);
                    $sheet->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text);
                    $anggotaCounter++;
                }
            } else {
                $sheet->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
            }
            // --- Akhir logika pengisian data ---

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $timestamp = now()->format('Ymd_His');
            $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'. $namaFile .'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // Log::error('Error exporting Excel: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString()); // Tambahkan log jika perlu
            return redirect()->back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        } catch (\Exception $e) { // Menangkap error umum juga
            // Log::error('Generic error exporting Excel: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan umum saat membuat file Excel.');
        }
    }

}
