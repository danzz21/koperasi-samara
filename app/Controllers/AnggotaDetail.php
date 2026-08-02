<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;

class AnggotaDetail extends BaseController
{
    public function index($id = null)
    {
        if ($id === null) {
            $id = $this->request->getGet('id');
        }

        if (!$id) {
            return redirect()->to(base_url('admin/members'))->with('error', 'ID Anggota tidak valid.');
        }

        $anggotaModel = new AnggotaModel();
        $db = \Config\Database::connect();

        // 1. Ambil Data Anggota berdasarkan Primary Key
        $anggota = $anggotaModel->find($id);

        if (!$anggota) {
            return redirect()->to(base_url('admin/members'))->with('error', 'Data anggota tidak ditemukan.');
        }

        $idAnggota = $anggota['id_anggota'];
        $validStatus = ['aktif', 'lunas', 'berhasil', 'disetujui', 'sukses'];

        // 2. Akumulasi Simpanan
        $simpananPokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $idAnggota)
            ->whereIn('status', $validStatus)
            ->selectSum('jumlah', 'total')
            ->selectMax('tanggal', 'tanggal_terakhir')
            ->get()->getRowArray();

        $simpananWajib = $db->table('simpanan_wajib')
            ->where('id_anggota', $idAnggota)
            ->whereIn('status', $validStatus)
            ->selectSum('jumlah', 'total')
            ->selectMax('tanggal', 'tanggal_terakhir')
            ->get()->getRowArray();

        $simpananSukarela = $db->table('simpanan_sukarela')
            ->where('id_anggota', $idAnggota)
            ->whereIn('status', $validStatus)
            ->selectSum('jumlah', 'total')
            ->selectMax('tanggal', 'tanggal_terakhir')
            ->get()->getRowArray();

        $totalSimpanan = (float)($simpananPokok['total'] ?? 0) 
                       + (float)($simpananWajib['total'] ?? 0) 
                       + (float)($simpananSukarela['total'] ?? 0);

        // 3. Akumulasi Pembiayaan (Qard, Murabahah, Mudharabah)
        $qard = $db->table('qard')->where('id_anggota', $idAnggota)->whereIn('status', ['aktif', 'berjalan'])->get()->getResultArray();
        $murabahah = $db->table('murabahah')->where('id_anggota', $idAnggota)->whereIn('status', ['aktif', 'berjalan'])->get()->getResultArray();
        $mudharabah = $db->table('mudharabah')->where('id_anggota', $idAnggota)->whereIn('status', ['aktif', 'berjalan'])->get()->getResultArray();

        $dataPembiayaan = [];
        $totalPembiayaan = 0;
        $maxSisaTenor = 0;

        foreach ($qard as $q) {
            $pinjam = (float)($q['jml_pinjam'] ?? 0);
            $terbayar = (float)($q['jml_terbayar'] ?? 0);
            $tenor = (int)($q['jml_angsuran'] ?? 1);
            $angsuran = $tenor > 0 ? $pinjam / $tenor : 0;
            $sisaTenor = max(0, ceil(($pinjam - $terbayar) / max($angsuran, 1)));

            $totalPembiayaan += $pinjam;
            if ($sisaTenor > $maxSisaTenor) $maxSisaTenor = $sisaTenor;

            $dataPembiayaan[] = [
                'jenis_pembiayaan'   => 'Pinjaman Qardh',
                'akad'               => 'Qardh',
                'nomor_pembiayaan'   => $q['no_qard'] ?? ('QRD-' . $q['id_qard']),
                'jumlah_pembiayaan'  => $pinjam,
                'jangka_waktu'       => $tenor,
                'angsuran_per_bulan' => $angsuran,
                'sisa_tenor'         => $sisaTenor,
                'total_dibayar'      => $terbayar,
                'status'             => $q['status'] ?? 'aktif',
                'nama_pembiayaan'    => 'Qardh #' . ($q['no_qard'] ?? $q['id_qard'])
            ];
        }

        foreach ($murabahah as $m) {
            $pinjam = (float)($m['jml_pinjam'] ?? 0);
            $terbayar = (float)($m['jml_terbayar'] ?? 0);
            $tenor = (int)($m['jml_angsuran'] ?? 1);
            $angsuran = $tenor > 0 ? $pinjam / $tenor : 0;
            $sisaTenor = max(0, ceil(($pinjam - $terbayar) / max($angsuran, 1)));

            $totalPembiayaan += $pinjam;
            if ($sisaTenor > $maxSisaTenor) $maxSisaTenor = $sisaTenor;

            $dataPembiayaan[] = [
                'jenis_pembiayaan'   => 'Pembiayaan Murabahah',
                'akad'               => 'Murabahah',
                'nomor_pembiayaan'   => $m['no_murabahah'] ?? ('MRB-' . $m['id_murabahah']),
                'jumlah_pembiayaan'  => $pinjam,
                'jangka_waktu'       => $tenor,
                'angsuran_per_bulan' => $angsuran,
                'sisa_tenor'         => $sisaTenor,
                'total_dibayar'      => $terbayar,
                'status'             => $m['status'] ?? 'aktif',
                'nama_pembiayaan'    => 'Murabahah #' . ($m['no_murabahah'] ?? $m['id_murabahah'])
            ];
        }

        foreach ($mudharabah as $md) {
            $pinjam = (float)($md['jml_pinjam'] ?? 0);
            $terbayar = (float)($md['jml_terbayar'] ?? 0);
            $tenor = (int)($md['jml_angsuran'] ?? 1);
            $angsuran = $tenor > 0 ? $pinjam / $tenor : 0;
            $sisaTenor = max(0, ceil(($pinjam - $terbayar) / max($angsuran, 1)));

            $totalPembiayaan += $pinjam;
            if ($sisaTenor > $maxSisaTenor) $maxSisaTenor = $sisaTenor;

            $dataPembiayaan[] = [
                'jenis_pembiayaan'   => 'Pembiayaan Mudharabah',
                'akad'               => 'Mudharabah',
                'nomor_pembiayaan'   => $md['no_mudharabah'] ?? ('MDH-' . $md['id_mudharabah']),
                'jumlah_pembiayaan'  => $pinjam,
                'jangka_waktu'       => $tenor,
                'angsuran_per_bulan' => $angsuran,
                'sisa_tenor'         => $sisaTenor,
                'total_dibayar'      => $terbayar,
                'status'             => $md['status'] ?? 'aktif',
                'nama_pembiayaan'    => 'Mudharabah #' . ($md['no_mudharabah'] ?? $md['id_mudharabah'])
            ];
        }

        // 4. Estimasi Bagi Hasil
        $estimasiBagiHasil = ((float)($simpananSukarela['total'] ?? 0) * 0.05);

        // 5. Riwayat Mutasi Transaksi
        $riwayatTransaksi = $db->query("
            SELECT 'pemasukan' AS type, 'Setoran Simpanan Pokok' AS keterangan, jumlah, tanggal, status FROM simpanan_pokok WHERE id_anggota = ?
            UNION ALL
            SELECT 'pemasukan' AS type, 'Setoran Simpanan Wajib' AS keterangan, jumlah, tanggal, status FROM simpanan_wajib WHERE id_anggota = ?
            UNION ALL
            SELECT 'pemasukan' AS type, 'Setoran Simpanan Sukarela' AS keterangan, jumlah, tanggal, status FROM simpanan_sukarela WHERE id_anggota = ?
            ORDER BY tanggal DESC LIMIT 20
        ", [$idAnggota, $idAnggota, $idAnggota])->getResultArray();

        $data = [
            'title'             => 'Detail Anggota - ' . $anggota['nama_lengkap'],
            'anggota'           => $anggota,
            'totalSimpanan'     => $totalSimpanan,
            'totalPembiayaan'   => $totalPembiayaan,
            'sisaAngsuran'      => $maxSisaTenor,
            'bagi_hasil'        => $estimasiBagiHasil,
            'simpanan_pokok'    => $simpananPokok,
            'simpanan_wajib'    => $simpananWajib,
            'simpanan_sukarela' => $simpananSukarela,
            'data_pembiayaan'   => $dataPembiayaan,
            'jadwal_angsuran'   => $dataPembiayaan,
            'riwayat_transaksi' => $riwayatTransaksi
        ];

        return view('layouts/header', $data)
            . view('dashboard_admin/detail_anggota', $data)
            . view('layouts/footer');
    }
}