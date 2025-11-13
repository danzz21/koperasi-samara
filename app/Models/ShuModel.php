<?php

namespace App\Models;

use CodeIgniter\Model;

class ShuModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // 🔸 Margin dari murabahah (10% dari total pinjaman)
    public function getMarginMurabahah($tahun)
    {
        $builder = $this->db->table('murabahah');
        $builder->selectSum('jml_pinjam', 'total');
        $builder->where('YEAR(tanggal)', $tahun);
        $result = $builder->get()->getRow();
        return ($result->total ?? 0) * 0.10;
    }

    // 🔸 Margin dari mudharabah (10% dari total pinjaman)
    public function getMarginMudharabah($tahun)
    {
        $builder = $this->db->table('mudharabah');
        $builder->selectSum('jml_pinjam', 'total');
        $builder->where('YEAR(tanggal)', $tahun);
        $result = $builder->get()->getRow();
        return ($result->total ?? 0) * 0.10;
    }

    // 🔸 Pemasukan umum
    public function getPemasukanUmum($tahun)
    {
        $builder = $this->db->table('transaksi_umum');
        $builder->selectSum('jumlah', 'total');
        $builder->where('jenis', 'pemasukan');
        $builder->where('YEAR(tanggal)', $tahun);
        $result = $builder->get()->getRow();
        return $result->total ?? 0;
    }

    // 🔸 Pengeluaran umum
    public function getPengeluaranUmum($tahun)
    {
        $builder = $this->db->table('transaksi_umum');
        $builder->selectSum('jumlah', 'total');
        $builder->where('jenis', 'pengeluaran');
        $builder->where('YEAR(tanggal)', $tahun);
        $result = $builder->get()->getRow();
        return $result->total ?? 0;
    }

    // 🔸 Hitung total SHU
    public function getSHU($tahun)
    {
        $marginMurabahah = $this->getMarginMurabahah($tahun);
        $marginMudharabah = $this->getMarginMudharabah($tahun);
        $pemasukanUmum = $this->getPemasukanUmum($tahun);
        $pengeluaranUmum = $this->getPengeluaranUmum($tahun);

        $pendapatanTotal = $marginMurabahah + $marginMudharabah + $pemasukanUmum;
        $shu = $pendapatanTotal - $pengeluaranUmum;

        return [
            'margin_murabahah' => $marginMurabahah,
            'margin_mudharabah' => $marginMudharabah,
            'pemasukan_umum' => $pemasukanUmum,
            'pengeluaran_umum' => $pengeluaranUmum,
            'shu' => $shu,
        ];
    }

    // 🔸 Data untuk grafik perkembangan bulanan
public function getDataGrafik($tahun)
{
    $data = [
        'pendapatan' => [],
        'shu' => [],
        'pengeluaran' => []
    ];

    for ($bulan = 1; $bulan <= 12; $bulan++) {
        // Pendapatan bulanan
        $pendapatanBulanan = $this->getPendapatanBulanan($tahun, $bulan);
        $pengeluaranBulanan = $this->getPengeluaranBulanan($tahun, $bulan);
        $shuBulanan = $pendapatanBulanan - $pengeluaranBulanan;

        $data['pendapatan'][] = $pendapatanBulanan;
        $data['shu'][] = $shuBulanan;
        $data['pengeluaran'][] = $pengeluaranBulanan;
    }

    return $data;
}

// 🔸 Pendapatan per bulan
private function getPendapatanBulanan($tahun, $bulan)
{
    // Margin Murabahah bulanan
    $builderMurabahah = $this->db->table('murabahah');
    $builderMurabahah->selectSum('jml_pinjam', 'total');
    $builderMurabahah->where('YEAR(tanggal)', $tahun);
    $builderMurabahah->where('MONTH(tanggal)', $bulan);
    $murabahah = $builderMurabahah->get()->getRow();
    $marginMurabahah = ($murabahah->total ?? 0) * 0.10;

    // Margin Mudharabah bulanan
    $builderMudharabah = $this->db->table('mudharabah');
    $builderMudharabah->selectSum('jml_pinjam', 'total');
    $builderMudharabah->where('YEAR(tanggal)', $tahun);
    $builderMudharabah->where('MONTH(tanggal)', $bulan);
    $mudharabah = $builderMudharabah->get()->getRow();
    $marginMudharabah = ($mudharabah->total ?? 0) * 0.10;

    // Pemasukan umum bulanan
    $builderPemasukan = $this->db->table('transaksi_umum');
    $builderPemasukan->selectSum('jumlah', 'total');
    $builderPemasukan->where('jenis', 'pemasukan');
    $builderPemasukan->where('YEAR(tanggal)', $tahun);
    $builderPemasukan->where('MONTH(tanggal)', $bulan);
    $pemasukan = $builderPemasukan->get()->getRow();

    return $marginMurabahah + $marginMudharabah + ($pemasukan->total ?? 0);
}

// 🔸 Pengeluaran per bulan
private function getPengeluaranBulanan($tahun, $bulan)
{
    $builder = $this->db->table('transaksi_umum');
    $builder->selectSum('jumlah', 'total');
    $builder->where('jenis', 'pengeluaran');
    $builder->where('YEAR(tanggal)', $tahun);
    $builder->where('MONTH(tanggal)', $bulan);
    $result = $builder->get()->getRow();
    
    return $result->total ?? 0;
}
}
