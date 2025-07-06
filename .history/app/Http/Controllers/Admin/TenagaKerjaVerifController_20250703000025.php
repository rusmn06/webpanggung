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
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
        // 1. Gunakan aturan validasi kondisional yang sudah kita buat
        $validatedData = $request->validate([
            'status' => 'required|in:validated,rejected',
            'admin_tgl_validasi' => 'required_if:status,validated|nullable|date',
            'admin_nama_kepaladusun' => 'required_if:status,validated|nullable|string|max:100',
            'admin_ttd_pendata' => 'required_if:status,validated|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'admin_catatan' => 'required_if:status,rejected|nullable|string|max:500',
        ], [
            'admin_catatan.required_if' => 'Komentar/alasan wajib diisi jika pengajuan ditolak.',
            'admin_tgl_validasi.required_if' => 'Tanggal verifikasi wajib diisi jika pengajuan disetujui.',
            'admin_nama_kepaladusun.required_if' => 'Nama verifikator wajib diisi jika pengajuan disetujui.',
            'admin_ttd_pendata.required_if' => 'Tanda tangan verifikator wajib diunggah jika pengajuan disetujui.'
        ]);

        // 2. Cari data pengajuan
        $item = RumahTangga::findOrFail($id);

        // 3. Siapkan array untuk menampung data yang akan di-update
        $updateData = [
            'status_validasi' => $validatedData['status'],
            'admin_catatan' => $validatedData['admin_catatan'] ?? null,
        ];

        // 4. Handle file upload dan data lain HANYA JIKA status 'validated'
        if ($validatedData['status'] === 'validated') {
            $ttdPath = $item->admin_ttd_pendata;
            if ($request->hasFile('admin_ttd_pendata')) {
                if ($item->admin_ttd_pendata) {
                    Storage::disk('public')->delete('ttd/admin/' . $item->admin_ttd_pendata);
                }
                $path = $request->file('admin_ttd_pendata')->store('ttd/admin', 'public');
                $ttdPath = basename($path);
            }

            // Masukkan data validasi ke array update
            $updateData['admin_tgl_validasi'] = $validatedData['admin_tgl_validasi'];
            $updateData['admin_nama_kepaladusun'] = $validatedData['admin_nama_kepaladusun'];
            $updateData['admin_ttd_pendata'] = $ttdPath;
        } else {
            // Jika ditolak, kita bisa kosongkan field ini di database untuk kebersihan data
            $updateData['admin_tgl_validasi'] = null;
            $updateData['admin_nama_kepaladusun'] = null;
            $updateData['admin_ttd_pendata'] = null;
        }

        // 5. Update data di database dengan array yang sudah kita siapkan
        $item->update($updateData);

        // 6. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.tkw.show', $item->id)
                        ->with('success', 'Status pengajuan untuk ' . $item->nama_responden . ' telah berhasil diperbarui!');
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

    public function edit($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);
        
        // Menggunakan view yang sudah kita siapkan untuk admin
        return view('admin.tkw.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi Lengkap
        // Aturan ini diambil dari StoreTenagaKerjaRequest dan disesuaikan
        $validatedData = $request->validate([
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'tgl_pembuatan' => 'required|date',
            'nama_pendata' => 'required|string|max:100',
            'nama_responden' => 'required|string|max:100',
            'jpr2rtp' => 'required|in:1,2,3,4,5',
            'verif_tgl_pembuatan' => 'required|date',
            'ttd_pendata' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama' => 'required|array|min:1',
            'nama.*' => 'required|string|max:100',
            'nik' => 'required|array|min:1',
            'nik.*' => 'required|string|digits:16|distinct',
            'kelamin' => 'required|array',
            'kelamin.*' => 'required|in:1,2',
            'hdkrt' => 'required|array', 'hdkrt.*' => 'required|integer',
            'hdkk' => 'required|array', 'hdkk.*' => 'required|integer',
            'nuk' => 'required|array', 'nuk.*' => 'required|integer',
            'status_perkawinan' => 'required|array', 'status_perkawinan.*' => 'required|integer',
            'status_pekerjaan' => 'required|array', 'status_pekerjaan.*' => 'required|integer',
            'pendidikan_terakhir' => 'required|array', 'pendidikan_terakhir.*' => 'required|integer',
            'jenis_pekerjaan' => 'nullable|array', 'jenis_pekerjaan.*' => 'nullable|integer',
            'sub_jenis_pekerjaan' => 'nullable|array', 'sub_jenis_pekerjaan.*' => 'nullable|integer',
            'pendapatan_per_bulan' => 'nullable|array', 'pendapatan_per_bulan.*' => 'nullable|integer',
        ]);

        $item = RumahTangga::findOrFail($id);

        DB::beginTransaction();
        try {
            // 2. Kalkulasi ulang rekapitulasi dari data yang divalidasi
            $recap = [
                'jart'      => count($validatedData['nama']),
                'jart_ab'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => $s == '1')->count(),
                'jart_ms'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => $s == '3')->count(),
                'jart_tb'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => in_array($s, ['2', '4', '5']))->count(),
            ];

            // 3. Siapkan data untuk update tabel utama
            $updateData = array_merge(
                Arr::except($validatedData, [
                    'nama', 'nik', 'kelamin', 'hdkrt', 'hdkk', 'nuk',
                    'status_perkawinan', 'status_pekerjaan', 'pendidikan_terakhir',
                    'jenis_pekerjaan', 'sub_jenis_pekerjaan', 'pendapatan_per_bulan',
                    'ttd_pendata' // Kecualikan TTD karena dihandle terpisah
                ]),
                $recap
            );
            
            // 4. Handle TTD jika admin menggantinya
            if ($request->hasFile('ttd_pendata')) {
                if ($item->ttd_pendata) {
                    Storage::disk('public')->delete('ttd/pendata/' . $item->ttd_pendata);
                }
                $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
                $updateData['ttd_pendata'] = basename($path);
            }
            
            // 5. Update data RumahTangga
            $item->update($updateData);

            // 6. Hapus anggota keluarga lama dan buat ulang dari data form
            $item->anggotaKeluarga()->delete();
            foreach ($validatedData['nama'] as $index => $nama) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id' => $item->id,
                    'nama' => $nama,
                    'nik' => $validatedData['nik'][$index],
                    'kelamin' => $validatedData['kelamin'][$index],
                    'hdkrt' => $validatedData['hdkrt'][$index],
                    'hdkk' => $validatedData['hdkk'][$index],
                    'nuk' => $validatedData['nuk'][$index],
                    'status_perkawinan' => $validatedData['status_perkawinan'][$index],
                    'status_pekerjaan' => $validatedData['status_pekerjaan'][$index],
                    'pendidikan_terakhir' => $validatedData['pendidikan_terakhir'][$index],
                    'jenis_pekerjaan' => $validatedData['jenis_pekerjaan'][$index] ?? null,
                    'sub_jenis_pekerjaan' => $validatedData['sub_jenis_pekerjaan'][$index] ?? null,
                    'pendapatan_per_bulan' => $validatedData['pendapatan_per_bulan'][$index] ?? null,
                ]);
            }

            // 7. Setelah di-edit oleh admin, kembalikan statusnya ke 'pending'
            $item->status_validasi = 'pending';
            $item->admin_catatan_validasi = 'Data telah diperbarui oleh admin.'; // Tambahkan catatan otomatis
            $item->save();

            DB::commit();

            return redirect()->route('admin.tkw.show', $item->id)
                             ->with('success', 'Data pengajuan berhasil diperbarui oleh Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin gagal update data: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
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