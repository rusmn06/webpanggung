<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TenagaKerjaController extends Controller
{
    // Metode untuk menampilkan halaman riwayat (diasumsikan ada di UserTenagaKerjaController)
    // public function index() { ... }

    // Metode untuk menampilkan detail (diasumsikan ada di UserTenagaKerjaController)
    // public function show($id) { ... }


    /**
     * Menampilkan form halaman tunggal untuk membuat pengajuan baru.
     */
    public function create()
    {
        return view('pages.tenagakerja.create');
    }

    /**
     * Menyimpan data pengajuan baru dari form halaman tunggal.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Informasi Pengajuan
            'provinsi'        => 'required|string|max:50',
            'kabupaten'       => 'required|string|max:50',
            'kecamatan'       => 'required|string|max:50',
            'desa'            => 'required|string|max:50',
            'rt'              => 'required|numeric',
            'rw'              => 'required|numeric',
            'tgl_pembuatan'   => 'required|date',
            'nama_pendata'    => 'required|string|max:100',
            'nama_responden'  => 'required|string|max:100',
            
            // Rekapitulasi & Verifikasi
            'jart'              => 'required|integer|min:1',
            'jart_ab'           => 'required|integer|min:0',
            'jart_tb'           => 'required|integer|min:0',
            'jart_ms'           => 'required|integer|min:0',
            'jpr2rtp'           => 'required|in:1,2,3,4,5',
            'verif_nama_pendata'  => 'required|string|max:100',
            'verif_tgl_pembuatan' => 'required|date',
            'ttd_pendata'       => 'required|image|mimes:jpg,jpeg,png|max:2048',

            // Anggota Keluarga
            'nama'                  => 'required|array|min:1',
            'nama.*'                => 'required|string|max:100',
            'nik'                   => 'required|array|min:1',
            'nik.*'                 => 'required|string|digits:16|distinct',
            'kelamin'               => 'required|array', 'kelamin.*' => 'required|in:1,2',
            'hdkrt'                 => 'required|array', 'hdkrt.*' => 'required',
            'hdkk'                  => 'required|array', 'hdkk.*' => 'required',
            'nuk'                   => 'required|array', 'nuk.*' => 'required|integer',
            'status_perkawinan'     => 'required|array', 'status_perkawinan.*' => 'required',
            'status_pekerjaan'      => 'required|array', 'status_pekerjaan.*' => 'required',
            'pendidikan_terakhir'   => 'required|array', 'pendidikan_terakhir.*' => 'required',
            // Jenis pekerjaan bisa nullable jika statusnya bukan 'Bekerja'
            'jenis_pekerjaan'       => 'required|array', 'jenis_pekerjaan.*' => 'nullable',
            'sub_jenis_pekerjaan'   => 'required|array', 'sub_jenis_pekerjaan.*' => 'nullable',
            'pendapatan_per_bulan'  => 'required|array', 'pendapatan_per_bulan.*' => 'nullable',
        ], [
            'nama.min' => 'Minimal harus ada 1 anggota keluarga.',
            'nik.*.distinct' => 'Ada NIK yang sama terinput lebih dari satu kali.',
            'ttd_pendata.required' => 'Tanda tangan pendata wajib diunggah.',
        ]);

        DB::beginTransaction();
        try {
            $ttdPath = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
            $userSequenceNumber = RumahTangga::where('user_id', Auth::id())->count() + 1;

            $rumahTangga = RumahTangga::create([
                'user_id' => Auth::id(),
                'user_sequence_number' => $userSequenceNumber,
                'provinsi' => $validatedData['provinsi'],
                'kabupaten' => $validatedData['kabupaten'],
                'kecamatan' => $validatedData['kecamatan'],
                'desa' => $validatedData['desa'],
                'rt' => $validatedData['rt'],
                'rw' => $validatedData['rw'],
                'tgl_pembuatan' => $validatedData['tgl_pembuatan'],
                'nama_pendata' => $validatedData['nama_pendata'],
                'nama_responden' => $validatedData['nama_responden'],
                'jart' => $validatedData['jart'],
                'jart_ab' => $validatedData['jart_ab'],
                'jart_tb' => $validatedData['jart_tb'],
                'jart_ms' => $validatedData['jart_ms'],
                'jpr2rtp' => $validatedData['jpr2rtp'],
                'verif_tgl_pembuatan' => $validatedData['verif_tgl_pembuatan'],
                'verif_nama_pendata' => $validatedData['verif_nama_pendata'],
                'ttd_pendata' => basename($ttdPath),
                'status_validasi' => 'pending',
            ]);

            $memberCount = count($validatedData['nama']);
            for ($i = 0; $i < $memberCount; $i++) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'   => $rumahTangga->id,
                    'nama'              => $validatedData['nama'][$i],
                    'nik'               => $validatedData['nik'][$i],
                    'kelamin'           => $validatedData['kelamin'][$i],
                    'hdkrt'             => $validatedData['hdkrt'][$i],
                    'hdkk'              => $validatedData['hdkk'][$i],
                    'nuk'               => $validatedData['nuk'][$i],
                    'status_perkawinan' => $validatedData['status_perkawinan'][$i],
                    'status_pekerjaan'  => $validatedData['status_pekerjaan'][$i],
                    'pendidikan_terakhir' => $validatedData['pendidikan_terakhir'][$i],
                    // Cek jika key ada sebelum mengaksesnya
                    'jenis_pekerjaan'   => $validatedData['jenis_pekerjaan'][$i] ?? null,
                    'sub_jenis_pekerjaan' => $validatedData['sub_jenis_pekerjaan'][$i] ?? null,
                    'pendapatan_per_bulan' => $validatedData['pendapatan_per_bulan'][$i] ?? null,
                ]);
            }

            DB::commit();
            
            // KEMBALIKAN KE HALAMAN DETAIL UNTUK MENAMPILKAN MODAL
            return redirect()->route('tenagakerja.show', $rumahTangga->id)
                ->with('show_success_modal', true)
                ->with('success_message_title', '🎉 Pengajuan Terkirim!')
                ->with('success_message_body', 'Data rumah tangga Anda (Pengajuan Ke-'.$rumahTangga->user_sequence_number.') telah berhasil dikirim. Silakan tunggu proses verifikasi oleh admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data pengajuan: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan. Pastikan semua data terisi dengan benar. Error: ' . $e->getMessage());
        }
    }
}