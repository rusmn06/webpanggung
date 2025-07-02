<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class TenagaKerjaVerifController extends Controller
{
    /**
     * Menampilkan daftar Rumah Tangga yang statusnya 'pending'.
     */
    public function index()
    {
        $items = RumahTangga::where('status_validasi', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        return view('admin.tkw.index', compact('items'));
    }

    /**
     * Menampilkan detail Rumah Tangga dan anggotanya untuk divalidasi.
     */
    public function show($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        return view('admin.tkw.show', compact('item'));
    }

    public function processVerification(Request $request, $id)
    {
        // 1. Validasi Input dari Form Verifikasi yang diperbarui
        $validatedData = $request->validate([
            'status' => 'required|in:validated,rejected',
            'admin_tgl_validasi' => 'required|date', // Tgl Validasi sekarang kembali divalidasi
            'admin_nama_kepaladusun' => 'required|string|max:100',
            'admin_ttd_pendata' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Diubah dari nullable menjadi required
            'admin_catatan' => 'required_if:status,rejected|nullable|string|max:500',
        ], [
            'admin_catatan.required_if' => 'Komentar/alasan wajib diisi jika pengajuan ditolak.',
            'admin_ttd_pendata.required' => 'Tanda tangan verifikator wajib diunggah.' // Pesan error baru
        ]);

        // 2. Cari data pengajuan
        $item = RumahTangga::findOrFail($id);

        // 3. Handle file upload TTD (logika tetap sama)
        $ttdPath = $item->admin_ttd_pendata;
        if ($request->hasFile('admin_ttd_pendata')) {
            if ($item->admin_ttd_pendata) {
                Storage::disk('public')->delete('ttd/admin/' . $item->admin_ttd_pendata);
            }
            $path = $request->file('admin_ttd_pendata')->store('ttd/admin', 'public');
            $ttdPath = basename($path);
        }

        // 4. Update data di database
        $item->update([
            'status_validasi' => $validatedData['status'],
            'admin_tgl_validasi' => $validatedData['admin_tgl_validasi'], // Ambil tanggal dari form
            'admin_nama_kepaladusun' => $validatedData['admin_nama_kepaladusun'],
            'admin_ttd_pendata' => $ttdPath,
            'admin_catatan' => $validatedData['admin_catatan'],
        ]);

        // 5. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.tkw.show', $item->id)
                        ->with('success', 'Status pengajuan untuk ' . $item->nama_responden . ' telah berhasil diperbarui!');
    }

    public function edit($id)
    {
        // Cari data, sama seperti metode show()
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);

        // Kita akan membuat view baru bernama 'edit.blade.php'
        // Ini akan berisi form yang bisa diubah
        return view('admin.tkw.edit', compact('item'));
    }

    // Kita siapkan juga kerangka untuk metode update
    public function update(Request $request, $id)
    {
        // Logika untuk validasi dan penyimpanan akan kita tulis di sini nanti
        // Untuk sekarang, kita bisa redirect kembali saja
        return redirect()->route('admin.tkw.show', $id)->with('success', 'Data akan diperbarui di sini!');
    }

    

    public function listRtPage()
    {
        // Hitung jumlah RumahTangga (Responden) per RT
        $rumahTanggaCounts = RumahTangga::select('rt', DB::raw('count(*) as total_rt'))
            ->whereBetween('rt', [1, 24])
            ->groupBy('rt')
            ->pluck('total_rt', 'rt'); // Hasil: [1 => 10, 2 => 15, ...]

        // Hitung jumlah AnggotaKeluarga per RT
        $anggotaCounts = AnggotaKeluarga::select('fm_rumah_tangga.rt', DB::raw('count(fm_anggota_keluarga.id) as total_anggota'))
            ->join('fm_rumah_tangga', 'fm_anggota_keluarga.rumah_tangga_id', '=', 'fm_rumah_tangga.id')
            ->whereBetween('fm_rumah_tangga.rt', [1, 24])
            ->groupBy('fm_rumah_tangga.rt')
            ->pluck('total_anggota', 'rt'); // Hasil: [1 => 50, 2 => 65, ...]

        // Kirim data counts ke view
        return view('admin.tkw.listrt', compact('rumahTanggaCounts', 'anggotaCounts'));
    }

    public function showRtData($rt)
    {
        // Ambil semua data Rumah Tangga untuk RT yang dipilih,
        // dan langsung muat (eager load) relasi anggotaKeluarga untuk setiap rumah tangga.
        // Ini jauh lebih efisien daripada query yang sebelumnya.
        $rumahTaggas = RumahTangga::where('rt', $rt)
                                    ->with('anggotaKeluarga') // Eager load anggota
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // Kirim data yang sudah dikelompokkan dan nomor RT ke view
        return view('admin.tkw.rtshow', [
            'rumahTaggas' => $rumahTaggas,
            'rt' => $rt,
        ]);
    }

    public function showHouseholdDetail($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);

        return view('admin.tkw.detail', compact('item'));
    }


    public function exportExcel($id)
    {
    $rumahTangga = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
    $templatePath = storage_path('app/templates/Template_Data_TenagaKerja.xlsx');
    try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getSheet(0);

            // Helper function untuk mengisi string per karakter
            $fillStringPerChar = function (Worksheet $currentSheet, $string, $startColumn, $row, $maxColumnChar = 'AG') {
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
                $currentColIndex = Coordinate::columnIndexFromString($startColumn);
                $paddedNumberString = str_pad((string)$numberString, $numDigitsToFill, $padChar, STR_PAD_LEFT);
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

            // Definisikan peta untuk baris setiap anggota keluarga
            $mainRows = [10, 13, 16, 19, 22, 25, 28, 31, 34];
            $cadanganRows = [65, 67, 69, 71];

            // Definisikan peta untuk kolom-kolom data
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
                    // Isi Nama Anggota
                    $sheet->setCellValue('D' . $namaRow, $anggota->nama);

                    // Isi NIK
                    $nikString = (string)$anggota->nik;
                    if (strlen($nikString) == 16) {
                        $fillNumberPerDigit($sheet, substr($nikString, 0, 6), 6, 'D', $infoRow); // Bagian 1
                        $fillNumberPerDigit($sheet, substr($nikString, 6, 6), 6, 'K', $infoRow); // Bagian 2
                        $fillNumberPerDigit($sheet, substr($nikString, 12, 4), 4, 'R', $infoRow); // Bagian 3
                    }
                    foreach ($dataColumns as $property => $column) {
                        $sheet->setCellValue($column . $infoRow, $anggota->$property);
                    }
                    $sheet->setCellValue('AY' . $infoRow, $anggota->pendapatan_per_bulan);
                }
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
            

            // Proses output file
            $writer = new Xlsx($spreadsheet);
        
            $namaResponden = $rumahTangga->nama_responden ?? 'TanpaNama';
            $namaRespondenClean = preg_replace('/[^\p{L}\p{N}\s_.-]/u', '', $namaResponden);
            $namaRespondenClean = str_replace(' ', '_', $namaRespondenClean);
            $tanggalDownload = now()->format('Y-m-d');
            $namaFile = 'Data_Tenaga_Kerja_' . $namaRespondenClean . '_' . $tanggalDownload . '.xlsx';

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $namaFile);

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            \Illuminate\Support\Facades\Log::error("PhpSpreadsheet Exception in exportExcel: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal membuat file Excel (PhpSpreadsheet): ' . $e->getMessage());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("General Exception in exportExcel: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan umum saat membuat file Excel: ' . $e->getMessage());
        }

    }
}