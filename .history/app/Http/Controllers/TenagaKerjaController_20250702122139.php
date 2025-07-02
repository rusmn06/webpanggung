<?php

namespace App\Http\Controllers;

// Gunakan Form Request yang sudah kita buat di pembahasan sebelumnya
use App\Http\Requests\StoreTenagaKerjaRequest;
use App\Models\AnggotaKeluarga;
use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TenagaKerjaController extends Controller
{
    /**
     * Menampilkan form pembuatan pengajuan yang sudah terintegrasi.
     */
    public function create()
    {
        // View 'create' yang baru dan terpadu
        return view('pages.tenagakerja.create');
    }

    /**
     * Menyimpan seluruh data pengajuan dari form tunggal.
     * Menggabungkan semua logika validasi dan penyimpanan dari wizard.
     */
    public function store(Request $request)
    {
        try {
            // Data sudah divalidasi secara otomatis oleh StoreTenagaKerjaRequest
            $validatedData = $request->validated();

            // Mulai transaksi database untuk memastikan semua data tersimpan atau tidak sama sekali
            return DB::transaction(function () use ($validatedData, $request) {
                // 1. Hitung rekapitulasi di backend (lebih aman!)
                $statusPekerjaanCollection = new Collection($validatedData['status_pekerjaan']);
                $recap = [
                    'jart'      => count($validatedData['nama']),
                    'jart_ab'   => $statusPekerjaanCollection->whereIn(0, ['1'])->count(), // Bekerja
                    'jart_ms'   => $statusPekerjaanCollection->whereIn(0, ['3'])->count(), // Bersekolah
                    'jart_tb'   => $statusPekerjaanCollection->whereIn(0, ['2', '4', '5'])->count(), // IRT, Tdk/Belum Bekerja, Lainnya
                ];

                // 2. Handle upload file tanda tangan
                $ttdPath = $request->file('ttd_pendata')->store('ttd/pendata', 'public');

                // 3. Siapkan data untuk tabel 'rumah_tangga'
                $rumahTanggaData = array_merge(
                    // Ambil semua data utama, kecualikan data array milik anggota
                    Arr::except($validatedData, [
                        'nama', 'nik', 'kelamin', 'hdkrt', 'hdkk', 'nuk',
                        'status_perkawinan', 'status_pekerjaan', 'pendidikan_terakhir',
                        'jenis_pekerjaan', 'sub_jenis_pekerjaan', 'pendapatan_per_bulan'
                    ]),
                    $recap, // Gabungkan dengan data rekapitulasi yang sudah dihitung
                    [
                        'user_id'              => Auth::id(),
                        'user_sequence_number' => RumahTangga::where('user_id', Auth::id())->count() + 1,
                        'ttd_pendata'          => basename($ttdPath),
                        'status_validasi'      => 'pending',
                    ]
                );

                $rumahTangga = RumahTangga::create($rumahTanggaData);

                // 4. Loop untuk menyimpan semua anggota keluarga
                foreach ($validatedData['nama'] as $index => $nama) {
                    AnggotaKeluarga::create([
                        'rumah_tangga_id'      => $rumahTangga->id,
                        'nama'                 => $nama,
                        'nik'                  => $validatedData['nik'][$index],
                        'kelamin'              => $validatedData['kelamin'][$index],
                        'hdkrt'                => $validatedData['hdkrt'][$index],
                        'hdkk'                 => $validatedData['hdkk'][$index],
                        'nuk'                  => $validatedData['nuk'][$index],
                        'status_perkawinan'    => $validatedData['status_perkawinan'][$index],
                        'status_pekerjaan'     => $validatedData['status_pekerjaan'][$index],
                        'pendidikan_terakhir'  => $validatedData['pendidikan_terakhir'][$index],
                        'jenis_pekerjaan'      => $validatedData['jenis_pekerjaan'][$index] ?? null,
                        'sub_jenis_pekerjaan'  => $validatedData['sub_jenis_pekerjaan'][$index] ?? null,
                        'pendapatan_per_bulan' => $validatedData['pendapatan_per_bulan'][$index] ?? null,
                    ]);
                }

                // 5. Redirect ke halaman detail dengan pesan sukses
                return redirect()->route('tenagakerja.show', $rumahTangga->id)
                    ->with('show_success_modal', true);
            });

        } catch (ValidationException $e) {
            // Jika validasi gagal (seharusnya sudah ditangani FormRequest, tapi untuk jaga-jaga)
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Menangkap error fatal lainnya
            Log::error('Gagal menyimpan data pengajuan: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan fatal pada server. Silakan coba lagi.');
        }
    }
}