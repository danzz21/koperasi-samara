<?php
namespace App\Controllers;
use App\Models\AnggotaModel;
use App\Models\UserModel;
use App\Models\TransaksiModel;

class AdminDashboard extends BaseController
{
    public function pendingLoans()
{
    $db = \Config\Database::connect();

    // Ambil data pending dari setiap jenis pinjaman
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


    // Gabungkan semua data
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
    protected $transaksiModel;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->anggotaModel = new AnggotaModel();
         $this->transaksiModel = new \App\Models\TransaksiModel();
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

    return view('layouts/header', ['title' => 'Dashboard Admin'])
        . view('dashboard_admin/index', [
            'totalAnggota'        => $totalAnggota,
            'totalSimpanan'       => $totalSimpanan,
            'totalPembiayaan'     => $totalPembiayaan,
            'totalMargin'         => $totalMargin,
            'pendingPinjamanCount'=> $pendingPinjamanCount,
            'pendingSimpananCount'=> $pendingSimpananCount,
            'pendingCount'        => $pendingCount,
        ])
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

    // =========================
    // FITUR SIMPANAN
    // =========================

   public function savings()
{
    $db = \Config\Database::connect();

    // Ambil semua data simpanan
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
    $totalPokok = array_sum(array_column($simpananPokok, 'jumlah'));
    $totalWajib = array_sum(array_column($simpananWajib, 'jumlah'));
    $totalSukarela = array_sum(array_column($simpananSukarela, 'jumlah'));

    // Hitung total anggota unik untuk simpanan pokok
    $anggotaPokok = count(array_unique(array_column($simpananPokok, 'id_anggota')));

    return view('layouts/header', ['title' => 'Manajemen Simpanan'])
        . view('dashboard_admin/savings', [
            'totalPokok' => $totalPokok,
            'totalWajib' => $totalWajib,
            'totalSukarela' => $totalSukarela,
            'anggotaPokok' => $anggotaPokok,
            'pokok' => $simpananPokok,// <<< INI WAJIB DITAMBAHKAN
            'wajib' => $simpananWajib,
            'sukarela' => $simpananSukarela
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
    $anggotaModel = new \App\Models\AnggotaModel();

    $result = [];

    // Jika "all", ambil semua jenis
    if ($jenis === 'all') {
        $models = [
            'pokok' => new \App\Models\SimpananPokokModel(),
            'wajib' => new \App\Models\SimpananWajibModel(),
            'sukarela' => new \App\Models\SimpananSukarelaModel(),
        ];
        foreach ($models as $j => $model) {
            $builder = $model->select('*, jumlah, tanggal, status');
            if ($id_anggota && $id_anggota !== 'all') {
                $builder->where('id_anggota', $id_anggota);
            }
            $data = $builder->orderBy('tanggal', 'DESC')->findAll();
            foreach ($data as &$row) {
                $anggota = $anggotaModel->find($row['id_anggota']);
                $row['anggota'] = $anggota ? $anggota['nama_lengkap'] : '-';
                $row['jenis'] = $j;
            }
            $result = array_merge($result, $data);
        }
        // Gabungkan semua data jadi satu array
        return $this->response->setJSON($result);
    }

    // Jika filter jenis tertentu
    if ($jenis === 'pokok') {
        $model = new \App\Models\SimpananPokokModel();
    } elseif ($jenis === 'wajib') {
        $model = new \App\Models\SimpananWajibModel();
    } elseif ($jenis === 'sukarela') {
        $model = new \App\Models\SimpananSukarelaModel();
    } else {
        return $this->response->setJSON([]);
    }

    $builder = $model->select('*, jumlah, tanggal, status');
    if ($id_anggota && $id_anggota !== 'all') {
        $builder->where('id_anggota', $id_anggota);
    }
    $data = $builder->orderBy('tanggal', 'DESC')->findAll();
    foreach ($data as &$row) {
        $anggota = $anggotaModel->find($row['id_anggota']);
        $row['anggota'] = $anggota ? $anggota['nama_lengkap'] : '-';
        $row['jenis'] = $jenis;
    }
    return $this->response->setJSON($data);
}


    // Autocomplete anggota untuk input simpanan
    public function searchAnggotaNama()
    {
        $q = $this->request->getGet('q');
        $anggotaModel = new \App\Models\AnggotaModel();
        $anggota = $anggotaModel
            ->like('nama_lengkap', $q)
            ->select('id_anggota as id, nama_lengkap as nama')
            ->findAll(10);
        return $this->response->setJSON($anggota);
    }

    public function simpanAnggota()
    {
        try {
            $id_anggota = 'ID-' . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            while ($this->anggotaModel->where('id_anggota', $id_anggota)->first()) {
                $id_anggota = 'ID-' . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            }
            $nama = $this->request->getPost('nama_lengkap');
            $email = $this->request->getPost('email');
            $username = $this->request->getPost('username');
            $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
            $no_ktp = $this->request->getPost('no_ktp');
            $no_hp = $this->request->getPost('no_telp');
            $foto_diri = $this->request->getFile('foto_diri');
            $foto_ktp = $this->request->getFile('foto_ktp');

            // Handle file upload (optional)
            $fotoDiriName = null;
            if ($foto_diri && $foto_diri->isValid()) {
                $fotoDiriName = $foto_diri->getRandomName();
                $foto_diri->move(WRITEPATH.'uploads', $fotoDiriName);
            }

            $fotoKtpName = null;
            if ($foto_ktp && $foto_ktp->isValid()) {
                $fotoKtpName = $foto_ktp->getRandomName();
                $foto_ktp->move(WRITEPATH.'uploads', $fotoKtpName);
            }

            // Data untuk tabel anggota
            $dataAnggota = [
                'id_anggota' => $id_anggota,
                'nomor_anggota' => $id_anggota, // Sementara samakan dengan id_anggota
                'nama_lengkap' => $nama,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'no_ktp' => $no_ktp,
                'no_hp' => $no_hp,
                'foto_diri' => $fotoDiriName,
                'foto_ktp' => $fotoKtpName,
                'tanggal_daftar' => date('Y-m-d'),
                'status' => 'aktif'
            ];

            $anggotaId = $this->anggotaModel->insert($dataAnggota, true);

            if ($anggotaId) {
                return redirect()->back()->with('success', 'Anggota berhasil ditambahkan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan anggota.');
            }

        } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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


    public function transactions()
{
    $db = \Config\Database::connect();

    $bulan = date('m');
    $tahun = date('Y');

    // Total pemasukan bulan ini
    $pemasukan = $db->table('transaksi')
        ->selectSum('jumlah')
        ->where('jenis', 'Pemasukan')
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->get()
        ->getRow()
        ->jumlah ?? 0;

    // Total pengeluaran bulan ini
    $pengeluaran = $db->table('transaksi')
        ->selectSum('jumlah')
        ->where('jenis', 'Pengeluaran')
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->get()
        ->getRow()
        ->jumlah ?? 0;

    // Ambil semua transaksi (urut terbaru)
    $transactions = $this->transaksiModel->orderBy('tanggal','DESC')->findAll();

    $data = [
        'title' => 'Transaksi Umum',
        'transactions' => $transactions,
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran
    ];

    return view('layouts/header', $data)
         . view('dashboard_admin/transactions', $data)
         . view('layouts/footer');
}

public function simpanTransaksi()
    {

        $data = [
            'deskripsi' => $this->request->getPost('deskripsi'),
            'kategori'  => $this->request->getPost('kategori'),
            'jumlah'    => $this->request->getPost('jumlah'),
            'jenis'     => $this->request->getPost('jenis'),
            'tanggal'   => date('Y-m-d H:i:s')
        ];

        $model = new TransaksiModel();

        if($model->insert($data)){
            return redirect()->back()->with('success', 'Transaksi berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi.');
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
        return view('layouts/header', ['title' => 'Pengaturan'])
             . view('dashboard_admin/settings')
             . view('layouts/footer');
    }

    public function extras()
    {
        return view('layouts/header', ['title' => 'Fitur Tambahan'])
             . view('dashboard_admin/extras')
             . view('layouts/footer');
    }

    public function detailAnggota($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if (!$anggota) {
            return redirect()->to('/admin/members')->with('error', 'Data anggota tidak ditemukan');
        }
        return view('layouts/header', ['title' => 'Detail Anggota'])
             . view('dashboard_admin/detail_anggota', ['anggota' => $anggota])
             . view('layouts/footer');
    }
}
