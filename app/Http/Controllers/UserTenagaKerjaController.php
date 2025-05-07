<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaKerja;
use Illuminate\Support\Facades\Auth;

class UserTenagaKerjaController extends Controller
{
    
    public function index()
    {
        // $items = TenagaKerja::where('id', Auth::id())
        $items = TenagaKerja::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.tenagakerja.index', compact('items'));
    }

    public function listrt()
    {
        // Kalau perlu, ambil data spesifik RT:
        // $rts = TenagaKerja::where('user_id',Auth::id())->get();
        //$items = TenagaKerja::orderBy('created_at','desc')->paginate(15);
        return view('pages.tenagakerja.listrt');
    }
    
    public function rtview(int $rt)
    {
        if ($rt < 1 || $rt > 24) {
            abort(404);
        }

        $items = TenagaKerja::where('rt', $rt)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("pages.tenagakerja.listrt.{$rt}", compact('items', 'rt'));
    }
}
