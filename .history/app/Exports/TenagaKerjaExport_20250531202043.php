<?php

namespace App\Exports;

use App\Models\RumahTangga;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional, untuk auto-size kolom
use Carbon\Carbon;

class TenagaKerjaExport implements WithEvents, ShouldAutoSize
{
    protected $rumahTangga;

    public function __construct(RumahTangga $rumahTangga)
    {
        $this->rumahTangga = $rumahTangga->load('anggotaKeluarga');
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 1. Tentukan Path ke Template Excel Anda
                $templatePath = storage_path('app/templates/template_data_responden.xlsx'); // GANTI NAMA FILE JIKA PERLU

                // 2. Load Template menggunakan PhpSpreadsheet
                $spreadsheet = IOFactory::load($templatePath);
                $sheet = $spreadsheet->getActiveSheet(); 

                // 3. Mengisi Data Utama (Rumah Tangga) ke Template
                // Sesuaikan sel berikut jika posisi di template Anda berbeda
                // Asumsi dari template Anda (Sheet1 - DATABASE RESPONDEN):
                // Data utama dimulai dari baris 4
                $sheet->setCellValue('A4', 1); 
                $sheet->setCellValue('B4', $this->rumahTangga->nama_responden);
                $sheet->setCellValue('C4', $this->rumahTangga->nama_pendata);
                $sheet->setCellValue('D4', Carbon::parse($this->rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY')); // Format tanggal diubah sedikit agar lebih umum
                $sheet->setCellValue('E4', $this->rumahTangga->provinsi);
                $sheet->setCellValue('F4', $this->rumahTangga->kabupaten);
                $sheet->setCellValue('G4', $this->rumahTangga->kecamatan);
                $sheet->setCellValue('H4', $this->rumahTangga->desa);
                $sheet->setCellValue('I4', $this->rumahTangga->rt);
                $sheet->setCellValue('J4', $this->rumahTangga->rw);
                $sheet->setCellValue('K4', $this->rumahTangga->jart);
                $sheet->setCellValue('L4', $this->rumahTangga->jart_ab);
                $sheet->setCellValue('M4', $this->rumahTangga->jart_tb);
                $sheet->setCellValue('N4', $this->rumahTangga->jart_ms);
                $sheet->setCellValue('O4', $this->rumahTangga->jpr2rtp_text); // Menggunakan accessor
                
                $sheet->setCellValue('P4', $this->rumahTangga->status_validasi_text); // Menggunakan accessor
                if ($this->rumahTangga->admin_tgl_validasi) {
                    $sheet->setCellValue('Q4', Carbon::parse($this->rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
                    $sheet->setCellValue('R4', $this->rumahTangga->admin_nama_kepaladusun ?? '-');
                } else {
                    $sheet->setCellValue('Q4', '-');
                    $sheet->setCellValue('R4', '-');
                }
                $sheet->setCellValue('S4', 'RT-' . $this->rumahTangga->id);


                // 4. Mengisi Data Anggota Keluarga
                // Asumsi data anggota keluarga dimulai dari baris 7 (Header di baris 6)
                $startRowAnggota = 7;
                $anggotaCounter = 0; 
                
                if ($this->rumahTangga->anggotaKeluarga && $this->rumahTangga->anggotaKeluarga->count() > 0) {
                    foreach ($this->rumahTangga->anggotaKeluarga as $anggota) {
                        $currentRow = $startRowAnggota + $anggotaCounter;

                        if ($anggotaCounter > 0) { 
                            // $sheet->insertNewRowBefore($currentRow, 1); // Uncomment jika perlu menyisipkan baris baru
                        }

                        $sheet->setCellValue('A' . $currentRow, $anggotaCounter + 1); 
                        $sheet->setCellValue('B' . $currentRow, $anggota->nama);
                        $sheet->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue('D' . $currentRow, $anggota->kelamin_text); 
                        $sheet->setCellValue('E' . $currentRow, $anggota->hdkrt_text); 
                        $sheet->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text); 
                        $sheet->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text); 
                        $sheet->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text); 
                        $sheet->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text); 
                        
                        $anggotaCounter++;
                    }
                } else {
                    $sheet->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
                    // $sheet->mergeCells('A'.$startRowAnggota.':I'.$startRowAnggota); // Uncomment jika ingin merge
                }

                // 5. Logika penggantian sheet (sama seperti sebelumnya)
                $finalSheet = $spreadsheet->getActiveSheet();
                $event->sheet->getDelegate()->getParent()->removeSheetByIndex(0); 
                $event->sheet->getDelegate()->getParent()->addSheet($finalSheet); 
                $event->sheet->getDelegate()->getParent()->setActiveSheetIndexByName($finalSheet->getTitle());
            },
        ];
    }
}