<?php

namespace App\Exports;

use App\Models\RumahTangga;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Diperlukan untuk type hint pada $destinationSheet
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TenagaKerjaExport implements WithEvents, ShouldAutoSize
{
    protected $rumahTangga;

    public function __construct(RumahTangga $rumahTangga)
    {
        $this->rumahTangga = $rumahTangga->load('anggotaKeluarga');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 1. Tentukan Path ke Template Excel Anda
                // PASTIKAN NAMA FILE TEMPLATE INI BENAR
                $templatePath = storage_path('app/templates/template_data_kuisioner.xlsx');

                // 2. Load Template ke objek PhpSpreadsheet terpisah
                $templateSpreadsheet = IOFactory::load($templatePath);
                $sourceSheet = $templateSpreadsheet->getSheet(0); // Asumsi kita bekerja dengan sheet pertama dari template

                // 3. Mengisi Data Utama (Rumah Tangga) ke $sourceSheet (sheet dari template yang di-load)
                $sourceSheet->setCellValue('A4', 1);
                $sourceSheet->setCellValue('B4', $this->rumahTangga->nama_responden);
                $sourceSheet->setCellValue('C4', $this->rumahTangga->nama_pendata);
                $sourceSheet->setCellValue('D4', Carbon::parse($this->rumahTangga->tgl_pembuatan)->isoFormat('D MMMM YYYY')); // Format tanggal
                $sourceSheet->setCellValue('E4', $this->rumahTangga->provinsi);
                $sourceSheet->setCellValue('F4', $this->rumahTangga->kabupaten);
                $sourceSheet->setCellValue('G4', $this->rumahTangga->kecamatan);
                $sourceSheet->setCellValue('H4', $this->rumahTangga->desa);
                $sourceSheet->setCellValue('I4', $this->rumahTangga->rt);
                $sourceSheet->setCellValue('J4', $this->rumahTangga->rw);
                $sourceSheet->setCellValue('K4', $this->rumahTangga->jart);
                $sourceSheet->setCellValue('L4', $this->rumahTangga->jart_ab);
                $sourceSheet->setCellValue('M4', $this->rumahTangga->jart_tb);
                $sourceSheet->setCellValue('N4', $this->rumahTangga->jart_ms);
                $sourceSheet->setCellValue('O4', $this->rumahTangga->jpr2rtp_text); // Menggunakan accessor
                $sourceSheet->setCellValue('P4', $this->rumahTangga->status_validasi_text); // Menggunakan accessor
                if ($this->rumahTangga->admin_tgl_validasi) {
                    $sourceSheet->setCellValue('Q4', Carbon::parse($this->rumahTangga->admin_tgl_validasi)->isoFormat('D MMMM YYYY'));
                    $sourceSheet->setCellValue('R4', $this->rumahTangga->admin_nama_kepaladusun ?? '-');
                } else {
                    $sourceSheet->setCellValue('Q4', '-');
                    $sourceSheet->setCellValue('R4', '-');
                }
                $sourceSheet->setCellValue('S4', 'RT-' . $this->rumahTangga->id);

                // 4. Mengisi Data Anggota Keluarga ke $sourceSheet
                $startRowAnggota = 7;
                $anggotaCounter = 0;
                if ($this->rumahTangga->anggotaKeluarga && $this->rumahTangga->anggotaKeluarga->count() > 0) {
                    foreach ($this->rumahTangga->anggotaKeluarga as $anggota) {
                        $currentRow = $startRowAnggota + $anggotaCounter;
                        $sourceSheet->setCellValue('A' . $currentRow, $anggotaCounter + 1);
                        $sourceSheet->setCellValue('B' . $currentRow, $anggota->nama);
                        $sourceSheet->setCellValueExplicit('C' . $currentRow, $anggota->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sourceSheet->setCellValue('D' . $currentRow, $anggota->kelamin_text);
                        $sourceSheet->setCellValue('E' . $currentRow, $anggota->hdkrt_text);
                        $sourceSheet->setCellValue('F' . $currentRow, $anggota->pendidikan_terakhir_text);
                        $sourceSheet->setCellValue('G' . $currentRow, $anggota->status_pekerjaan_text);
                        $sourceSheet->setCellValue('H' . $currentRow, $anggota->jenis_pekerjaan_text);
                        $sourceSheet->setCellValue('I' . $currentRow, $anggota->status_perkawinan_text);
                        $anggotaCounter++;
                    }
                } else {
                    $sourceSheet->setCellValue('A' . $startRowAnggota, 'Tidak ada data anggota keluarga.');
                    // Pertimbangkan untuk merge jika ada di template asli:
                    // $sourceSheet->mergeCells('A'.$startRowAnggota.':I'.$startRowAnggota);
                }

                // 5. Dapatkan sheet yang sedang "disiapkan" oleh Maatwebsite/Excel
                /** @var Worksheet $destinationSheet */
                $destinationSheet = $event->sheet->getDelegate();

                // 6. Transfer konten dari $sourceSheet ke $destinationSheet
                // Atur Judul Sheet
                $destinationSheet->setTitle($sourceSheet->getTitle());

                // Salin dimensi kolom (lebar)
                foreach ($sourceSheet->getColumnDimensions() as $columnDimension) {
                    $destinationSheet->getColumnDimension($columnDimension->getColumnIndex())->setWidth($columnDimension->getWidth());
                    // Jika ada properti lain seperti autoSize, visible, dll., bisa disalin juga
                    if ($columnDimension->getAutoSize()) {
                        $destinationSheet->getColumnDimension($columnDimension->getColumnIndex())->setAutoSize(true);
                    }
                }

                // Salin dimensi baris (tinggi) - opsional, jika ada pengaturan khusus di template
                foreach ($sourceSheet->getRowDimensions() as $rowDimension) {
                    $destinationSheet->getRowDimension($rowDimension->getRowIndex())->setRowHeight($rowDimension->getRowHeight());
                }

                // Salin nilai sel dan style (ini bagian yang paling berisiko menyebabkan error XF Index)
                // Kita akan coba salin nilainya dulu, dan style akan mengikuti dari template jika memungkinkan
                // Atau kita bisa coba menyalin style secara eksplisit dengan hati-hati
                $highestRow = $sourceSheet->getHighestRow();
                $highestColumn = $sourceSheet->getHighestColumn();

                for ($row = 1; $row <= $highestRow; ++$row) {
                    for ($col = 'A'; $col <= $highestColumn; ++$col) {
                        $cellCoordinate = $col . $row;
                        $sourceCell = $sourceSheet->getCell($cellCoordinate);
                        $destinationCell = $destinationSheet->getCell($cellCoordinate);

                        $destinationCell->setValue($sourceCell->getValue()); // Salin nilai

                        // Mencoba menyalin style (ini yang berisiko)
                        // Jika ini menyebabkan error XF Index lagi, baris ini bisa di-komen dulu
                        // $destinationCell->setXfIndex($sourceCell->getXfIndex());
                        // Atau cara yang lebih aman tapi mungkin tidak semua style tercopy:
                        // $styleArray = $sourceSheet->getStyle($cellCoordinate)->exportArray();
                        // $destinationSheet->getStyle($cellCoordinate)->applyFromArray($styleArray);
                    }
                }

                // Salin merged cells
                foreach ($sourceSheet->getMergeCells() as $mergeRange) {
                    $destinationSheet->mergeCells($mergeRange);
                }

                // Hal lain yang mungkin perlu disalin: proteksi sheet, header/footer, gambar, grafik, dll.
                // Untuk sekarang kita fokus pada data, dimensi, dan merge.
            },
        ];
    }
}