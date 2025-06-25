<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class AdminController extends Controller 
{
    public function dashboard()
    {
        $rtCounts = DB::table('tb_tenagakerja')
            ->select('rt', DB::raw('COUNT(*) as total'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->pluck('total', 'rt');   // koleksi key=RT, value=jumlah

        $grandTotal = $rtCounts->sum();

        return view('admin.dashboard', compact('rtCounts', 'grandTotal'));
        //return view('admin.dashboard');
    }

}

