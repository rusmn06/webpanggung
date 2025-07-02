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

    