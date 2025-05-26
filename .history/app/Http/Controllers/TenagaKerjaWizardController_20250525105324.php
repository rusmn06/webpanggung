<?php

namespace App\Http\Controllers; // Jika Anda memindahkan ini ke App\Http\Controllers\User, sesuaikan namespace

// Model yang kita gunakan sekarang
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;

// Helper Laravel yang kita butuhkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// Jika Anda sudah memindahkan controller ini ke User namespace, ubah menjadi:
// namespace App\Http\Controllers\User;
// use App\Http\Controllers\Controller;

class TenagaKerjaWizardController extends Controller
{
    //======================================================================
    // LANGKAH 1, 2, 3 (Asumsi tidak ada perubahan signifikan dari kode Anda)
    //======================================================================
    public function showStep1()
    {
        $data = session('tk.step1', []);
        // Pastikan path view ini sesuai dengan struktur Anda
        return view('pages.tenagakerja.tkw.step1', compact('data'));
    }

    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'provinsi'      => 'required|string|max:20',
            'kabupaten'     => 'required|string|max:20',
            'kecamatan'     => 'required|string|max:20',
            'desa'          => 'required|string|max:20',
            'rt'            => 'required|string|max:3',
            'rw'            => 'required|string|max:3',
            'tgl_pembuatan' => 'required|date',
            'nama_pendata'  => 'required|string|max:100',
            'nama_responden'=> 'required|string|max:100',
        ]);
        session(['tk.step1' => $data]);
        // Pastikan nama route ini sesuai dengan definisi di web.php
        return redirect()->route('user.tkwizard.step2'); // Contoh jika nama route diubah
    }

    public function showStep2()
    {
        $data = session('tk.step2', []);
        return view('pages.tenagakerja.tkw.step2', compact('data'));
    }

    public function postStep2(Request $request)
    {
        $validatedData = $request->validate([
            'nama'                  => 'required|array',
            'nama.*'                => 'required|string|max:100',
            'nik'                   => 'required|array',
            'nik.*'                 => 'required|digits:16|distinct',
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
            'jenis_pekerjaan'       => 'required|array',
            'jenis_pekerjaan.*'     => 'required|in:1,2,3,4',
            'sub_jenis_pekerjaan'   => 'required|array',
            'sub_jenis_pekerjaan.*' => 'required|in:1,2,3,4,5',
            'pendidikan_terakhir'   => 'required|array',
            'pendidikan_terakhir.*' => 'required|in:1,2,3,4,5,6',
            'pendapatan_per_bulan'  => 'required|array',
            'pendapatan_per_bulan.*'=> 'required|in:1,2,3,4,5,6',
        ], [
            'nama.*.required' => 'Nama anggota #:position wajib diisi.',
            'nik.*.required'  => 'NIK anggota #:position wajib diisi.',
            'nik.*.digits'    => 'NIK anggota #:position harus 16 digit.',
            'nik.*.distinct'  => 'NIK anggota #:position tidak boleh sama dengan NIK anggota lain.',
        ]);
        session(['tk.step2' => $validatedData]);
        return redirect()->route('user.tkwizard.step3'); // Contoh jika nama route diubah
    }

    public function showStep3()
    {
        $data = session('tk.step3', []);
        return view('pages.tenagakerja.tkw.step3', compact('data'));
    }

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
        return redirect()->route('user.tkwizard.step4'); // Contoh jika nama route diubah
    }

    public function showStep4()
    {
        $data = session('tk.step4', []); // Data ini mungkin perlu diisi dengan nilai default dari Auth::user()->name untuk 'verif_nama_pendata'
                                        // dan now()->toDateString() untuk 'verif_tgl_pembuatan' jika kosong.
        // Contoh mengisi default jika $data kosong atau field tertentu tidak ada
        $data['verif_nama_pendata'] = old('verif_nama_pendata', $data['verif_nama_pendata'] ?? Auth::user()->name ?? '');
        $data['verif_tgl_pembuatan'] = old('verif_tgl_pembuatan', $data['verif_tgl_pembuatan'] ?? now()->toDateString());

        return view('pages.tenagakerja.tkw.step4', compact('data'));
    }

    //======================================================================
    // LANGKAH 4: VERIFIKASI & PENYIMPANAN AKHIR (Method yang dimodifikasi)
    //======================================================================
    public function postStep4(Request $request)
    {
        $step4Data = $request->validate([
            'verif_tgl_pembuatan'    => 'required|date',
            'verif_nama_pendata'     => 'required|string|max:100',
            'ttd_pendata'            => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'admin_nama_kepaladusun' => 'nullable|string|max:100', // Ini dari form step 4 user
        ]);

        $ttdPath = null;
        if ($request->hasFile('ttd_pendata')) {
            $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
            $ttdPath = basename($path);
        }

        $step1Data = session('tk.step1');
        $step2Data = session('tk.step2');
        $step3Data = session('tk.step3');

        if (!$step1Data || !$step2Data || !$step3Data) {
            // Sesuaikan nama route ke halaman awal wizard jika nama route sudah diubah
            return redirect()->route('user.tkwizard.step1')->with('error', 'Sesi Anda telah berakhir, silakan mulai dari awal.');
        }

        DB::beginTransaction();
        try {
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
                'ttd_pendata'       => $ttdPath,
                // admin_nama_kepaladusun dari form user di step 4, BUKAN dari admin
                'admin_nama_kepaladusun' => $step4Data['admin_nama_kepaladusun'] ?? null,
                'status_validasi'   => 'pending',
                'user_id'           => Auth::id(),
            ]);

            $memberCount = count($step2Data['nama']);
            for ($i = 0; $i < $memberCount; $i++) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'     => $rumahTangga->id,
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
            DB::commit();
            session()->forget(['tk.step1', 'tk.step2', 'tk.step3', 'tk.step4']);

            // ================================================================
            // PERUBAHAN UTAMA DI SINI: Redirect ke halaman detail data baru
            // ================================================================
            // Pastikan route 'user.submission.tkw.show' sudah didefinisikan di web.php
            // dan mengarah ke UserTenagaKerjaController@show (atau controller user yang sesuai)
            return redirect()->route('user.submission.tkw.show', $rumahTangga->id)
                             ->with('show_success_modal', true)
                             ->with('success_message_title', '🎉 Pengajuan Terkirim!')
                             ->with('success_message_body', 'Data rumah tangga Anda telah berhasil dikirim dan sedang menunggu proses verifikasi oleh admin. Anda bisa memantau statusnya secara berkala. Terima kasih atas partisipasinya!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data wizard: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan fatal saat menyimpan data. Silakan coba lagi atau hubungi dukungan.');
        }
    }
}