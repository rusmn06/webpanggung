<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;


class UserTenagaKerjaController extends Controller
{
    // UserTenagaKerjaController.php - method index()
public function index()
{
    $userId = Auth::id();
    $userSubmissionsQuery = RumahTangga::where('user_id', $userId);

    // Statistik
    $totalPengajuan = (clone $userSubmissionsQuery)->count(); // Tetap gunakan ini untuk total keseluruhan
    $pengajuanPending = (clone $userSubmissionsQuery)->where('status_validasi', 'pending')->count();
    $pengajuanDisetujui = (clone $userSubmissionsQuery)->where('status_validasi', 'validated')->count();
    $pengajuanDitolak = (clone $userSubmissionsQuery)->where('status_validasi', 'rejected')->count();

    // Ambil item untuk halaman saat ini, terbaru di atas
    $items = RumahTangga::where('user_id', $userId) // Buat query baru agar tidak terpengaruh clone
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);

    $userAllSubmissionIdsOrderedAsc = RumahTangga::where('user_id', $userId)
                                        ->orderBy('created_at', 'asc')
                                        ->pluck('id')
                                        ->toArray();

    foreach ($items as $item) {
        $itemSequenceIndex = array_search($item->id, $userAllSubmissionIdsOrderedAsc);
        if ($itemSequenceIndex !== false) {
            $item->user_sequence_number = $itemSequenceIndex + 1;
        } else {
            $item->user_sequence_number = '?';
        }
    }

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

        $userAllSubmissionIds = RumahTangga::where('user_id', $userId)
                                        ->orderBy('created_at', 'asc')
                                        ->pluck('id')
                                        ->toArray();

        $itemSequenceIndex = array_search($item->id, $userAllSubmissionIds);

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
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0);

            // Helper function untuk mengisi string per karakter
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
                            $currentColIndex++;
                        } else { break; }
                    }
                    if ($currentColIndex > $maxColIndex) break;
                    $firstWordProcessed = true;
                }
            };
            
            // Helper function untuk mengisi angka per digit
            $fillNumberPerDigit = function(Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row, $padChar = '0') {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, $padChar, STR_PAD_LEFT);

                // Loop sebanyak jumlah digit
                for ($i = 0; $i < $numDigitsToFill; $i++) { 
                    $charToFill = $paddedNumberString[$i]; 

                    $cellCoordinate = Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row;
                    $currentSheet->setCellValue($cellCoordinate, $charToFill);
                }
            };

            // 1. PENGENALAN TEMPAT
            $fillStringPerChar($sheet, strtoupper($rumahTangga->provinsi),  'O', 2, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->kabupaten), 'O', 3, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->kecamatan), 'O', 4, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->desa),      'O', 5, 'AG');
            $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6, '0');
            $sheet->setCellValue('R6', '/');
            $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6, '0');

            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tglPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('d'), 2, 'AP', 2);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('m'), 2, 'AS', 2);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('Y'), 4, 'AV', 2);
            }
            $sheet->setCellValue('AP4', $rumahTangga->nama_pendata); 
            $sheet->setCellValue('AP6', $rumahTangga->nama_responden);

            // 3. KETERANGAN STATUS PEKERJAAN (ANGGOTA KELUARGA 1-9)
            $anggotaKeluargaPertama9 = $rumahTangga->anggotaKeluarga->take(9);
            $barisNamaBase = [10, 13, 16, 19, 22, 25, 28, 31, 34]; 
            $barisInfoBase = [11, 14, 17, 20, 23, 26, 29, 32, 35]; 
            foreach ($anggotaKeluargaPertama9 as $index => $anggota) {
                if($index >= count($barisNamaBase)) break;
                $barisNamaSaatIni = $barisNamaBase[$index];
                $barisInfoSaatIni = $barisInfoBase[$index];
                $sheet->setCellValue('D' . $barisNamaSaatIni, $anggota->nama);
                $nikString = (string)$anggota->nik;
                if (strlen($nikString) == 16) {
                    $fillNumberPerDigit($sheet, substr($nikString, 0, 6), 6, 'D', $barisInfoSaatIni);
                    $fillNumberPerDigit($sheet, substr($nikString, 6, 6), 6, 'K', $barisInfoSaatIni);
                    $fillNumberPerDigit($sheet, substr($nikString, 12, 4), 4, 'R', $barisInfoSaatIni);
                } else { 
                    for($k=0; $k<6; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('D')+$k) . $barisInfoSaatIni, '');
                    for($k=0; $k<6; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('K')+$k) . $barisInfoSaatIni, '');
                    for($k=0; $k<4; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('R')+$k) . $barisInfoSaatIni, '');
                }
                $sheet->setCellValue('X'  . $barisInfoSaatIni, $anggota->hdkrt);
                $sheet->setCellValue('AA' . $barisInfoSaatIni, $anggota->nuk);
                $sheet->setCellValue('AD' . $barisInfoSaatIni, $anggota->kelamin);
                $sheet->setCellValue('AG' . $barisInfoSaatIni, $anggota->status_perkawinan);
                $sheet->setCellValue('AJ' . $barisInfoSaatIni, $anggota->status_pekerjaan);
                $sheet->setCellValue('AM' . $barisInfoSaatIni, $anggota->jenis_pekerjaan);
                $sheet->setCellValue('AP' . $barisInfoSaatIni, $anggota->sub_jenis_pekerjaan);
                $sheet->setCellValue('AS' . $barisInfoSaatIni, $anggota->pendidikan_terakhir);
                $sheet->setCellValue('AV' . $barisInfoSaatIni, $anggota->pendapatan_per_bulan); 
                $sheet->setCellValue('AY' . $barisInfoSaatIni, $anggota->pendapatan_per_bulan);
            }

            // 4. KOLOM CADANGAN ANGGOTA KELUARGA (10+)
            $anggotaKeluargaCadangan = $rumahTangga->anggotaKeluarga->skip(9);
            $startRowCadangan = 45; 
            $counterCadangan = 0;
            foreach ($anggotaKeluargaCadangan as $anggota) {
                $currentRowCadangan = $startRowCadangan + $counterCadangan;
                $sheet->setCellValue('A' . $currentRowCadangan, 10 + $counterCadangan);
                $sheet->setCellValue('B' . $currentRowCadangan, $anggota->nama);
                $nikStringCadangan = (string)$anggota->nik;
                if(strlen($nikStringCadangan) == 16) {
                    for ($i = 0; $i < 16; $i++) {
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('D') + $i) . $currentRowCadangan, $nikStringCadangan[$i]);
                    }
                }
                $sheet->setCellValue('U' . $currentRowCadangan, $anggota->hdkrt_text);
                $sheet->setCellValue('V' . $currentRowCadangan, $anggota->nuk);
                $sheet->setCellValue('W' . $currentRowCadangan, $anggota->kelamin_text);
                $sheet->setCellValue('X' . $currentRowCadangan, $anggota->status_perkawinan_text);
                $sheet->setCellValue('Y' . $currentRowCadangan, $anggota->status_pekerjaan_text);
                $sheet->setCellValue('Z' . $currentRowCadangan, $anggota->jenis_pekerjaan_text);
                $sheet->setCellValue('AA'. $currentRowCadangan, $anggota->sub_jenis_pekerjaan); 
                $sheet->setCellValue('AB'. $currentRowCadangan, $anggota->pendidikan_terakhir_text);
                $sheet->setCellValue('AC'. $currentRowCadangan, $anggota->pendapatan_per_bulan); 
                $counterCadangan++;
            }

            // 5. REKAPITULASI
            $fillNumberPerDigit($sheet, $rumahTangga->jart, 2, 'N', 50);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ab, 2, 'N', 51);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_tb, 2, 'N', 52);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ms, 2, 'N', 53);
            $sheet->setCellValue('N54', $rumahTangga->jpr2rtp);

            // 6. VERIFIKASI DAN VALIDASI
            if ($rumahTangga->verif_tgl_pembuatan) {
                $tglVerifPendata = Carbon::parse($rumahTangga->verif_tgl_pembuatan);
                $fillNumberPerDigit($sheet, $tglVerifPendata->format('d'), 2, 'T', 52);
                $fillNumberPerDigit($sheet, $tglVerifPendata->format('m'), 2, 'W', 52);
                $fillNumberPerDigit($sheet, $tglVerifPendata->format('Y'), 4, 'Z', 52);
            }
            $pathTTDPendata = $rumahTangga->ttd_pendata;
            if ($pathTTDPendata && file_exists(storage_path('app/public/' . $pathTTDPendata))) {
                $drawingPendata = new Drawing();
                $drawingPendata->setName('TTD Pendata'); $drawingPendata->setPath(storage_path('app/public/' . $pathTTDPendata));
                $drawingPendata->setHeight(35); $drawingPendata->setCoordinates('T57'); $drawingPendata->setWorksheet($sheet);
            } else { $sheet->setCellValue('T57', $pathTTDPendata ? 'File TTD Pendata Tdk Ditemukan' : 'Tidak Ada TTD Pendata'); }

            if ($rumahTangga->admin_tgl_validasi) {
                $tglValAdmin = Carbon::parse($rumahTangga->admin_tgl_validasi);
                $fillNumberPerDigit($sheet, $tglValAdmin->format('d'), 2, 'AP', 52);
                $fillNumberPerDigit($sheet, $tglValAdmin->format('m'), 2, 'AS', 52);
                $fillNumberPerDigit($sheet, $tglValAdmin->format('Y'), 4, 'AV', 52);
            }
            $pathTTDKades = $rumahTangga->admin_ttd_pendata; 
            if ($pathTTDKades && file_exists(storage_path('app/public/' . $pathTTDKades))) {
                $drawingKades = new Drawing();
                $drawingKades->setName('TTD Kades'); $drawingKades->setPath(storage_path('app/public/' . $pathTTDKades));
                $drawingKades->setHeight(35); $drawingKades->setCoordinates('AP57'); $drawingKades->setWorksheet($sheet);
            } else { $sheet->setCellValue('AP57', $pathTTDKades ? 'File TTD Kades Tdk Ditemukan' : 'Tidak Ada TTD Kades'); }
            
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
