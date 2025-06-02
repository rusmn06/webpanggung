<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory; // Untuk memuat file Excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Untuk menulis file Excel
use Carbon\Carbon;


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

    // --- PASTE KODE BARU MULAI DARI SINI, HAPUS METHOD exportExcel LAMA ANDA ---
    public function exportExcel($id)
    {
        $userId = Auth::id();
        $rumahTangga = RumahTangga::with('anggotaKeluarga')
                                    ->where('user_id', $userId)
                                    ->findOrFail($id);

        // 1. Tentukan Path ke Template Excel Anda
        // PASTIKAN NAMA FILE TEMPLATE INI BENAR
        $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx'); // Sesuai yang terakhir Anda gunakan

        try {
            // 2. Load Template menggunakan PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath); // Tambahkan backslash di depan IOFactory
            $sheet = $spreadsheet->getActiveSheet();

            // 3. Mengisi Data Utama (Rumah Tangga) ke Template
            $sheet->setCellValue('A4', 1);
            $sheet->setCellValue('B4', $this->rumahTangga->nama_responden);
            $sheet->setCellValue('C4', $this->rumahTangga->nama_pendata);
            $sheet->setCellValue('D4', \Carbon\Carbon::parse($this->rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY')); // Perbaiki format isoFormat
            $sheet->setCellValue('E4', $this->rumahTangga->provinsi);
            $sheet->setCellValue('F4', $this->rumahTangga->kabupaten);
            $sheet->setCellValue('G4', $this->rumahTangga->kecamatan);
            $sheet->setCellValue('H4', $this->rumahTangga->desa);
            $sheet->setCellValue('I4', $this->rumahTangga->rt);
            $sheet->setCellValue('J4', $this->rumahTangga->rw);
            $sheet->setCellValue('K4', $this->rumahTangga->jart);
            $sheet->setCellValue('L4', $this->rumahTangga->jart_ab);
            $sheet->setCellValue('M4', $this->rumahTangga->jart_tb);
            $sheet->setCellValue('N4', $this->rumahTangga->jart_ms);
            $sheet->setCellValue('O4', $this->rumahTangga->jpr2rtp_text); // Menggunakan accessor
            $sheet->setCellValue('P4', $this->rumahTangga->status_validasi_text); // Menggunakan accessor
            if ($this->rumahTangga->admin_tgl_validasi) {
                $sheet->setCellValue('Q4', \Carbon\Carbon::parse($this->rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY')); // Perbaiki format isoFormat
                $sheet->setCellValue('R4', $this->rumahTangga->admin_nama_kepaladusun ?? '-');
            } else {
                $sheet->setCellValue('Q4', '-');
                $sheet->setCellValue('R4', '-');
            }
            $sheet->setCellValue('S4', 'RT-' . $this->rumahTangga->id);

            // 4. Mengisi Data Anggota Keluarga
            $startRowAnggota = 7;
            $anggotaCounter = 0;
            if ($this->rumahTangga->anggotaKeluarga && $this->rumahTangga->anggotaKeluarga->count() > 0) {
                foreach ($this->rumahTangga->anggotaKeluarga as $anggota) {
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

            // 5. Siapkan Writer dan Nama File untuk Download
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet); // Tambahkan backslash di depan Xlsx
            $timestamp = now()->format('Ymd_His');
            $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

            // Atur header HTTP untuk memicu download di browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'. $namaFile .'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) { // Tambahkan backslash di depan Exception
            return redirect()->back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        }
    }
    // --- AKHIR METHOD BARU ---

}
