<?php

namespace App\Exports;

use App\Models\RumahTangga; // Pastikan ini model utama Anda
use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Tidak perlu di-use secara eksplisit jika tidak dipakai langsung
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TenagaKerjaExport implements WithEvents, ShouldAutoSize
{
    protected $rumahTangga;
    protected $loadedSpreadsheet; // Properti untuk menyimpan spreadsheet yang di-load dari template

    public function __construct(RumahTangga $rumahTangga)
    {
        // Memuat relasi anggotaKeluarga saat objek RumahTangga diterima
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
                // PASTIKAN NAMA FILE TEMPLATE INI BENAR: 'template_data_kuisioner.xlsx'
                // Sebelumnya kita membahas 'template_data_responden.xlsx'. Sesuaikan jika perlu.
                $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx');

                // 2. Load Template menggunakan PhpSpreadsheet
                $this->loadedSpreadsheet = IOFactory::load($templatePath);
                // Kita akan bekerja dengan sheet pertama dari template yang di-load
                $sheetFromTemplate = $this->loadedSpreadsheet->getSheet(0); // Atau ->getActiveSheet() jika pasti hanya satu

                // 3. Mengisi Data Utama (Rumah Tangga) ke $sheetFromTemplate
                // (Kode pengisian data Anda untuk data utama RumahTangga)
                $sheetFromTemplate->setCellValue('A4', 1);
                $sheetFromTemplate->setCellValue('B4', $this->rumahTangga->nama_responden);
                $sheetFromTemplate->setCellValue('C4', $this->rumahTangga->nama_pendata);
                $sheetFromTemplate->setCellValue('D4', Carbon::parse($this->rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY'));
                $sheetFromTemplate->setCellValue('E4', $this->rumahTangga->provinsi);
                $sheetFromTemplate->setCellValue('F4', $this->rumahTangga->kabupaten);
                $sheetFromTemplate->setCellValue('G4', $this->rumahTangga->kecamatan);
                $sheetFromTemplate->setCellValue('H4', $this->rumahTangga->desa);
                $sheetFromTemplate->setCellValue('I4', $this->rumahTangga->rt);
                $sheetFromTemplate->setCellValue('J4', $this->rumahTangga->rw);
                $sheetFromTemplate->setCellValue('K4', $this->rumahTangga->jart);
                $sheetFromTemplate->setCellValue('L4', $this->rumahTangga->jart_ab);
                $sheetFromTemplate->setCellValue('M4', $this->rumahTangga->jart_tb);
                $sheetFromTemplate->setCellValue('N4', $this->rumahTangga->jart_ms);
                $sheetFromTemplate->setCellValue('O4', $this->rumahTangga->jpr2rtp_text); // Menggunakan accessor
                $sheetFromTemplate->setCellValue('P4', $this->rumahTangga->status_validasi_text); // Menggunakan accessor
                if ($this->rumahTangga->admin_tgl_validasi) {
                    $sheetFromTemplate->setCellValue('Q4', Carbon::parse($this->rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
                    $sheetFromTemplate->setCellValue('R4', $this->rumahTangga->admin_nama_kepaladusun ?? '-');
                } else {
                    $sheetFromTemplate->setCellValue('Q4', '-');
                    $sheetFromTemplate->setCellValue('R4', '-');
                }
                $sheetFromTemplate->setCellValue('S4', 'RT-' . $this->rumahTangga->id);

                // 4. Mengisi Data Anggota Keluarga ke $sheetFromTemplate
                // (Kode pengisian data Anda untuk anggotaKeluarga)
                $startRowAnggota = 7;
                $anggotaCounter = 0;
                if ($this->rumahTangga->anggotaKeluarga && $this->rumahTangga->anggotaKeluarga->count() > 0) {
                    foreach ($this->rumahTangga->anggotaKeluarga as $anggota) {
                        $currentRow = $startRowAnggota + $anggotaCounter;
                        
                        // Jika template Anda hanya punya 1 baris contoh untuk anggota,
                        // dan Anda perlu menyisipkan baris baru untuk anggota ke-2 dst.:
                        if ($anggotaCounter > 0 && $currentRow > $sheetFromTemplate->getHighestRow()) {
                             // $sheetFromTemplate->insertNewRowBefore($currentRow, 1); // Hati-hati dengan ini, pastikan sesuai kebutuhan
                        }

                        $sheetFromTemplate->setCellValue('A' . $currentRow, $anggotaCounter + 1);
                        $sheetFromTemplate->setCellValue('B' . $currentRow, $anggota->nama);
                        $sheetFromTemplate->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheetFromTemplate->setCellValue('D' . $currentRow, $anggota->kelamin_text);
                        $sheetFromTemplate->setCellValue('E' . $currentRow, $anggota->hdkrt_text);
                        $sheetFromTemplate->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text);
                        $sheetFromTemplate->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text);
                        $sheetFromTemplate->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text);
                        $sheetFromTemplate->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text);
                        
                        $anggotaCounter++;
                    }
                } else {
                    $sheetFromTemplate->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
                    // Pertimbangkan untuk merge: $sheetFromTemplate->mergeCells('A'.$startRowAnggota.':I'.$startRowAnggota);
                }

                // 5. Mengganti sheet yang akan diekspor dengan sheet dari template kita yang sudah diisi
                $spreadsheetYangAkanDiekspor = $event->sheet->getDelegate()->getParent(); // Ini adalah objek Spreadsheet utama yang dikelola Maatwebsite

                // Hapus semua sheet yang mungkin sudah ada di objek Spreadsheet Maatwebsite
                $jumlahSheetAwal = $spreadsheetYangAkanDiekspor->getSheetCount();
                for ($i = 0; $i < $jumlahSheetAwal; $i++) {
                    $spreadsheetYangAkanDiekspor->removeSheetByIndex(0); // Hapus dari index 0 karena index akan bergeser
                }

                // Tambahkan sheet dari template kita (yang sudah diisi data) ke objek Spreadsheet Maatwebsite
                // $sheetFromTemplate adalah objek sheet yang sudah kita modifikasi dari $this->loadedSpreadsheet
                $spreadsheetYangAkanDiekspor->addSheet($sheetFromTemplate);

                // Set sheet yang baru kita tambahkan sebagai sheet aktif (biasanya yang pertama ditambahkan akan jadi aktif)
                $spreadsheetYangAkanDiekspor->setActiveSheetIndex(0); // Atau setActiveSheetIndexByName($sheetFromTemplate->getTitle())
            },
        ];
    }
}