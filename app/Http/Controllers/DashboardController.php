<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Fixed: Constructor with middleware
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    public function index()
    {
        $rtCounts = DB::table('tb_tenagakerja')
            ->select('rt', DB::raw('COUNT(*) as total'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->pluck('total', 'rt');   // koleksi key=RT, value=jumlah

        $grandTotal = $rtCounts->sum();

        return view('pages.dashboard', compact('rtCounts', 'grandTotal'));
        //return view('pages.dashboard');
    }
}
