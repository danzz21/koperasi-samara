<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class Pinjaman extends BaseController
{
    public function index()
    {
        $session = session();
        $id_user = $session->get('id');
        $db = \Config\Database::connect();

        $userModel = new UserModel();
        $anggotaModel = new AnggotaModel();

        $user = $userModel->find($id_user);
        $anggota = $anggotaModel->find($id_user);

        // Ambil nama dari anggota_lengkap jika ada, jika tidak pakai user
        $nama = $anggota['nama_lengkap'] ?? $user['nama_lengkap'] ?? '-';
        $nomor_anggota = $anggota['nomor_anggota'] ?? $user['id'] ?? '-';

        $hasNoRekening = !empty($anggota['no_rek']);
        $loanStatus = $this->getLoanStatusDetail($id_user); // Cek status 'pending', 'aktif', atau 'none'
        $hasActiveLoan = ($loanStatus !== 'none');
        $pendingLoansCount = $this->getPendingLoans($id_user);

        // Cek tenor simpanan pokok anggota
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_user)
            ->get()
            ->getRowArray();

        $showTenorModal = false;
        if (!$tenorData || $tenorData['tenor'] === null) {
            $showTenorModal = true;
        }

        // CEK APAKAH SUDAH BUAT PIN
        $hasPin = false;
        $pinData = $db->table('user_pin')
            ->where('user_id', $id_user)
            ->get()
            ->getRowArray();

        $hasPin = !empty($pinData);

        // AMBIL RIWAYAT PINJAMAN (Semua data ditampilkan agar pengajuan yang ditolak tetap terlihat)
        $riwayatPinjaman = $db->query(
            "
            SELECT 'Al-Qord' AS akad, jml_pinjam AS nominal, jml_angsuran AS tenor, status, tanggal, COALESCE(deskripsi, '') AS alasan FROM qard WHERE id_anggota = ?
            UNION ALL
            SELECT 'Murabahah' AS akad, jml_pinjam AS nominal, jml_angsuran AS tenor, status, tanggal, COALESCE(deskripsi, '') AS alasan FROM murabahah WHERE id_anggota = ?
            UNION ALL
            SELECT 'Mudharabah' AS akad, jml_pinjam AS nominal, jml_angsuran AS tenor, status, tanggal, COALESCE(deskripsi, '') AS alasan FROM mudharabah WHERE id_anggota = ?
            ORDER BY tanggal DESC
            ",
            [$id_user, $id_user, $id_user]
        )->getResultArray();

        $riwayatPinjaman = array_map(function ($item) {
            $item['status'] = normalizeLoanStatusValue($item['status'] ?? '');
            return $item;
        }, $riwayatPinjaman);

        return view('pinjaman', [
            'nama'              => $nama,
            'nomor_anggota'     => $nomor_anggota,
            'user'              => $user,
            'anggota'           => $anggota,
            'hasActiveLoan'     => $hasActiveLoan,
            'loanStatus'        => $loanStatus,
            'hasNoRekening'     => $hasNoRekening,
            'pendingLoansCount' => $pendingLoansCount,
            'showTenorModal'    => $showTenorModal,
            'hasPin'            => $hasPin,
            'riwayatPinjaman'   => $riwayatPinjaman
        ]);
    }

    public function ajukan()
    {
        $id_user = session()->get('id');

        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);

        if (empty($anggota) || empty($anggota['no_rek'])) {
            return redirect()->back()->withInput()->with('error', 'Anda belum mengisi nomor rekening. Harap lengkapi data rekening di menu Profil terlebih dahulu sebelum mengajukan pinjaman.');
        }

        if ($this->hasActiveLoan($id_user)) {
            return redirect()->back()->with('error', 'Anda masih memiliki pengajuan pending atau pinjaman aktif yang sedang berjalan.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'jenis' => 'required|in_list[qard,murabahah,mudharabah]',
            'jumlah' => 'required',
            'lama_cicilan' => 'required|numeric|greater_than[0]|less_than_equal_to[12]',
            'deskripsi' => 'required|min_length[10]|max_length[500]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $jenis = $this->request->getPost('jenis');
        $jumlah = preg_replace('/[^0-9]/', '', $this->request->getPost('jumlah'));
        $lama_cicilan = $this->request->getPost('lama_cicilan');
        $deskripsi = $this->request->getPost('deskripsi');

        if ($jumlah == '' || $jumlah == 0) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman tidak valid');
        }

        $maxLimit = 4000000;
        if ($jenis === 'murabahah') {
            $maxLimit = 10000000;
        } elseif ($jenis === 'mudharabah') {
            $maxLimit = 20000000;
        }

        if ($jumlah > $maxLimit) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman ' . strtoupper($jenis) . ' melebihi batas maksimum Rp ' . number_format($maxLimit, 0, ',', '.'));
        }

        if ($jumlah < 100000) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman minimal Rp 100.000');
        }

        $session = session();
        $pinjamanData = [
            'id_anggota' => $id_user,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'lama_cicilan' => $lama_cicilan,
            'deskripsi' => $deskripsi,
            'tanggal' => date('Y-m-d'),
            'status' => 'pending'
        ];

        $sessionKey = 'pending_pinjaman_' . $id_user . '_' . time();
        $session->set($sessionKey, $pinjamanData);
        $session->set('current_pinjaman_key', $sessionKey);

        return redirect()->to('pinjaman')
            ->with('show_pin_modal', true)
            ->with('pinjaman_session_key', $sessionKey);
    }

    public function processAfterPin()
    {
        $id_user = session()->get('id');

        if ($this->hasActiveLoan($id_user)) {
            return redirect()->to('pinjaman')->with('error', 'Anda masih memiliki pengajuan pending atau pinjaman aktif.');
        }

        $pin_input = $this->request->getPost('pin');

        if (empty($pin_input) || strlen($pin_input) !== 6) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'PIN harus 6 digit');
        }

        if (!$this->verifyPin($id_user, $pin_input)) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'PIN yang Anda masukkan salah.');
        }

        $jenis        = $this->request->getPost('pinjaman_jenis');
        $jumlah       = (float) preg_replace('/[^0-9]/', '', $this->request->getPost('pinjaman_jumlah'));
        $lama_cicilan = $this->request->getPost('pinjaman_lama_cicilan');
        $deskripsi    = $this->request->getPost('pinjaman_deskripsi');

        if (!$jenis || !$jumlah || !$lama_cicilan || !$deskripsi) {
            return redirect()->to('pinjaman')->with('error', 'Data pinjaman tidak lengkap. Silakan ulangi pengajuan.');
        }

        if ($jumlah == '' || $jumlah == 0) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman tidak valid');
        }

        $maxLimit = 4000000;
        if ($jenis === 'murabahah') {
            $maxLimit = 10000000;
        } elseif ($jenis === 'mudharabah') {
            $maxLimit = 20000000;
        }

        if ($jumlah > $maxLimit) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman ' . strtoupper($jenis) . ' melebihi batas maksimum Rp ' . number_format($maxLimit, 0, ',', '.'));
        }

        if ($jumlah < 100000) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman minimal Rp 100.000');
        }

        $db = \Config\Database::connect();

        try {
            if ($jenis == 'qard') {
                // Qard (Nir-Margin): Tetap Nominal Murni
                $db->table('qard')->insert([
                    'id_anggota'   => $id_user,
                    'jml_pinjam'   => $jumlah,
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi'    => $deskripsi,
                    'tanggal'      => date('Y-m-d'),
                    'status'       => 'pending'
                ]);
            } elseif ($jenis == 'murabahah') {
                // Murabahah: Tambah Margin 10% ke Total Pinjaman (Contoh: 10 Juta -> 11 Juta)
                $totalDenganMargin = $jumlah + ($jumlah * 0.10);

                $db->table('murabahah')->insert([
                    'id_anggota'   => $id_user,
                    'jml_pinjam'   => $totalDenganMargin, // Total Pokok + Margin 10%
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi'    => $deskripsi,
                    'tanggal'      => date('Y-m-d'),
                    'status'       => 'pending'
                ]);
            } elseif ($jenis == 'mudharabah') {
                // Mudharabah: Tambah Margin/Nisbah 10% ke Total Pinjaman (Contoh: 10 Juta -> 11 Juta)
                $totalDenganMargin = $jumlah + ($jumlah * 0.10);

                $db->table('mudharabah')->insert([
                    'id_anggota'   => $id_user,
                    'jml_pinjam'   => $totalDenganMargin, // Total Pokok + Margin 10%
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi'    => $deskripsi,
                    'tanggal'      => date('Y-m-d'),
                    'status'       => 'pending'
                ]);
            }

            return redirect()->to('pinjaman')->with('pinjaman_success', true);
        } catch (\Exception $e) {
            return redirect()->to('pinjaman')->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function createPin()
    {
        $id_user = session()->get('id');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'new_pin' => 'required|numeric|exact_length[6]',
            'confirm_pin' => 'required|matches[new_pin]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }

        $new_pin = $this->request->getPost('new_pin');
        $db = \Config\Database::connect();

        try {
            $pin_hash = password_hash($new_pin, PASSWORD_DEFAULT);

            $existing = $db->table('user_pin')
                ->where('user_id', $id_user)
                ->get()
                ->getRowArray();

            if ($existing) {
                $db->table('user_pin')
                    ->where('user_id', $id_user)
                    ->update([
                        'pin_hash' => $pin_hash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $db->table('user_pin')->insert([
                    'user_id' => $id_user,
                    'pin_hash' => $pin_hash,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function verifyPinAjax()
    {
        $id_user = session()->get('id');
        $pin_input = $this->request->getPost('pin');

        if (empty($pin_input) || strlen($pin_input) !== 6) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PIN harus 6 digit'
            ]);
        }

        if ($this->verifyPin($id_user, $pin_input)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN valid'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PIN salah'
            ]);
        }
    }

    public function checkNoRekening()
    {
        $id_user = session()->get('id');
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);

        return $this->response->setJSON([
            'hasNoRekening' => !empty($anggota['no_rek']),
            'no_rek' => $anggota['no_rek'] ?? null
        ]);
    }

    public function validateBeforeSubmit()
    {
        $id_user = session()->get('id');

        $hasPin = $this->hasPinCreated($id_user);

        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);
        $hasNoRekening = !empty($anggota['no_rek']);

        $hasActiveLoan = $this->hasActiveLoan($id_user);

        $canSubmit = $hasNoRekening && !$hasActiveLoan;

        $messages = [];
        if (!$hasNoRekening) $messages[] = 'Nomor rekening belum diisi';
        if ($hasActiveLoan) $messages[] = 'Anda masih memiliki pengajuan pending atau pinjaman aktif';

        return $this->response->setJSON([
            'success' => true,
            'hasPin' => $hasPin,
            'hasNoRekening' => $hasNoRekening,
            'hasActiveLoan' => $hasActiveLoan,
            'canSubmit' => $canSubmit,
            'messages' => $messages
        ]);
    }

    private function hasActiveLoan($id_anggota)
    {
        $db = \Config\Database::connect();
        $tables = ['qard', 'murabahah', 'mudharabah'];

        foreach ($tables as $table) {
            $rows = $db->table($table)
                ->where('id_anggota', $id_anggota)
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $status = normalizeLoanStatusValue($row['status'] ?? '');
                
                // HANYA status 'pending' atau 'aktif' yang mengunci pengajuan
                // Status 'ditolak' dan 'lunas' DIABAIKAN (User BISA mengajukan pinjaman baru)
                if (in_array($status, ['pending', 'aktif'], true)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getPendingLoans($id_anggota)
    {
        $db = \Config\Database::connect();
        $tables = ['qard', 'murabahah', 'mudharabah'];
        $count = 0;

        foreach ($tables as $table) {
            $rows = $db->table($table)
                ->where('id_anggota', $id_anggota)
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                if (normalizeLoanStatusValue($row['status'] ?? '') === 'pending') {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function getActiveLoan()
    {
        $id_user = session()->get('id');
        $db = \Config\Database::connect();

        $activeLoans = [];
        $tables = ['qard', 'murabahah', 'mudharabah'];

        foreach ($tables as $table) {
            $rows = $db->table($table)
                ->where('id_anggota', $id_user)
                ->get()
                ->getResultArray();

            foreach ($rows as $loan) {
                $status = normalizeLoanStatusValue($loan['status'] ?? '');
                if (in_array($status, ['aktif', 'pending'], true)) {
                    $loan['status'] = $status;
                    $loan['jenis'] = $table;
                    $activeLoans[] = $loan;
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $activeLoans
        ]);
    }

    private function hasPinCreated($user_id)
    {
        $db = \Config\Database::connect();

        $pinData = $db->table('user_pin')
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();

        return !empty($pinData);
    }

    private function verifyPin($user_id, $pin_input)
    {
        $db = \Config\Database::connect();

        $pinData = $db->table('user_pin')
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();

        if (empty($pinData)) {
            return false;
        }

        if (isset($pinData['pin_hash']) && password_verify($pin_input, $pinData['pin_hash'])) {
            return true;
        }

        return false;
    }

    private function getLoanStatusDetail($id_anggota)
    {
        $db = \Config\Database::connect();
        $tables = ['qard', 'murabahah', 'mudharabah'];

        // Cek apakah ada yang PENDING
        foreach ($tables as $t) {
            $rows = $db->table($t)->where('id_anggota', $id_anggota)->get()->getResultArray();
            foreach ($rows as $row) {
                $status = normalizeLoanStatusValue($row['status'] ?? '');
                if ($status === 'pending') {
                    return 'pending';
                }
            }
        }

        // Cek apakah ada yang AKTIF
        foreach ($tables as $t) {
            $rows = $db->table($t)->where('id_anggota', $id_anggota)->get()->getResultArray();
            foreach ($rows as $row) {
                $status = normalizeLoanStatusValue($row['status'] ?? '');
                if ($status === 'aktif') {
                    return 'aktif';
                }
            }
        }

        // Jika tidak ada yang pending/aktif (sudah ditolak atau lunas semua)
        return 'none';
    }

    private function normalizeLoanStatus($status)
    {
        return normalizeLoanStatusValue($status);
    }
}
