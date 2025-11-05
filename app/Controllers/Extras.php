<?php
namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\QardModel;
use App\Models\MurabahahModel;
use App\Models\MudharabahModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Extras extends Controller
{
    use ResponseTrait;

    public function search()
{
    try {
        $q = $this->request->getGet('q');
        $q = trim((string)$q);

        log_message('debug', '=== SEARCH DEBUG START ===');
        log_message('debug', 'Search query: ' . $q);

        if (empty($q) || strlen($q) < 2) {
            return $this->respond(['members' => [], 'transactions' => []]);
        }

        $anggotaModel = new AnggotaModel();
        $qardModel = new QardModel();
        $muraModel = new MurabahahModel();
        $mudaModel = new MudharabahModel();

        // 1. CARI ANGGOTA
        $members = $anggotaModel
            ->groupStart()
                ->like('nama_lengkap', $q)
                ->orLike('nomor_anggota', $q)
                ->orLike('email', $q)
                ->orLike('no_ktp', $q)
            ->groupEnd()
            ->findAll(10);

        log_message('debug', 'Members found: ' . count($members));

        // 2. DEBUG: CEK MODEL DAN TABEL
        log_message('debug', '=== MODEL CONFIGURATION ===');
        
        // Cek koneksi database
        $db = \Config\Database::connect();
        log_message('debug', 'Database connected: ' . ($db ? 'YES' : 'NO'));
        
        // Cek apakah model bisa akses data
        try {
            $totalQard = $qardModel->countAll();
            log_message('debug', 'Total QARD records: ' . $totalQard);
            
            $sampleQard = $qardModel->findAll(2);
            log_message('debug', 'Sample QARD data: ' . json_encode($sampleQard));
        } catch (\Exception $e) {
            log_message('error', 'QARD model error: ' . $e->getMessage());
        }

        try {
            $totalMura = $muraModel->countAll();
            log_message('debug', 'Total MURABAHAH records: ' . $totalMura);
            
            $sampleMura = $muraModel->findAll(2);
            log_message('debug', 'Sample MURABAHAH data: ' . json_encode($sampleMura));
        } catch (\Exception $e) {
            log_message('error', 'MURABAHAH model error: ' . $e->getMessage());
        }

        try {
            $totalMuda = $mudaModel->countAll();
            log_message('debug', 'Total MUDHARABAH records: ' . $totalMuda);
            
            $sampleMuda = $mudaModel->findAll(2);
            log_message('debug', 'Sample MUDHARABAH data: ' . json_encode($sampleMuda));
        } catch (\Exception $e) {
            log_message('error', 'MUDHARABAH model error: ' . $e->getMessage());
        }

        // 3. CARI TRANSAKSI SEDERHANA
        $txs = [];
        
        // Cari berdasarkan ID anggota yang ditemukan
        if (!empty($members)) {
            $anggotaIds = [];
            foreach ($members as $member) {
                if (isset($member['nomor_anggota'])) {
                    $anggotaIds[] = $member['nomor_anggota'];
                }
                if (isset($member['id'])) {
                    $anggotaIds[] = $member['id'];
                }
                if (isset($member['id_anggota'])) {
                    $anggotaIds[] = $member['id_anggota'];
                }
            }
            
            log_message('debug', 'Searching transactions for anggota IDs: ' . json_encode($anggotaIds));
            
            // Cari di semua tabel dengan ID anggota
            foreach ($anggotaIds as $anggotaId) {
                try {
                    $qardForAnggota = $qardModel->like('id_anggota', $anggotaId)->findAll(3);
                    foreach ($qardForAnggota as $t) {
                        $t['type'] = 'QARD';
                        $txs[] = $t;
                    }
                } catch (\Exception $e) {}
                
                try {
                    $muraForAnggota = $muraModel->like('id_anggota', $anggotaId)->findAll(3);
                    foreach ($muraForAnggota as $t) {
                        $t['type'] = 'MURABAHAH';
                        $txs[] = $t;
                    }
                } catch (\Exception $e) {}
                
                try {
                    $mudaForAnggota = $mudaModel->like('id_anggota', $anggotaId)->findAll(3);
                    foreach ($mudaForAnggota as $t) {
                        $t['type'] = 'MUDHARABAH';
                        $txs[] = $t;
                    }
                } catch (\Exception $e) {}
            }
        }

        // 4. Juga cari dengan query langsung
        try {
            $directQard = $qardModel
                ->groupStart()
                    ->like('id_qard', $q)
                    ->orLike('id_anggota', $q)
                    ->orLike('nama_peminjam', $q)
                ->groupEnd()
                ->findAll(3);
            foreach ($directQard as $t) {
                $t['type'] = 'QARD';
                $txs[] = $t;
            }
        } catch (\Exception $e) {}

        try {
            $directMura = $muraModel
                ->groupStart()
                    ->like('id_mr', $q)
                    ->orLike('id_anggota', $q)
                    ->orLike('nama_anggota', $q)
                ->groupEnd()
                ->findAll(3);
            foreach ($directMura as $t) {
                $t['type'] = 'MURABAHAH';
                $txs[] = $t;
            }
        } catch (\Exception $e) {}

        try {
            $directMuda = $mudaModel
                ->groupStart()
                    ->like('id_md', $q)
                    ->orLike('id_anggota', $q)
                    ->orLike('nama_anggota', $q)
                ->groupEnd()
                ->findAll(3);
            foreach ($directMuda as $t) {
                $t['type'] = 'MUDHARABAH';
                $txs[] = $t;
            }
        } catch (\Exception $e) {}

        log_message('debug', 'Total transactions found: ' . count($txs));
        log_message('debug', '=== SEARCH DEBUG END ===');

        return $this->respond([
            'members' => $members,
            'transactions' => $txs,
            'debug_info' => [
                'query' => $q,
                'total_members' => count($members),
                'total_transactions' => count($txs)
            ]
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Search error: ' . $e->getMessage());
        return $this->respond([
            'members' => [],
            'transactions' => [],
            'error' => $e->getMessage()
        ]);
    }
}

    // Method lainnya tetap sama...
     // Method lainnya tetap sama...
    public function exportExcelLengkap()
    {
        try {
            log_message('debug', 'Export Excel LENGKAP request');

            $anggotaModel = new AnggotaModel();
            $qardModel = new QardModel();
            $muraModel = new MurabahahModel();
            $mudaModel = new MudharabahModel();

            // Ambil semua data dengan logging
            log_message('debug', 'Fetching Anggota data...');
            $anggotaData = $anggotaModel->findAll();
            log_message('debug', 'Anggota data count: ' . count($anggotaData));
            if (!empty($anggotaData)) {
                log_message('debug', 'Sample Anggota data: ' . json_encode($anggotaData[0]));
            }

            log_message('debug', 'Fetching Qard data...');
            $qardData = $qardModel->findAll();
            log_message('debug', 'Qard data count: ' . count($qardData));
            if (!empty($qardData)) {
                log_message('debug', 'Sample Qard data: ' . json_encode($qardData[0]));
            }

            // Join Qard data with Anggota to get nama peminjam
            if (!empty($qardData)) {
                foreach ($qardData as &$qard) {
                    if (!empty($qard['id_anggota'])) {
                        $anggota = $anggotaModel->where('id_anggota', $qard['id_anggota'])->first();
                        if ($anggota) {
                            $qard['nama_peminjam'] = $anggota['nama_lengkap'];
                        }
                    }
                }
            }

            log_message('debug', 'Fetching Murabahah data...');
            $muraData = $muraModel->findAll();
            log_message('debug', 'Murabahah data count: ' . count($muraData));
            if (!empty($muraData)) {
                log_message('debug', 'Sample Murabahah data: ' . json_encode($muraData[0]));
            }

            // Join Murabahah data with Anggota to get nama anggota
            if (!empty($muraData)) {
                foreach ($muraData as &$mura) {
                    if (!empty($mura['id_anggota'])) {
                        $anggota = $anggotaModel->where('id_anggota', $mura['id_anggota'])->first();
                        if ($anggota) {
                            $mura['nama_anggota'] = $anggota['nama_lengkap'];
                        }
                    }
                }
            }

            log_message('debug', 'Fetching Mudharabah data...');
            $mudaData = $mudaModel->findAll();
            log_message('debug', 'Mudharabah data count: ' . count($mudaData));
            if (!empty($mudaData)) {
                log_message('debug', 'Sample Mudharabah data: ' . json_encode($mudaData[0]));
            }

            // Join Mudharabah data with Anggota to get nama anggota
            if (!empty($mudaData)) {
                foreach ($mudaData as &$muda) {
                    if (!empty($muda['id_anggota'])) {
                        $anggota = $anggotaModel->where('id_anggota', $muda['id_anggota'])->first();
                        if ($anggota) {
                            $muda['nama_anggota'] = $anggota['nama_lengkap'];
                        }
                    }
                }
            }

            $filename = 'data_koperasi_lengkap';

            // Call the export method directly instead of returning it
            $this->exportExcelKeren($anggotaData, $qardData, $muraData, $mudaData, $filename);

        } catch (\Exception $e) {
            log_message('error', 'Export Excel error: ' . $e->getMessage());
            // Return error response instead of failing
            $this->response->setStatusCode(500);
            $this->response->setJSON(['error' => 'Error exporting Excel: ' . $e->getMessage()]);
            return $this->response;
        }
    }

    private function exportExcelKeren($anggotaData, $qardData, $muraData, $mudaData, $filename)
    {
        $spreadsheet = new Spreadsheet();

        // ===== SHEET 1: DATA ANGGOTA =====
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('DATA ANGGOTA');

        // Header Sheet
        $sheet1->setCellValue('A1', 'DATA ANGGOTA KOPERASI');
        $sheet1->mergeCells('A1:I1');
        $sheet1->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E75B6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers Tabel
        $anggotaHeaders = ['No', 'ID', 'Nomor Anggota', 'Nama Lengkap', 'No KTP', 'Email', 'Telepon', 'Alamat', 'Status'];
        $sheet1->fromArray($anggotaHeaders, NULL, 'A3');

        // Style Header Tabel
        $sheet1->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5B9BD5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data Anggota
        $row = 4;
        $no = 1;
        foreach ($anggotaData as $item) {
            $sheet1->setCellValue('A' . $row, $no++);
            $sheet1->setCellValue('B' . $row, $item['id'] ?? '');
            $sheet1->setCellValue('C' . $row, $item['nomor_anggota'] ?? '');
            $sheet1->setCellValue('D' . $row, $item['nama_lengkap'] ?? '');
            $sheet1->setCellValue('E' . $row, $item['no_ktp'] ?? '');
            $sheet1->setCellValue('F' . $row, $item['email'] ?? '');
            $sheet1->setCellValue('G' . $row, $item['telepon'] ?? $item['no_hp'] ?? '');
            $sheet1->setCellValue('H' . $row, $item['alamat'] ?? '');
            $sheet1->setCellValue('I' . $row, $item['status'] ?? 'aktif');
            $row++;
        }

        // Style Data Tabel
        $lastRow = $row - 1;
        if ($lastRow >= 4) {
            $sheet1->getStyle('A4:I' . $lastRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ]);
        }

        // Auto Size Columns
        foreach (range('A', 'I') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // ===== SHEET 2: DATA QARD =====
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('DATA QARD');
        
        // Header Sheet
        $sheet2->setCellValue('A1', 'DATA PEMBIAYAAN QARD');
        $sheet2->mergeCells('A1:H1');
        $sheet2->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ED7D31']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers Tabel
        $qardHeaders = ['No', 'ID', 'ID Qard', 'ID Anggota', 'Nama Peminjam', 'Jumlah Pinjam', 'Jumlah Angsuran', 'Jumlah Terbayar', 'Tanggal', 'Status'];
        $sheet2->fromArray($qardHeaders, NULL, 'A3');

        // Style Header Tabel
        $sheet2->getStyle('A3:J3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F4B084']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data Qard
        log_message('debug', 'Processing Qard data for Excel...');
        $row = 4;
        $no = 1;
        foreach ($qardData as $item) {
            log_message('debug', 'Processing Qard item: ' . json_encode($item));
            $sheet2->setCellValue('A' . $row, $no++);
            $sheet2->setCellValue('B' . $row, $item['id'] ?? $item['id_qard'] ?? '');
            $sheet2->setCellValue('C' . $row, $item['id_qard'] ?? '');
            $sheet2->setCellValue('D' . $row, $item['id_anggota'] ?? '');
            $sheet2->setCellValue('E' . $row, $item['nama_peminjam'] ?? ''); // Nama peminjam sudah di-join
            $sheet2->setCellValue('F' . $row, $item['jml_pinjam'] ?? 0);
            $sheet2->setCellValue('G' . $row, $item['jml_angsuran'] ?? 0);
            $sheet2->setCellValue('H' . $row, $item['jml_terbayar'] ?? 0);
            $sheet2->setCellValue('I' . $row, $item['tanggal'] ?? '');
            $sheet2->setCellValue('J' . $row, $item['status'] ?? '');
            $row++;
        }

        // Format Currency untuk Jumlah Pinjam
        $sheet2->getStyle('F4:F' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');

        // Style Data Tabel
        if ($row > 4) {
            $sheet2->getStyle('A4:J' . ($row-1))->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ]);
        }

        // Auto Size Columns
        foreach (range('A', 'J') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // ===== SHEET 3: DATA MURABAHAH =====
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('DATA MURABAHAH');
        
        // Header Sheet
        $sheet3->setCellValue('A1', 'DATA PEMBIAYAAN MURABAHAH');
        $sheet3->mergeCells('A1:H1');
        $sheet3->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers Tabel
        $muraHeaders = ['No', 'ID', 'ID Murabahah', 'ID Anggota', 'Nama Anggota', 'Jumlah', 'Tanggal', 'Status', 'Keterangan'];
        $sheet3->fromArray($muraHeaders, NULL, 'A3');

        // Style Header Tabel
        $sheet3->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A9D18E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data Murabahah
        log_message('debug', 'Processing Murabahah data for Excel...');
        $row = 4;
        $no = 1;
        foreach ($muraData as $item) {
            log_message('debug', 'Processing Murabahah item: ' . json_encode($item));
            $sheet3->setCellValue('A' . $row, $no++);
            $sheet3->setCellValue('B' . $row, $item['id'] ?? '');
            $sheet3->setCellValue('C' . $row, $item['id_murabahah'] ?? $item['id_mr'] ?? '');
            $sheet3->setCellValue('D' . $row, $item['id_anggota'] ?? '');
            $sheet3->setCellValue('E' . $row, $item['nama_anggota'] ?? ''); // Nama anggota sudah di-join
            $sheet3->setCellValue('F' . $row, $item['jumlah'] ?? $item['jml_pinjam'] ?? $item['total'] ?? 0);
            $sheet3->setCellValue('G' . $row, $item['tanggal'] ?? $item['tgl_transaksi'] ?? '');
            $sheet3->setCellValue('H' . $row, $item['status'] ?? '');
            $sheet3->setCellValue('I' . $row, $item['keterangan'] ?? $item['catatan'] ?? '');
            $row++;
        }

        // Format Currency untuk Jumlah
        $sheet3->getStyle('F4:F' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');

        // Style Data Tabel
        if ($row > 4) {
            $sheet3->getStyle('A4:I' . ($row-1))->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ]);
        }

        // Auto Size Columns
        foreach (range('A', 'I') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }

        // ===== SHEET 4: DATA MUDHARABAH =====
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('DATA MUDHARABAH');
        
        // Header Sheet
        $sheet4->setCellValue('A1', 'DATA PEMBIAYAAN MUDHARABAH');
        $sheet4->mergeCells('A1:H1');
        $sheet4->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers Tabel
        $mudaHeaders = ['No', 'ID', 'ID Mudharabah', 'ID Anggota', 'Nama Anggota', 'Jumlah', 'Tanggal', 'Status', 'Keterangan'];
        $sheet4->fromArray($mudaHeaders, NULL, 'A3');

        // Style Header Tabel
        $sheet4->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data Mudharabah
        log_message('debug', 'Processing Mudharabah data for Excel...');
        $row = 4;
        $no = 1;
        foreach ($mudaData as $item) {
            log_message('debug', 'Processing Mudharabah item: ' . json_encode($item));
            $sheet4->setCellValue('A' . $row, $no++);
            $sheet4->setCellValue('B' . $row, $item['id'] ?? '');
            $sheet4->setCellValue('C' . $row, $item['id_mudharabah'] ?? $item['id_md'] ?? '');
            $sheet4->setCellValue('D' . $row, $item['id_anggota'] ?? '');
            $sheet4->setCellValue('E' . $row, $item['nama_anggota'] ?? ''); // Nama anggota sudah di-join
            $sheet4->setCellValue('F' . $row, $item['jumlah'] ?? $item['jml_pinjam'] ?? $item['total'] ?? 0);
            $sheet4->setCellValue('G' . $row, $item['tanggal'] ?? $item['tgl_transaksi'] ?? '');
            $sheet4->setCellValue('H' . $row, $item['status'] ?? '');
            $sheet4->setCellValue('I' . $row, $item['keterangan'] ?? $item['catatan'] ?? '');
            $row++;
        }

        // Format Currency untuk Jumlah
        $sheet4->getStyle('F4:F' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');

        // Style Data Tabel
        if ($row > 4) {
            $sheet4->getStyle('A4:I' . ($row-1))->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ]);
        }

        // Auto Size Columns
        foreach (range('A', 'I') as $col) {
            $sheet4->getColumnDimension($col)->setAutoSize(true);
        }

        // Kembali ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);

        // Clear any previous output
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        try {
            $writer->save('php://output');
        } catch (\Throwable $e) {
            log_message('error', 'Excel export failed: ' . $e->getMessage());
            // Return error response instead of echoing
            return $this->fail('Error exporting Excel: ' . $e->getMessage());
        }
        exit();
    }



}