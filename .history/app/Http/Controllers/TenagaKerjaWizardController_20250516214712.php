<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class TenagaKerjaWizardController extends Controller
{
    // step 1: informasi form
    public function showStep1()
    {
        $data = session('tk.step1', []);
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
        return redirect()->route('tkw.step2');
    }

    // step 2: identitas
    public function showStep2()
    {
        $data = session('tk.step2', []);
        return view('pages.tenagakerja.tkw.step2', compact('data'));
    }

    public function postStep2(Request $request)
    {
        $data = $request->validate([
            'nama'                 => 'required|string|max:100',
            'nik'                  => 'required|digits_between:1,20',
            'hdkrt'                => 'required|in:1,2,3,4,5,6,7,8',
            'nuk'                  => 'required|in:1,2',
            'hdkk'                 => 'required|in:1,2,3,4,5,6,7,8',
            'kelamin'              => 'required|in:1,2',
            'status_perkawinan'    => 'required|in:1,2,3,4',
            'status_pekerjaan'     => 'required|in:1,2,3,4,5',
            'jenis_pekerjaan'      => 'required|in:1,2,3,4',
            'sub_jenis_pekerjaan'  => 'required|in:1,2,3,4,5',
            'pendidikan_terakhir'  => 'required|in:1,2,3,4,5,6',
            'pendapatan_per_bulan' => 'required|in:1,2,3,4,5,6',
        ]);

        session(['tk.step2' => $data]);
        return redirect()->route('tkw.step3');
    }

    // step 3: rekapitulasi
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
        return redirect()->route('tkw.step4');
    }

    // step 4: verifikasi dan validasi
    public function showStep4()
    {
        $data = session('tk.step4', []);
        return view('pages.tenagakerja.tkw.step4', compact('data'));
    }

    public function postStep4(Request $request)
    {
        $data = $request->validate([
            'verif_tgl_pembuatan'    => 'required|date',
            'verif_nama_pendata'     => 'required|string|max:100',
            'ttd_pendata'            => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'admin_nama_kepaladusun' => 'nullable|string|max:100',
        ]);

        // Save pendata signature
        if ($request->hasFile('ttd_pendata')) {
            $path = $request->file('ttd_pendata')->store('ttd/pendata', 'public');
            $data['ttd_pendata'] = basename($path);
        }

        // Merge all step data
        $all = array_merge(
            session('tk.step1', []),
            session('tk.step2', []),
            session('tk.step3', []),
            $data
        );

        // Persist and clear session
        TenagaKerja::create($all + ['id'   => Auth::id()]);
        session()->forget(['tk.step1', 'tk.step2', 'tk.step3', 'tk.step4']);

        return redirect()->route('tkw.step1')
                         ->with('success', 'Data berhasil disimpan dan menunggu validasi admin.');
    }

    
}
