<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
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

    public function exportExcel($id)
{
    $userId = Auth::id();
    $rumahTangga = RumahTangga::with('anggotaKeluarga')->where('user_id', $userId)->findOrFail($id);
    $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx');

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheet(0);

        // 1. PENGATURAN STYLE GLOBAL (Contoh)
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
        // Untuk alignment default (misal semua rata kiri, nanti angka di-override jadi tengah)
        // $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // HELPER FUNCTIONS (Sama seperti sebelumnya, pastikan sudah benar)
        $fillStringPerChar = function(Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') {
            $currentColIndex = Coordinate::columnIndexFromString($startColumn);
            $maxColIndex = Coordinate::columnIndexFromString($maxColumnChar);
            $words = explode(' ', (string)$string);
            $firstWordProcessed = false;
            foreach ($words as $word) {
                if (empty($word) && !$firstWordProcessed) continue;
                if (empty($word) && $firstWordProcessed) {
                    if ($currentColIndex <= $maxColIndex) { $currentColIndex++; if ($currentColIndex > $maxColIndex) break; continue; } else { break; }
                }
                if ($firstWordProcessed) {
                    if ($currentColIndex <= $maxColIndex) { $currentColIndex++; } else { break; }
                }
                for ($i = 0; $i < strlen($word); $i++) {
                    if ($currentColIndex <= $maxColIndex) {
                        $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, $word[$i]);
                        // $currentSheet->getStyle(Coordinate::stringFromColumnIndex($currentColIndex) . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $currentColIndex++;
                    } else { break; }
                }
                if ($currentColIndex > $maxColIndex) break;
                $firstWordProcessed = true;
            }
        };
        $fillNumberPerDigit = function(Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row, $padChar = '0') {
            $currentColIndex = Coordinate::columnIndexFromString($startColumn);
            $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, $padChar, STR_PAD_LEFT);
            $rangeToCenter = Coordinate::stringFromColumnIndex($currentColIndex) . $row . ':' . Coordinate::stringFromColumnIndex($currentColIndex + $numDigitsToFill - 1) . $row;

            for ($i = 0; $i < $numDigitsToFill; $i++) {
                $charToFill = ($i < strlen($paddedNumberString)) ? $paddedNumberString[$i] : ($padChar === '0' ? '0' : ' ');
                $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row, $charToFill);
            }
             $currentSheet->getStyle($rangeToCenter)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        };

        // 1. PENGENALAN TEMPAT
        $fillStringPerChar($sheet, $rumahTangga->provinsi,  'O', 2, 'AG');
        $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');
        $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
        $fillStringPerChar($sheet, $rumahTangga->desa,      'O', 5, 'AG');
        
        $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6, ' '); 
        $sheet->setCellValue('R6', '/'); // Pemisah RT/RW
        $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6, ' ');

        // 2. PENDATAAN KETENAGAKERJAAN DI DESA
        if ($rumahTangga->tgl_pembuatan) {
            $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
            $fillNumberPerDigit($sheet, $tanggalPembuatan->format('d'), 2, 'AP', 2);
            $fillNumberPerDigit($sheet, $tanggalPembuatan->format('m'), 2, 'AS', 2);
            $fillNumberPerDigit($sheet, $tanggalPembuatan->format('Y'), 4, 'AV', 2);
        }
        // PERBAIKAN NAMA PENDATA & RESPONDEN:
        $sheet->setCellValue('AP3', $rumahTangga->nama_pendata);    // Pastikan $rumahTangga->nama_pendata ada isinya
        $sheet->setCellValue('AP4', $rumahTangga->nama_responden);
        // $sheet->getStyle('AP3:AZ3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Contoh alignment jika perlu
        // $sheet->getStyle('AP4:AZ4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        // 3. KETERANGAN STATUS PEKERJAAN (ANGGOTA KELUARGA)
        $anggotaKeluarga = $rumahTangga->anggotaKeluarga->take(9);
        $barisNamaBase = [10, 13, 16, 19, 22, 25, 28, 31, 34]; 
        $barisInfoBase = [11, 14, 17, 20, 23, 26, 29, 32, 35]; 

        foreach ($anggotaKeluarga as $index => $anggota) {
            $barisNamaSaatIni = $barisNamaBase[$index];
            $barisInfoSaatIni = $barisInfoBase[$index];

            $sheet->setCellValue('D' . $barisNamaSaatIni, $anggota->nama);
            // $sheet->getStyle('D' . $barisNamaSaatIni)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $nikString = (string)$anggota->nik;
            if (strlen($nikString) == 16) {
                $fillNumberPerDigit($sheet, substr($nikString, 0, 6), 6, 'D', $barisInfoSaatIni);
                $fillNumberPerDigit($sheet, substr($nikString, 6, 6), 6, 'K', $barisInfoSaatIni);
                $fillNumberPerDigit($sheet, substr($nikString, 12, 4), 4, 'R', $barisInfoSaatIni);
            }
            // Kolom untuk data anggota lain (X, AA, AD, AG, dst. di barisInfoSaatIni)
            $sheet->setCellValue('X'  . $barisInfoSaatIni, $anggota->hdkrt);
            $sheet->setCellValue('AA' . $barisInfoSaatIni, $anggota->nuk);
            $sheet->setCellValue('AD' . $barisInfoSaatIni, $anggota->kelamin);
            $sheet->setCellValue('AG' . $barisInfoSaatIni, $anggota->status_perkawinan);
            $sheet->setCellValue('AJ' . $barisInfoSaatIni, $anggota->status_pekerjaan);
            $sheet->setCellValue('AM' . $barisInfoSaatIni, $anggota->jenis_pekerjaan);
            $sheet->setCellValue('AP' . $barisInfoSaatIni, $anggota->sub_jenis_pekerjaan);
            $sheet->setCellValue('AS' . $barisInfoSaatIni, $anggota->pendidikan_terakhir);
            $sheet->setCellValue('AV' . $barisInfoSaatIni, $anggota->pendapatan_per_bulan);
            // $sheet->getStyle('X'.$barisInfoSaatIni.':AY'.$barisInfoSaatIni)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // 4. REKAPITULASI
        $fillNumberPerDigit($sheet, $rumahTangga->jart, 2, 'N', 50);
        $fillNumberPerDigit($sheet, $rumahTangga->jart_ab, 2, 'N', 51);
        $fillNumberPerDigit($sheet, $rumahTangga->jart_tb, 2, 'N', 52);
        $fillNumberPerDigit($sheet, $rumahTangga->jart_ms, 2, 'N', 53);
        $sheet->setCellValue('N54', $rumahTangga->jpr2rtp); // PERBAIKAN: Isi data pendapatan rata-rata
        // $sheet->getStyle('N54')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        // 5. VERIFIKASI DAN VALIDASI
        // Pendata Tgl/Bulan/Tahun
        if ($rumahTangga->verif_tgl_pembuatan) {
            $tanggalVerifPendata = Carbon::parse($rumahTangga->verif_tgl_pembuatan);
            // PERBAIKAN: Tanggal (T52, U52)
            $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('d'), 2, 'T', 52);
            // Bulan (W52, X52)
            $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('m'), 2, 'W', 52);
            // Tahun (Z52 - AC52)
            $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('Y'), 4, 'Z', 52);
        }
        // Tanda tangan pendata (merge T57 hingga AH57)
        if ($rumahTangga->ttd_pendata && file_exists(storage_path('app/public/' . $rumahTangga->ttd_pendata))) {
            $drawingPendata = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawingPendata->setName('TTD Pendata');
            $drawingPendata->setPath(storage_path('app/public/' . $rumahTangga->ttd_pendata));
            $drawingPendata->setHeight(35); // Sesuaikan tinggi
            $drawingPendata->setCoordinates('T57'); 
            $drawingPendata->setWorksheet($sheet);
        }

        // Kepala Dusun Tgl/Bulan/Tahun
        if ($rumahTangga->admin_tgl_validasi) {
            $tanggalValAdmin = Carbon::parse($rumahTangga->admin_tgl_validasi);
            $fillNumberPerDigit($sheet, $tanggalValAdmin->format('d'), 2, 'AP', 52);
            $fillNumberPerDigit($sheet, $tanggalValAdmin->format('m'), 2, 'AS', 52);
            $fillNumberPerDigit($sheet, $tanggalValAdmin->format('Y'), 4, 'AV', 52);
        }
        // Tanda tangan kepala dusun (merge AP57 dan BC57)
         if ($rumahTangga->admin_ttd_pendata && file_exists(storage_path('app/public/' . $rumahTangga->admin_ttd_pendata))) { // Asumsi kolom 'admin_ttd_pendata'
            $drawingKades = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawingKades->setName('TTD Kades');
            $drawingKades->setPath(storage_path('app/public/' . $rumahTangga->admin_ttd_pendata));
            $drawingKades->setHeight(35); 
            $drawingKades->setCoordinates('AP57'); 
            $drawingKades->setWorksheet($sheet);
        }

        // --- AKHIR PENGISIAN DATA ---

        $writer = new Xlsx($spreadsheet);
        $timestamp = now()->format('Ymd_His');
        $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $namaFile .'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;

    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        \Illuminate\Support\Facades\Log::error("PhpSpreadsheet Exception in exportExcel: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Gagal membuat file Excel (PhpSpreadsheet): ' . $e->getMessage());
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("General Exception in exportExcel: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Terjadi kesalahan umum saat membuat file Excel: ' . $e->getMessage());
    }
}

}
