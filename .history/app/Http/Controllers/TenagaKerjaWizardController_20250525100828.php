<?php

namespace App\Http\Controllers;

// Model yang kita gunakan sekarang
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;

// Helper Laravel yang kita butuhkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Untuk menangani file

class TenagaKerjaWizardController extends Controller
{
    //======================================================================
    // LANGKAH 1: INFORMASI LOKASI & PENDATA
    //======================================================================

    /**
     * Menampilkan form untuk Langkah 1.
     * Mengambil data dari session jika ada (untuk edit atau kembali).
     */
    public function showStep1()
    {
        $data = session('tk.step1', []);
        return view('pages.tenagakerja.tkw.step1', compact('data'));
    }

    /**
     * Memvalidasi dan menyimpan data Langkah 1 ke session.
     * Lalu mengarahkan ke Langkah 2.
     */
    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'provinsi'      => 'required|string|max:20',
            'kabupaten'     => 'required|string|max:20',
            'kecamatan'     => 'required|string|max:20',
            'desa'          => 'required|string|max:20',
            'rt'            => 'required|string|max:3', // Mungkin lebih baik 'numeric' atau 'integer'?
            'rw'            => 'required|string|max:3', // Mungkin lebih baik 'numeric' atau 'integer'?
            'tgl_pembuatan' => 'required|date',
            'nama_pendata'  => 'required|string|max:100',
            'nama_responden'=> 'required|string|max:100',
        ]);

        session(['tk.step1' => $data]);
        return redirect()->route('tkw.step2');
    }

    //======================================================================
    // LANGKAH 2: IDENTITAS ANGGOTA KELUARGA
    //======================================================================

    /**
     * Menampilkan form untuk Langkah 2.
     * Mengambil data (yang mungkin berbentuk array) dari session.
     */
    public function showStep2()
    {
        $data = session('tk.step2', []);
        return view('pages.tenagakerja.tkw.step2', compact('data'));
    }

    /**
     * Memvalidasi dan menyimpan data Langkah 2 (array anggota) ke session.
     * Lalu mengarahkan ke Langkah 3.
     */
    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nama'                  => 'required|array',
            'nama.*'                => 'required|string|max:100',
            'nik'                   => 'required|array',
            'nik.*'                 => 'required|digits:16|distinct', // NIK harus 16 digit & unik dalam 1 form
            'hdkrt'                 => 'required|array',
            'hdkrt.*'               => 'required|in:1,2,3,4,5,6,7,8',
            'nuk'                   => 'required|array',
            'nuk.*'                 => 'required|integer|min:1|max:99',
            'hdkk'                  => 'required|array',
            'hdkk.*'                => 'required|in:1,2,3,4,5,6,7,8',
            'kelamin'               => 'required|array',
            'kelamin.*'             => 'required|in:1,2',
            'status_perkawinan'     => 'required|array',
            'status_perkawinan.*'   => 'required|in:1,2,3,4',
            'status_pekerjaan'      => 'required|array',
            'status_pekerjaan.*'    => 'required|in:1,2,3,4,5',
            'jenis_pekerjaan'       => 'required|array', // Pertimbangkan 'nullable' jika tidak semua status bekerja
            'jenis_pekerjaan.*'     => 'required|in:1,2,3,4',
            'sub_jenis_pekerjaan'   => 'required|array', // Pertimbangkan 'nullable'
            'sub_jenis_pekerjaan.*' => 'required|in:1,2,3,4,5',
            'pendidikan_terakhir'   => 'required|array',
            'pendidikan_terakhir.*' => 'required|in:1,2,3,4,5,6',
            'pendapatan_per_bulan'  => 'required|array',
            'pendapatan_per_bulan.*'=> 'required|in:1,2,3,4,5,6',
        ], [
            // Pesan error custom untuk validasi array
            'nama.*.required' => 'Nama anggota #:position wajib diisi.',
            'nik.*.required'  => 'NIK anggota #:position wajib diisi.',
            'nik.*.digits'    => 'NIK anggota #:position harus 16 digit.',
            'nik.*.distinct'  => 'NIK anggota #:position tidak boleh sama dengan NIK anggota lain.',
            // ... (pesan custom lainnya) ...
        ]);

        session(['tk.step2' => $validatedData]);
        return redirect()->route('tkw.step3');
    }

    //======================================================================
    // LANGKAH 3: REKAPITULASI RUMAH TANGGA
    //======================================================================

    /**
     * Menampilkan form untuk Langkah 3.
     */
    public function showStep3()
    {
        $data = session('tk.step3', []);
        return view('pages.tenagakerja.tkw.step3', compact('data'));
    }

    /**
     * Memvalidasi dan menyimpan data Langkah 3 ke session.
     * Lalu mengarahkan ke Langkah 4.
     */
    public function postStep3(Request $request)
    {
        $data = $request->validate([
            'jart'    => 'required|integer|min:0|max:99',
            'jart_ab' => 'required|integer|min:0|max:99',
            'jart_tb' => 'required|integer|min:0|max:99',
            'jart_ms' => 'required|integer|min:0|max:99',
            'jpr2rtp' => 'required|in:0,1,2,3,4',
        ]);

        session(['tk.step3' => $data]);
        return redirect()->route('tkw.step4');
    }

    //======================================================================
    // LANGKAH 4: VERIFIKASI & PENYIMPANAN AKHIR
    //======================================================================

    /**
     * Menampilkan form untuk Langkah 4.
     */
    public function showStep4()
    {
        $data = session('tk.step4', []);
        return view('pages.tenagakerja.tkw.step4', compact('data'));
    }

    /**
     * REVISI FUNGSI INI!
     * Menyimpan data ke tabel RumahTangga dan AnggotaKeluarga
     * dengan status 'pending' untuk divalidasi admin.
     */
    public function postStep4(Request $request)
    {
        // Validasi Step 4
        $step4Data = $request->validate([
            'verif_tgl_pembuatan'    => 'required|date',
            'verif_nama_pendata'     => 'required|string|max:100',
            'ttd_pendata'            => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'admin_nama_kepaladusun' => 'nullable|string|max:100',
        ]);

        // Handle file upload
        $ttdPath = null; // Default null
        if ($request->hasFile('ttd_pendata')) {
            $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
            $ttdPath = basename($path); // Hanya simpan nama file
        }

        // Ambil data dari session
        $step1Data = session('tk.step1');
        $step2Data = session('tk.step2');
        $step3Data = session('tk.step3');

        // Pastikan semua session ada
        if (!$step1Data || !$step2Data || !$step3Data) {
            return redirect()->route('tkw.step1')->with('error', 'Sesi Anda telah berakhir, silakan mulai dari awal.');
        }

        // Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // 1. Simpan data Rumah Tangga dengan status 'pending'
            $rumahTangga = RumahTangga::create([
                'provinsi'          => $step1Data['provinsi'],
                'kabupaten'         => $step1Data['kabupaten'],
                'kecamatan'         => $step1Data['kecamatan'],
                'desa'              => $step1Data['desa'],
                'rt'                => $step1Data['rt'],
                'rw'                => $step1Data['rw'],
                'tgl_pembuatan'     => $step1Data['tgl_pembuatan'],
                'nama_pendata'      => $step1Data['nama_pendata'],
                'nama_responden'    => $step1Data['nama_responden'],
                'jart'              => $step3Data['jart'],
                'jart_ab'           => $step3Data['jart_ab'],
                'jart_tb'           => $step3Data['jart_tb'],
                'jart_ms'           => $step3Data['jart_ms'],
                'jpr2rtp'           => $step3Data['jpr2rtp'],
                'verif_tgl_pembuatan'=> $step4Data['verif_tgl_pembuatan'],
                'verif_nama_pendata' => $step4Data['verif_nama_pendata'],
                'ttd_pendata'       => $ttdPath, // Simpan path TTD user
                'status_validasi'   => 'pending', // <-- SET KE PENDING
                'user_id'           => Auth::id(),
                // Field admin_... biarkan null dulu, diisi saat validasi
            ]);

            // 2. Simpan data setiap Anggota Keluarga
            $memberCount = count($step2Data['nama']);
            for ($i = 0; $i < $memberCount; $i++) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'     => $rumahTangga->id, // Link ke RT
                    'nama'                => $step2Data['nama'][$i],
                    'nik'                 => $step2Data['nik'][$i],
                    'hdkrt'               => $step2Data['hdkrt'][$i],
                    'nuk'                 => $step2Data['nuk'][$i],
                    'hdkk'                => $step2Data['hdkk'][$i],
                    'kelamin'             => $step2Data['kelamin'][$i],
                    'status_perkawinan'   => $step2Data['status_perkawinan'][$i],
                    'status_pekerjaan'    => $step2Data['status_pekerjaan'][$i],
                    'jenis_pekerjaan'     => $step2Data['jenis_pekerjaan'][$i],
                    'sub_jenis_pekerjaan' => $step2Data['sub_jenis_pekerjaan'][$i],
                    'pendidikan_terakhir' => $step2Data['pendidikan_terakhir'][$i],
                    'pendapatan_per_bulan'=> $step2Data['pendapatan_per_bulan'][$i],
                ]);
            }

            // 3. Commit transaksi
            DB::commit();

            // 4. Hapus session
            session()->forget(['tk.step1', 'tk.step2', 'tk.step3', 'tk.step4']);

            // 5. Redirect dengan sukses
            return redirect()->route('tkw.step1') // Atau ke halaman status/terima kasih
                             ->with('success', 'Data Anda berhasil dikirim dan menunggu validasi admin.');

        } catch (\Exception $e) {
            // 6. Rollback jika ada error
            DB::rollBack();
            Log::error('Gagal menyimpan data wizard: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()
                         ->with('error', 'Terjadi kesalahan fatal saat mengirim data. Silakan coba lagi.');
        }
    }
}