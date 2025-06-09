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

    public function index()
    {
        $userId = Auth::id();
        $userSubmissionsQuery = RumahTangga::where('user_id', $userId);

        // Statistik
        $totalPengajuan = (clone $userSubmissionsQuery)->count();
        $pengajuanPending = (clone $userSubmissionsQuery)->where('status_validasi', 'pending')->count();
        $pengajuanDisetujui = (clone $userSubmissionsQuery)->where('status_validasi', 'validated')->count();
        $pengajuanDitolak = (clone $userSubmissionsQuery)->where('status_validasi', 'rejected')->count();

        $items = RumahTangga::where('user_id', $userId)
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
            $fillStringPerChar = function (Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') {
                // ... (Fungsi ini tidak diubah) ...
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumnChar);
                $words = explode(' ', (string)$string);
                $firstWordProcessed = false;
                foreach ($words as $word) {
                    if (empty($word) && !$firstWordProcessed) continue;
                    if (empty($word) && $firstWordProcessed) {
                        if ($currentColIndex <= $maxColIndex) {
                            $currentColIndex++;
                            if ($currentColIndex > $maxColIndex) break;
                            continue;
                        } else {
                            break;
                        }
                    }
                    if ($firstWordProcessed) {
                        if ($currentColIndex <= $maxColIndex) {
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
            $fillNumberPerDigit = function (Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row, $padChar = '0') {
                // ... (Fungsi ini tidak diubah) ...
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, $padChar, STR_PAD_LEFT);
                for ($i = 0; $i < $numDigitsToFill; $i++) {
                    $charToFill = $paddedNumberString[$i];
                    $cellCoordinate = Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row;
                    $currentSheet->setCellValue($cellCoordinate, $charToFill);
                }
            };

            // 1. PENGENALAN TEMPAT (Tidak diubah)
            // ... (Kode ini tidak diubah) ...
            $fillStringPerChar($sheet, strtoupper($rumahTangga->provinsi),  'O', 2, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->kabupaten), 'O', 3, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->kecamatan), 'O', 4, 'AG');
            $fillStringPerChar($sheet, strtoupper($rumahTangga->desa),      'O', 5, 'AG');
            $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6, '0');
            $sheet->setCellValue('R6', '/');
            $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6, '0');


            // 2. PENDATAAN KETENAGAKERJAAN DI DESA (Tidak diubah)
            // ... (Kode ini tidak diubah) ...
            if ($rumahTangga->tgl_pembuatan) {
                $tglPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('d'), 2, 'AP', 2);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('m'), 2, 'AS', 2);
                $fillNumberPerDigit($sheet, $tglPembuatan->format('Y'), 4, 'AV', 2);
            }
            $sheet->setCellValue('AP4', $rumahTangga->nama_pendata); 
            $sheet->setCellValue('AP6', $rumahTangga->nama_responden);


            // ========================================================================= //
            // == BAGIAN YANG DIPERBARUI: PENGISIAN DATA ANGGOTA KELUARGA (3 & 4)      == //
            // ========================================================================= //

            // Definisikan "peta" untuk baris setiap anggota keluarga
            $mainRows = [10, 13, 16, 19, 22, 25, 28, 31, 34]; // Untuk anggota 1-9
            $cadanganRows = [65, 67, 69, 71]; // Untuk anggota 10-13

            // Definisikan "peta" untuk kolom-kolom data
            $dataColumns = [
                'hdkrt'                 => 'X',
                'nuk'                   => 'AA',
                'kelamin'               => 'AD',
                'status_perkawinan'     => 'AG',
                'status_pekerjaan'      => 'AJ',
                'jenis_pekerjaan'       => 'AM',
                'sub_jenis_pekerjaan'   => 'AP',
                'pendidikan_terakhir'   => 'AS',
                'pendapatan_per_bulan'  => 'AV',
            ];

            foreach ($rumahTangga->anggotaKeluarga as $index => $anggota) {
                $namaRow = null;
                $infoRow = null;

                if ($index < 9) {
                    // Anggota ke 1-9 (Bagian Utama)
                    if (isset($mainRows[$index])) {
                        $namaRow = $mainRows[$index];
                        $infoRow = $namaRow + 1;
                    }
                } else {
                    // Anggota ke 10+ (Bagian Cadangan)
                    $cadanganIndex = $index - 9;
                    if (isset($cadanganRows[$cadanganIndex])) {
                        $namaRow = $cadanganRows[$cadanganIndex];
                        $infoRow = $namaRow + 1;
                    }
                }

                // Jika baris ditemukan, isi datanya. Jika tidak, lewati (misal anggota ke-14 dst)
                if ($namaRow && $infoRow) {
                    // Isi Nama Anggota (di cell merge D:T)
                    $sheet->setCellValue('D' . $namaRow, $anggota->nama);

                    // Isi NIK (dibagi 3 bagian)
                    $nikString = (string)$anggota->nik;
                    if (strlen($nikString) == 16) {
                        $fillNumberPerDigit($sheet, substr($nikString, 0, 6), 6, 'D', $infoRow); // Bagian 1
                        $fillNumberPerDigit($sheet, substr($nikString, 6, 6), 6, 'K', $infoRow); // Bagian 2
                        $fillNumberPerDigit($sheet, substr($nikString, 12, 4), 4, 'R', $infoRow); // Bagian 3
                    }

                    // Isi data lainnya menggunakan peta kolom
                    foreach ($dataColumns as $property => $column) {
                        $sheet->setCellValue($column . $infoRow, $anggota->$property);
                    }
                    // Khusus kolom AY juga diisi dengan pendapatan
                    $sheet->setCellValue('AY' . $infoRow, $anggota->pendapatan_per_bulan);
                }
            }
            
            // Kolom cadangan lama (sekarang sudah digabung di atas) sengaja saya hapus
            // agar tidak ada duplikasi atau kesalahan logika.


            // 5. REKAPITULASI (Tidak diubah)
            // ... (Kode ini tidak diubah) ...
            $fillNumberPerDigit($sheet, $rumahTangga->jart, 2, 'N', 50);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ab, 2, 'N', 51);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_tb, 2, 'N', 52);
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ms, 2, 'N', 53);
            $sheet->setCellValue('N54', $rumahTangga->jpr2rtp);
            

            // 6. VERIFIKASI DAN VALIDASI (Tidak diubah)
            // ... (Kode ini tidak diubah) ...
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
            

            // Proses output file
            $writer = new Xlsx($spreadsheet);
            
            // 1. Ambil nama responden dan bersihkan untuk nama file
            $namaResponden = $rumahTangga->nama_responden ?? 'TanpaNama';
            $namaRespondenClean = preg_replace('/[^A-Za-z0-9_.-]/', '_', $namaResponden); // Ganti karakter non-alfanumerik dengan _
            
            // 2. Ambil tanggal hari ini
            $tanggalDownload = now()->format('Y-m-d'); // Format: 2025-06-09

            // 3. Susun nama file baru sesuai format yang diinginkan
            $namaFile = 'Data_Tenaga_Kerja_' . $namaRespondenClean . '_' . $tanggalDownload . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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