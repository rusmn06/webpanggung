<?php

namespace App\Exports;

use App\Models\RumahTangga;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TenagaKerjaExport implements WithEvents, ShouldAutoSize
{
    protected $rumahTangga;
    protected $loadedSpreadsheet;

    public function __construct(RumahTangga $rumahTangga)
    {
        $this->rumahTangga = $rumahTangga->load('anggotaKeluarga');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx'); // Pastikan nama file ini benar

                $this->loadedSpreadsheet = IOFactory::load($templatePath);
                $sheetFromTemplateOriginal = $this->loadedSpreadsheet->getSheet(0);

                // --- MULAI ISI DATA KE $sheetFromTemplateOriginal ---
                $sheetFromTemplateOriginal->setCellValue('A4', 1);
                $sheetFromTemplateOriginal->setCellValue('B4', $this->rumahTangga->nama_responden);
                $sheetFromTemplateOriginal->setCellValue('C4', $this->rumahTangga->nama_pendata);
                $sheetFromTemplateOriginal->setCellValue('D4', Carbon::parse($this->rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY')); // Sedikit penyesuaian format agar lebih umum
                $sheetFromTemplateOriginal->setCellValue('E4', $this->rumahTangga->provinsi);
                $sheetFromTemplateOriginal->setCellValue('F4', $this->rumahTangga->kabupaten);
                $sheetFromTemplateOriginal->setCellValue('G4', $this->rumahTangga->kecamatan);
                $sheetFromTemplateOriginal->setCellValue('H4', $this->rumahTangga->desa);
                $sheetFromTemplateOriginal->setCellValue('I4', $this->rumahTangga->rt);
                $sheetFromTemplateOriginal->setCellValue('J4', $this->rumahTangga->rw);
                $sheetFromTemplateOriginal->setCellValue('K4', $this->rumahTangga->jart);
                $sheetFromTemplateOriginal->setCellValue('L4', $this->rumahTangga->jart_ab);
                $sheetFromTemplateOriginal->setCellValue('M4', $this->rumahTangga->jart_tb);
                $sheetFromTemplateOriginal->setCellValue('N4', $this->rumahTangga->jart_ms);
                $sheetFromTemplateOriginal->setCellValue('O4', $this->rumahTangga->jpr2rtp_text);
                $sheetFromTemplateOriginal->setCellValue('P4', $this->rumahTangga->status_validasi_text);
                if ($this->rumahTangga->admin_tgl_validasi) {
                    $sheetFromTemplateOriginal->setCellValue('Q4', Carbon::parse($this->rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
                    $sheetFromTemplateOriginal->setCellValue('R4', $this->rumahTangga->admin_nama_kepaladusun ?? '-');
                } else {
                    $sheetFromTemplateOriginal->setCellValue('Q4', '-');
                    $sheetFromTemplateOriginal->setCellValue('R4', '-');
                }
                $sheetFromTemplateOriginal->setCellValue('S4', 'RT-' . $this->rumahTangga->id);

                $startRowAnggota = 7;
                $anggotaCounter = 0;
                if ($this->rumahTangga->anggotaKeluarga && $this->rumahTangga->anggotaKeluarga->count() > 0) {
                    foreach ($this->rumahTangga->anggotaKeluarga as $anggota) {
                        $currentRow = $startRowAnggota + $anggotaCounter;
                        $sheetFromTemplateOriginal->setCellValue('A' . $currentRow, $anggotaCounter + 1);
                        $sheetFromTemplateOriginal->setCellValue('B' . $currentRow, $anggota->nama);
                        $sheetFromTemplateOriginal->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheetFromTemplateOriginal->setCellValue('D' . $currentRow, $anggota->kelamin_text);
                        $sheetFromTemplateOriginal->setCellValue('E' . $currentRow, $anggota->hdkrt_text);
                        $sheetFromTemplateOriginal->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text);
                        $sheetFromTemplateOriginal->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text);
                        $sheetFromTemplateOriginal->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text);
                        $sheetFromTemplateOriginal->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text);
                        $anggotaCounter++;
                    }
                } else {
                    $sheetFromTemplateOriginal->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
                }
                // --- AKHIR ISI DATA ---

                // 5. Mengganti sheet yang akan diekspor
                $spreadsheetYangAkanDiekspor = $event->sheet->getDelegate()->getParent();

                $sheetCount = $spreadsheetYangAkanDiekspor->getSheetCount();
                for ($i = 0; $i < $sheetCount; $i++) {
                    $spreadsheetYangAkanDiekspor->removeSheetByIndex(0);
                }

                // !!! PERUBAHAN PENTING DI SINI !!!
                // Kita CLONE sheet dari template sebelum menambahkannya
                $clonedSheetFromTemplate = clone $sheetFromTemplateOriginal;
                $spreadsheetYangAkanDiekspor->addSheet($clonedSheetFromTemplate);
                // !!! AKHIR PERUBAHAN PENTING !!!

                $spreadsheetYangAkanDiekspor->setActiveSheetIndex(0);
            },
        ];
    }
}