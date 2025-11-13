<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Simpanan extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id');

        $db = \Config\Database::connect();

        // Ambil data anggota
        $anggota = $db->table('anggota')
            ->select('id_anggota, nama_lengkap, nomor_anggota, photo')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        // **AMBIL TENOR DARI SIMPANAN_POKOK**
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        // **FILTER: hanya ambil simpanan pokok yang jumlah > 0**
        $pokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->where('jumlah >', 0) // **INI YANG PENTING**
            ->orderBy('tanggal', 'ASC')
            ->get()->getResultArray();

        // Simpanan Wajib
        $wajib = $db->table('simpanan_wajib')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'ASC')
            ->get()->getResultArray();

        // Simpanan Sukarela
        $sukarela = $db->table('simpanan_sukarela')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // Cek apakah sudah punya tenor
        $showTenorModal = false;
        if (!$tenorData || $tenorData['tenor'] === null) {
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
            'tenor_anggota' => $tenorData['tenor'] ?? null, // **Tenor dari simpanan_pokok**
            'showTenorModal'=> $showTenorModal,
        ]);
    }

    public function setTenor()
    {
        $session = session();
        $id_anggota = $session->get('id'); 
        $tenor = $this->request->getPost('tenor');

        $db = \Config\Database::connect();
        
        // **CEK APAKAH SUDAH ADA DATA DI SIMPANAN_POKOK**
        $existingData = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        if ($existingData) {
            // **UPDATE TENOR SAJA tanpa buat record baru**
            $db->table('simpanan_pokok')
                ->where('id_anggota', $id_anggota)
                ->update(['tenor' => $tenor]);
        } else {
            // **INSERT HANYA TENOR, TANPA JUMLAH & TANPA STATUS**
            $db->table('simpanan_pokok')->insert([
                'id_anggota' => $id_anggota,
                'tenor'      => $tenor,
                'tanggal'    => date('Y-m-d')
                // **TIDAK ADA 'jumlah' dan 'status'**
            ]);
        }

        return redirect()->to(base_url('anggota/simpanan'))->with('success', 'Tenor berhasil disimpan.');
    }

    // Method untuk menyimpan simpanan pokok dari anggota
    public function storePokok()
    {
        try {
            $session = session();
            $id_anggota = $session->get('id');

            // Validasi CSRF token - CARA YANG LEBIH SEDERHANA
            if (!$this->request->getPost('csrf_test_name')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Token CSRF tidak valid'
                ]);
            }

            // Ambil data dari form
            $jumlah = $this->request->getPost('jumlah');
            $bukti = $this->request->getFile('bukti');

            // Validasi input
            if (empty($jumlah) || $jumlah <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jumlah setoran harus lebih dari 0'
                ]);
            }

            // Validasi maksimal simpanan pokok (500.000)
            $db = \Config\Database::connect();
            $totalPokok = $db->table('simpanan_pokok')
                ->selectSum('jumlah')
                ->where('id_anggota', $id_anggota)
                ->where('status', 'aktif')
                ->get()
                ->getRow()->jumlah ?? 0;

            if (($totalPokok + $jumlah) > 500000) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Total simpanan pokok tidak boleh melebihi Rp 500.000. Sisa yang dapat disetor: Rp ' . number_format(500000 - $totalPokok, 0, ',', '.')
                ]);
            }

            // Validasi file bukti
            if (!$bukti || !$bukti->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bukti transfer harus diupload'
                ]);
            }

            // Validasi ukuran file (max 2MB)
            if ($bukti->getSize() > 2097152) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 2MB'
                ]);
            }

            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($bukti->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Format file harus JPG, PNG, atau PDF'
                ]);
            }

            // Generate nama file unik
            $namaFile = $bukti->getRandomName();
            
            // Pindahkan file ke folder uploads
            $uploadPath = ROOTPATH . 'public/uploads/bukti_simpanan/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $bukti->move($uploadPath, $namaFile);

            // Data untuk disimpan
            $data = [
                'id_anggota' => $id_anggota,
                'jumlah' => $jumlah,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'pending', // Status pending menunggu konfirmasi admin
                'bukti_transfer' => $namaFile,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Simpan ke database
            $db = \Config\Database::connect();
            $result = $db->table('simpanan_pokok')->insert($data);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Setoran simpanan pokok berhasil dikirim. Menunggu konfirmasi admin.'
                ]);
            } else {
                // Hapus file yang sudah diupload jika gagal simpan
                if (file_exists($uploadPath . $namaFile)) {
                    unlink($uploadPath . $namaFile);
                }
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data simpanan'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error storePokok: ' . $e->getMessage());
            
            // Hapus file jika ada error
            if (isset($uploadPath) && isset($namaFile) && file_exists($uploadPath . $namaFile)) {
                unlink($uploadPath . $namaFile);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk menyimpan simpanan sukarela dari anggota
    public function storeSukarela()
    {
        try {
            $session = session();
            $id_anggota = $session->get('id');

            // Validasi CSRF token - CARA YANG LEBIH SEDERHANA
            if (!$this->request->getPost('csrf_test_name')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Token CSRF tidak valid'
                ]);
            }

            // Ambil data dari form
            $jumlah = $this->request->getPost('jumlah');
            $bukti = $this->request->getFile('bukti');

            // Validasi input
            if (empty($jumlah) || $jumlah <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jumlah setoran harus lebih dari 0'
                ]);
            }

            // Validasi file bukti
            if (!$bukti || !$bukti->isValid()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bukti transfer harus diupload'
                ]);
            }

            // Validasi ukuran file (max 2MB)
            if ($bukti->getSize() > 2097152) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ukuran file maksimal 2MB'
                ]);
            }

            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($bukti->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Format file harus JPG, PNG, atau PDF'
                ]);
            }

            // Generate nama file unik
            $namaFile = $bukti->getRandomName();
            
            // Pindahkan file ke folder uploads
            $uploadPath = ROOTPATH . 'public/uploads/bukti_simpanan/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $bukti->move($uploadPath, $namaFile);

            // Data untuk disimpan
            $data = [
                'id_anggota' => $id_anggota,
                'jumlah' => $jumlah,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'pending', // Status pending menunggu konfirmasi admin
                'bukti_transfer' => $namaFile,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Simpan ke database
            $db = \Config\Database::connect();
            $result = $db->table('simpanan_sukarela')->insert($data);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Setoran simpanan sukarela berhasil dikirim. Menunggu konfirmasi admin.'
                ]);
            } else {
                // Hapus file yang sudah diupload jika gagal simpan
                if (file_exists($uploadPath . $namaFile)) {
                    unlink($uploadPath . $namaFile);
                }
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data simpanan'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error storeSukarela: ' . $e->getMessage());
            
            // Hapus file jika ada error
            if (isset($uploadPath) && isset($namaFile) && file_exists($uploadPath . $namaFile)) {
                unlink($uploadPath . $namaFile);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    // ATAU JIKA MAU VALIDASI CSRF YANG LEBIH KETAT, GUNAKAN INI:
    private function validateCSRF()
    {
        $csrf_token = $this->request->getPost('csrf_test_name');
        $current_token = csrf_hash();
        
        return hash_equals($current_token, $csrf_token);
    }
}