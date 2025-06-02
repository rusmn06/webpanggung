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

        $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx'); // Pastikan nama file ini benar

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0); // Targetkan sheet pertama

            // --- MULAI MENGISI DATA SESUAI PERMINTAAN ANDA ---

            // 1. PENGENALAN TEMPAT
            // Helper function untuk mengisi string per karakter dengan spasi antar kata
            $fillStringPerChar = function(Worksheet $sheet, $string, $startColumn, $row, $maxColumn = 'AG') use (&$Coordinate) {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumn);
                $words = explode(' ', (string)$string);
                $firstWord = true;

                foreach ($words as $word) {
                    if (!$firstWord) {
                        // Tambah 1 kolom kosong untuk spasi antar kata
                        if ($currentColIndex <= $maxColIndex) {
                            $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, ' '); // Atau biarkan kosong: ''
                            $currentColIndex++;
                        } else {
                            break; // Sudah melewati batas kolom maksimal
                        }
                    }
                    for ($i = 0; $i < strlen($word); $i++) {
                        if ($currentColIndex <= $maxColIndex) {
                            $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, $word[$i]);
                            $currentColIndex++;
                        } else {
                            break; // Sudah melewati batas kolom maksimal
                        }
                    }
                    if ($currentColIndex > $maxColIndex) break;
                    $firstWord = false;
                }
            };
            
            // Helper function untuk mengisi angka per digit
            $fillNumberPerDigit = function(Worksheet $sheet, $numberString, $startColumn, $row, $maxColumn) use (&$Coordinate) {
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

            // Isi Provinsi (O2 - AG2)
            $fillStringPerChar($sheet, $rumahTangga->provinsi, 'O', 2, 'AG');
            
            // Isi Kabupaten/Kota (O3 - AG3)
            $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');

            // Isi Kecamatan (O4 - AG4)
            $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
            
            // Isi Desa/Kelurahan (O5 - AG5)
            $fillStringPerChar($sheet, $rumahTangga->desa, 'O', 5, 'AG');

            // Isi RT (O6 - Q6)
            $rtString = str_pad((string)$rumahTangga->rt, 3, ' ', STR_PAD_LEFT); // Asumsi RT max 3 digit, pad dengan spasi di kiri jika kurang
            $fillNumberPerDigit($sheet, $rtString, 'O', 6, 'Q');
            
            // Isi RW (S6 - U6)
            $rwString = str_pad((string)$rumahTangga->rw, 3, ' ', STR_PAD_LEFT); // Asumsi RW max 3 digit
            $fillNumberPerDigit($sheet, $rwString, 'S', 6, 'U');
            // Note: Untuk "/" di R6, jika template sudah ada, biarkan. Jika tidak, Anda bisa set: $sheet->setCellValue('R6', '/');


            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                
                // Tanggal (AP2, AQ2) - 2 digit
                $hari = $tanggalPembuatan->format('d'); // Misal "05"
                $sheet->setCellValue('AP2', $hari[0]);
                $sheet->setCellValue('AQ2', $hari[1]);

                // Bulan (AS2, AT2) - 2 digit
                $bulan = $tanggalPembuatan->format('m'); // Misal "11"
                $sheet->setCellValue('AS2', $bulan[0]);
                $sheet->setCellValue('AT2', $bulan[1]);

                // Tahun (AV2 - AY2) - 4 digit
                $tahun = $tanggalPembuatan->format('Y'); // Misal "2023"
                $sheet->setCellValue('AV2', $tahun[0]);
                $sheet->setCellValue('AW2', $tahun[1]);
                $sheet->setCellValue('AX2', $tahun[2]);
                $sheet->setCellValue('AY2', $tahun[3]);
            }

            // Nama Pendata (AP3 - AZ3, asumsi sudah di-merge di template)
            $sheet->setCellValue('AP3', $rumahTangga->nama_pendata); 
            // Jika belum di-merge di template, Anda bisa tambahkan: $sheet->mergeCells('AP3:AZ3');
            // dan mungkin atur alignment: $sheet->getStyle('AP3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Nama Responden (AP4 - AZ4, asumsi sudah di-merge di template)
            $sheet->setCellValue('AP4', $rumahTangga->nama_responden);
            // Jika belum di-merge: $sheet->mergeCells('AP4:AZ4');
            // dan alignment jika perlu

            // --- AKHIR BAGIAN PENGISIAN DATA YANG DIMINTA ---


            // Siapkan Writer dan Nama File untuk Download
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $timestamp = now()->format('Ymd_His');
            // Nama file masih "Data_Tenaga_Kerja" karena ini fokus utama kita
            $namaFile = 'Data_Tenaga_Kerja_RT-' . $rumahTangga->id . '_' . $timestamp . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'. $namaFile .'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan umum saat membuat file Excel.');
        }
    }

}
