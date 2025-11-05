<?php
namespace App\Controllers;
use App\Models\AnggotaModel;
use App\Models\UserModel;
use App\Models\QardModel;
use App\Models\MurabahahModel;
use App\Models\MudharabahModel;

class AdminDashboard extends BaseController
{
    public function pendingLoans()
{
    $db = \Config\Database::connect();
    
    $qard = $db->table('qard')
    ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
    ->where('qard.status', 'pending')
    ->select('qard.id_qard AS id, qard.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, qard.tanggal, qard.jml_pinjam, qard.status, "qard" as jenis')
    ->get()->getResultArray();

    $murabahah = $db->table('murabahah')
        ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
        ->where('murabahah.status', 'pending')
        ->select('murabahah.id_mr AS id, murabahah.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, murabahah.tanggal, murabahah.jml_pinjam, murabahah.status, "murabahah" as jenis')
        ->get()->getResultArray();

    $mudharabah = $db->table('mudharabah')
        ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
        ->where('mudharabah.status', 'pending')
        ->select('mudharabah.id_md AS id, mudharabah.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, mudharabah.tanggal, mudharabah.jml_pinjam, mudharabah.status, "mudharabah" as jenis')
        ->get()->getResultArray();

    $pending = array_merge($qard, $murabahah, $mudharabah);

    return view('layouts/header', ['title' => 'Verifikasi Pinjaman'])
        . view('dashboard_admin/pending_pinjaman', ['pending' => $pending])
        . view('layouts/footer');
}
public function verifikasiPinjaman($jenis, $id)
{
    $allowed = ['qard', 'murabahah', 'mudharabah'];
    $jenisMap = [
        'qard' => 'id_qard',
        'murabahah' => 'id_mr',
        'mudharabah' => 'id_md'
    ];

    if (!in_array($jenis, $allowed)) {
        return redirect()->back()->with('error', 'Jenis pinjaman tidak valid.');
    }

    $db = \Config\Database::connect();
    $updated = $db->table($jenis)
                  ->where($jenisMap[$jenis], $id)  // pakai kolom ID yang sesuai
                  ->update(['status' => 'aktif']);

    if ($updated) {
        return redirect()->back()->with('success', 'Pinjaman berhasil diverifikasi.');
    } else {
        return redirect()->back()->with('error', 'Gagal memverifikasi pinjaman.');
    }
}

public function tolakPinjaman($jenis, $id)
{
    $allowed = ['qard', 'murabahah', 'mudharabah'];
    $jenisMap = [
        'qard' => 'id_qard',
        'murabahah' => 'id_mr',
        'mudharabah' => 'id_md'
    ];

    if (!in_array($jenis, $allowed)) {
        return redirect()->back()->with('error', 'Jenis pinjaman tidak valid.');
    }

    $db = \Config\Database::connect();
    // Jika ingin ubah status menjadi "ditolak", bisa tambahkan update di sini:
    $updated = $db->table($jenis)
                  ->where($jenisMap[$jenis], $id)
                  ->update(['status' => 'ditolak']);  // atau sesuai status tolak

    if ($updated) {
        return redirect()->back()->with('success', 'Pinjaman berhasil ditolak.');
    } else {
        return redirect()->back()->with('error', 'Gagal menolak pinjaman.');
    }
}


    protected $userModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->anggotaModel = new AnggotaModel();
    }

    // API untuk live search anggota (tidak dipakai di input simpanan, tapi tetap ada)
    public function searchAnggota()
{
    $search = $this->request->getGet('q');
    $builder = $this->anggotaModel;
    
    if ($search) {
        $builder = $builder->like('nama_lengkap', $search)
                           ->orLike('no_ktp', $search);
    }
    $anggota = $builder->findAll(10);
    
    $userModel = new \App\Models\UserModel();
    $users = $userModel->findAll();
    
    // Buat mapping email -> user id
    $emailToUserId = [];
    foreach ($users as $user) {
        $emailToUserId[$user['email']] = $user['id'];
    }
    
    $result = array_map(function($data) use ($emailToUserId) {
        $user_id = null;
        
        // Cari user_id berdasarkan email
        if (isset($data['email']) && isset($emailToUserId[$data['email']])) {
            $user_id = $emailToUserId[$data['email']];
        }
        
        return [
            'id_anggota' => $data['id_anggota'],
            'id_user' => $user_id, // ID dari table users (kolom 'id')
            'nama_lengkap' => $data['nama_lengkap'],
            'no_ktp' => $data['no_ktp'],
            'status' => $data['status'] ?? 'Menunggu Verifikasi',
            'tanggal_daftar' => isset($data['tanggal_daftar']) ? date('d M Y', strtotime($data['tanggal_daftar'])) : '-',
            'urlDetail' => base_url('admin/detail-anggota/' . $data['id_anggota'])
        ];
    }, $anggota);
    
    return $this->response->setJSON($result);
}

    public function index()
{
    $db = \Config\Database::connect();

    // =========================
    // Hitung Total Anggota Aktif
    // =========================
    $totalAnggota = $db->table('anggota')
        ->where('status', 'aktif')
        ->countAllResults();

    // =========================
    // Hitung Total Simpanan
    // =========================
    $totalSimpananPokok = $db->table('simpanan_pokok')
        ->where('status', 'aktif')
        ->selectSum('jumlah')
        ->get()->getRow()->jumlah ?? 0;

    $totalSimpananWajib = $db->table('simpanan_wajib')
        ->where('status', 'aktif')
        ->selectSum('jumlah')
        ->get()->getRow()->jumlah ?? 0;

    $totalSimpananSukarela = $db->table('simpanan_sukarela')
        ->where('status', 'aktif')
        ->selectSum('jumlah')
        ->get()->getRow()->jumlah ?? 0;

    $totalSimpanan = $totalSimpananPokok + $totalSimpananWajib + $totalSimpananSukarela;

    // =========================
    // Hitung Pembiayaan Berjalan
    // =========================
    $totalQard = $db->table('qard')
        ->where('status', 'aktif')
        ->selectSum('jml_pinjam')
        ->get()->getRow()->jml_pinjam ?? 0;

    $totalMurabahah = $db->table('murabahah')
        ->where('status', 'aktif')
        ->selectSum('jml_pinjam')
        ->get()->getRow()->jml_pinjam ?? 0;

    $totalMudharabah = $db->table('mudharabah')
        ->where('status', 'aktif')
        ->selectSum('jml_pinjam')
        ->get()->getRow()->jml_pinjam ?? 0;

    $totalPembiayaan = $totalQard + $totalMurabahah + $totalMudharabah;

    // =========================
    // Hitung Pendapatan Margin Bulanan (10%)
    // =========================
    $bulanIni = date('m');
    $tahunIni = date('Y');

    $marginQard = $db->table('qard')
        ->where('status', 'aktif')
        ->where('MONTH(tanggal)', $bulanIni)
        ->where('YEAR(tanggal)', $tahunIni)
        ->selectSum('jml_terbayar')
        ->get()->getRow()->jumlah ?? 0;

    $marginMurabahah = $db->table('murabahah')
        ->where('status', 'aktif')
        ->where('MONTH(tanggal)', $bulanIni)
        ->where('YEAR(tanggal)', $tahunIni)
        ->selectSum('jml_terbayar')
        ->get()->getRow()->jumlah ?? 0;

    $marginMudharabah = $db->table('mudharabah')
        ->where('status', 'aktif')
        ->where('MONTH(tanggal)', $bulanIni)
        ->where('YEAR(tanggal)', $tahunIni)
        ->selectSum('jml_terbayar')
        ->get()->getRow()->jumlah ?? 0;

    $totalMargin = ($marginQard + $marginMurabahah + $marginMudharabah) * 0.10;

    // =========================
    // Pending (yang udah ada)
    // =========================
    $pendingSimpananCount = $db->table('simpanan_sukarela')->where('status', 'pending')->countAllResults();
    $pendingCount = $this->userModel->where('role', 'anggota')
                                    ->where('status', 'pending')
                                    ->countAllResults();
    $pendingQard = $db->table('qard')->where('status', 'pending')->countAllResults();
    $pendingMurabahah = $db->table('murabahah')->where('status', 'pending')->countAllResults();
    $pendingMudharabah = $db->table('mudharabah')->where('status', 'pending')->countAllResults();
    $pendingPinjamanCount = $pendingQard + $pendingMurabahah + $pendingMudharabah;
    $pendingPembayaranCount = $db->table('pembayaran_pending')->where('status', 'pending')->countAllResults();

    // =========================
    // DATA CHART
    // =========================
    // SIMPANAN (biarin tetep)
$simpanan = $db->query("
    SELECT bulan, SUM(total) AS total FROM (
        SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total
        FROM simpanan_pokok
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
        UNION ALL
        SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total
        FROM simpanan_sukarela
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
        UNION ALL
        SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total
        FROM simpanan_wajib
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
    ) AS gabungan
    GROUP BY bulan
    ORDER BY bulan

")->getResultArray();

// PEMBIAYAAN: gabungin 3 tabel jadi 1 hasil
$pembiayaan = $db->query("
    SELECT bulan, SUM(total) AS total FROM (
        SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total
        FROM mudharabah
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
        UNION ALL
        SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total
        FROM murabahah
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
        UNION ALL
        SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total
        FROM qard
        WHERE YEAR(tanggal) = YEAR(CURDATE())
        GROUP BY bulan
    ) AS gabungan
    GROUP BY bulan
    ORDER BY bulan
")->getResultArray();

$labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$simpananData = array_fill(0, 12, 0);
$pembiayaanData = array_fill(0, 12, 0);

foreach ($simpanan as $row) {
    $simpananData[$row['bulan'] - 1] = (int)$row['total'];
}
foreach ($pembiayaan as $row) {
    $pembiayaanData[$row['bulan'] - 1] = (int)$row['total'];
}

// Gabungkan semua data ke satu array
$data = [
    'totalAnggota'        => $totalAnggota,
    'totalSimpanan'       => $totalSimpanan,
    'totalPembiayaan'     => $totalPembiayaan,
    'totalMargin'         => $totalMargin,
    'pendingPinjamanCount'=> $pendingPinjamanCount,
    'pendingSimpananCount'=> $pendingSimpananCount,
    'pendingPembayaranCount' => $pendingPembayaranCount,
    'pendingCount'        => $pendingCount,
    'chartLabels'         => json_encode($labels),
    'chartSimpanan'       => json_encode($simpananData),
    'chartPembiayaan'     => json_encode($pembiayaanData),
];


    return view('layouts/header', ['title' => 'Dashboard Admin'])
        . view('dashboard_admin/index', $data)
        . view('layouts/footer');
}


    public function pendingMembers()
    {
        $pending = $this->userModel->where('role', 'anggota')
                                    ->where('status', 'pending')
                                    ->findAll();

        // Ambil data foto dari session untuk setiap user pending
        foreach ($pending as &$user) {
            $sessionKey = 'register_data_' . $user['id'];
            $registerData = session()->get($sessionKey);

            if ($registerData) {
                $user['foto_diri'] = $registerData['foto_diri'] ?? '';
                $user['foto_ktp'] = $registerData['foto_ktp'] ?? '';
                $user['foto_diri_ktp'] = $registerData['foto_diri_ktp'] ?? '';
            } else {
                $user['foto_diri'] = '';
                $user['foto_ktp'] = '';
                $user['foto_diri_ktp'] = '';
            }
        }

        return view('layouts/header', ['title' => 'Verifikasi Anggota'])
            . view('dashboard_admin/pending_members', ['anggota' => $pending])
            . view('layouts/footer');
    }

    public function generateNomorAnggota()
    {
        $today = date('Y-m-d');
        $countToday = $this->anggotaModel
            ->where('DATE(tanggal_daftar)', $today)
            ->countAllResults();

        $nextNumber = $countToday + 1;
        $nomor = date('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        while ($this->anggotaModel->where('nomor_anggota', $nomor)->first()) {
            $nextNumber++;
            $nomor = date('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $nomor;
    }

    public function verify($id)
{
    $existingAnggota = $this->anggotaModel->where('id_anggota', $id)->first();
    if ($existingAnggota) {
        return redirect()->back()->with('warning', 'Anggota sudah diverifikasi sebelumnya.');
    }

    $user = $this->userModel->find($id);
    if (!$user) {
        return redirect()->back()->with('error', 'Data user tidak ditemukan.');
    }

    $nomor_anggota = $this->generateNomorAnggota();
    $today = date('Y-m-d');

    // 1. UPDATE TABLE USERS
    $this->userModel->update($id, ['status' => 'verified']);

    // 2. Ambil data tambahan dari session (fallback ke POST jika tidak ada)
    $sessionKey = 'register_data_' . $id;
    $registerData = session()->get($sessionKey);

    // 3. INSERT KE TABLE ANGGOTA dengan data dari form tambahan
    $anggotaData = [
        'id_anggota'          => $id,
        'nomor_anggota'       => $nomor_anggota,

        // Data dari users
        'nama_lengkap'        => $user['nama_lengkap'] ?? '',
        'email'               => $user['email'] ?? '',
        'no_ktp'              => $user['nomor_ktp'] ?? '',
        'foto_diri'           => $user['foto'] ?? '',
        'no_hp'               => $user['nomor_hp'] ?? '',

        // Data dari session atau POST (prioritas session)
        'jenis_kelamin'       => $registerData['jenis_kelamin'] ?? $this->request->getPost('jenis_kelamin') ?? '',
        'pekerjaan'           => $registerData['pekerjaan'] ?? $this->request->getPost('pekerjaan') ?? '',
        'alamat'              => $registerData['alamat'] ?? $this->request->getPost('alamat') ?? '',
        'no_rek'              => $registerData['no_rek'] ?? $this->request->getPost('no_rek') ?? '',
        'atasnama_rekening'   => $registerData['atasnama_rekening'] ?? $this->request->getPost('atasnama_rekening') ?? '',
        'jenis_bank'          => $registerData['jenis_bank'] ?? $this->request->getPost('jenis_bank') ?? '',
        'foto_ktp'            => $registerData['foto_ktp'] ?? $this->request->getPost('foto_ktp') ?? '',
        'foto_diri_ktp'       => $registerData['foto_diri_ktp'] ?? $this->request->getPost('foto_diri_ktp') ?? '',

        'status'              => 'aktif',
        'tanggal_daftar'      => $today
    ];

    $result = $this->anggotaModel->insert($anggotaData);

    if (!$result) {
        $errors = $this->anggotaModel->errors();
        return redirect()->back()->with('error', 'Gagal insert anggota: ' . implode(', ', $errors));
    }

    // 4. Hapus data session setelah berhasil verifikasi
    session()->remove($sessionKey);

    return redirect()->to('/admin/pending-members')
        ->with('success', 'Anggota berhasil diverifikasi.');
}

    public function reject($id)
    {
        $this->userModel->update($id, ['status' => 'rejected']);
        return redirect()->to('/admin/pending-members')->with('success', 'Anggota berhasil ditolak!');
    }
   public function members()
{
    $search = $this->request->getGet('search');
    $builder = $this->anggotaModel;
    
    if ($search) {
        $builder = $builder->like('nama_lengkap', $search)
                           ->orLike('no_ktp', $search);
    }
    $anggota = $builder->findAll();

    // Cari relasi antara anggota dan users
    $userModel = new \App\Models\UserModel();
    $users = $userModel->findAll();
    
    // Buat mapping untuk mencari user_id berdasarkan email atau data lain
    $emailToUserId = [];
    foreach ($users as $user) {
        $emailToUserId[$user['email']] = $user['id']; // id dari table users
    }
    
    foreach ($anggota as &$data) {
        $user_id = null;
        
        // Cara 1: Cari berdasarkan email (paling umum)
        if (isset($data['email']) && isset($emailToUserId[$data['email']])) {
            $user_id = $emailToUserId[$data['email']];
        }
        
        // Cara 2: Jika ada foreign key di table anggota
        if (!$user_id && isset($data['id_user'])) {
            $user_id = $data['id_user'];
        }
        
        $data['id_user'] = $user_id;
    }

    return view('layouts/header', ['title' => 'Manajemen Anggota'])
         . view('dashboard_admin/members', ['anggota' => $anggota, 'search' => $search])
         . view('layouts/footer');
}

   public function resetPassword()
{
    // Cek jika request AJAX
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(405)->setJSON([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]);
    }

    $userModel = new UserModel();
    
    // Dapatkan semua data POST
    $postData = $this->request->getPost();
    $userId = $this->request->getPost('user_id');
    
    // DEBUG DETAIL
    log_message('debug', '=== RESET PASSWORD DEBUG START ===');
    log_message('debug', 'Raw POST data: ' . json_encode($postData));
    log_message('debug', 'User ID from POST: ' . $userId);
    log_message('debug', 'Type of User ID: ' . gettype($userId));
    log_message('debug', 'User ID after trim: ' . trim($userId));
    log_message('debug', 'Is empty: ' . (empty($userId) ? 'YES' : 'NO'));
    log_message('debug', 'Is null: ' . ($userId === null ? 'YES' : 'NO'));
    log_message('debug', 'Equals "null": ' . ($userId === 'null' ? 'YES' : 'NO'));
    log_message('debug', 'Equals "undefined": ' . ($userId === 'undefined' ? 'YES' : 'NO'));
    log_message('debug', '=== RESET PASSWORD DEBUG END ===');

    // Validasi user_id - PERBAIKI VALIDASI INI
    if ($userId === null || $userId === '' || $userId === 'null' || $userId === 'undefined') {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'ID user tidak valid atau kosong. Value: ' . json_encode($userId)
        ]);
    }

    // Pastikan user_id adalah integer
    $userId = (int) $userId;
    log_message('debug', 'User ID after int conversion: ' . $userId);
    
    if ($userId <= 0) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Format ID user tidak valid. Value: ' . $userId
        ]);
    }

    // Cek apakah user exists di table users
    log_message('debug', 'Checking if user exists with ID: ' . $userId);
    $user = $userModel->find($userId);
    
    if (!$user) {
        log_message('debug', 'User not found with ID: ' . $userId);
        
        // Cek apa yang ada di table users
        $allUsers = $userModel->findAll();
        $userIds = array_column($allUsers, 'id');
        log_message('debug', 'Available user IDs: ' . json_encode($userIds));
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'User dengan ID ' . $userId . ' tidak ditemukan di database. Available IDs: ' . json_encode($userIds)
        ]);
    }

    log_message('debug', 'User found: ' . json_encode($user));

    // Reset password ke "123"
    $defaultPassword = '123';
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);
    
    $updateData = [
        'password' => $hashedPassword
    ];

    log_message('debug', 'Attempting to update password for user ID: ' . $userId);
    
    if ($userModel->update($userId, $updateData)) {
        log_message('info', "Password user ID {$userId} ({$user['email']}) berhasil direset oleh admin");
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Password berhasil direset ke default (123)'
        ]);
    } else {
        $error = $userModel->errors();
        log_message('error', 'Failed to reset password. Errors: ' . json_encode($error));
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mereset password. Error: ' . json_encode($error)
        ]);
    }
}
public function toggleMemberStatus()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $memberId = $this->request->getPost('member_id');
    $currentStatus = $this->request->getPost('current_status');
    
    $newStatus = ($currentStatus === 'aktif') ? 'nonaktif' : 'aktif';
    
    try {
        // Update status di database
        $this->anggotaModel->update($memberId, ['status' => $newStatus]);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status anggota berhasil diubah menjadi ' . $newStatus
        ]);
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mengubah status: ' . $e->getMessage()
        ]);
    }
}
   public function saveMember()
{
    try {
        $request = $this->request;

        // Ambil data dari form
        $nama_lengkap = $request->getPost('nama_lengkap');
        $email        = $request->getPost('email');
        $username     = $request->getPost('username');
        $password     = $request->getPost('password');
        $no_ktp       = $request->getPost('no_ktp');
        $no_telp      = $request->getPost('no_telp');
        $alamat       = $request->getPost('alamat');

        // Validasi required fields
        if (empty($nama_lengkap) || empty($email) || empty($username) || empty($password)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Semua field wajib diisi!'
            ]);
        }

        // Validasi KTP duplikat
        $existing = $this->anggotaModel->where('no_ktp', $no_ktp)->first();
        if ($existing) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Anggota dengan No. KTP ini sudah terdaftar.'
            ]);
        }

        // Validasi username/email duplikat di users
        $existingUser = $this->userModel->where('username', $username)->orWhere('email', $email)->first();
        if ($existingUser) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Username atau email sudah digunakan.'
            ]);
        }

        // Handle file upload
        $fotoDiriName = 'default.png';
        $fotoKtpName  = 'default.png';

        $fotoDiri = $request->getFile('foto_diri');
        if ($fotoDiri && $fotoDiri->isValid() && !$fotoDiri->hasMoved()) {
            $fotoDiriName = $fotoDiri->getRandomName();
            $fotoDiri->move(FCPATH . 'uploads/', $fotoDiriName);
        }

        $fotoKtp = $request->getFile('foto_ktp');
        if ($fotoKtp && $fotoKtp->isValid() && !$fotoKtp->hasMoved()) {
            $fotoKtpName = $fotoKtp->getRandomName();
            $fotoKtp->move(FCPATH . 'uploads/', $fotoKtpName);
        }

        // 1️⃣ Simpan ke tabel users
        $userData = [
            'nama_lengkap' => $nama_lengkap,
            'email'        => $email,
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'anggota',
            'no_ktp'       => $no_ktp,
            'nomor_hp'     => $no_telp,
            'status'       => 'verified',
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();

        // 2️⃣ Simpan ke tabel anggota
        $anggotaData = [
            'id_anggota'        => $userId,
            'nomor_anggota'     => 'AGT-' . date('Ymd') . '-' . $userId,
            'nama_lengkap'      => $nama_lengkap,
            'no_ktp'            => $no_ktp,
            'alamat'            => $alamat,
            'email'             => $email,
            'status'            => 'aktif',
            'foto_diri'         => $fotoDiriName,
            'foto_ktp'          => $fotoKtpName,
            'photo'             => $fotoDiriName,
            'tanggal_daftar'    => date('Y-m-d'),
        ];

        $this->anggotaModel->insert($anggotaData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Anggota berhasil ditambahkan!'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error saveMember: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}




    // =========================
    // FITUR SIMPANAN
    // =========================

  public function savings()
{
    $db = \Config\Database::connect();

    try {
        // **PERBAIKAN: Ambil data simpanan pokok dengan filter jumlah > 0**
        $simpananPokok = $db->table('simpanan_pokok')
            ->select('simpanan_pokok.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
            ->where('simpanan_pokok.jumlah >', 0)  // **FILTER PENTING**
            ->get()->getResultArray();

        $simpananWajib = $db->table('simpanan_wajib')
            ->select('simpanan_wajib.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota')
            ->get()->getResultArray();
            
        $simpananSukarela = $db->table('simpanan_sukarela')
            ->select('simpanan_sukarela.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota')
            ->get()->getResultArray();

        // Hitung total masing-masing (sudah terfilter jumlah > 0)
        $totalPokok = array_sum(array_column($simpananPokok, 'jumlah')) ?? 0;
        $totalWajib = array_sum(array_column($simpananWajib, 'jumlah')) ?? 0;
        $totalSukarela = array_sum(array_column($simpananSukarela, 'jumlah')) ?? 0;

        // **PERBAIKAN: Hitung anggota unik yang punya simpanan pokok > 0**
        $anggotaPokok = $db->table('simpanan_pokok')
            ->select('id_anggota')
            ->where('jumlah >', 0)  // **FILTER PENTING**
            ->groupBy('id_anggota')
            ->countAllResults();
        
        // **PERBAIKAN: Hitung anggota lunas (total simpanan pokok >= 500000)**
        $anggotaLunas = $db->table('simpanan_pokok')
            ->select('id_anggota, SUM(jumlah) as total')
            ->where('jumlah >', 0)  // **FILTER PENTING**
            ->groupBy('id_anggota')
            ->having('total >=', 500000)
            ->countAllResults();

        // Ambil data anggota untuk filter
        $anggotaList = $db->table('anggota')
            ->select('id_anggota, nama_lengkap')
            ->where('status', 'aktif')
            ->get()->getResultArray();

    } catch (\Exception $e) {
        // Jika error, set default values
        $totalPokok = 0;
        $totalWajib = 0;
        $totalSukarela = 0;
        $anggotaPokok = 0;
        $anggotaLunas = 0;
        $simpananPokok = [];
        $simpananWajib = [];
        $simpananSukarela = [];
        $anggotaList = [];
        
        log_message('error', 'Error in savings method: ' . $e->getMessage());
    }

    return view('layouts/header', ['title' => 'Manajemen Simpanan'])
        . view('dashboard_admin/savings', [
            'totalPokok' => $totalPokok,
            'totalWajib' => $totalWajib,
            'totalSukarela' => $totalSukarela,
            'anggotaPokok' => $anggotaPokok,
            'anggotaLunas' => $anggotaLunas,
            'pokok' => $simpananPokok,
            'wajib' => $simpananWajib,
            'sukarela' => $simpananSukarela,
            'anggotaList' => $anggotaList
        ])
        . view('layouts/footer');
}

    // Input simpanan (POST)
   public function inputSimpanan()
{
    $jenis = $this->request->getPost('jenis');
    $jumlah = $this->request->getPost('jumlah');
    $id_anggota = $this->request->getPost('id_anggota');
    $tanggal = date('Y-m-d');
    $status = 'aktif';

    // Pilih model & kolom sesuai jenis
    if ($jenis === 'pokok') {
        $model = new \App\Models\SimpananPokokModel();
        
        // Validasi jumlah tidak boleh 0 atau negatif
        if ($jumlah <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jumlah simpanan harus lebih dari 0'
            ]);
        }
        
        // Jika "Semua Anggota"
        if ($id_anggota === 'all') {
            $anggotaModel = new \App\Models\AnggotaModel();
            $allAnggota = $anggotaModel->findAll();
            
            $successCount = 0;
            $failedCount = 0;
            $details = [];
            
            foreach ($allAnggota as $a) {
                $currentId = $a['id_anggota'];
                $currentNama = $a['nama_lengkap'];
                
                // Validasi untuk setiap anggota
                $validation = $model->validateInput($currentId, $jumlah);
                
                if ($validation['valid']) {
                    $data = [
                        'id_anggota' => $currentId,
                        'jumlah' => $jumlah,
                        'tanggal' => $tanggal,
                        'status' => 'aktif'
                    ];
                    
                    if ($model->insert($data)) {
                        $successCount++;
                        // Update status setelah insert
                        $model->updateStatus($currentId);
                    } else {
                        $failedCount++;
                        $details[] = "$currentNama: Gagal menyimpan";
                    }
                } else {
                    $failedCount++;
                    $details[] = "$currentNama: " . $validation['message'];
                }
            }
            
            $message = "Berhasil: {$successCount} data, Gagal: {$failedCount} data";
            if (!empty($details)) {
                $message .= "\nDetail: " . implode("; ", $details);
            }
            
            return $this->response->setJSON([
                'success' => $successCount > 0,
                'message' => $message
            ]);
        }
        
        // Untuk input per anggota
        // Validasi input
        $validation = $model->validateInput($id_anggota, $jumlah);
        if (!$validation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation['message']
            ]);
        }
        
        $data = [
            'id_anggota' => $id_anggota,
            'jumlah' => $jumlah,
            'tanggal' => $tanggal,
            'status' => $status
        ];
        
        $result = $model->insert($data);
        
        // Update status setelah insert
        if ($result) {
            $model->updateStatus($id_anggota);
        }
        
        return $this->response->setJSON([
            'success' => $result ? true : false,
            'message' => $result ? 'Data berhasil disimpan' : 'Gagal menyimpan data'
        ]);
        
    } elseif ($jenis === 'wajib') {
        $model = new \App\Models\SimpananWajibModel();
        
        // Validasi jumlah
        if ($jumlah <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jumlah simpanan harus lebih dari 0'
            ]);
        }
        
        $data = [
            'id_anggota' => $id_anggota,
            'jumlah' => $jumlah,
            'tanggal' => $tanggal,
            'status' => $status
        ];
        
        // Jika "Semua Anggota"
        if ($id_anggota === 'all') {
            $anggotaModel = new \App\Models\AnggotaModel();
            $allAnggota = $anggotaModel->findAll();
            foreach ($allAnggota as $a) {
                $data['id_anggota'] = $a['id_anggota'];
                $model->insert($data);
            }
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Data berhasil disimpan untuk semua anggota'
            ]);
        }
        
        $result = $model->insert($data);
        return $this->response->setJSON([
            'success' => $result ? true : false,
            'message' => $result ? 'Data berhasil disimpan' : 'Gagal menyimpan data'
        ]);
        
    } elseif ($jenis === 'sukarela') {
        $model = new \App\Models\SimpananSukarelaModel();
        
        // Validasi jumlah
        if ($jumlah <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jumlah simpanan harus lebih dari 0'
            ]);
        }
        
        $data = [
            'id_anggota' => $id_anggota,
            'jumlah' => $jumlah,
            'tanggal' => $tanggal,
            'status' => 'pending'
        ];
        
        // Jika "Semua Anggota"
        if ($id_anggota === 'all') {
            $anggotaModel = new \App\Models\AnggotaModel();
            $allAnggota = $anggotaModel->findAll();
            foreach ($allAnggota as $a) {
                $data['id_anggota'] = $a['id_anggota'];
                $model->insert($data);
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil disimpan untuk semua anggota'
            ]);
        }
        
        $result = $model->insert($data);
        return $this->response->setJSON([
            'success' => $result ? true : false,
            'message' => $result ? 'Data berhasil disimpan' : 'Gagal menyimpan data'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Jenis simpanan tidak valid'
        ]);
    }
}

    // Ambil data simpanan (GET)
   public function getSimpananList()
{
    $jenis = $this->request->getGet('jenis');
    $id_anggota = $this->request->getGet('id_anggota');
    $db = \Config\Database::connect();

    $result = [];

    try {
        // Jika "all", ambil semua jenis
        if ($jenis === 'all' || empty($jenis)) {
            // **SIMPANAN POKOK - FILTER jumlah > 0**
            $builderPokok = $db->table('simpanan_pokok')
                ->select('simpanan_pokok.*, anggota.nama_lengkap, "pokok" as jenis')
                ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
                ->where('simpanan_pokok.jumlah >', 0);  // **FILTER PENTING**
            
            if ($id_anggota && $id_anggota !== 'all') {
                $builderPokok->where('simpanan_pokok.id_anggota', $id_anggota);
            }
            $pokok = $builderPokok->get()->getResultArray();

            // **SIMPANAN WAJIB - tanpa filter jumlah**
            $builderWajib = $db->table('simpanan_wajib')
                ->select('simpanan_wajib.*, anggota.nama_lengkap, "wajib" as jenis')
                ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota');
            
            if ($id_anggota && $id_anggota !== 'all') {
                $builderWajib->where('simpanan_wajib.id_anggota', $id_anggota);
            }
            $wajib = $builderWajib->get()->getResultArray();

            // **SIMPANAN SUKARELA - tanpa filter jumlah**
            $builderSukarela = $db->table('simpanan_sukarela')
                ->select('simpanan_sukarela.*, anggota.nama_lengkap, "sukarela" as jenis')
                ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota');
            
            if ($id_anggota && $id_anggota !== 'all') {
                $builderSukarela->where('simpanan_sukarela.id_anggota', $id_anggota);
            }
            $sukarela = $builderSukarela->get()->getResultArray();

            $result = array_merge($pokok, $wajib, $sukarela);
            
        } else {
            // Jika filter jenis tertentu
            if ($jenis === 'pokok') {
                $builder = $db->table('simpanan_pokok')
                    ->select('simpanan_pokok.*, anggota.nama_lengkap, "pokok" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
                    ->where('simpanan_pokok.jumlah >', 0);  // **FILTER PENTING**
                    
            } elseif ($jenis === 'wajib') {
                $builder = $db->table('simpanan_wajib')
                    ->select('simpanan_wajib.*, anggota.nama_lengkap, "wajib" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota');
                    // Tidak perlu filter jumlah untuk wajib
                    
            } elseif ($jenis === 'sukarela') {
                $builder = $db->table('simpanan_sukarela')
                    ->select('simpanan_sukarela.*, anggota.nama_lengkap, "sukarela" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota');
                    // Tidak perlu filter jumlah untuk sukarela
                    
            } else {
                return $this->response->setJSON([]);
            }

            if ($id_anggota && $id_anggota !== 'all') {
                $builder->where('id_anggota', $id_anggota);
            }
            
            $result = $builder->orderBy('tanggal', 'DESC')->get()->getResultArray();
        }

        // Format tanggal
        foreach ($result as &$row) {
            if (isset($row['tanggal'])) {
                $row['tanggal'] = date('d M Y', strtotime($row['tanggal']));
            }
            
            // **OPTIONAL: Filter tambahan di PHP untuk memastikan**
            if ($jenis === 'pokok' || $jenis === 'all') {
                // Pastikan tidak ada data pokok dengan jumlah 0 yang lolos
                if ($row['jenis'] === 'pokok' && $row['jumlah'] <= 0) {
                    continue; // Skip data ini
                }
            }
        }

        // **Hapus entry yang di-skip**
        $result = array_values(array_filter($result));

        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        log_message('error', 'Error getSimpananList: ' . $e->getMessage());
        return $this->response->setJSON([]);
    }
}
public function checkSimpananPokok($id_anggota = null)
{
    if (!$id_anggota) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID Anggota tidak valid'
        ]);
    }
    
    $model = new \App\Models\SimpananPokokModel();
    
    $total = $model->getTotalSimpananPokok($id_anggota);
    $isLunas = $model->isLunas($id_anggota);
    $sisa = $model->getSisaSimpananPokok($id_anggota);
    
    return $this->response->setJSON([
        'success' => true,
        'total' => $total,
        'isLunas' => $isLunas,
        'sisa' => $sisa,
        'max_limit' => 500000
    ]);
}

    // =========================
    // MENU LAINNYA
    // =========================

    public function financing()
{
    $db = \Config\Database::connect();

    // Total aktif dan jumlah dari ketiga tabel
    $aktifQard = $db->table('qard')->select('COUNT(*) as total, SUM(jml_pinjam) as jumlah')->where('status', 'aktif')->get()->getRowArray();
    $aktifMurabahah = $db->table('murabahah')->select('COUNT(*) as total, SUM(jml_pinjam) as jumlah')->where('status', 'aktif')->get()->getRowArray();
    $aktifMudharabah = $db->table('mudharabah')->select('COUNT(*) as total, SUM(jml_pinjam) as jumlah')->where('status', 'aktif')->get()->getRowArray();

    $total_aktif = ($aktifQard['total'] ?? 0) + ($aktifMurabahah['total'] ?? 0) + ($aktifMudharabah['total'] ?? 0);
    $total_jumlah = ($aktifQard['jumlah'] ?? 0) + ($aktifMurabahah['jumlah'] ?? 0) + ($aktifMudharabah['jumlah'] ?? 0);

    // Total menunggu dari ketiga tabel
    $menungguQard = $db->table('qard')->select('COUNT(*) as total')->where('status', 'pending')->get()->getRowArray();
    $menungguMurabahah = $db->table('murabahah')->select('COUNT(*) as total')->where('status', 'pending')->get()->getRowArray(); // PERBAIKI: 'peding' -> 'pending'
    $menungguMudharabah = $db->table('mudharabah')->select('COUNT(*) as total')->where('status', 'pending')->get()->getRowArray();

    $total_menunggu = ($menungguQard['total'] ?? 0) + ($menungguMurabahah['total'] ?? 0) + ($menungguMudharabah['total'] ?? 0);

    // Total jatuh tempo (<= +3 hari) dari ketiga tabel dengan status aktif
    $tanggalLimit = date('Y-m-d', strtotime('+3 days'));

    $jatuhQard = $db->table('qard')
        ->select('COUNT(*) as total')
        ->where('tanggal <=', $tanggalLimit) // PERBAIKAN: gunakan tanggal bukan jml_angsuran
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $jatuhMurabahah = $db->table('murabahah')
        ->select('COUNT(*) as total')
        ->where('tanggal <=', $tanggalLimit) // PERBAIKAN: gunakan tanggal bukan jml_angsuran
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $jatuhMudharabah = $db->table('mudharabah')
        ->select('COUNT(*) as total')
        ->where('tanggal <=', $tanggalLimit) // PERBAIKAN: gunakan tanggal bukan jml_angsuran
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $total_jatuh_tempo = ($jatuhQard['total'] ?? 0) + ($jatuhMurabahah['total'] ?? 0) + ($jatuhMudharabah['total'] ?? 0);

     // Ambil data pembiayaan gabungan dari 3 tabel dengan field yang benar
    $qard = $db->table('qard')
        ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
        ->select('qard.id_qard AS id, anggota.nama_lengkap, "qard" as akad, qard.jml_pinjam, qard.tanggal, qard.jml_angsuran as tenor, qard.status') // GUNAKAN: jml_angsuran sebagai tenor
        ->get()->getResultArray();

    $murabahah = $db->table('murabahah')
        ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
        ->select('murabahah.id_mr AS id, anggota.nama_lengkap, "murabahah" as akad, murabahah.jml_pinjam, murabahah.tanggal, murabahah.jml_angsuran as tenor, murabahah.status') // GUNAKAN: jml_angsuran sebagai tenor
        ->get()->getResultArray();

    $mudharabah = $db->table('mudharabah')
        ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
        ->select('mudharabah.id_md AS id, anggota.nama_lengkap, "mudharabah" as akad, mudharabah.jml_pinjam, mudharabah.tanggal, mudharabah.jml_angsuran as tenor, mudharabah.status') // GUNAKAN: jml_angsuran sebagai tenor
        ->get()->getResultArray();

    // Gabungkan data
    $pembiayaan = array_merge($qard, $murabahah, $mudharabah);

    $data = [
        'total_aktif' => $total_aktif,
        'total_jumlah' => $total_jumlah,
        'total_menunggu' => $total_menunggu,
        'total_jatuh_tempo' => $total_jatuh_tempo,
        'pembiayaan' => $pembiayaan,
    ];

    return view('layouts/header', ['title' => 'Manajemen Pembiayaan'])
         . view('dashboard_admin/financing', $data)
         . view('layouts/footer');
}
// Simpan pengajuan pembiayaan baru
public function savePembiayaan()
{
    try {
        $request = $this->request;
        
        $id_anggota = $request->getPost('id_anggota');
        $akad = $request->getPost('akad');
        $jml_pinjam = $request->getPost('jml_pinjam');
        $tenor = $request->getPost('tenor'); // ini dari form input
        $keperluan = $request->getPost('keperluan');
        $tanggal = $request->getPost('tanggal');
        
        // DEBUG DETAIL
        log_message('debug', '=== SAVE PEMBIAYAAN DEBUG ===');
        log_message('debug', 'id_anggota: ' . $id_anggota);
        log_message('debug', 'akad: ' . $akad);
        log_message('debug', 'jml_pinjam: ' . $jml_pinjam);
        log_message('debug', 'tenor dari form: ' . $tenor);
        log_message('debug', 'tanggal dari form: ' . $tanggal);

        // Validasi data
        if (empty($id_anggota) || empty($akad) || empty($jml_pinjam) || empty($tenor) || empty($tanggal)) {
            log_message('error', 'Data tidak lengkap');
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap. Pastikan semua field diisi.'
            ]);
        }

        // CEK: Apakah anggota exists di database
        $db = \Config\Database::connect();
        
        $anggota = $db->table('anggota')
            ->where('id_anggota', $id_anggota)
            ->orWhere('id', $id_anggota)
            ->get()
            ->getRow();
        
        if (!$anggota) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ]);
        }

        // PERBAIKAN: Gunakan field sesuai struktur database yang sebenarnya
        $data = [
            'id_anggota' => $anggota->id_anggota,
            'tanggal' => $tanggal,
            'jml_pinjam' => str_replace('.', '', $jml_pinjam),
            'jml_angsuran' => $tenor, // SIMPAN TENOR KE jml_angsuran
            'keperluan' => $keperluan,
            'status' => 'aktif',
            'jml_terbayar' => 0,
            'sisa_tenor' => $tenor
        ];

        log_message('debug', 'Data yang akan disimpan: ' . print_r($data, true));

        // Simpan ke tabel sesuai akad
        if ($akad === 'qard') {
            $model = new \App\Models\QardModel();
        } elseif ($akad === 'murabahah') {
            $model = new \App\Models\MurabahahModel();
        } elseif ($akad === 'mudharabah') {
            $model = new \App\Models\MudharabahModel();
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Jenis akad tidak valid'
            ]);
        }

        if ($model->insert($data)) {
            $insertID = $model->getInsertID();
            log_message('debug', 'Data berhasil disimpan dengan ID: ' . $insertID);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembiayaan berhasil diajukan dan langsung aktif'
            ]);
        } else {
            $errors = $model->errors();
            log_message('error', 'Gagal insert: ' . print_r($errors, true));
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan pembiayaan: ' . implode(', ', $errors)
            ]);
        }

    } catch (\Exception $e) {
        log_message('error', 'Exception savePembiayaan: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}



    public function transactions()
{
    // Ambil data dari database
    $db = \Config\Database::connect();
    
    $bulan_ini = date('Y-m');
    
    // Total pemasukan bulan ini
    $total_pemasukan = $db->table('transaksi_umum')
        ->where('jenis', 'pemasukan')
        ->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan_ini)
        ->selectSum('jumlah')
        ->get()
        ->getRowArray();

    // Total pengeluaran bulan ini
    $total_pengeluaran = $db->table('transaksi_umum')
        ->where('jenis', 'pengeluaran')
        ->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan_ini)
        ->selectSum('jumlah')
        ->get()
        ->getRowArray();

    // Riwayat transaksi
    $riwayat = $db->table('transaksi_umum')
        ->orderBy('tanggal', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->get()
        ->getResultArray();

    $data = [
        'title' => 'Transaksi Umum',
        'total_pemasukan' => $total_pemasukan['jumlah'] ?? 0,
        'total_pengeluaran' => $total_pengeluaran['jumlah'] ?? 0,
        'riwayat' => $riwayat
    ];

    return view('layouts/header', $data)
         . view('dashboard_admin/transactions')
         . view('layouts/footer');
}

// Function untuk handle save transaksi (AJAX)
public function saveTransaksi()
{
    try {
        $request = $this->request;
        
        $data = [
            'deskripsi' => $request->getPost('deskripsi'),
            'kategori' => $request->getPost('kategori'),
            'jumlah' => str_replace('.', '', $request->getPost('jumlah')),
            'jenis' => $request->getPost('jenis'),
            'tanggal' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        if ($db->table('transaksi_umum')->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi'
            ]);
        }

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

    public function reports()
    {
        return view('layouts/header', ['title' => 'Laporan & Analisis'])
             . view('dashboard_admin/reports')
             . view('layouts/footer');
    }

   public function settings()
{
    $userModel = new UserModel();
    $admins = $userModel->where('role', 'admin')->findAll();
    
    $data = [
        'title' => 'Pengaturan',
        'admins' => $admins
    ];

    return view('layouts/header', $data)
         . view('dashboard_admin/settings')
         . view('layouts/footer');
}

   public function getAdmins()
{
    try {
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $admins
        ]);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error database: ' . $e->getMessage()
        ]);
    }
}

public function getAdmin($id)
{
    try {
        $userModel = new UserModel();
        $admin = $userModel->find($id);
        
        if (!$admin) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Admin tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $admin
        ]);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

public function saveAdmin()
{
    // HAPUS validasi AJAX - biarkan semua request bisa akses
    $userModel = new UserModel();
    
    $validation = \Config\Services::validation();
    $validation->setRules([
        'nama_lengkap' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'username' => 'required|min_length[3]',
        'nomor_ktp' => 'required|min_length[16]',
        'nomor_hp' => 'required',
        'role' => 'required',
        'status' => 'required'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validation->getErrors()
        ]);
    }

    $data = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'email' => $this->request->getPost('email'),
        'username' => $this->request->getPost('username'),
        'nomor_ktp' => $this->request->getPost('nomor_ktp'),
        'nomor_hp' => $this->request->getPost('nomor_hp'),
        'nomor_hp_keluarga' => $this->request->getPost('nomor_hp_keluarga'),
        'role' => $this->request->getPost('role'),
        'status' => $this->request->getPost('status')
    ];

    // Jika password diisi, hash password
    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $id = $this->request->getPost('id');
    
    try {
        if ($id) {
            // Update existing admin
            $userModel->update($id, $data);
            $message = 'Admin berhasil diperbarui';
        } else {
            // Tambah admin baru
            if (empty($password)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Password wajib diisi untuk admin baru'
                ]);
            }
            $userModel->insert($data);
            $message = 'Admin berhasil ditambahkan';
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

public function deleteAdmin($id)
{
    // HAPUS validasi AJAX - biarkan semua request bisa akses
    $userModel = new UserModel();
    
    try {
        $userModel->delete($id);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Admin berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
public function getAkadSettings()
{
    try {
        // Data dummy akad - nanti bisa diganti dengan model database
        $akadSettings = [
            [
                'id' => 1,
                'name' => 'Murabahah',
                'detail' => 'Margin: 10%',
                'status' => 'active',
                'color' => 'emerald',
                'margin_rate' => 10,
                'description' => 'Jual beli dengan harga pokok plus margin keuntungan'
            ],
            [
                'id' => 2,
                'name' => 'Mudharabah',
                'detail' => 'Bagi Hasil: 60:40',
                'status' => 'active',
                'color' => 'blue',
                'profit_sharing' => '60:40',
                'description' => 'Kerjasama bagi hasil antara pemilik modal dan pengelola'
            ],
            [
                'id' => 3,
                'name' => 'Ijarah',
                'detail' => 'Sewa: 8%',
                'status' => 'active',
                'color' => 'purple',
                'rent_rate' => 8,
                'description' => 'Sewa menyewa asset dengan imbalan sewa'
            ]
        ];
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $akadSettings
        ]);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

public function saveAkad()
{
    try {
        $data = $this->request->getPost();
        
        // Simulasi penyimpanan data
        // Di sini nanti bisa disimpan ke database
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pengaturan akad berhasil diperbarui'
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
    public function extras()
{
    return view('layouts/header', ['title' => 'Fitur Tambahan'])
         . view('dashboard_admin/extras')
         . view('layouts/footer');
}

// Tambahkan method-method berikut di Controller yang sama
public function search()
{
    $keyword = $this->request->getGet('q');
    
    if (empty($keyword)) {
        return $this->response->setJSON([
            'members' => [],
            'transactions' => []
        ]);
    }
    
    $db = \Config\Database::connect();
    $results = [
        'members' => [],
        'transactions' => []
    ];
    
    // Pencarian di tabel anggota (dari screenshot: ada typo 'angosta' seharusnya 'anggota')
    try {
        // Coba cari di tabel 'anggota' dulu
        $tableName = 'anggota';
        if (!$db->tableExists($tableName)) {
            // Fallback ke 'angosta' jika 'anggota' tidak ada
            $tableName = 'angosta';
        }
        
        $builder = $db->table($tableName);
        $builder->select('*');
        $builder->groupStart();
        $builder->like('nama_lengkap', $keyword);
        $builder->orLike('email', $keyword);
        $builder->orLike('nomor_angosta', $keyword);
        $builder->orLike('no_ktp', $keyword);
        $builder->orLike('no_rek', $keyword);
        $builder->orLike('atasnama_rekening', $keyword);
        $builder->orLike('alamat', $keyword);
        $builder->groupEnd();
        $builder->limit(5);
        
        $results['members'] = $builder->get()->getResultArray();
    } catch (\Exception $e) {
        log_message('error', 'Search anggota error: ' . $e->getMessage());
        $results['members'] = [];
    }
    
    // Pencarian di tabel transaksi (qard, murabahah, mudharabah)
    try {
        $transactionResults = [];
        
        // Cari di tabel qard
        if ($db->tableExists('qard')) {
            $builderQard = $db->table('qard');
            $builderQard->select("*, 'QARD' as jenis_transaksi, jml_pinjam as jumlah");
            $builderQard->groupStart();
            $builderQard->like('id_qard', $keyword);
            $builderQard->orLike('jml_pinjam', $keyword);
            $builderQard->orLike('status', $keyword);
            $builderQard->groupEnd();
            $builderQard->limit(3);
            $qardResults = $builderQard->get()->getResultArray();
            $transactionResults = array_merge($transactionResults, $qardResults);
        }
        
        // Cari di tabel murabahah
        if ($db->tableExists('murabahah')) {
            $builderMurabahah = $db->table('murabahah');
            $builderMurabahah->select("*, 'MURABAHAH' as jenis_transaksi, jml_pinjam as jumlah");
            $builderMurabahah->groupStart();
            $builderMurabahah->like('id_murabahah', $keyword);
            $builderMurabahah->orLike('jml_pinjam', $keyword);
            $builderMurabahah->orLike('status', $keyword);
            $builderMurabahah->groupEnd();
            $builderMurabahah->limit(3);
            $murabahahResults = $builderMurabahah->get()->getResultArray();
            $transactionResults = array_merge($transactionResults, $murabahahResults);
        }
        
        // Cari di tabel mudharabah
        if ($db->tableExists('mudharabah')) {
            $builderMudharabah = $db->table('mudharabah');
            $builderMudharabah->select("*, 'MUDHARABAH' as jenis_transaksi, jml_pinjam as jumlah");
            $builderMudharabah->groupStart();
            $builderMudharabah->like('id_mudharabah', $keyword);
            $builderMudharabah->orLike('jml_pinjam', $keyword);
            $builderMudharabah->orLike('status', $keyword);
            $builderMudharabah->groupEnd();
            $builderMudharabah->limit(3);
            $mudharabahResults = $builderMudharabah->get()->getResultArray();
            $transactionResults = array_merge($transactionResults, $mudharabahResults);
        }
        
        $results['transactions'] = $transactionResults;
        
    } catch (\Exception $e) {
        log_message('error', 'Search transactions error: ' . $e->getMessage());
        $results['transactions'] = [];
    }
    
    return $this->response->setJSON($results);
}

public function importData()
{
    $file = $this->request->getFile('file');
    
    if ($file->isValid() && !$file->hasMoved()) {
        $extension = $file->getClientExtension();
        
        if (in_array($extension, ['csv', 'xlsx'])) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            
            // Process import
            $imported = $this->processImport(WRITEPATH . 'uploads/' . $newName, $extension);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil diimport: ' . $imported . ' records'
            ]);
        }
    }
    
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Gagal mengimport data'
    ]);
}

public function backupDatabase()
{
    // Backup database
    $db = \Config\Database::connect();
    $backup = \Config\Services::backup();
    
    $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
    $backup->setFilename($filename);
    
    try {
        $backup->backup();
        
        return $this->response->download(WRITEPATH . 'backups/' . $filename, null);
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Backup gagal: ' . $e->getMessage()
        ]);
    }
}

public function auditLog()
{
    $logModel = new \App\Models\AuditLogModel(); // Buat model ini
    $logs = $logModel->orderBy('created_at', 'DESC')->findAll(50);
    
    return $this->response->setJSON($logs);
}

public function updateNotificationSettings()
{
    $whatsapp = $this->request->getPost('whatsapp');
    $email = $this->request->getPost('email');
    
    // Simpan setting ke database atau file config
    $settings = [
        'whatsapp' => (bool)$whatsapp,
        'email' => (bool)$email
    ];
    
    // Save to file atau database
    file_put_contents(WRITEPATH . 'config/notification.json', json_encode($settings));
    
    return $this->response->setJSON([
        'success' => true,
        'message' => 'Pengaturan notifikasi berhasil diupdate'
    ]);
}

public function pembayaranPending()
{
    $db = \Config\Database::connect();
    
    // ✅ GUNAKAN QUERY YANG LEBIH EXPLISIT
    $pembayaran_pending = $db->table('pembayaran_pending')
                            ->select('*') // Ambil semua kolom
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->getResult();

    // ✅ DEBUG: Log struktur data
    if (!empty($pembayaran_pending)) {
        $first_item = $pembayaran_pending[0];
        $properties = [];
        foreach ($first_item as $key => $value) {
            $properties[] = $key;
        }
        log_message('debug', 'Properties available: ' . implode(', ', $properties));
    }

    $data = [
        'title' => 'Pembayaran Pending - Admin',
        'pembayaran_pending' => $pembayaran_pending,
        'active_menu' => 'pembayaran-pending'
    ];

    return view('layouts/header', $data)
         . view('dashboard_admin/pembayaran_pending')
         . view('layouts/footer');
}
public function verifikasiPembayaran($id)
{
    try {
        // Debug log
        log_message('debug', '=== VERIFIKASI PEMBAYARAN START ===');
        log_message('debug', 'Verifikasi pembayaran dipanggil, ID: ' . $id);

        // Validasi ID
        if (empty($id) || $id == 0) {
            log_message('error', 'ID pembayaran tidak valid: ' . $id);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID pembayaran tidak valid'
            ]);
        }

        $db = \Config\Database::connect();

        // Ambil data pembayaran pending
        $pembayaran = $db->table('pembayaran_pending')
                        ->where('id', $id)
                        ->get()
                        ->getRow();

        if (!$pembayaran) {
            log_message('error', 'Data pembayaran tidak ditemukan. ID: ' . $id);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pembayaran tidak ditemukan. ID: ' . $id
            ]);
        }

        log_message('debug', 'Data pembayaran ditemukan: ' . json_encode($pembayaran));

        // ✅ UPDATE STATUS TANPA updated_at
        $updateResult = $db->table('pembayaran_pending')
           ->where('id', $id)
           ->update([
               'status' => 'diverifikasi'
               // ❌ HAPUS updated_at karena kolom tidak ada
           ]);

        log_message('debug', 'Update status pembayaran_pending result: ' . ($updateResult ? 'SUCCESS' : 'FAILED'));

        // Update jml_terbayar di tabel pinjaman sesuai jenis
        log_message('debug', 'Memanggil updatePinjamanTerbayar dengan params: jenis=' . $pembayaran->jenis_pinjaman . ', id_pinjaman=' . $pembayaran->id_pinjaman . ', jumlah=' . $pembayaran->jumlah_bayar);
        $this->updatePinjamanTerbayar(
            $pembayaran->jenis_pinjaman,
            $pembayaran->id_pinjaman,
            $pembayaran->jumlah_bayar
        );

        log_message('debug', 'Pembayaran berhasil diverifikasi: ' . $id);
        log_message('debug', '=== VERIFIKASI PEMBAYARAN END ===');

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pembayaran berhasil diverifikasi'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error verifikasiPembayaran: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}

public function tolakPembayaran($id)
{
    try {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'status' => 'error',
                'message' => 'Method tidak diizinkan'
            ]);
        }

        $alasan = $this->request->getPost('alasan');

        $db = \Config\Database::connect();
        
        // ✅ UPDATE STATUS TANPA updated_at
        $db->table('pembayaran_pending')
           ->where('id', $id)
           ->update([
               'status' => 'ditolak',
               'alasan_penolakan' => $alasan
               // ❌ HAPUS updated_at karena kolom tidak ada
           ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pembayaran berhasil ditolak'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error tolakPembayaran: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}

private function updatePinjamanTerbayar($jenis, $id_pinjaman, $jumlah_bayar)
{
    log_message('debug', '=== UPDATE PINJAMAN TERBAYAR START ===');
    log_message('debug', 'Jenis: ' . $jenis . ', ID Pinjaman: ' . $id_pinjaman . ', Jumlah Bayar: ' . $jumlah_bayar);

    $db = \Config\Database::connect();

    switch ($jenis) {
        case 'Qard':
            $table = 'qard';
            $id_field = 'id_qard';
            break;
        case 'Murabahah':
            $table = 'murabahah';
            $id_field = 'id_mr';
            break;
        case 'Mudharabah':
            $table = 'mudharabah';
            $id_field = 'id_md';
            break;
        default:
            log_message('error', 'Jenis pinjaman tidak valid: ' . $jenis);
            return;
    }

    log_message('debug', 'Table: ' . $table . ', ID Field: ' . $id_field);

    // Ambil data pinjaman saat ini
    $pinjaman = $db->table($table)
                   ->where($id_field, $id_pinjaman)
                   ->get()
                   ->getRow();

    if (!$pinjaman) {
        log_message('error', 'Data pinjaman tidak ditemukan. Table: ' . $table . ', ID: ' . $id_pinjaman);
        return;
    }

    log_message('debug', 'Data pinjaman ditemukan: ' . json_encode($pinjaman));

    $terbayar_lama = $pinjaman->jml_terbayar ?? 0;
    $terbayar_baru = $terbayar_lama + $jumlah_bayar;

    log_message('debug', 'Terbayar lama: ' . $terbayar_lama . ', Terbayar baru: ' . $terbayar_baru . ', Jml Pinjam: ' . $pinjaman->jml_pinjam);

    $updateData = [
        'jml_terbayar' => $terbayar_baru
        // ❌ HAPUS updated_at karena mungkin tidak ada
    ];

    // Cek jika sudah lunas
    if ($terbayar_baru >= $pinjaman->jml_pinjam) {
        $updateData['status'] = 'lunas';
        log_message('debug', 'Status diubah menjadi LUNAS');
    }

    log_message('debug', 'Update data: ' . json_encode($updateData));

    $updateResult = $db->table($table)
       ->where($id_field, $id_pinjaman)
       ->update($updateData);

    log_message('debug', 'Update result: ' . ($updateResult ? 'SUCCESS' : 'FAILED'));
    log_message('debug', '=== UPDATE PINJAMAN TERBAYAR END ===');
}
private function exportCSV($data)
{
    $filename = 'export-data-' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Header
    fputcsv($output, ['No', 'Nama', 'Email']); // Sesuaikan dengan kolom
    
    // Data
    $no = 1;
    foreach ($data as $item) {
        fputcsv($output, [
            $no++,
            $item['nama'],
            $item['email']
            // Tambahkan field lainnya
        ]);
    }
    
    fclose($output);
    exit;
}

private function processImport($filePath, $extension)
{
    $imported = 0;
    
    if ($extension === 'csv') {
        // Process CSV
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) { // Skip header
                    // Process data
                    $imported++;
                }
                $row++;
            }
            fclose($handle);
        }
    }
    
    // Hapus file temporary
    unlink($filePath);
    
    return $imported;
}
public function detailAnggota($id)
{
$anggota = $this->anggotaModel->find($id);
if (!$anggota) {
    return redirect()->to('/admin/members')->with('error', 'Data anggota tidak ditemukan');
}

// ===== MODEL =====
$simpananPokokModel = new \App\Models\SimpananPokokModel();
$simpananWajibModel = new \App\Models\SimpananWajibModel();
$simpananSukarelaModel = new \App\Models\SimpananSukarelaModel();

$qardModel = new \App\Models\QardModel();
$murabahahModel = new \App\Models\MurabahahModel();
$mudharabahModel = new \App\Models\MudharabahModel();

// ===== SIMPANAN =====
$totalPokok = $simpananPokokModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jumlah')
    ->first()['jumlah'] ?? 0;

$totalWajib = $simpananWajibModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jumlah')
    ->first()['jumlah'] ?? 0;

$totalSukarela = $simpananSukarelaModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jumlah')
    ->first()['jumlah'] ?? 0;

$totalSimpanan = $totalPokok + $totalWajib + $totalSukarela;

// ===== PEMBIAYAAN (hanya yang aktif) =====
$totalQard = $qardModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->first()['jml_pinjam'] ?? 0;

$totalMurabahah = $murabahahModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->first()['jml_pinjam'] ?? 0;

$totalMudharabah = $mudharabahModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->first()['jml_pinjam'] ?? 0;

$totalPembiayaan = $totalQard + $totalMurabahah + $totalMudharabah;

// ===== SISA ANGSURAN (dari semua pembiayaan aktif) =====
log_message('debug', '=== DETAIL ANGGOTA - HITUNG SISA ANGSURAN START ===');
log_message('debug', 'ID Anggota: ' . $id);

$sisaAngsuran = 0;

// Hitung sisa angsuran untuk Qard
$qardAktif = $qardModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->findAll();

log_message('debug', 'Qard aktif ditemukan: ' . count($qardAktif));

foreach ($qardAktif as $q) {
    if ($q['jml_angsuran'] > 0 && $q['jml_pinjam'] > 0) {
        $angsuran_per_bulan = $q['jml_pinjam'] / $q['jml_angsuran'];
        $tenor_dibayar = floor($q['jml_terbayar'] / $angsuran_per_bulan);
        $sisa_qard = max(0, $q['jml_angsuran'] - $tenor_dibayar);
        $sisaAngsuran += $sisa_qard;

        log_message('debug', 'Qard ID ' . $q['id_qard'] . ': jml_pinjam=' . $q['jml_pinjam'] . ', jml_angsuran=' . $q['jml_angsuran'] . ', jml_terbayar=' . $q['jml_terbayar'] . ', angsuran_per_bulan=' . $angsuran_per_bulan . ', tenor_dibayar=' . $tenor_dibayar . ', sisa=' . $sisa_qard);
    }
}

// Hitung sisa angsuran untuk Murabahah
$murabahahAktif = $murabahahModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->findAll();

log_message('debug', 'Murabahah aktif ditemukan: ' . count($murabahahAktif));

foreach ($murabahahAktif as $m) {
    if ($m['jml_angsuran'] > 0 && $m['jml_pinjam'] > 0) {
        $angsuran_per_bulan = $m['jml_pinjam'] / $m['jml_angsuran'];
        $tenor_dibayar = floor($m['jml_terbayar'] / $angsuran_per_bulan);
        $sisa_murabahah = max(0, $m['jml_angsuran'] - $tenor_dibayar);
        $sisaAngsuran += $sisa_murabahah;

        log_message('debug', 'Murabahah ID ' . $m['id_mr'] . ': jml_pinjam=' . $m['jml_pinjam'] . ', jml_angsuran=' . $m['jml_angsuran'] . ', jml_terbayar=' . $m['jml_terbayar'] . ', angsuran_per_bulan=' . $angsuran_per_bulan . ', tenor_dibayar=' . $tenor_dibayar . ', sisa=' . $sisa_murabahah);
    }
}

// Hitung sisa angsuran untuk Mudharabah
$mudharabahAktif = $mudharabahModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->findAll();

log_message('debug', 'Mudharabah aktif ditemukan: ' . count($mudharabahAktif));

foreach ($mudharabahAktif as $md) {
    if ($md['jml_angsuran'] > 0 && $md['jml_pinjam'] > 0) {
        $angsuran_per_bulan = $md['jml_pinjam'] / $md['jml_angsuran'];
        $tenor_dibayar = floor($md['jml_terbayar'] / $angsuran_per_bulan);
        $sisa_mudharabah = max(0, $md['jml_angsuran'] - $tenor_dibayar);
        $sisaAngsuran += $sisa_mudharabah;

        log_message('debug', 'Mudharabah ID ' . $md['id_md'] . ': jml_pinjam=' . $md['jml_pinjam'] . ', jml_angsuran=' . $md['jml_angsuran'] . ', jml_terbayar=' . $md['jml_terbayar'] . ', angsuran_per_bulan=' . $angsuran_per_bulan . ', tenor_dibayar=' . $tenor_dibayar . ', sisa=' . $sisa_mudharabah);
    }
}

// Jika tidak ada pembiayaan aktif, set sisa angsuran ke 0
if (empty($qardAktif) && empty($murabahahAktif) && empty($mudharabahAktif)) {
    $sisaAngsuran = 0;
    log_message('debug', 'Tidak ada pembiayaan aktif, sisa angsuran = 0');
}

log_message('debug', 'Total sisa angsuran: ' . $sisaAngsuran);
log_message('debug', '=== DETAIL ANGGOTA - HITUNG SISA ANGSURAN END ===');

// ===== BAGI HASIL (sementara nol, nanti bisa diambil dari tabel keuntungan) =====
$bagiHasil = 0;

// ===== DETAIL SIMPANAN =====
$simpanan_pokok = [
    'total' => $totalPokok,
    'tanggal_terakhir' => $simpananPokokModel
        ->where('id_anggota', $id)
        ->where('status', 'aktif')
        ->orderBy('tanggal', 'DESC')
        ->first()['tanggal'] ?? null
];

$simpanan_wajib = [
    'total' => $totalWajib,
    'setoran_bulanan' => 50000, // Default, bisa disesuaikan
    'tanggal_terakhir' => $simpananWajibModel
        ->where('id_anggota', $id)
        ->where('status', 'aktif')
        ->orderBy('tanggal', 'DESC')
        ->first()['tanggal'] ?? null
];

$simpanan_sukarela = [
    'total' => $totalSukarela
];

// ===== DETAIL PEMBIAYAAN =====
$data_pembiayaan = [];

// Qard
$qardData = $qardModel
    ->where('id_anggota', $id)
    ->findAll(); // Ambil semua status, bukan hanya aktif

foreach ($qardData as $q) {
    $angsuran_per_bulan = $q['jml_angsuran'] > 0 ? $q['jml_pinjam'] / $q['jml_angsuran'] : 0;
    $tenor_dibayar = $angsuran_per_bulan > 0 ? floor($q['jml_terbayar'] / $angsuran_per_bulan) : 0;
    $sisa_tenor = max(0, $q['jml_angsuran'] - $tenor_dibayar);

    $data_pembiayaan[] = [
        'jenis_pembiayaan' => 'Qard',
        'akad' => 'Qard',
        'nomor_pembiayaan' => 'QRD' . $q['id_qard'],
        'jumlah_pembiayaan' => $q['jml_pinjam'],
        'margin' => 0, // Qard biasanya tanpa margin
        'jangka_waktu' => $q['jml_angsuran'],
        'angsuran_per_bulan' => $angsuran_per_bulan,
        'sisa_tenor' => $sisa_tenor,
        'total_dibayar' => $q['jml_terbayar'],
        'tanggal_pembiayaan' => $q['tgl_pengajuan'] ?? $q['tanggal'] ?? date('Y-m-d'),
        'status' => $q['status']
    ];
}

// Murabahah
$murabahahData = $murabahahModel
    ->where('id_anggota', $id)
    ->findAll(); // Ambil semua status

foreach ($murabahahData as $m) {
    $angsuran_per_bulan = $m['jml_angsuran'] > 0 ? $m['jml_pinjam'] / $m['jml_angsuran'] : 0;
    $tenor_dibayar = $angsuran_per_bulan > 0 ? floor($m['jml_terbayar'] / $angsuran_per_bulan) : 0;
    $sisa_tenor = max(0, $m['jml_angsuran'] - $tenor_dibayar);

    $data_pembiayaan[] = [
        'jenis_pembiayaan' => 'Murabahah',
        'akad' => 'Murabahah',
        'nomor_pembiayaan' => 'MRB' . $m['id_mr'],
        'jumlah_pembiayaan' => $m['jml_pinjam'],
        'margin' => 10, // Default margin
        'jangka_waktu' => $m['jml_angsuran'],
        'angsuran_per_bulan' => $angsuran_per_bulan,
        'sisa_tenor' => $sisa_tenor,
        'total_dibayar' => $m['jml_terbayar'],
        'tanggal_pembiayaan' => $m['tgl_pengajuan'] ?? $m['tanggal'] ?? date('Y-m-d'),
        'status' => $m['status']
    ];
}

// Mudharabah
$mudharabahData = $mudharabahModel
    ->where('id_anggota', $id)
    ->findAll(); // Ambil semua status

foreach ($mudharabahData as $md) {
    $angsuran_per_bulan = $md['jml_angsuran'] > 0 ? $md['jml_pinjam'] / $md['jml_angsuran'] : 0;
    $tenor_dibayar = $angsuran_per_bulan > 0 ? floor($md['jml_terbayar'] / $angsuran_per_bulan) : 0;
    $sisa_tenor = max(0, $md['jml_angsuran'] - $tenor_dibayar);

    $data_pembiayaan[] = [
        'jenis_pembiayaan' => 'Mudharabah',
        'akad' => 'Mudharabah',
        'nomor_pembiayaan' => 'MDH' . $md['id_md'],
        'jumlah_pembiayaan' => $md['jml_pinjam'],
        'margin' => 0, // Mudharabah biasanya bagi hasil
        'jangka_waktu' => $md['jml_angsuran'],
        'angsuran_per_bulan' => $angsuran_per_bulan,
        'sisa_tenor' => $sisa_tenor,
        'total_dibayar' => $md['jml_terbayar'],
        'tanggal_pembiayaan' => $md['tgl_pengajuan'] ?? $md['tanggal'] ?? date('Y-m-d'),
        'status' => $md['status']
    ];
}

// ===== JADWAL ANGSURAN =====
$jadwal_angsuran = [];

// Gabungkan semua pembiayaan untuk jadwal angsuran
$all_pembiayaan = array_merge($qardData, $murabahahData, $mudharabahData);

foreach ($all_pembiayaan as $p) {
    $jenis = '';
    $nama_pembiayaan = '';
    $tanggal_pembiayaan = '';
    $sisa_tenor = 0;

    if (isset($p['id_qard'])) {
        $jenis = 'qard';
        $nama_pembiayaan = 'Qard - QRD' . $p['id_qard'];
        $tanggal_pembiayaan = $p['tgl_pengajuan'] ?? date('Y-m-d');
        $sisa_tenor = $p['jml_angsuran'] - ($p['jml_terbayar'] / ($p['jml_pinjam'] / $p['jml_angsuran']));
    } elseif (isset($p['id_mr'])) {
        $jenis = 'murabahah';
        $nama_pembiayaan = 'Murabahah - MRB' . $p['id_mr'];
        $tanggal_pembiayaan = $p['tgl_pengajuan'] ?? date('Y-m-d');
        $sisa_tenor = $p['jml_angsuran'] - ($p['jml_terbayar'] / ($p['jml_pinjam'] / $p['jml_angsuran']));
    } elseif (isset($p['id_md'])) {
        $jenis = 'mudharabah';
        $nama_pembiayaan = 'Mudharabah - MDH' . $p['id_md'];
        $tanggal_pembiayaan = $p['tgl_pengajuan'] ?? date('Y-m-d');
        $sisa_tenor = $p['jml_angsuran'] - ($p['jml_terbayar'] / ($p['jml_pinjam'] / $p['jml_angsuran']));
    }

    $jadwal_angsuran[] = [
        'nama_pembiayaan' => $nama_pembiayaan,
        'tanggal_pembiayaan' => $tanggal_pembiayaan,
        'sisa_tenor' => max(0, $sisa_tenor),
        'angsuran_per_bulan' => $p['jml_pinjam'] / $p['jml_angsuran']
    ];
}

// ===== RIWAYAT TRANSAKSI =====
$riwayat_transaksi = [];

// Transaksi Simpanan
$transaksiPokok = $simpananPokokModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Setoran Simpanan Pokok' as keterangan, tanggal, 'pemasukan' as type, 'berhasil' as status")
    ->findAll();

$transaksiWajib = $simpananWajibModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Setoran Simpanan Wajib' as keterangan, tanggal, 'pemasukan' as type, 'berhasil' as status")
    ->findAll();

$transaksiSukarela = $simpananSukarelaModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Setoran Simpanan Sukarela' as keterangan, tanggal, 'pemasukan' as type, 'berhasil' as status")
    ->findAll();

// Gabung semua transaksi
$riwayat_transaksi = array_merge($transaksiPokok, $transaksiWajib, $transaksiSukarela);

// Urutkan berdasarkan tanggal terbaru
usort($riwayat_transaksi, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

// ===== KIRIM DATA KE VIEW =====
$data = [
    'anggota' => $anggota,
    'totalSimpanan' => $totalSimpanan,
    'totalPembiayaan' => $totalPembiayaan,
    'sisaAngsuran' => $sisaAngsuran,
    'sisaQardTotal' => $sisaAngsuran,
    'bagiHasil' => $bagiHasil,
    'simpanan_pokok' => $simpanan_pokok,
    'simpanan_wajib' => $simpanan_wajib,
    'simpanan_sukarela' => $simpanan_sukarela,
    'data_pembiayaan' => $data_pembiayaan,
    'jadwal_angsuran' => $jadwal_angsuran,
    'riwayat_transaksi' => $riwayat_transaksi,
    'total_qard' => ['total' => $totalQard],
    'total_murabahah' => ['total' => $totalMurabahah],
    'total_mudharabah' => ['total' => $totalMudharabah],
    'sisa_tenor_qard' => 0,
    'sisa_tenor_murabahah' => 0,
    'sisa_tenor_mudharabah' => 0,
    'bagi_hasil' => $bagiHasil,
    'bagi_hasil_bulan_ini' => 0,
    'bagi_hasil_tahun_ini' => 0
];


// ===== TRANSAKSI SIMPANAN (gabungan semua jenis) =====
$transaksiPokok = $simpananPokokModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Simpanan Pokok' as jenis, tanggal, 'masuk' as tipe, 'berhasil' as status")
    ->findAll();

$transaksiWajib = $simpananWajibModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Simpanan Wajib' as jenis, tanggal, 'masuk' as tipe, 'berhasil' as status")
    ->findAll();

$transaksiSukarela = $simpananSukarelaModel
    ->where('id_anggota', $id)
    ->where('status', 'aktif')
    ->select("jumlah, 'Simpanan Sukarela' as jenis, tanggal, 'masuk' as tipe, 'berhasil' as status")
    ->findAll();

// Gabung semua transaksi simpanan
$transaksi = array_merge($transaksiPokok, $transaksiWajib, $transaksiSukarela);

// Urutkan berdasarkan tanggal terbaru
usort($transaksi, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

$data['transaksi'] = $transaksi;


    return view('layouts/header', ['title' => 'Detail Anggota'])
         . view('dashboard_admin/detail_anggota', $data)
         . view('layouts/footer');
}
 public function installments()
{
    // Load models langsung di method
    $qardModel = new QardModel();
    $murabahahModel = new MurabahahModel();
    $mudharabahModel = new MudharabahModel();

    $data = [
        'title' => 'Manajemen Angsuran',
        'active_menu' => 'installments',
        'qard' => $qardModel->getAngsuranWithAnggota(),
        'murabahah' => $murabahahModel->getAngsuranWithAnggota(),
        'mudharabah' => $mudharabahModel->getAngsuranWithAnggota()
    ];

    return view('dashboard_admin/installments', $data);
}

public function bayarAngsuran()
{
    // Ambil data dari POST request
    $jenis = $this->request->getPost('jenis');
    $id = $this->request->getPost('id');
    $jumlah_bayar = $this->request->getPost('jumlah_bayar');

    // Load models
    $qardModel = new QardModel();
    $murabahahModel = new MurabahahModel();
    $mudharabahModel = new MudharabahModel();

    switch ($jenis) {
        case 'qard':
            $result = $qardModel->bayarAngsuran($id, $jumlah_bayar);
            break;
        case 'murabahah':
            $result = $murabahahModel->bayarAngsuran($id, $jumlah_bayar);
            break;
        case 'mudharabah':
            $result = $mudharabahModel->bayarAngsuran($id, $jumlah_bayar);
            break;
        default:
            return redirect()->back()->with('error', 'Jenis pembiayaan tidak valid');
    }

    if ($result) {
        return redirect()->back()->with('success', 'Pembayaran angsuran berhasil');
    } else {
        return redirect()->back()->with('error', 'Gagal melakukan pembayaran angsuran');
    }
}

public function getDetailAngsuran()
{
// Load models
$qardModel = new QardModel();
$murabahahModel = new MurabahahModel();
$mudharabahModel = new MudharabahModel();

// Ambil data dari GET request
$jenis = $this->request->getGet('jenis');
$id = $this->request->getGet('id');

switch ($jenis) {
    case 'qard':
        $data = $qardModel->find($id);
        break;
    case 'murabahah':
        $data = $murabahahModel->find($id);
        break;
    case 'mudharabah':
        $data = $mudharabahModel->find($id);
        break;
    default:
        return $this->response->setJSON(['error' => 'Jenis tidak valid']);
}

if ($data) {
    $total_pinjaman = $data['jml_pinjam'] ?? 0;
    $terbayar = $data['jml_terbayar'] ?? 0;
    $sisa = $total_pinjaman - $terbayar;
    $tenor_dibayar = $data['tenor_dibayar'] ?? 0;
    $total_tenor = $data['jml_angsuran'] ?? 0;

    return $this->response->setJSON([
        'success' => true,
        'data' => $data,
        'sisa_pembayaran' => $sisa,
        'tenor_dibayar' => $tenor_dibayar,
        'jml_angsuran' => $total_tenor
    ]);
}

return $this->response->setJSON(['error' => 'Data tidak ditemukan']);
}

// =========================
// PENDING SUKARELA
// =========================

public function pendingSukarela()
{
    $db = \Config\Database::connect();

    $pending = $db->table('simpanan_sukarela')
        ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota')
        ->where('simpanan_sukarela.status', 'pending')
        ->select('simpanan_sukarela.*, anggota.nama_lengkap, anggota.nomor_anggota')
        ->orderBy('simpanan_sukarela.tanggal', 'DESC')
        ->get()->getResultArray();

    return view('layouts/header', ['title' => 'Pending Sukarela'])
        . view('dashboard_admin/pending_sukarela', ['pending' => $pending])
        . view('layouts/footer');
}

public function approveSukarela($id)
{
    $db = \Config\Database::connect();

    $updated = $db->table('simpanan_sukarela')
        ->where('id_ss', $id)
        ->update(['status' => 'aktif']);

    if ($updated) {
        return redirect()->back()->with('success', 'Setoran sukarela berhasil disetujui.');
    } else {
        return redirect()->back()->with('error', 'Gagal menyetujui setoran sukarela.');
    }
}

public function rejectSukarela($id)
{
    $db = \Config\Database::connect();

    $updated = $db->table('simpanan_sukarela')
        ->where('id_ss', $id)
        ->update(['status' => 'ditolak']);

    if ($updated) {
        return redirect()->back()->with('success', 'Setoran sukarela berhasil ditolak.');
    } else {
        return redirect()->back()->with('error', 'Gagal menolak setoran sukarela.');
    }
}

}
