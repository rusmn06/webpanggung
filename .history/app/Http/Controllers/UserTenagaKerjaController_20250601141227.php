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
        $rumahTangga = RumahTangga::with('anggotaKeluarga')->where('user_id', $userId)->findOrFail($id);
        $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx'); // Pastikan nama file ini benar

        try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0); // Targetkan sheet pertama

            // HELPER FUNCTIONS (Sama seperti sebelumnya)
            $fillStringPerChar = function(Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumnChar);
                $words = explode(' ', (string)$string);
                $firstWordProcessed = false;

                foreach ($words as $word) {
                    if (empty($word) && !$firstWordProcessed) continue; // Lewati kata kosong di awal
                    if (empty($word) && $firstWordProcessed) { // Jika kata kosong di tengah, anggap sebagai satu spasi
                        if ($currentColIndex <= $maxColIndex) {
                            // $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, ' '); // Opsional jika ingin spasi tertulis
                            $currentColIndex++;
                            if ($currentColIndex > $maxColIndex) break;
                            continue;
                        } else {
                            break;
                        }
                    }

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
            
            $fillNumberPerDigit = function(Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row, $padChar = '0') {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, $padChar, STR_PAD_LEFT);

                for ($i = 0; $i < $numDigitsToFill; $i++) { 
                    $charToFill = ($i < strlen($paddedNumberString)) ? $paddedNumberString[$i] : ($padChar === '0' ? '0' : ' '); 
                    $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row, $charToFill);
                }
            };
            // --- END HELPER FUNCTIONS ---

            // --- MULAI MENGISI DATA ---

            // 1. PENGENALAN TEMPAT
            $fillStringPerChar($sheet, $rumahTangga->provinsi,  'O', 2, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
            $fillStringPerChar($sheet, $rumahTangga->desa,      'O', 5, 'AG');
            
            // RT (O6-Q6) - 3 kolom
            $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6, ' '); // Pad dengan spasi jika < 3 digit
            // RW (S6-U6) - 3 kolom
            $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6, ' '); // Pad dengan spasi jika < 3 digit
            // Jika "/" di R6 sudah ada di template, biarkan. Jika belum:
            // $sheet->setCellValue('R6', '/');


            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                // Tgl (AP2, AQ2) - 2 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('d'), 2, 'AP', 2);
                // Bulan (AS2, AT2) - 2 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('m'), 2, 'AS', 2);
                // Tahun (AV2 - AY2) - 4 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('Y'), 4, 'AV', 2);
            }
            // Nama Pendata (merge AP3 hingga AZ3)
            $sheet->setCellValue('AP3', $rumahTangga->nama_pendata);
            // Nama Responden (merge AP4 hingga AZ4)
            $sheet->setCellValue('AP4', $rumahTangga->nama_responden);


            // 3. KETERANGAN STATUS PEKERJAAN (ANGGOTA KELUARGA)
            // Maksimal 9 anggota keluarga
            $anggotaKeluarga = $rumahTangga->anggotaKeluarga->take(9); // Ambil maksimal 9 anggota

            $barisAnggota = [10, 13, 16, 19, 22, 25, 28, 31, 34]; // Baris untuk nama & NIK
            $barisAnggotaInfo = [11, 14, 17, 20, 23, 26, 29, 32, 35]; // Baris untuk info detail per anggota

            foreach ($anggotaKeluarga as $index => $anggota) {
                if ($index >= 9) break; // Pastikan tidak lebih dari 9

                $barisNamaSaatIni = $barisAnggota[$index];
                $barisInfoSaatIni = $barisAnggotaInfo[$index];

                // Isi Nama Anggota (merge D hingga T)
                $sheet->setCellValue('D' . $barisNamaSaatIni, $anggota->nama);

                // Isi NIK (dibagi 3 bagian: 6 digit, 6 digit, 4 digit)
                $nikString = (string)$anggota->nik;
                if (strlen($nikString) == 16) {
                    // Bagian 1 (D hingga I - 6 kolom)
                    $fillNumberPerDigit($sheet, substr($nikString, 0, 6), 6, 'D', $barisInfoSaatIni);
                    // Bagian 2 (K hingga P - 6 kolom)
                    $fillNumberPerDigit($sheet, substr($nikString, 6, 6), 6, 'K', $barisInfoSaatIni);
                    // Bagian 3 (R hingga U - 4 kolom)
                    $fillNumberPerDigit($sheet, substr($nikString, 12, 4), 4, 'R', $barisInfoSaatIni);
                } else {
                    // Handle jika NIK tidak 16 digit, mungkin isi dengan strip atau kosongkan
                    for ($k=0; $k<6; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('D')+$k) . $barisInfoSaatIni, '-');
                    for ($k=0; $k<6; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('K')+$k) . $barisInfoSaatIni, '-');
                    for ($k=0; $k<4; $k++) $sheet->setCellValue(Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString('R')+$k) . $barisInfoSaatIni, '-');
                }

                // Isi data lain per anggota (1 angka/kode per kolom)
                // Hubungan Kepala Rumah Tangga -> $anggota->hdkrt (bukan hdkrt_text karena butuh angkanya)
                $sheet->setCellValue('X'  . $barisInfoSaatIni, $anggota->hdkrt);
                // No urut anggota keluarga -> $anggota->nuk (Nomor Urut Keluarga)
                $sheet->setCellValue('AA' . $barisInfoSaatIni, $anggota->nuk);
                // Jenis Kelamin -> $anggota->kelamin
                $sheet->setCellValue('AD' . $barisInfoSaatIni, $anggota->kelamin);
                // Status Perkawinan -> $anggota->status_perkawinan
                $sheet->setCellValue('AG' . $barisInfoSaatIni, $anggota->status_perkawinan);
                // Status Pekerjaan -> $anggota->status_pekerjaan
                $sheet->setCellValue('AJ' . $barisInfoSaatIni, $anggota->status_pekerjaan);
                // Jenis Pekerjaan -> $anggota->jenis_pekerjaan
                $sheet->setCellValue('AM' . $barisInfoSaatIni, $anggota->jenis_pekerjaan);
                // Sub Jenis Pekerjaan -> $anggota->sub_jenis_pekerjaan
                $sheet->setCellValue('AP' . $barisInfoSaatIni, $anggota->sub_jenis_pekerjaan);
                // Pendidikan Terakhir -> $anggota->pendidikan_terakhir
                $sheet->setCellValue('AS' . $barisInfoSaatIni, $anggota->pendidikan_terakhir);
                // Pendapatan rata-rata perbulan -> $anggota->pendapatan_per_bulan
                $sheet->setCellValue('AV' . $barisInfoSaatIni, $anggota->pendapatan_per_bulan);
                // Untuk AY, Anda tidak menyebutkan datanya, saya kosongkan. Jika ada, tambahkan:
                // $sheet->setCellValue('AY' . $barisInfoSaatIni, $data_untuk_AY);
            }


            // 4. REKAPITULASI
            // JUMLAH ANGGOTA RUMAH TANGGA (N50 dan O50) - 2 digit
            $fillNumberPerDigit($sheet, $rumahTangga->jart, 2, 'N', 50);
            // JUMLAH ANGGOTA RUMAH TANGGA YANG AKTIF BEKERJA (N51 dan O51) - 2 digit
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ab, 2, 'N', 51);
            // JUMLAH ANGGOTA RUMAH TANGGA YANG TIDAK/BELUM BEKERJA (N52 dan O52) - 2 digit
            $fillNumberPerDigit($sheet, $rumahTangga->jart_tb, 2, 'N', 52);
            // JUMLAH ANGGOTA RUMAH TANGGA YANG MASIH SEKOLAH (N53 dan O53) - 2 digit
            $fillNumberPerDigit($sheet, $rumahTangga->jart_ms, 2, 'N', 53);
            // JUMLAH PENDAPATAN RATA-RATA RUMAH TANGGA PERBULAN (N54) - 1 digit kode
            $sheet->setCellValue('N54', $rumahTangga->jpr2rtp); // Langsung kodenya


            // 5. VERIFIKASI DAN VALIDASI
            // Pendata Tgl/Bulan/Tahun
            // Anda menyebutkan: U52 dan V52 / W52 dan S52 / Z52 dan AC52
            // Ada S52 di tengah, apakah ini typo? Saya asumsikan W52 dan X52 untuk bulan. Mohon periksa.
            // Saya akan gunakan asumsi S52 adalah typo dan seharusnya X52 (atau Anda bisa klarifikasi)
            if ($rumahTangga->verif_tgl_pembuatan) { // Asumsi ini kolom tanggal verifikasi oleh pendata
                $tanggalVerifPendata = Carbon::parse($rumahTangga->verif_tgl_pembuatan);
                // Tanggal (U52, V52)
                $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('d'), 2, 'U', 52);
                // Bulan (W52, X52) - ASUMSI X52, bukan S52
                $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('m'), 2, 'W', 52);
                // Tahun (Z52, AA52, AB52, AC52) - Ini 4 kolom
                $fillNumberPerDigit($sheet, $tanggalVerifPendata->format('Y'), 4, 'Z', 52);
            }
            // Tanda tangan pendata (merge T57 hingga AH57)
            // Jika Anda menyimpan path gambar atau hanya teks "TTD", isi di T57
            // $sheet->setCellValue('T57', 'TTD Pendata Sesuai'); // Atau path gambar jika bisa di-render
            // Untuk gambar, butuh kode lebih kompleks:
            // if ($rumahTangga->ttd_pendata && file_exists(storage_path('app/public/' . $rumahTangga->ttd_pendata))) {
            //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            //     $drawing->setName('TTD Pendata');
            //     $drawing->setDescription('TTD Pendata');
            //     $drawing->setPath(storage_path('app/public/' . $rumahTangga->ttd_pendata));
            //     $drawing->setHeight(50); // Sesuaikan ukuran
            //     $drawing->setCoordinates('T57'); // Pojok kiri atas area merge
            //     $drawing->setWorksheet($sheet);
            // }


            // Kepala Dusun Tgl/Bulan/Tahun
            // AP52 dan AQ52/ AS52 dan AT52/ AV52 dan AY52
            if ($rumahTangga->admin_tgl_validasi) {
                $tanggalValAdmin = Carbon::parse($rumahTangga->admin_tgl_validasi);
                // Tanggal (AP52, AQ52)
                $fillNumberPerDigit($sheet, $tanggalValAdmin->format('d'), 2, 'AP', 52);
                // Bulan (AS52, AT52)
                $fillNumberPerDigit($sheet, $tanggalValAdmin->format('m'), 2, 'AS', 52);
                // Tahun (AV52 - AY52)
                $fillNumberPerDigit($sheet, $tanggalValAdmin->format('Y'), 4, 'AV', 52);
            }
            // Tanda tangan kepala dusun (merge AP57 dan BC57)
            // Sama seperti TTD Pendata, isi di AP57
            // $sheet->setCellValue('AP57', 'TTD Kepala Dusun Sesuai');


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
