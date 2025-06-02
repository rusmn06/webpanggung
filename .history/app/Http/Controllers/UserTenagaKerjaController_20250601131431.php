<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use App\Exports\TenagaKerjaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory; // Perlu ini
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Perlu ini
use Carbon\Carbon; // Perlu ini
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; // << PASTIKAN INI ADA
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


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

        $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx');

        try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0);

            // Helper function untuk mengisi string per karakter dengan spasi antar kata
            // PERBAIKAN: Menghapus `use (&$Coordinate)` yang salah
            $fillStringPerChar = function(Worksheet $sheet, $string, $startColumn, $row, $maxColumn = 'AG') {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumn);
                $words = explode(' ', (string)$string);
                $firstWord = true;

                foreach ($words as $word) {
                    if (!$firstWord && strlen($word) > 0) { // Hanya tambah spasi jika bukan kata pertama & kata saat ini tidak kosong
                        if ($currentColIndex <= $maxColIndex) {
                            // $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, ' '); // Kolom kosong untuk spasi
                            $currentColIndex++; // Pindah ke kolom berikutnya untuk karakter kata
                        } else {
                            break; 
                        }
                    }
                    for ($i = 0; $i < strlen($word); $i++) {
                        if ($currentColIndex <= $maxColIndex) {
                            $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, $word[$i]);
                            $currentColIndex++;
                        } else {
                            break;
                        }
                    }
                    if ($currentColIndex > $maxColIndex) break;
                    if (strlen($word) > 0) { // Hanya set firstWord ke false jika kata yang diproses tidak kosong
                       $firstWord = false;
                    }
                }
            };
            
            // Helper function untuk mengisi angka per digit
            // PERBAIKAN: Menghapus `use (&$Coordinate)` yang salah
            $fillNumberPerDigit = function(Worksheet $sheet, $numberString, $startColumn, $row, $maxColumn) {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumn);
                for ($i = 0; $i < strlen($numberString); $i++) {
                    if ($currentColIndex <= $maxColIndex) {
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, $numberString[$i]);
                        $currentColIndex++;
                    } else {
                        break; 
                    }
                }
            };

            // 1. PENGENALAN TEMPAT
            $fillStringPerChar($sheet, $rumahTangga->provinsi, 'O', 2, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->desa, 'O', 5, 'AG');

            $rtString = str_pad((string)$rumahTangga->rt, 3, '0', STR_PAD_LEFT); // Misal jadi "005"
            $fillNumberPerDigit($sheet, $rtString, 'O', 6, 'Q');
            
            $rwString = str_pad((string)$rumahTangga->rw, 3, '0', STR_PAD_LEFT); // Misal jadi "011"
            $fillNumberPerDigit($sheet, $rwString, 'S', 6, 'U');
            // $sheet->setCellValue('R6', '/'); // Jika perlu

            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                $hari = $tanggalPembuatan->format('d');
                if (strlen($hari) == 2) { // Pastikan ada 2 digit
                    $sheet->setCellValue('AP2', $hari[0]);
                    $sheet->setCellValue('AQ2', $hari[1]);
                }

                $bulan = $tanggalPembuatan->format('m');
                if (strlen($bulan) == 2) { // Pastikan ada 2 digit
                    $sheet->setCellValue('AS2', $bulan[0]);
                    $sheet->setCellValue('AT2', $bulan[1]);
                }

                $tahun = $tanggalPembuatan->format('Y');
                if (strlen($tahun) == 4) { // Pastikan ada 4 digit
                    $sheet->setCellValue('AV2', $tahun[0]);
                    $sheet->setCellValue('AW2', $tahun[1]);
                    $sheet->setCellValue('AX2', $tahun[2]);
                    $sheet->setCellValue('AY2', $tahun[3]);
                }
            }

            $sheet->setCellValue('AP3', $rumahTangga->nama_pendata);
            // $sheet->mergeCells('AP3:AZ3'); // Jika perlu merge dan belum di template
            // $sheet->getStyle('AP3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue('AP4', $rumahTangga->nama_responden);
            // $sheet->mergeCells('AP4:AZ4'); // Jika perlu merge dan belum di template
            // $sheet->getStyle('AP4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // --- (Tempat untuk mengisi data lain yang menyusul) ---

            // Siapkan Writer dan Nama File untuk Download
            $writer = new Xlsx($spreadsheet);
            $timestamp = now()->format('Ymd_His');
            $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'. $namaFile .'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // Log::error('PhpSpreadsheet Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal membuat file Excel (PhpSpreadsheet): ' . $e->getMessage());
        } catch (\Exception $e) {
            // Log::error('General Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan umum saat membuat file Excel: ' . $e->getMessage());
        }
    }

}
