<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;         // << PENTING untuk helper
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;     // << PENTING untuk type hint helper
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
    }

   public function exportExcel($id)
    {
        $userId = Auth::id();
        $rumahTangga = RumahTangga::with('anggotaKeluarga')
                                    ->where('user_id', $userId)
                                    ->findOrFail($id);

        $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx');

        try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0);

            // Helper function untuk mengisi string per karakter dengan spasi antar kata
            // PERBAIKAN: Menghapus `use ($Coordinate)`
            $fillStringPerChar = function(Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumnChar);
                $words = explode(' ', (string)$string);
                $firstWordProcessed = false;

                foreach ($words as $word) {
                    if (empty($word)) continue; 

                    if ($firstWordProcessed) { 
                        if ($currentColIndex <= $maxColIndex) {
                            // $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, ' '); 
                            $currentColIndex++;
                        } else {
                            break; 
                        }
                    }
                    
                    for ($i = 0; $i < strlen($word); $i++) {
                        if ($currentColIndex <= $maxColIndex) {
                            $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, $word[$i]);
                            $currentColIndex++;
                        } else {
                            break; 
                        }
                    }
                    if ($currentColIndex > $maxColIndex) break;
                    $firstWordProcessed = true;
                }
            };
            
            // Helper function untuk mengisi angka per digit
            // PERBAIKAN: Menghapus `use ($Coordinate)`
            $fillNumberPerDigit = function(Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row) {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, '0', STR_PAD_LEFT); // Pad dengan '0'

                for ($i = 0; $i < $numDigitsToFill; $i++) { 
                    $charToFill = ($i < strlen($paddedNumberString)) ? $paddedNumberString[$i] : ' '; 
                    $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row, $charToFill);
                }
            };

            // 1. PENGENALAN TEMPAT
            $fillStringPerChar($sheet, $rumahTangga->provinsi, 'O', 2, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->desa, 'O', 5, 'AG');
            
            $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6);
            $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6);
            // $sheet->setCellValue('R6', '/'); // Jika perlu

            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('d'), 2, 'AP', 2);
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('m'), 2, 'AS', 2);
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('Y'), 4, 'AV', 2);
            }

            $sheet->setCellValue('AP3', $rumahTangga->nama_pendata);
            $sheet->setCellValue('AP4', $rumahTangga->nama_responden);

            // --- (Tempat untuk mengisi data lain sesuai template Anda) ---
            // Contoh:
            // $sheet->setCellValue('K4', $rumahTangga->jart); // PERIKSA ULANG KOORDINAT INI
            // ... dan seterusnya untuk data utama lainnya ...

            // Mengisi Data Anggota Keluarga (contoh, sesuaikan kolomnya)
            // $startRowAnggota = 7; // Tentukan baris awal data anggota
            // $anggotaCounter = 0;
            // if ($rumahTangga->anggotaKeluarga && $rumahTangga->anggotaKeluarga->count() > 0) {
            //     foreach ($rumahTangga->anggotaKeluarga as $anggota) {
            //         $currentRow = $startRowAnggota + $anggotaCounter;
            //         // $sheet->setCellValue('A' . $currentRow, $anggotaCounter + 1); // No
            //         // $sheet->setCellValue('B' . $currentRow, $anggota->nama);   // Nama
            //         // $fillNumberPerDigit($sheet, $anggota->nik, 16, 'C', $currentRow); // NIK per digit
            //         // ... (data anggota lainnya) ...
            //         $anggotaCounter++;
            //     }
            // }

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