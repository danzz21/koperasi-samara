<?php
namespace App\Controllers;
use App\Models\AnggotaModel;
use App\Models\UserModel;

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
        $result = array_map(function($data) {
            return [
                'id' => $data['id_anggota'],
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
        $this->userModel->update($id, ['status' => 'verified']);
        $today = date('Y-m-d');

        $result = $this->anggotaModel->insert([
            'id_anggota'        => $id,
            'nomor_anggota'     => $nomor_anggota,
            'nama_lengkap'      => $user['nama_lengkap'] ?? null,
            'no_ktp'            => $user['nomor_ktp'] ?? null,
            'foto_diri'         => $user['foto'] ?? null,
            'email'             => $user['email'] ?? null,
            'status'            => 'aktif',
            'tanggal_daftar'    => $today,
            'jenis_kelamin'     => null,
            'pekerjaan'         => null,
            'alamat'            => null,
            'no_rek'            => null,
            'atasnama_rekening' => null
        ]);

        if (!$result) {
            $errors = $this->anggotaModel->errors();
            return redirect()->back()->with('error', 'Gagal insert anggota: ' . implode(', ', $errors));
        }

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

        return view('layouts/header', ['title' => 'Manajemen Anggota'])
             . view('dashboard_admin/members', ['anggota' => $anggota, 'search' => $search])
             . view('layouts/footer');
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
        // Ambil semua data simpanan untuk statistik
        $simpananPokok = $db->table('simpanan_pokok')
            ->select('simpanan_pokok.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
            ->get()->getResultArray();

        $simpananWajib = $db->table('simpanan_wajib')
            ->select('simpanan_wajib.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota')
            ->get()->getResultArray();
            
        $simpananSukarela = $db->table('simpanan_sukarela')
            ->select('simpanan_sukarela.*, anggota.nama_lengkap')
            ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota')
            ->get()->getResultArray();

        // Hitung total masing-masing
        $totalPokok = array_sum(array_column($simpananPokok, 'jumlah')) ?? 0;
        $totalWajib = array_sum(array_column($simpananWajib, 'jumlah')) ?? 0;
        $totalSukarela = array_sum(array_column($simpananSukarela, 'jumlah')) ?? 0;

        // Hitung total anggota unik untuk simpanan pokok
        $anggotaPokok = count(array_unique(array_column($simpananPokok, 'id_anggota'))) ?? 0;

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
            $data = [
                'id_anggota' => $id_anggota,
                'jumlah' => $jumlah,
                'tanggal' => $tanggal,
                'status' => $status
            ];
        } elseif ($jenis === 'wajib') {
            $model = new \App\Models\SimpananWajibModel();
            $data = [
                'id_anggota' => $id_anggota,
                'jumlah' => $jumlah,
                'tanggal' => $tanggal,
                'status' => $status
            ];
        } elseif ($jenis === 'sukarela') {
            $model = new \App\Models\SimpananSukarelaModel();
            $data = [
                'id_anggota' => $id_anggota,
                'jumlah' => $jumlah,
                'tanggal' => $tanggal,
                'status' => 'pending' // sukarela selalu pending
            ];
        } else {
            return $this->response->setJSON(['success' => false]);
        }

        // Jika "Semua Anggota"
        if ($id_anggota === 'all') {
            $anggotaModel = new \App\Models\AnggotaModel();
            $allAnggota = $anggotaModel->findAll();
            foreach ($allAnggota as $a) {
                $data['id_anggota'] = $a['id_anggota'];
                $model->insert($data);
            }
            return $this->response->setJSON(['success' => true]);
        }

        $result = $model->insert($data);
        return $this->response->setJSON(['success' => $result ? true : false]);
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
            // Simpanan Pokok
            $builderPokok = $db->table('simpanan_pokok')
                ->select('simpanan_pokok.*, anggota.nama_lengkap, "pokok" as jenis')
                ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota');
            
            if ($id_anggota && $id_anggota !== 'all') {
                $builderPokok->where('simpanan_pokok.id_anggota', $id_anggota);
            }
            $pokok = $builderPokok->get()->getResultArray();

            // Simpanan Wajib
            $builderWajib = $db->table('simpanan_wajib')
                ->select('simpanan_wajib.*, anggota.nama_lengkap, "wajib" as jenis')
                ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota');
            
            if ($id_anggota && $id_anggota !== 'all') {
                $builderWajib->where('simpanan_wajib.id_anggota', $id_anggota);
            }
            $wajib = $builderWajib->get()->getResultArray();

            // Simpanan Sukarela
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
                    ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota');
            } elseif ($jenis === 'wajib') {
                $builder = $db->table('simpanan_wajib')
                    ->select('simpanan_wajib.*, anggota.nama_lengkap, "wajib" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota');
            } elseif ($jenis === 'sukarela') {
                $builder = $db->table('simpanan_sukarela')
                    ->select('simpanan_sukarela.*, anggota.nama_lengkap, "sukarela" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota');
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
        }

        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        log_message('error', 'Error getSimpananList: ' . $e->getMessage());
        return $this->response->setJSON([]);
    }
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
    $menungguMurabahah = $db->table('murabahah')->select('COUNT(*) as total')->where('status', 'peding')->get()->getRowArray();
    $menungguMudharabah = $db->table('mudharabah')->select('COUNT(*) as total')->where('status', 'pending')->get()->getRowArray();

    $total_menunggu = ($menungguQard['total'] ?? 0) + ($menungguMurabahah['total'] ?? 0) + ($menungguMudharabah['total'] ?? 0);

    // Total jatuh tempo (<= +3 hari) dari ketiga tabel dengan status aktif
    $tanggalLimit = date('Y-m-d', strtotime('+3 days'));

    $jatuhQard = $db->table('qard')
        ->select('COUNT(*) as total')
        ->where('jml_angsuran <=', $tanggalLimit)
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $jatuhMurabahah = $db->table('murabahah')
        ->select('COUNT(*) as total')
        ->where('jml_angsuran <=', $tanggalLimit)
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $jatuhMudharabah = $db->table('mudharabah')
        ->select('COUNT(*) as total')
        ->where('jml_angsuran <=', $tanggalLimit)
        ->where('status', 'aktif')
        ->get()
        ->getRowArray();

    $total_jatuh_tempo = ($jatuhQard['total'] ?? 0) + ($jatuhMurabahah['total'] ?? 0) + ($jatuhMudharabah['total'] ?? 0);

    // Ambil data pembiayaan gabungan dari 3 tabel, misal UNION semua data (pastikan kolom yang diambil sama)
    $sql = "
    SELECT 'qard' as jenis, id_qard as id, jml_pinjam, status, jml_angsuran FROM qard
    UNION ALL
    SELECT 'murabahah' as jenis, id_mr as id, jml_pinjam, status, jml_angsuran FROM murabahah
    UNION ALL
    SELECT 'mudharabah' as jenis, id_md as id, jml_pinjam, status, jml_angsuran FROM mudharabah
    ORDER BY id DESC
";
$pembiayaan = $db->query($sql)->getResultArray();


    $data = [
        'total_aktif' => $total_aktif,
        'total_jumlah' => $total_jumlah,
        'total_menunggu' => $total_menunggu,
        'total_jatuh_tempo' => $total_jatuh_tempo,
        'pembiayaan' => $pembiayaan,
    ];

    $qard = $db->table('qard')
        ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
        ->select('qard.id_qard AS id, anggota.nama_lengkap, "Qard" as akad, qard.jml_pinjam AS jumlah, qard.status')
        ->get()->getResultArray();

    $murabahah = $db->table('murabahah')
        ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
        ->select('murabahah.id_mr AS id, anggota.nama_lengkap, "Murabahah" as akad, murabahah.jml_pinjam AS jumlah, murabahah.status')
        ->get()->getResultArray();

    $mudharabah = $db->table('mudharabah')
        ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
        ->select('mudharabah.id_md AS id, anggota.nama_lengkap, "Mudharabah" as akad, mudharabah.jml_pinjam AS jumlah, mudharabah.status')
        ->get()->getResultArray();

    // Format ID biar beda jenis
    foreach ($qard as &$q) {
        $q['id'] = 'QRD' . $q['id'];
    }

    foreach ($murabahah as &$m) {
        $m['id'] = 'MRB' . $m['id'];
    }

    foreach ($mudharabah as &$md) {
        $md['id'] = 'MDH' . $md['id'];
    }

    $pembiayaan = array_merge($qard, $murabahah, $mudharabah);

    $data['pembiayaan'] = $pembiayaan;
    

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
        
        // DEBUG DETAIL
        log_message('debug', '=== SAVE PEMBIAYAAN DEBUG ===');
        log_message('debug', 'id_anggota: ' . $id_anggota);
        log_message('debug', 'akad: ' . $akad);
        log_message('debug', 'jml_pinjam: ' . $jml_pinjam);
        log_message('debug', 'tenor dari form: ' . $tenor); // debug tenor dari form

        // Validasi data
        if (empty($id_anggota) || empty($akad) || empty($jml_pinjam) || empty($tenor)) {
            log_message('error', 'Data tidak lengkap - tenor: ' . $tenor);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap. Pastikan tenor diisi.'
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

        // PERBAIKAN: Gunakan jml_angsuran sesuai nama field di database
        $data = [
            'id_anggota' => $anggota->id_anggota,
            'jml_pinjam' => str_replace('.', '', $jml_pinjam),
            'jml_angsuran' => $tenor, // Simpan nilai tenor ke field jml_angsuran
            'keperluan' => $keperluan,
            'tgl_pengajuan' => date('Y-m-d H:i:s'),
            'status' => 'aktif',
            'tgl_disetujui' => date('Y-m-d H:i:s'),
            'disetujui_oleh' => session()->get('user_id')
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

public function exportData()
{
    $type = $this->request->getGet('type');
    
    // Load model
    $model = new \App\Models\MemberModel(); // Ganti dengan model yang sesuai
    
    $data = $model->findAll();
    
    if ($type === 'excel') {
        return $this->exportExcel($data);
    } else {
        return $this->exportCSV($data);
    }
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

// Helper methods
private function exportExcel($data)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama');
    // Tambahkan header lainnya...
    
    // Data
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $row-1);
        $sheet->setCellValue('B' . $row, $item['nama']);
        // Tambahkan data lainnya...
        $row++;
    }
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'export-data-' . date('Y-m-d') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    
    $writer->save('php://output');
    exit;
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
        log_message('debug', 'Verifikasi pembayaran dipanggil, ID: ' . $id);
        
        // Validasi ID
        if (empty($id) || $id == 0) {
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
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data pembayaran tidak ditemukan. ID: ' . $id
            ]);
        }

        // ✅ UPDATE STATUS TANPA updated_at
        $db->table('pembayaran_pending')
           ->where('id', $id)
           ->update([
               'status' => 'diverifikasi'
               // ❌ HAPUS updated_at karena kolom tidak ada
           ]);

        // Update jml_terbayar di tabel pinjaman sesuai jenis
        $this->updatePinjamanTerbayar(
            $pembayaran->jenis_pinjaman,
            $pembayaran->id_pinjaman,
            $pembayaran->jumlah_bayar
        );

        log_message('debug', 'Pembayaran berhasil diverifikasi: ' . $id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pembayaran berhasil diverifikasi'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error verifikasiPembayaran: ' . $e->getMessage());
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
            return;
    }

    // Ambil data pinjaman saat ini
    $pinjaman = $db->table($table)
                   ->where($id_field, $id_pinjaman)
                   ->get()
                   ->getRow();

    if ($pinjaman) {
        $terbayar_baru = ($pinjaman->jml_terbayar ?? 0) + $jumlah_bayar;
        
        $updateData = [
            'jml_terbayar' => $terbayar_baru
            // ❌ HAPUS updated_at karena mungkin tidak ada
        ];

        // Cek jika sudah lunas
        if ($terbayar_baru >= $pinjaman->jml_pinjam) {
            $updateData['status'] = 'lunas';
        }

        $db->table($table)
           ->where($id_field, $id_pinjaman)
           ->update($updateData);
    }
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

    // ===== SISA ANGSURAN (hanya dari mudharabah aktif) =====
    $row = $mudharabahModel
        ->selectSum('jml_angsuran')
        ->selectSum('jml_terbayar')
        ->where('id_anggota', $id)
        ->where('status', 'aktif')
        ->first();

    $sisaAngsuran = ($row['jml_angsuran'] ?? 0) - ($row['jml_terbayar'] ?? 0);

    // ===== BAGI HASIL (sementara nol, nanti bisa diambil dari tabel keuntungan) =====
    $bagiHasil = 0;

    // ===== KIRIM DATA KE VIEW =====
    $data = [
        'anggota' => $anggota,
        'totalSimpanan' => $totalSimpanan,
        'totalPembiayaan' => $totalPembiayaan,
        'sisaAngsuran' => $sisaAngsuran,
        'bagiHasil' => $bagiHasil,
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
}
