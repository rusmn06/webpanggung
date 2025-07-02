<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RumahTangga; // Pastikan ini ada

class TenagaKerjaController extends Controller
{
    // ... (metode create dan store yang sudah ada)

    /**
     * Menampilkan form untuk mengedit pengajuan yang sudah ada.
     */
    public function edit($id)
    {
        // Cari data rumah tangga beserta relasi anggota keluarganya
        $item = RumahTangga::with('anggotaKeluarga')->findOrFail($id);

        // PENTING: Otorisasi (Contoh sederhana)
        // Pastikan pengguna yang login adalah pemilik data
        if ($item->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Kita akan menggunakan ulang view 'create', karena tampilannya sama.
        // Kita hanya perlu mengirimkan data yang akan diedit.
        return view('pages.tenagakerja.edit', compact('item'));
    }

    /**
     * Memperbarui data pengajuan di database.
     */
    public function update(StoreTenagaKerjaRequest $request, $id)
    {
        $validatedData = $request->validated();
        $item = RumahTangga::findOrFail($id);

        // Otorisasi lagi untuk keamanan
        if ($item->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Jangan biarkan data yang sudah divalidasi/ditolak diubah
        if (in_array($item->status_validasi, ['validated', 'rejected'])) {
             return redirect()->route('tenagakerja.show', $item->id)
                ->with('error', 'Data yang sudah final tidak dapat diubah.');
        }

        DB::beginTransaction();
        try {
            // Logika update mirip dengan 'store', tapi kita me-replace data lama

            // 1. Kalkulasi ulang rekapitulasi di backend
            $statusPekerjaanCollection = new Collection($validatedData['status_pekerjaan']);
            $recap = [
                'jart'      => count($validatedData['nama']),
                'jart_ab'   => $statusPekerjaanCollection->whereIn(0, ['1'])->count(),
                'jart_ms'   => $statusPekerjaanCollection->whereIn(0, ['3'])->count(),
                'jart_tb'   => $statusPekerjaanCollection->whereIn(0, ['2', '4', '5'])->count(),
            ];

            // 2. Siapkan data utama untuk di-update
            $updateData = array_merge(
                 Arr::except($validatedData, [ /* ... field array anggota ... */ ]),
                 $recap
            );

            // 3. Handle jika ada upload TTD baru
            if ($request->hasFile('ttd_pendata')) {
                // Hapus file lama jika ada
                if ($item->ttd_pendata) {
                    Storage::disk('public')->delete('ttd/pendata/' . $item->ttd_pendata);
                }
                // Simpan file baru
                $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
                $updateData['ttd_pendata'] = basename($path);
            }

            // 4. Update data RumahTangga
            $item->update($updateData);

            // 5. STRATEGI TERBAIK: Hapus semua anggota lama lalu buat ulang dari data form
            // Ini jauh lebih simpel dan aman daripada mencocokkan satu per satu
            $item->anggotaKeluarga()->delete(); 
            foreach ($validatedData['nama'] as $index => $nama) {
                AnggotaKeluarga::create([
                    'rumah_tangga_id' => $item->id,
                    'nama' => $nama,
                    // ... semua field anggota lainnya
                    'nik' => $validatedData['nik'][$index],
                    'kelamin' => $validatedData['kelamin'][$index],
                    // ...dst
                ]);
            }

            DB::commit();

            return redirect()->route('tenagakerja.show', $item->id)
                ->with('success', 'Data pengajuan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update data: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
}