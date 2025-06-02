<?php

namespace App\Http\Controllers;

use App\Models\RumahTangga;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;         // << PENTING untuk helper
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;     // << PENTING untuk type hint helper
use Carbon\Carbon;


class UserTenagaKerjaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userSubmissionsQuery = RumahTangga::where('user_id', $userId);

        $totalPengajuan = (clone $userSubmissionsQuery)->count();
        $pengajuanPending = (clone $userSubmissionsQuery)->where('status_validasi', 'pending')->count();
        $pengajuanDisetujui = (clone $userSubmissionsQuery)->where('status_validasi', 'validated')->count();
        $pengajuanDitolak = (clone $userSubmissionsQuery)->where('status_validasi', 'rejected')->count();

        $items = $userSubmissionsQuery->orderBy('created_at', 'desc')->paginate(6);

        return view('pages.tenagakerja.index', compact(
            'items',
            'totalPengajuan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    public function show($id)
    {
    $userId = Auth::id();
    $item = RumahTangga::with('anggotaKeluarga')
                       ->where('user_id', $userId)
                       ->findOrFail($id);

    // Tambahan: Hitung nomor urut pengajuan untuk user ini
    $userAllSubmissionIds = RumahTangga::where('user_id', $userId)
                                       ->orderBy('created_at', 'asc')
                                       ->pluck('id')
                                       ->toArray();

    $itemSequenceIndex = array_search($item->id, $userAllSubmissionIds);

        // nomor urut ke objek $item untuk dikirim ke view
        if ($itemSequenceIndex !== false) {
                $item->user_sequence_number = $itemSequenceIndex + 1;
            } else {
                $item->user_sequence_number = '?';
        }
    }

    [{
	"resource": "/C:/laragon/www/webpanggung/app/Http/Controllers/UserTenagaKerjaController.php",
	"owner": "_generated_diagnostic_collection_name_#0",
	"code": "P1001",
	"severity": 8,
	"message": "Unexpected 'public'.",
	"source": "intelephense",
	"startLineNumber": 59,
	"startColumn": 5,
	"endLineNumber": 59,
	"endColumn": 11
},{
	"resource": "/C:/laragon/www/webpanggung/app/Http/Controllers/UserTenagaKerjaController.php",
	"owner": "_generated_diagnostic_collection_name_#5",
	"code": "PHP2014",
	"severity": 8,
	"message": "Syntax error: unexpected token 'public'",
	"source": "PHP",
	"startLineNumber": 59,
	"startColumn": 5,
	"endLineNumber": 59,
	"endColumn": 11
}]
}