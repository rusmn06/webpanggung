<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\RumahTangga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TenagaKerjaController extends Controller
{
    public function create()
    {
        return view('pages.tenagakerja.create');
    }

    public function store(Request $request)
{
    try {
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
            'jart' => 'required|integer|min:1',
            'jart_ab' => 'required|integer|min:0',
            'jart_tb' => 'required|integer|min:0',
            'jart_ms' => 'required|integer|min:0',
            'jpr2rtp' => 'required|in:1,2,3,4,5',
            'verif_nama_pendata' => 'required|string|max:100',
            'verif_tgl_pembuatan' => 'required|date',
            'ttd_pendata' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'nama' => 'required|array|min:1',
            'nama.*' => 'required|string|max:100',
            'nik' => 'required|array|min:1',
            'nik.*' => 'required|string|digits:16|distinct',
            'kelamin' => 'required|array', 'kelamin.*' => 'required|in:1,2',
            'hdkrt' => 'required|array', 'hdkrt.*' => 'required',
            'hdkk' => 'required|array', 'hdkk.*' => 'required',
            'nuk' => 'required|array', 'nuk.*' => 'required|integer',
            'status_perkawinan' => 'required|array', 'status_perkawinan.*' => 'required',
            'status_pekerjaan' => 'required|array', 'status_pekerjaan.*' => 'required',
            'pendidikan_terakhir' => 'required|array', 'pendidikan_terakhir.*' => 'required',
            'jenis_pekerjaan' => 'nullable|array', 'jenis_pekerjaan.*' => 'nullable',
            'sub_jenis_pekerjaan' => 'nullable|array', 'sub_jenis_pekerjaan.*' => 'nullable',
            'pendapatan_per_bulan' => 'nullable|array', 'pendapatan_per_bulan.*' => 'nullable',
        ]);

        // DB::transaction akan me-return apapun yang di-return oleh closure di dalamnya
        return DB::transaction(function () use ($validatedData, $request) {
            $ttdPath = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
            $userSequenceNumber = RumahTangga::where('user_id', Auth::id())->count() + 1;

            $rumahTangga = RumahTangga::create([
                'user_id' => Auth::id(), 'user_sequence_number' => $userSequenceNumber,
                'provinsi' => $validatedData['provinsi'], 'kabupaten' => $validatedData['kabupaten'],
                'kecamatan' => $validatedData['kecamatan'], 'desa' => $validatedData['desa'],
                'rt' => $validatedData['rt'], 'rw' => $validatedData['rw'],
                'tgl_pembuatan' => $validatedData['tgl_pembuatan'], 'nama_pendata' => $validatedData['nama_pendata'],
                'nama_responden' => $validatedData['nama_responden'], 'jart' => $validatedData['jart'],
                'jart_ab' => $validatedData['jart_ab'], 'jart_tb' => $validatedData['jart_tb'],
                'jart_ms' => $validatedData['jart_ms'], 'jpr2rtp' => $validatedData['jpr2rtp'],
                'verif_tgl_pembuatan' => $validatedData['verif_tgl_pembuatan'],
                'verif_nama_pendata' => $validatedData['verif_nama_pendata'],
                'ttd_pendata' => basename($ttdPath), 'status_validasi' => 'pending',
            ]);

            foreach ($validatedData['nama'] as $index => $nama) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id' => $rumahTangga->id,
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

            // Redirect sekarang ada DI DALAM blok transaksi yang sukses
            return redirect()->route('tenagakerja.show', $rumahTangga->id)
                ->with('show_success_modal', true)
                ->with('success_message_title', '🎉 Pengajuan Terkirim!')
                ->with('success_message_body', 'Data Anda (Pengajuan Ke-' . $rumahTangga->user_sequence_number . ') telah berhasil dikirim.');
        });

    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan data pengajuan: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan server. Silakan coba lagi.');
    }
}
}