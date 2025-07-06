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

        // Ganti seluruh blok try...catch Anda dengan ini
DB::beginTransaction();
try {
    // ... (kode untuk $recap dan $updateData tetap sama) ...
    $statusPekerjaanCollection = new Collection($validatedData['status_pekerjaan']);
    $recap = [
        'jart'      => count($validatedData['nama']),
        'jart_ab'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => $s == '1')->count(),
        'jart_ms'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => $s == '3')->count(),
        'jart_tb'   => collect($validatedData['status_pekerjaan'])->filter(fn($s) => in_array($s, ['2', '4', '5']))->count(),
    ];
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
    } // <-- PERBAIKAN 1: Kurung kurawal '}' untuk menutup foreach diletakkan di sini.

    // PERBAIKAN 2: Logika 'if' diletakkan SETELAH loop selesai.
    if ($item->status_validasi === 'rejected') {
        $item->status_validasi = 'pending';
        $item->admin_catatan_validasi = null;
        $item->save();
    }

    DB::commit();

    return redirect()->route('tenagakerja.show', $item->id)
                     ->with('show_success_modal', true)
                     ->with('success_message_title', 'Data Berhasil Diperbarui!')
                     ->with('success_message_body', 'Perubahan pada data pengajuan Anda telah berhasil disimpan.');

} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Gagal update data: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
    return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
}
    }
}