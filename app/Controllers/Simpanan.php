<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Simpanan extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id_anggota') ?? $session->get('id');

        $db = \Config\Database::connect();

        // Data anggota
        $anggota = $db->table('anggota')
            ->select('id_anggota, nama_lengkap, nomor_anggota, photo')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        // Tenor
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_anggota)
            ->where('tenor IS NOT NULL')
            ->get()
            ->getRowArray();

        // Riwayat Simpanan Pokok (Tampilkan semua riwayat agar transparan)
        $pokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->groupStart()
                ->where('jumlah >', 0)
                ->orWhere('status IS NOT NULL')
            ->groupEnd()
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // Simpanan Wajib
        $wajib = $db->table('simpanan_wajib')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // Simpanan Sukarela
        $sukarela = $db->table('simpanan_sukarela')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        $showTenorModal = false;
        if (!$tenorData || empty($tenorData['tenor'])) {
            $showTenorModal = true;
        }

        return view('simpanan', [
            'nama'          => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'foto_diri'     => $anggota['photo'] ?? null,
            'pokok'         => $pokok,
            'wajib'         => $wajib,
            'sukarela'      => $sukarela,
            'anggota'       => $anggota,
            'tenor_anggota' => $tenorData['tenor'] ?? null,
            'showTenorModal'=> $showTenorModal,
        ]);
    }

    public function setTenor()
    {
        $session = session();
        $id_anggota = $session->get('id_anggota') ?? $session->get('id');
        $tenor = $this->request->getPost('tenor');

        $db = \Config\Database::connect();
        
        $existingData = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        if ($existingData) {
            $db->table('simpanan_pokok')
                ->where('id_anggota', $id_anggota)
                ->update(['tenor' => $tenor]);
        } else {
            $db->table('simpanan_pokok')->insert([
                'id_anggota' => $id_anggota,
                'tenor'      => $tenor,
                'tanggal'    => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to(base_url('anggota/simpanan'))->with('success', 'Tenor berhasil disimpan.');
    }

    public function storePokok()
    {
        try {
            $session = session();
            $id_anggota = $session->get('id_anggota') ?? $session->get('id');

            $jumlah = (float) preg_replace('/[^0-9]/', '', (string)$this->request->getPost('jumlah'));
            $bukti  = $this->request->getFile('bukti');

            if ($jumlah <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jumlah setoran harus lebih dari Rp 0'
                ]);
            }

            $db = \Config\Database::connect();
            
            // CEK HANYA SIMPANAN POKOK YANG SUDAH DI-ACC / AKTIFF / LUNAS
            // Transaksi 'pending' atau 'ditolak'TIDAK DITAMBAHKAN ke batas Rp 500.000
            $totalPokokSah = $db->table('simpanan_pokok')
                ->selectSum('jumlah')
                ->where('id_anggota', $id_anggota)
                ->whereIn('status', ['aktif', 'lunas', 'berhasil', 'disetujui'])
                ->get()->getRow()->jumlah ?? 0;

            $sisaKekurangan = max(0, 500000 - $totalPokokSah);

            if ($jumlah > $sisaKekurangan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Setoran melebihi sisa kekurangan. Maksimal setoran saat ini: Rp ' . number_format($sisaKekurangan, 0, ',', '.')
                ]);
            }

            if (!$bukti || !$bukti->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bukti transfer wajib diupload'
                ]);
            }

            $namaFile = $bukti->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/bukti_simpanan/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $bukti->move($uploadPath, $namaFile);

            $data = [
                'id_anggota'     => $id_anggota,
                'jumlah'         => $jumlah,
                'tanggal'        => date('Y-m-d H:i:s'),
                'status'         => 'pending', // Status awal pengajuan
                'bukti_transfer' => $namaFile,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ];

            $result = $db->table('simpanan_pokok')->insert($data);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Setoran berhasil dikirim! Menunggu konfirmasi admin.'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function storeSukarela()
    {
        try {
            $session = session();
            $id_anggota = $session->get('id_anggota') ?? $session->get('id');

            $jumlah = (float) preg_replace('/[^0-9]/', '', (string)$this->request->getPost('jumlah'));
            $bukti  = $this->request->getFile('bukti');

            if ($jumlah <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jumlah setoran harus lebih dari Rp 0'
                ]);
            }

            if (!$bukti || !$bukti->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bukti transfer wajib diupload'
                ]);
            }

            $namaFile = $bukti->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/bukti_simpanan/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $bukti->move($uploadPath, $namaFile);

            $data = [
                'id_anggota'     => $id_anggota,
                'jumlah'         => $jumlah,
                'tanggal'        => date('Y-m-d H:i:s'),
                'status'         => 'pending',
                'bukti_transfer' => $namaFile,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ];

            $db = \Config\Database::connect();
            $result = $db->table('simpanan_sukarela')->insert($data);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Setoran simpanan sukarela berhasil dikirim!'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}