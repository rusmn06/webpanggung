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
    // ... (method index dan show Anda) ...

    // GANTI SELURUH METHOD exportExcel ANDA DENGAN YANG DI BAWAH INI
    public function exportExcel($id)
    {
        $userId = Auth::id();
        $rumahTangga = RumahTangga::with('anggotaKeluarga')
                                    ->where('user_id', $userId)
                                    ->findOrFail($id);

        // Menggunakan nama template dari kode Anda
        $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx'); 

        try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0); // Targetkan sheet pertama

            // --- HELPER FUNCTIONS (didefinisikan di dalam method agar bisa akses $sheet dll jika perlu) ---
            $fillStringPerChar = function(Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') use ($Coordinate) {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $maxColIndex = Coordinate::columnIndexFromString($maxColumnChar);
                $words = explode(' ', (string)$string);
                $firstWordProcessed = false;

                foreach ($words as $word) {
                    if (empty($word)) continue; // Lewati kata kosong (jika ada spasi berlebih)

                    if ($firstWordProcessed) { // Jika ini bukan kata pertama yang diproses, tambahkan spasi
                        if ($currentColIndex <= $maxColIndex) {
                            // $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex) . $row, ' '); // Opsional: sel kosong untuk spasi
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
            
            $fillNumberPerDigit = function(Worksheet $currentSheet, $numberString, $numDigitsToFill, $startColumn, $row) use ($Coordinate) {
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                // Pastikan string angka memiliki panjang yang sesuai (misal, padding dengan '0' di kiri)
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, ' ', STR_PAD_LEFT); // Pad dengan spasi, atau '0' jika mau

                for ($i = 0; $i < $numDigitsToFill; $i++) { // Loop sebanyak jumlah kolom yang disediakan
                    $charToFill = ($i < strlen($paddedNumberString)) ? $paddedNumberString[$i] : ' '; // Isi spasi jika digit habis
                    $currentSheet->setCellValue(Coordinate::stringFromColumnIndex($currentColIndex + $i) . $row, $charToFill);
                }
            };
            // --- AKHIR HELPER FUNCTIONS ---


            // --- MULAI MENGISI DATA ---

            // 1. PENGENALAN TEMPAT
            // provinsi, O2 HINGGA AG2
            $fillStringPerChar($sheet, $rumahTangga->provinsi, 'O', 2, 'AG');
            // kabupaten/kota , O3 HINGGA AG3
            $fillStringPerChar($sheet, $rumahTangga->kabupaten, 'O', 3, 'AG');
            // kecamatan, O4 HINGGA AG4
            $fillStringPerChar($sheet, $rumahTangga->kecamatan, 'O', 4, 'AG');
            // desa/kelurahan, O5 HINGGA AG5
            $fillStringPerChar($sheet, $rumahTangga->desa, 'O', 5, 'AG');
            
            // rt/dusun, O6 HINGGA Q6 / S6 Hingga U6
            // RT: O6-Q6 (3 kolom)
            $fillNumberPerDigit($sheet, $rumahTangga->rt, 3, 'O', 6);
            // RW: S6-U6 (3 kolom)
            $fillNumberPerDigit($sheet, $rumahTangga->rw, 3, 'S', 6);
            // Untuk '/' di R6, jika template sudah ada, biarkan. Jika tidak: $sheet->setCellValue('R6', '/');


            // 2. PENDATAAN KETENAGAKERJAAN DI DESA
            if ($rumahTangga->tgl_pembuatan) {
                $tanggalPembuatan = Carbon::parse($rumahTangga->tgl_pembuatan);
                
                // Tanggal (AP2, AQ2) - 2 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('d'), 2, 'AP', 2);
                // Bulan (AS2, AT2) - 2 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('m'), 2, 'AS', 2);
                // Tahun (AV2 - AY2) - 4 digit
                $fillNumberPerDigit($sheet, $tanggalPembuatan->format('Y'), 4, 'AV', 2);
            }

            // Nama pendata, hanya 1 kolom hasil merge AP3 hingga AZ3
            $sheet->setCellValue('AP3', $rumahTangga->nama_pendata);
            // Asumsi template sudah merge AP3:AZ3. Jika belum, tambahkan: $sheet->mergeCells('AP3:AZ3');

            // nama responde, sama dengan nama pendata diatas (maksudnya di baris berikutnya AP4-AZ4)
            $sheet->setCellValue('AP4', $rumahTangga->nama_responden);
            // Asumsi template sudah merge AP4:AZ4. Jika belum, tambahkan: $sheet->mergeCells('AP4:AZ4');


            // --- BARIS-BARIS $sheet->setCellValue() LAMA ANDA YANG TIDAK PERLU PEMBAGIAN KARAKTER/DIGIT ---
            // --- BISA DITEMPATKAN DI SINI ATAU SESUAI URUTAN LOGISNYA ---
            // Contoh (data ini tidak Anda sebutkan di permintaan terakhir, jadi saya ambil dari kode Anda sebelumnya)
            // Pastikan koordinat sel nya sudah benar sesuai template Anda yang sekarang.
            // $sheet->setCellValue('K4', $rumahTangga->jart);
            // $sheet->setCellValue('L4', $rumahTangga->jart_ab);
            // $sheet->setCellValue('M4', $rumahTangga->jart_tb);
            // $sheet->setCellValue('N4', $rumahTangga->jart_ms);
            // $sheet->setCellValue('O4', $rumahTangga->jpr2rtp_text); // Perhatikan, O4 sekarang mungkin dipakai oleh Provinsi. Cek lagi!
            // $sheet->setCellValue('P4', $rumahTangga->status_validasi_text); // P4 juga mungkin dipakai Provinsi. Cek lagi!
            // if ($rumahTangga->admin_tgl_validasi) {
            //     $sheet->setCellValue('Q4', Carbon::parse($rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
            //     $sheet->setCellValue('R4', $rumahTangga->admin_nama_kepaladusun ?? '-');
            // } else {
            //     $sheet->setCellValue('Q4', '-');
            //     $sheet->setCellValue('R4', '-');
            // }
            // $sheet->setCellValue('S4', 'RT-' . $rumahTangga->id);

            // Mengisi Data Anggota Keluarga
            $startRowAnggota = 7; // Asumsi NIK dan data anggota lain dimulai di sini
            $anggotaCounter = 0;
            if ($rumahTangga->anggotaKeluarga && $rumahTangga->anggotaKeluarga->count() > 0) {
                foreach ($rumahTangga->anggotaKeluarga as $anggota) {
                    $currentRow = $startRowAnggota + $anggotaCounter;
                    $sheet->setCellValue('A' . $currentRow, $anggotaCounter + 1); // No
                    $sheet->setCellValue('B' . $currentRow, $anggota->nama);   // Nama Anggota

                    // NIK Anggota (16 kolom, misal dari C sampai R di $currentRow)
                    // $fillNumberPerDigit($sheet, $anggota->nik, 16, 'C', $currentRow); // Ini jika NIK juga per digit
                    // Jika NIK tetap satu sel:
                    $sheet->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);


                    // Kolom D, E, F, G, H, I untuk data anggota lain sesuai template Anda
                    // $sheet->setCellValue('D' . $currentRow, $anggota->kelamin_text);
                    // $sheet->setCellValue('E' . $currentRow, $anggota->hdkrt_text);
                    // $sheet->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text);
                    // $sheet->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text);
                    // $sheet->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text);
                    // $sheet->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text);
                    
                    $anggotaCounter++;
                }
            } else {
                // $sheet->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
            }
            // --- AKHIR BAGIAN PENGISIAN DATA ---

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