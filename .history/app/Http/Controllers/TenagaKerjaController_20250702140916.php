<?php

namespace App\Http\Controllers;

// Gunakan 'Request' standar untuk metode yang tidak butuh validasi kompleks
use Illuminate\Http\Request;

// Helper dan Fasad penting dari Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

// Model yang kita butuhkan
use App\Models\RumahTangga;
use App\Models\AnggotaKeluarga;

// Form Request untuk validasi
use App\Http\Requests\StoreTenagaKerjaRequest;


class TenagaKerjaController extends Controller
{
    // ... (metode create dan store yang sudah ada)

    /**
     * Menampilkan form untuk mengedit pengajuan yang sudah ada.
     */
    public function edit($id)
    {
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);

        // Menggunakan fasad Auth::id() yang lebih eksplisit
        if ($item->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return view('pages.tenagakerja.edit', compact('item'));
    }

    /**
     * Memperbarui data pengajuan di database.
     */
    public function update(StoreTenagaKerjaRequest $request, $id)
    {
        $validatedData = $request->validated();
        $item = RumahTangga::findOrFail($id);

        if ($item->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

       if ($item->status_validasi === 'validated') {
            return redirect()->route('tenagakerja.show', $item->id)
                ->with('error', 'Data yang sudah final tidak dapat diubah.');
        }

        DB::beginTransaction();
        try {
            // PERBAIKAN DI BLOK INI
            $statusPekerjaanCollection = new Collection($validatedData['status_pekerjaan']);
            $recap = [
                'jart'      => $statusPekerjaanCollection->count(),
                'jart_ab'   => $statusPekerjaanCollection->filter(fn($status) => $status == '1')->count(),
                'jart_ms'   => $statusPekerjaanCollection->filter(fn($status) => $status == '3')->count(),
                'jart_tb'   => $statusPekerjaanCollection->filter(fn($status) => in_array($status, ['2', '4', '5']))->count(),
            ];
            // AKHIR DARI BLOK PERBAIKAN

            $updateData = array_merge(
                Arr::except($validatedData, [
                    'nama', 'nik', 'kelamin', 'hdkrt', 'hdkk', 'nuk',
                    'status_perkawinan', 'status_pekerjaan', 'pendidikan_terakhir',
                    'jenis_pekerjaan', 'sub_jenis_pekerjaan', 'pendapatan_per_bulan'
                ]),
                $recap
            );
            
            if ($request->hasFile('ttd_pendata')) {
                if ($item->ttd_pendata) {
                    Storage::disk('public')->delete('ttd/pendata/' . $item->ttd_pendata);
                }
                $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
                $updateData['ttd_pendata'] = basename($path);
            }

            $item->update($updateData);

            $item->anggotaKeluarga()->delete();
            foreach ($validatedData['nama'] as $index => $nama) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id'      => $item->id,
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

            DB::commit();

            return redirect()->route('tenagakerja.show', $item->id)
                ->with('success', 'Data pengajuan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update data: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
}