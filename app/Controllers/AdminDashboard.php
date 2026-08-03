<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\UserModel;
use App\Models\QardModel;
use App\Models\ShuModel;
use App\Models\MurabahahModel;
use App\Models\MudharabahModel;
use App\Models\DetailAngsuranModel;

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

    // Kunci nama tabel secara eksplisit pada select dan like agar tidak ambiguous
    $builder = $this->anggotaModel->select('anggota.id_anggota, anggota.nama_lengkap, anggota.no_ktp, anggota.status, anggota.tanggal_daftar');

    if (!empty($search)) {
        $builder->groupStart()
                ->like('anggota.nama_lengkap', $search)
                ->orLike('anggota.nomor_anggota', $search)
                ->orLike('anggota.no_ktp', $search)
                ->groupEnd();
    }

    $anggota = $builder->findAll(10);

    $result = array_map(function ($data) {
        return [
            'id_anggota'     => $data['id_anggota'],
            'nama_lengkap'   => $data['nama_lengkap'],
            'no_ktp'         => $data['no_ktp'] ?? '',
            'status'         => $data['status'] ?? 'Menunggu Verifikasi',
            'tanggal_daftar' => isset($data['tanggal_daftar']) ? date('d M Y', strtotime($data['tanggal_daftar'])) : '-',
            'urlDetail'      => base_url('admin/detail-anggota/' . $data['id_anggota'])
        ];
    }, $anggota);

    return $this->response->setJSON($result);
}

   public function index()
    {
        $db = \Config\Database::connect();

        // Ambil parameter filter (Default: 'tahun')
        $filter = $this->request->getGet('filter') ?? 'tahun';

        // Status sah untuk simpanan (Mencegah pending / ditolak ikut masuk)
        $validSimpananStatus = ['aktif', 'lunas', 'berhasil', 'disetujui'];

        // 1. TOTAL ANGGOTA AKTIF
        $totalAnggota = $db->table('anggota')
            ->where('status', 'aktif')
            ->countAllResults();

        // 2. TOTAL SIMPANAN ANGGOTA (Hanya status SAH)
        $totalSimpananPokok = $db->table('simpanan_pokok')
            ->whereIn('status', $validSimpananStatus)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $totalSimpananWajib = $db->table('simpanan_wajib')
            ->whereIn('status', $validSimpananStatus)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $totalSimpananSukarela = $db->table('simpanan_sukarela')
            ->whereIn('status', $validSimpananStatus)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $totalSimpanan = (float)$totalSimpananPokok + (float)$totalSimpananWajib + (float)$totalSimpananSukarela;

        // 3. PEMBIAYAAN & POKOK PINJAMAN BEREDAR
        $qardRow = $db->table('qard')
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam', 'pinjam')
            ->selectSum('jml_terbayar', 'terbayar')
            ->get()->getRow();

        $pokokQard = (float)($qardRow->pinjam ?? 0);
        $terbayarQard = (float)($qardRow->terbayar ?? 0);

        $mrbRow = $db->table('murabahah')
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam', 'pinjam')
            ->selectSum('jml_terbayar', 'terbayar')
            ->get()->getRow();

        $totalMrbWithMargin = (float)($mrbRow->pinjam ?? 0);
        $terbayarMrbWithMargin = (float)($mrbRow->terbayar ?? 0);

        $pokokMrbMurni = $totalMrbWithMargin / 1.10;
        $terbayarMrbMurni = $terbayarMrbWithMargin / 1.10;

        $mdhRow = $db->table('mudharabah')
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam', 'pinjam')
            ->selectSum('jml_terbayar', 'terbayar')
            ->get()->getRow();

        $totalMdhWithMargin = (float)($mdhRow->pinjam ?? 0);
        $terbayarMdhWithMargin = (float)($mdhRow->terbayar ?? 0);

        $pokokMdhMurni = $totalMdhWithMargin / 1.10;
        $terbayarMdhMurni = $terbayarMdhWithMargin / 1.10;

        $totalPokokMurniPinjam = $pokokQard + $pokokMrbMurni + $pokokMdhMurni;
        $totalTerbayarPokokMurni = $terbayarQard + $terbayarMrbMurni + $terbayarMdhMurni;

        $sisaPokokPinjaman = max(0, $totalPokokMurniPinjam - $totalTerbayarPokokMurni);

        // 4. FILTER PEMASUKAN & PENGELUARAN DINAMIS (Hari, Minggu, Bulan, Tahun)
        $builderMasuk = $db->table('transaksi_umum')->where('jenis', 'pemasukan');
        $builderKeluar = $db->table('transaksi_umum')->where('jenis', 'pengeluaran');

        $filterLabel = 'Tahun ' . date('Y');

        if ($filter === 'hari') {
            $builderMasuk->where('DATE(tanggal)', date('Y-m-d'));
            $builderKeluar->where('DATE(tanggal)', date('Y-m-d'));
            $filterLabel = 'Hari Ini (' . date('d M Y') . ')';
        } elseif ($filter === 'minggu') {
            $builderMasuk->where('YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)');
            $builderKeluar->where('YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)');
            $filterLabel = 'Minggu Ini';
        } elseif ($filter === 'bulan') {
            $builderMasuk->where('YEAR(tanggal)', date('Y'))->where('MONTH(tanggal)', date('m'));
            $builderKeluar->where('YEAR(tanggal)', date('Y'))->where('MONTH(tanggal)', date('m'));
            $filterLabel = 'Bulan ' . date('F Y');
        } else {
            // Default: 'tahun'
            $builderMasuk->where('YEAR(tanggal)', date('Y'));
            $builderKeluar->where('YEAR(tanggal)', date('Y'));
            $filterLabel = 'Tahun ' . date('Y');
        }

        $pemasukanFiltered = $builderMasuk->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $pengeluaranFiltered = $builderKeluar->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;

        // Total Kumulatif Transaksi Umum
        $pemasukanUmumTotal = $db->table('transaksi_umum')->where('jenis', 'pemasukan')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $pengeluaranUmumTotal = $db->table('transaksi_umum')->where('jenis', 'pengeluaran')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;

        // 5. PROFIT REALISASI & POTENSI MARGIN
        $terbayarMurabahah = $db->table('murabahah')->whereIn('status', ['aktif', 'lunas'])->selectSum('jml_terbayar', 'total')->get()->getRow()->total ?? 0;
        $terbayarMudharabah = $db->table('mudharabah')->whereIn('status', ['aktif', 'lunas'])->selectSum('jml_terbayar', 'total')->get()->getRow()->total ?? 0;

        $totalTerbayarBerMargin = (float)$terbayarMurabahah + (float)$terbayarMudharabah;
        $realisasiMargin = $totalTerbayarBerMargin - ($totalTerbayarBerMargin / 1.10);

        $pinjamMurabahah = $db->table('murabahah')->where('status', 'aktif')->selectSum('jml_pinjam')->get()->getRow()->jml_pinjam ?? 0;
        $pinjamMudharabah = $db->table('mudharabah')->where('status', 'aktif')->selectSum('jml_pinjam')->get()->getRow()->jml_pinjam ?? 0;
        $totalPinjamBerMargin = (float)$pinjamMurabahah + (float)$pinjamMudharabah;

        $potensiMargin = $totalPinjamBerMargin - ($totalPinjamBerMargin / 1.10);

        // 6. KAS REAL, ESTIMASI SHU, & TOTAL ASET
        $kasReal = max(0, ($totalSimpanan + $realisasiMargin + (float)$pemasukanUmumTotal) - ($sisaPokokPinjaman + (float)$pengeluaranUmumTotal));
        $shuTahunBerjalan = ($realisasiMargin + (float)$pemasukanFiltered) - (float)$pengeluaranFiltered;
        $totalAset = $kasReal + $sisaPokokPinjaman;

        // 7. ESTIMASI TAGIHAN BULAN INI
        $tablesPembiayaan = ['qard', 'murabahah', 'mudharabah'];
        $tagihanBulanIni = 0;

        foreach ($tablesPembiayaan as $t) {
            $aktifList = $db->table($t)->where('status', 'aktif')->get()->getResultArray();
            foreach ($aktifList as $item) {
                $tenor = (int)($item['jml_angsuran'] ?? 1);
                $pinjam = (float)($item['jml_pinjam'] ?? 0);
                if ($tenor > 0) {
                    $tagihanBulanIni += ($pinjam / $tenor);
                }
            }
        }

        // 8. NOTIFIKASI PENDING
        $pendingSimpananCount      = $db->table('simpanan_sukarela')->where('status', 'pending')->countAllResults();
        $pendingSimpananPokokCount = $db->table('simpanan_pokok')->where('status', 'pending')->countAllResults();
        $pendingCount              = $this->userModel->where('role', 'anggota')->where('status', 'pending')->countAllResults();
        $pendingQard               = $db->table('qard')->where('status', 'pending')->countAllResults();
        $pendingMurabahah          = $db->table('murabahah')->where('status', 'pending')->countAllResults();
        $pendingMudharabah         = $db->table('mudharabah')->where('status', 'pending')->countAllResults();
        $pendingPinjamanCount      = $pendingQard + $pendingMurabahah + $pendingMudharabah;
        $pendingPembayaranCount    = $db->table('pembayaran_pending')->where('status', 'pending')->countAllResults();

        // 9. DATA CHART BULANAN
        $simpanan = $db->query("
            SELECT bulan, SUM(total) AS total FROM (
                SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total FROM simpanan_pokok WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status IN ('aktif', 'lunas', 'berhasil', 'disetujui') GROUP BY bulan
                UNION ALL
                SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total FROM simpanan_sukarela WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status IN ('aktif', 'lunas', 'berhasil', 'disetujui') GROUP BY bulan
                UNION ALL
                SELECT MONTH(tanggal) AS bulan, SUM(jumlah) AS total FROM simpanan_wajib WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status IN ('aktif', 'lunas', 'berhasil', 'disetujui') GROUP BY bulan
            ) AS gabungan
            GROUP BY bulan ORDER BY bulan
        ")->getResultArray();

        $pembiayaan = $db->query("
            SELECT bulan, SUM(total) AS total FROM (
                SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total FROM mudharabah WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status = 'aktif' GROUP BY bulan
                UNION ALL
                SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total FROM murabahah WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status = 'aktif' GROUP BY bulan
                UNION ALL
                SELECT MONTH(tanggal) AS bulan, SUM(jml_pinjam) AS total FROM qard WHERE YEAR(tanggal) = YEAR(CURDATE()) AND status = 'aktif' GROUP BY bulan
            ) AS gabungan
            GROUP BY bulan ORDER BY bulan
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

        $data = [
            'kasReal'                   => $kasReal,
            'totalSimpanan'             => $totalSimpanan,
            'sisaPokokPinjaman'         => $sisaPokokPinjaman,
            'realisasiMargin'           => $realisasiMargin,
            'potensiMargin'             => $potensiMargin,
            'tagihanBulanIni'           => $tagihanBulanIni,
            'totalAset'                 => $totalAset,
            'totalAnggota'              => $totalAnggota,
            'pemasukanFiltered'         => $pemasukanFiltered,
            'pengeluaranFiltered'       => $pengeluaranFiltered,
            'filterActive'              => $filter,
            'filterLabel'               => $filterLabel,
            'shuTahunBerjalan'          => $shuTahunBerjalan,

            'pendingPinjamanCount'      => $pendingPinjamanCount,
            'pendingSimpananCount'      => $pendingSimpananCount,
            'pendingSimpananPokokCount' => $pendingSimpananPokokCount,
            'pendingPembayaranCount'    => $pendingPembayaranCount,
            'pendingCount'              => $pendingCount,

            'chartLabels'               => json_encode($labels),
            'chartSimpanan'             => json_encode($simpananData),
            'chartPembiayaan'           => json_encode($pembiayaanData),
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
            'id_user'             => $id,
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
        $builder = $this->anggotaModel->select('anggota.*, users.id AS id_user')
            ->join('users', 'users.id = anggota.id_anggota', 'left');

        if ($search) {
            $builder = $builder->like('nama_lengkap', $search)
                ->orLike('no_ktp', $search);
        }
        $anggota = $builder->orderBy('tanggal_daftar', 'ASC')
            ->orderBy('id_anggota', 'ASC')
            ->findAll();

        foreach ($anggota as &$data) {
            $data['id_user'] = $data['id_user'] ?? $data['id_anggota'];
        }

        return view('layouts/header', ['title' => 'Manajemen Anggota'])
            . view('dashboard_admin/members', ['anggota' => $anggota, 'search' => $search])
            . view('layouts/footer');
    }

    public function saveMember()
{
    try {
        $request = $this->request;

        // Ambil Data Input Form
        $nama_lengkap    = trim($request->getPost('nama_lengkap') ?? '');
        $email           = trim($request->getPost('email') ?? '');
        $username        = trim($request->getPost('username') ?? '');
        $password        = $request->getPost('password');
        $no_ktp          = trim($request->getPost('no_ktp') ?? '');
        $no_hp           = trim($request->getPost('no_hp') ?? $request->getPost('no_telp') ?? '');
        $alamat          = trim($request->getPost('alamat') ?? '');
        $jenis_kelamin   = $request->getPost('jenis_kelamin') ?? 'L';
        $pekerjaan       = trim($request->getPost('pekerjaan') ?? '-');
        $instansi        = trim($request->getPost('instansi') ?? '-');
        
        // Rekening Bank
        $jenis_bank        = trim($request->getPost('jenis_bank') ?? '');
        $no_rek            = trim($request->getPost('no_rek') ?? '');
        $atasnama_rekening = trim($request->getPost('atasnama_rekening') ?? '');

        // Simpanan Pokok Awal
        $setoranAwalPokok  = (float)($request->getPost('simpanan_pokok_awal') ?? 0);

        // Validasi Required
        if (empty($nama_lengkap) || empty($email) || empty($username) || empty($password) || empty($no_ktp)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Lengkapi seluruh field wajib bertanda bintang (*)'
            ]);
        }

        // Validasi Duplikat KTP
        $existing = $this->anggotaModel->where('no_ktp', $no_ktp)->first();
        if ($existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Anggota dengan No. KTP ini sudah terdaftar.'
            ]);
        }

        // Validasi Duplikat User
        $existingUser = $this->userModel->where('username', $username)->orWhere('email', $email)->first();
        if ($existingUser) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Username atau Email sudah terdaftar di sistem.'
            ]);
        }

        // --- UPLOAD FOTO ---
        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fotoDiriName   = null;
        $fotoKtpName    = null;
        $fotoSelfieName = null;

        $fotoDiri = $request->getFile('foto_diri');
        if ($fotoDiri && $fotoDiri->isValid() && !$fotoDiri->hasMoved()) {
            $fotoDiriName = $fotoDiri->getRandomName();
            $fotoDiri->move($uploadPath, $fotoDiriName);
        }

        $fotoKtp = $request->getFile('foto_ktp');
        if ($fotoKtp && $fotoKtp->isValid() && !$fotoKtp->hasMoved()) {
            $fotoKtpName = $fotoKtp->getRandomName();
            $fotoKtp->move($uploadPath, $fotoKtpName);
        }

        $fotoSelfie = $request->getFile('foto_diri_ktp');
        if ($fotoSelfie && $fotoSelfie->isValid() && !$fotoSelfie->hasMoved()) {
            $fotoSelfieName = $fotoSelfie->getRandomName();
            $fotoSelfie->move($uploadPath, $fotoSelfieName);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert ke Tabel Users (Disesuaikan dengan struktur tabel users)
        $userData = [
            'nama_lengkap' => $nama_lengkap,
            'email'        => $email,
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'anggota',
            'nomor_ktp'    => $no_ktp, // SESUAI DENGAN KOLOM DIBAMBAR USERS (nomor_ktp)
            'nomor_hp'     => $no_hp,
            'status'       => 'verified',
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();

        if (!$userId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat user baru. Silakan coba lagi.'
            ]);
        }

        // 2. Insert ke Tabel Anggota (Hanya kolom yang ada di foto tabel anggota saja!)
        $anggotaData = [
            'id'                => $userId, // Diisi $userId agar Primary Key id tidak NULL
            'id_anggota'        => $userId,
            'nomor_anggota'     => 'AGT-' . date('Ymd') . '-' . sprintf('%04d', $userId),
            'nama_lengkap'      => $nama_lengkap,
            'no_ktp'            => $no_ktp,
            'no_rek'            => $no_rek,
            'atasnama_rekening' => $atasnama_rekening,
            'foto_diri'         => $fotoDiriName,
            'alamat'            => $alamat,
            'email'             => $email,
            'tanggal_daftar'    => date('Y-m-d'),
            'status'            => 'aktif', // enum ('aktif', 'nonaktif')
            'jenis_kelamin'     => $jenis_kelamin,
            'pekerjaan'         => $pekerjaan,
            'instansi'          => $instansi,
            'foto_ktp'          => $fotoKtpName,
            'photo'             => $fotoDiriName,
            'jenis_bank'        => $jenis_bank,
            'foto_diri_ktp'     => $fotoSelfieName,
            'no_hp'             => $no_hp
        ];

        // Direct Insert ke tabel anggota
        $inserted = $db->table('anggota')->insert($anggotaData);

        if (!$inserted) {
            $error = $db->error();
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal DB: ' . $error['message']
            ]);
        }

        // 3. Simpanan Pokok (jika ada)
        if ($setoranAwalPokok > 0) {
            $db->table('simpanan_pokok')->insert([
                'id_anggota' => $userId,
                'jumlah'     => $setoranAwalPokok,
                'tanggal'    => date('Y-m-d H:i:s'),
                'status'     => 'aktif'
            ]);
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Anggota baru berhasil ditambahkan!'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error saveMember: ' . $e->getMessage());
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}

    public function getAnggotaDetail($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if (!$anggota) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
        return $this->response->setJSON(['status' => 'success', 'data' => $anggota]);
    }

    // Method untuk Update Data Anggota
    public function updateAnggota($id = null)
    {
        try {
            if (!$id) {
                $id = $this->request->getPost('id_anggota');
            }

            $anggota = $this->anggotaModel->find($id);
            if (!$anggota) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data anggota tidak ditemukan']);
            }

            $request = $this->request;

            $namaLengkap = trim($request->getPost('nama_lengkap') ?? '');
            if ($namaLengkap === '') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Nama lengkap wajib diisi']);
            }

            // Lokasi penyimpanan gambar
            $uploadPath = FCPATH . 'uploads/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Handle Re-upload Foto Diri
            $fotoDiriName = $anggota['foto_diri'];
            $fotoDiri = $request->getFile('foto_diri');
            if ($fotoDiri && $fotoDiri->isValid() && !$fotoDiri->hasMoved()) {
                $fotoDiriName = $fotoDiri->getRandomName();
                $fotoDiri->move($uploadPath, $fotoDiriName);
            }

            // Handle Re-upload Foto KTP
            $fotoKtpName = $anggota['foto_ktp'];
            $fotoKtp = $request->getFile('foto_ktp');
            if ($fotoKtp && $fotoKtp->isValid() && !$fotoKtp->hasMoved()) {
                $fotoKtpName = $fotoKtp->getRandomName();
                $fotoKtp->move($uploadPath, $fotoKtpName);
            }

            // Handle Re-upload Foto Diri + KTP (Selfie)
            $fotoSelfieName = $anggota['foto_diri_ktp'] ?? null;
            $fotoSelfie = $request->getFile('foto_diri_ktp');
            if ($fotoSelfie && $fotoSelfie->isValid() && !$fotoSelfie->hasMoved()) {
                $fotoSelfieName = $fotoSelfie->getRandomName();
                $fotoSelfie->move($uploadPath, $fotoSelfieName);
            }

            // Array Data Update
            $data = [
                'nama_lengkap'      => $namaLengkap,
                'no_ktp'            => trim($request->getPost('no_ktp') ?? $anggota['no_ktp']),
                'no_hp'             => trim($request->getPost('no_hp') ?? $anggota['no_hp']),
                'jenis_kelamin'     => $request->getPost('jenis_kelamin') ?? $anggota['jenis_kelamin'],
                'pekerjaan'         => trim($request->getPost('pekerjaan') ?? $anggota['pekerjaan']),
                'instansi'          => trim($request->getPost('instansi') ?? $anggota['instansi']),
                'alamat'            => trim($request->getPost('alamat') ?? $anggota['alamat']),
                'jenis_bank'        => trim($request->getPost('jenis_bank') ?? $anggota['jenis_bank']),
                'no_rek'            => trim($request->getPost('no_rek') ?? $anggota['no_rek']),
                'atasnama_rekening' => trim($request->getPost('atasnama_rekening') ?? $anggota['atasnama_rekening']),
                'status'            => trim($request->getPost('status') ?? $anggota['status']),
                'tanggal_daftar'    => $request->getPost('tanggal_daftar') ?? $anggota['tanggal_daftar'],
                'foto_diri'         => $fotoDiriName,
                'photo'             => $fotoDiriName,
                'foto_ktp'          => $fotoKtpName,
                'foto_diri_ktp'     => $fotoSelfieName,
            ];

            // 1. Update Tabel Anggota
            $this->anggotaModel->update($id, $data);

            // 2. Sinkronkan ke Tabel Users jika ada
            if (!empty($anggota['email'])) {
                $user = $this->userModel->where('email', $anggota['email'])->first();
                if ($user) {
                    $this->userModel->update($user['id'], [
                        'nama_lengkap' => $namaLengkap,
                        'no_ktp'       => $data['no_ktp'],
                        'nomor_hp'     => $data['no_hp'],
                    ]);
                }
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data anggota berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error updateAnggota: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
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

    /**
     * DELETE ANGGOTA (SOFT DELETE)
     */
    /**
     * DELETE ANGGOTA (SOFT DELETE)
     */
    public function deleteAnggota()
    {
        // Cek request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        // Validasi token CSRF - PERBAIKI INI
        $csrf_token = $this->request->getPost('csrf_token');
        $current_csrf_token = csrf_hash();

        // Debug CSRF
        log_message('debug', '=== CSRF VALIDATION ===');
        log_message('debug', 'Posted CSRF: ' . $csrf_token);
        log_message('debug', 'Current CSRF: ' . $current_csrf_token);
        log_message('debug', 'Match: ' . ($csrf_token === $current_csrf_token ? 'YES' : 'NO'));

        // Validasi CSRF dengan cara CI4 yang benar
        if (empty($csrf_token) || $csrf_token !== $current_csrf_token) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Token CSRF tidak valid atau expired'
            ]);
        }

        $memberId = $this->request->getPost('member_id');
        $hardDelete = $this->request->getPost('hard_delete') === 'true'; // Opsi hard delete

        log_message('debug', '=== DELETE ANGGOTA START ===');
        log_message('debug', 'Member ID: ' . $memberId);
        log_message('debug', 'Hard Delete: ' . ($hardDelete ? 'Yes' : 'No'));

        if (!$memberId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID anggota tidak ditemukan'
            ]);
        }

        try {
            // Cari data anggota
            $anggota = $this->anggotaModel->find($memberId);

            if (!$anggota) {
                log_message('debug', 'Anggota tidak ditemukan dengan ID: ' . $memberId);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data anggota tidak ditemukan'
                ]);
            }

            // Simpan nama anggota untuk pesan konfirmasi
            $namaAnggota = $anggota['nama_lengkap'];
            $idUser = $anggota['id_anggota']; // ID user yang terkait

            log_message('debug', 'Nama Anggota: ' . $namaAnggota);
            log_message('debug', 'ID User terkait: ' . $idUser);

            // Mulai transaction database
            $db = \Config\Database::connect();
            $db->transStart();

            if ($hardDelete) {
                // ===== HARD DELETE (hapus permanen) =====

                // 1. Hapus dari tabel pembayaran_pending (jika ada)
                if ($db->tableExists('pembayaran_pending')) {
                    $db->table('pembayaran_pending')
                        ->where('id_anggota', $memberId)
                        ->delete();
                    log_message('debug', 'Hapus dari pembayaran_pending: OK');
                }

                // 2. Hapus dari tabel simpanan
                $tablesSimpanan = ['simpanan_pokok', 'simpanan_wajib', 'simpanan_sukarela'];
                foreach ($tablesSimpanan as $table) {
                    if ($db->tableExists($table)) {
                        $db->table($table)
                            ->where('id_anggota', $memberId)
                            ->delete();
                        log_message('debug', 'Hapus dari ' . $table . ': OK');
                    }
                }

                // 3. Hapus dari tabel pembiayaan
                $tablesPembiayaan = ['qard', 'murabahah', 'mudharabah'];
                foreach ($tablesPembiayaan as $table) {
                    if ($db->tableExists($table)) {
                        $db->table($table)
                            ->where('id_anggota', $memberId)
                            ->delete();
                        log_message('debug', 'Hapus dari ' . $table . ': OK');
                    }
                }

                // 4. Hapus dari tabel anggota
                $anggotaDeleted = $this->anggotaModel->delete($memberId);
                log_message('debug', 'Hapus dari anggota: ' . ($anggotaDeleted ? 'OK' : 'FAILED'));

                // 5. Hapus dari tabel users jika ada
                if ($idUser) {
                    $userDeleted = $this->userModel->delete($idUser);
                    log_message('debug', 'Hapus dari users: ' . ($userDeleted ? 'OK' : 'FAILED'));
                }

                $message = 'Anggota <strong>' . $namaAnggota . '</strong> berhasil dihapus permanen beserta semua datanya.';
            } else {
                // ===== SOFT DELETE (hanya ubah status) =====

                // 1. Ubah status anggota menjadi 'dihapus'
                $anggotaUpdated = $this->anggotaModel->update($memberId, [
                    'status' => 'dihapus',
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                log_message('debug', 'Update status anggota: ' . ($anggotaUpdated ? 'OK' : 'FAILED'));

                // 2. Nonaktifkan user terkait
                if ($idUser) {
                    $userUpdated = $this->userModel->update($idUser, [
                        'status' => 'nonaktif',
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                    log_message('debug', 'Update status user: ' . ($userUpdated ? 'OK' : 'FAILED'));
                }

                // 3. Nonaktifkan simpanan dan pembiayaan yang aktif
                $tablesToDeactivate = ['simpanan_pokok', 'simpanan_wajib', 'simpanan_sukarela', 'qard', 'murabahah', 'mudharabah'];
                foreach ($tablesToDeactivate as $table) {
                    if ($db->tableExists($table)) {
                        $db->table($table)
                            ->where('id_anggota', $memberId)
                            ->where('status', 'aktif')
                            ->update(['status' => 'nonaktif']);
                        log_message('debug', 'Nonaktifkan ' . $table . ': OK');
                    }
                }

                $message = 'Anggota <strong>' . $namaAnggota . '</strong> berhasil dinonaktifkan (soft delete). Data dapat dipulihkan kembali.';
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                log_message('error', 'Transaction failed for member deletion');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus anggota. Terjadi kesalahan database.'
                ]);
            }

            log_message('debug', '=== DELETE ANGGOTA END - SUCCESS ===');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message,
                'reload' => true // Untuk reload halaman
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error deleteAnggota: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    
    /**
     * GET DETAIL ANGGOTA UNTUK KONFIRMASI HAPUS
     */
    public function getMemberDetails($id)
    {
        // Cek request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        try {
            // Cari data anggota
            $anggota = $this->anggotaModel->find($id);

            if (!$anggota) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Anggota tidak ditemukan'
                ]);
            }

            $db = \Config\Database::connect();

            // Hitung jumlah data terkait
            $dataSummary = [
                'simpanan_pokok' => $db->table('simpanan_pokok')
                    ->where('id_anggota', $id)
                    ->countAllResults(),
                'simpanan_wajib' => $db->table('simpanan_wajib')
                    ->where('id_anggota', $id)
                    ->countAllResults(),
                'simpanan_sukarela' => $db->table('simpanan_sukarela')
                    ->where('id_anggota', $id)
                    ->countAllResults(),
                'pembiayaan_aktif' => $db->table('qard')
                    ->where('id_anggota', $id)
                    ->where('status', 'aktif')
                    ->countAllResults() +
                    $db->table('murabahah')
                    ->where('id_anggota', $id)
                    ->where('status', 'aktif')
                    ->countAllResults() +
                    $db->table('mudharabah')
                    ->where('id_anggota', $id)
                    ->where('status', 'aktif')
                    ->countAllResults(),
                'pembayaran_pending' => $db->tableExists('pembayaran_pending') ?
                    $db->table('pembayaran_pending')
                    ->where('id_anggota', $id)
                    ->where('status', 'pending')
                    ->countAllResults() : 0
            ];

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'anggota' => [
                        'id' => $anggota['id_anggota'],
                        'nama' => $anggota['nama_lengkap'],
                        'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
                        'email' => $anggota['email'] ?? '-',
                        'status' => $anggota['status'] ?? 'tidak diketahui',
                        'tanggal_daftar' => isset($anggota['tanggal_daftar']) ?
                            date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-'
                    ],
                    'summary' => $dataSummary,
                    'total_data_terkait' => array_sum($dataSummary)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getMemberDetails: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    /**
     * RESTORE ANGGOTA (PULIHKAN DARI SOFT DELETE)
     */
    public function restoreAnggota()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $memberId = $this->request->getPost('member_id');

        if (!$memberId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID anggota tidak ditemukan'
            ]);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Pulihkan anggota
            $anggotaUpdated = $this->anggotaModel->update($memberId, [
                'status' => 'aktif',
                'deleted_at' => null
            ]);

            // 2. Pulihkan user terkait
            $anggota = $this->anggotaModel->find($memberId);
            if ($anggota && isset($anggota['id_anggota'])) {
                $this->userModel->update($anggota['id_anggota'], [
                    'status' => 'verified',
                    'deleted_at' => null
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memulihkan anggota'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Anggota berhasil dipulihkan',
                'reload' => true
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error restoreAnggota: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
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




    // =========================
    // FITUR SIMPANAN
    // =========================

    public function savings()
    {
        $db = \Config\Database::connect();
        $validStatus = ['aktif', 'lunas', 'berhasil', 'disetujui'];

        try {
            $simpananPokok = $db->table('simpanan_pokok')
                ->select('simpanan_pokok.*, anggota.nama_lengkap')
                ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
                ->where('simpanan_pokok.jumlah >', 0)
                ->whereIn('simpanan_pokok.status', $validStatus) // Filter Status Sah
                ->get()->getResultArray();

            $simpananWajib = $db->table('simpanan_wajib')
                ->select('simpanan_wajib.*, anggota.nama_lengkap')
                ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota')
                ->whereIn('simpanan_wajib.status', $validStatus) // Filter Status Sah
                ->get()->getResultArray();

            $simpananSukarela = $db->table('simpanan_sukarela')
                ->select('simpanan_sukarela.*, anggota.nama_lengkap')
                ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota')
                ->whereIn('simpanan_sukarela.status', $validStatus) // Filter Status Sah
                ->get()->getResultArray();

            $totalPokok = array_sum(array_column($simpananPokok, 'jumlah')) ?? 0;
            $totalWajib = array_sum(array_column($simpananWajib, 'jumlah')) ?? 0;
            $totalSukarela = array_sum(array_column($simpananSukarela, 'jumlah')) ?? 0;

            $anggotaPokok = $db->table('simpanan_pokok')
                ->select('id_anggota')
                ->where('jumlah >', 0)
                ->whereIn('status', $validStatus)
                ->groupBy('id_anggota')
                ->countAllResults();

            $anggotaLunas = $db->table('simpanan_pokok')
                ->select('id_anggota, SUM(jumlah) as total')
                ->where('jumlah >', 0)
                ->whereIn('status', $validStatus)
                ->groupBy('id_anggota')
                ->having('total >=', 500000)
                ->countAllResults();

            $anggotaList = $db->table('anggota')
                ->select('id_anggota, nama_lengkap')
                ->where('status', 'aktif')
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $totalPokok = 0;
            $totalWajib = 0;
            $totalSukarela = 0;
            $anggotaPokok = 0;
            $anggotaLunas = 0;
            $simpananPokok = [];
            $simpananWajib = [];
            $simpananSukarela = [];
            $anggotaList = [];
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
        $tenor = $this->request->getPost('tenor');
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

            if (empty($tenor) || !is_numeric($tenor) || (int) $tenor <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tenor simpanan pokok wajib diisi dengan angka bulan yang valid'
                ]);
            }

            $tenor = (int) $tenor;

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
                            'status' => 'aktif',
                            'tenor' => $tenor
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
                'status' => $status,
                'tenor' => $tenor
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
                'status' => 'aktif'
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
            if ($jenis === 'all' || empty($jenis)) {
                // Simpanan Pokok
                $builderPokok = $db->table('simpanan_pokok')
                    ->select('simpanan_pokok.*, anggota.nama_lengkap, "pokok" as jenis')
                    ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
                    ->where('simpanan_pokok.jumlah >', 0);

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
                // Filter Jenis Spesifik
                if ($jenis === 'pokok') {
                    $builder = $db->table('simpanan_pokok')
                        ->select('simpanan_pokok.*, anggota.nama_lengkap, "pokok" as jenis')
                        ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
                        ->where('simpanan_pokok.jumlah >', 0);

                    if ($id_anggota && $id_anggota !== 'all') {
                        $builder->where('simpanan_pokok.id_anggota', $id_anggota);
                    }
                } elseif ($jenis === 'wajib') {
                    $builder = $db->table('simpanan_wajib')
                        ->select('simpanan_wajib.*, anggota.nama_lengkap, "wajib" as jenis')
                        ->join('anggota', 'anggota.id_anggota = simpanan_wajib.id_anggota');

                    if ($id_anggota && $id_anggota !== 'all') {
                        // PERBAIKAN PENTING: Panggil tabel spesifik 'simpanan_wajib.id_anggota'
                        $builder->where('simpanan_wajib.id_anggota', $id_anggota);
                    }
                } elseif ($jenis === 'sukarela') {
                    $builder = $db->table('simpanan_sukarela')
                        ->select('simpanan_sukarela.*, anggota.nama_lengkap, "sukarela" as jenis')
                        ->join('anggota', 'anggota.id_anggota = simpanan_sukarela.id_anggota');

                    if ($id_anggota && $id_anggota !== 'all') {
                        $builder->where('simpanan_sukarela.id_anggota', $id_anggota);
                    }
                } else {
                    return $this->response->setJSON([]);
                }

                $result = $builder->orderBy('tanggal', 'DESC')->get()->getResultArray();
            }

            // Format tanggal & sanitasi array
            $formattedResult = [];
            foreach ($result as $row) {
                // Khusus simpanan pokok, buang jika jumlah 0
                if ($row['jenis'] === 'pokok' && (float)($row['jumlah'] ?? 0) <= 0) {
                    continue;
                }

                if (isset($row['tanggal'])) {
                    $row['tanggal'] = date('d M Y', strtotime($row['tanggal']));
                }

                $formattedResult[] = $row;
            }

            return $this->response->setJSON($formattedResult);
        } catch (\Exception $e) {
            log_message('error', 'Error getSimpananList: ' . $e->getMessage());
            return $this->response->setJSON([]);
        }
    }

    public function checkSimpananPokok($id_anggota)
    {
        $db = \Config\Database::connect();

        // Total simpanan pokok
        $totalPokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->whereIn('status', ['aktif', 'lunas'])
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $maxLimit = 500000;
        $isLunas  = ($totalPokok >= $maxLimit);
        $sisa     = max(0, $maxLimit - $totalPokok);

        // Hitung berapa transaksi yang sudah ada
        $count = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->where('jumlah >', 0)
            ->countAllResults();

        // === FIX TENOR: Ambil tenor dari transaksi pertama berdasarkan tanggal / id terkecil ===
        $firstTx = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->where('tenor IS NOT NULL')
            ->where('tenor >', 0)
            ->orderBy('created_at', 'ASC') // Jika tidak ada kolom created_at, ganti dengan orderBy('id_sp', 'ASC')
            ->get()
            ->getRow();

        $existingTenor = ($firstTx && isset($firstTx->tenor)) ? (int) $firstTx->tenor : 0;

        return $this->response->setJSON([
            'success'       => true,
            'total'         => (float) $totalPokok,
            'max_limit'     => (float) $maxLimit,
            'sisa'          => (float) $sisa,
            'isLunas'       => $isLunas,
            'count'         => $count,
            'existingTenor' => $existingTenor
        ]);
    }
    // =========================
    // HAPUS SIMPANAN
    // =========================
    public function deleteSimpanan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $jenis = $this->request->getPost('jenis');
        $id = $this->request->getPost('id');

        $db = \Config\Database::connect();

        try {
            switch ($jenis) {
                case 'pokok':
                    $table = 'simpanan_pokok';
                    $id_field = 'id_sp';
                    break;
                case 'wajib':
                    $table = 'simpanan_wajib';
                    $id_field = 'id_sw';
                    break;
                case 'sukarela':
                    $table = 'simpanan_sukarela';
                    $id_field = 'id_ss';
                    break;
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Jenis simpanan tidak valid'
                    ]);
            }

            // Hapus data
            $deleted = $db->table($table)
                ->where($id_field, $id)
                ->delete();

            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data simpanan berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data simpanan'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // =========================
    // PENDING SIMPANAN POKOK
    // =========================

    public function pendingSimpananPokok()
    {
        $db = \Config\Database::connect();

        $pending = $db->table('simpanan_pokok')
            ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
            ->where('simpanan_pokok.status', 'pending')
            ->select('simpanan_pokok.*, anggota.nama_lengkap, anggota.nomor_anggota, anggota.photo')
            ->orderBy('simpanan_pokok.tanggal', 'DESC')
            ->get()
            ->getResultArray(); // Pastikan ini mengembalikan array

        $data = [
            'title' => 'Pending Simpanan Pokok',
            'pending' => $pending // Pastikan ini array
        ];

        return view('layouts/header', $data)
            . view('dashboard_admin/pending_simpanan_pokok', $data)
            . view('layouts/footer');
    }

    public function approveSimpananPokok($id)
    {
        log_message('debug', 'Approve Simpanan Pokok dipanggil, ID: ' . $id);

        $db = \Config\Database::connect();

        // Debug: Cek apakah data exist
        $dataExist = $db->table('simpanan_pokok')
            ->where('id_sp', $id)
            ->where('status', 'pending')
            ->countAllResults();

        log_message('debug', 'Data ditemukan: ' . $dataExist);

        $updated = $db->table('simpanan_pokok')
            ->where('id_sp', $id)
            ->update(['status' => 'aktif']);

        log_message('debug', 'Update berhasil: ' . ($updated ? 'Ya' : 'Tidak'));

        if ($updated) {
            return redirect()->back()->with('success', 'Simpanan pokok berhasil disetujui.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyetujui simpanan pokok.');
        }
    }

    public function rejectSimpananPokok($id)
    {
        log_message('debug', 'Reject Simpanan Pokok dipanggil, ID: ' . $id);

        $db = \Config\Database::connect();

        $updated = $db->table('simpanan_pokok')
            ->where('id_sp', $id)
            ->update(['status' => 'ditolak']);

        log_message('debug', 'Update berhasil: ' . ($updated ? 'Ya' : 'Tidak'));

        if ($updated) {
            return redirect()->back()->with('success', 'Simpanan pokok berhasil ditolak.');
        } else {
            return redirect()->back()->with('error', 'Gagal menolak simpanan pokok.');
        }
    }

    public function detailSimpananPokok($id)
    {
        $db = \Config\Database::connect();

        $simpanan = $db->table('simpanan_pokok')
            ->join('anggota', 'anggota.id_anggota = simpanan_pokok.id_anggota')
            ->where('simpanan_pokok.id_sp', $id)
            ->select('simpanan_pokok.*, anggota.nama_lengkap, anggota.nomor_anggota, anggota.photo, anggota.email, anggota.no_hp')
            ->get()
            ->getRow();

        if (!$simpanan) {
            return redirect()->to('/admin/pending-simpanan-pokok')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Simpanan Pokok',
            'simpanan' => $simpanan
        ];

        return view('layouts/header', $data)
            . view('dashboard_admin/detail_simpanan_pokok')
            . view('layouts/footer');
    }

    // =========================
    // MENU LAINNYA
    // =========================

    public function financing()
    {
        $db = \Config\Database::connect();

        // Helper closure untuk kalkulasi pokok & margin
        $parsePembiayaan = function ($data, $akad) {
            return array_map(function ($item) use ($akad) {
                $item['akad'] = $akad;
                $item['status'] = normalizeLoanStatusValue($item['status'] ?? '');
                $total = (float)($item['jml_pinjam'] ?? 0);

                if ($akad === 'murabahah' || $akad === 'mudharabah') {
                    // Karena total = pokok + 10% margin (pokok * 1.10)
                    // Pokok = total / 1.10
                    $item['pokok']  = round($total / 1.10);
                    $item['margin'] = $total - $item['pokok'];
                } else {
                    // Qard 0% margin
                    $item['pokok']  = $total;
                    $item['margin'] = 0;
                }

                return $item;
            }, $data);
        };

        // 1. Ambil Data Gabungan dari 3 Tabel
        $qardRaw = $db->table('qard')
            ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
            ->select('qard.id_qard AS id, qard.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, anggota.no_ktp, qard.jml_pinjam, qard.jml_terbayar, qard.tanggal, qard.jml_angsuran as tenor, qard.keperluan, qard.status')
            ->get()->getResultArray();

        $murabahahRaw = $db->table('murabahah')
            ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
            ->select('murabahah.id_mr AS id, murabahah.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, anggota.no_ktp, murabahah.jml_pinjam, murabahah.jml_terbayar, murabahah.tanggal, murabahah.jml_angsuran as tenor, murabahah.keperluan, murabahah.status')
            ->get()->getResultArray();

        $mudharabahRaw = $db->table('mudharabah')
            ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
            ->select('mudharabah.id_md AS id, mudharabah.id_anggota, anggota.nama_lengkap, anggota.nomor_anggota, anggota.no_ktp, mudharabah.jml_pinjam, mudharabah.jml_terbayar, mudharabah.tanggal, mudharabah.jml_angsuran as tenor, mudharabah.keperluan, mudharabah.status')
            ->get()->getResultArray();

        $qard       = $parsePembiayaan($qardRaw, 'qard');
        $murabahah  = $parsePembiayaan($murabahahRaw, 'murabahah');
        $mudharabah = $parsePembiayaan($mudharabahRaw, 'mudharabah');

        $allPembiayaan = array_merge($qard, $murabahah, $mudharabah);
        usort($allPembiayaan, function ($a, $b) {
            return strtotime($b['tanggal'] ?? 0) - strtotime($a['tanggal'] ?? 0);
        });

        // 2. Metrics Card Counter
        $total_aktif       = 0;
        $total_jumlah      = 0;
        $total_menunggu    = 0;
        $total_jatuh_tempo = 0;
        $tanggalLimit      = date('Y-m-d', strtotime('+3 days'));

        foreach ($allPembiayaan as $p) {
            $st     = normalizeLoanStatusValue($p['status'] ?? '');
            $pinjam = (float)($p['jml_pinjam'] ?? 0);
            $tgl    = $p['tanggal'] ?? '9999-12-31';

            if ($st === 'aktif') {
                $total_aktif++;
                $total_jumlah += $pinjam;
                if ($tgl <= $tanggalLimit) {
                    $total_jatuh_tempo++;
                }
            } elseif ($st === 'pending') {
                $total_menunggu++;
            } elseif ($st === 'lunas') {
                // lunas tidak masuk dalam hitungan aktif/pending
            }
        }

        $data = [
            'title'             => 'Manajemen Pembiayaan',
            'total_aktif'       => $total_aktif,
            'total_jumlah'      => $total_jumlah,
            'total_menunggu'    => $total_menunggu,
            'total_jatuh_tempo' => $total_jatuh_tempo,
            'qard'              => $qard,
            'murabahah'         => $murabahah,
            'mudharabah'        => $mudharabah,
            'pembiayaan'        => $allPembiayaan
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
        $akad       = $request->getPost('akad');
        
        // Baca nominal pinjam dengan aman (bebas format titik/koma)
        $rawPinjam  = $request->getPost('jml_pinjam');
        $jml_pinjam = (float) preg_replace('/[^0-9]/', '', (string)$rawPinjam);
        
        $tenor     = (int) $request->getPost('tenor');
        $keperluan = $request->getPost('keperluan');
        $tanggal   = $request->getPost('tanggal');

        // Validasi kelengkapan data
        if (empty($id_anggota) || empty($akad) || $jml_pinjam < 100000 || $tenor <= 0 || empty($tanggal)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Harap lengkapi semua field dengan benar. Nominal minimal Rp 100.000.'
            ]);
        }

        $db = \Config\Database::connect();
        $anggota = $db->table('anggota')->where('id_anggota', $id_anggota)->get()->getRow();

        if (!$anggota) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data anggota tidak ditemukan.'
            ]);
        }

        // Hitung Margin (Murabahah & Mudharabah 10%, Qard 0%)
        $rateMargin     = ($akad === 'murabahah' || $akad === 'mudharabah') ? 0.10 : 0;
        $nominalMargin  = $jml_pinjam * $rateMargin;
        $totalKewajiban = $jml_pinjam + $nominalMargin;

        // Data disesuaikan murni dengan allowedFields di Model Anda
        $data = [
            'id_anggota'   => $anggota->id_anggota,
            'tanggal'      => $tanggal,
            'jml_pinjam'   => $totalKewajiban, // Pokok + Margin
            'jml_angsuran' => $tenor,           // Berfungsi sebagai Total Tenor
            'keperluan'    => $keperluan,
            'status'       => 'aktif',
            'jml_terbayar' => 0
        ];

        // Instansiasi Model Sesuai Akad
        if ($akad === 'qard') {
            $model = new \App\Models\QardModel();
        } elseif ($akad === 'murabahah') {
            $model = new \App\Models\MurabahahModel();
        } elseif ($akad === 'mudharabah') {
            $model = new \App\Models\MudharabahModel();
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Jenis akad tidak valid.'
            ]);
        }

        if ($model->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Pembiayaan ' . ucfirst($akad) . ' berhasil diajukan dan aktif!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menyimpan transaksi ke database.'
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}

    // Method Hapus Pembiayaan (Otomatis Bersihkan Detail Angsuran)
    public function deletePembiayaan()
    {
        $id   = $this->request->getPost('id');
        $akad = $this->request->getPost('akad');

        $tableMap = [
            'qard'       => ['table' => 'qard', 'pk' => 'id_qard'],
            'murabahah'  => ['table' => 'murabahah', 'pk' => 'id_mr'],
            'mudharabah' => ['table' => 'mudharabah', 'pk' => 'id_md']
        ];

        if (!isset($tableMap[$akad]) || empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak valid.']);
        }

        $table = $tableMap[$akad]['table'];
        $pk    = $tableMap[$akad]['pk'];

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Hapus riwayat angsuran terkait dari tabel detail_angsuran
        $db->table('detail_angsuran')
            ->where('jenis_pembiayaan', $akad)
            ->where('id_pembiayaan', $id)
            ->delete();

        // 2. Hapus data master pinjaman
        $db->table($table)->where($pk, $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === TRUE) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data pembiayaan beserta seluruh riwayat angsurannya berhasil dihapus!'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    }


    public function transactions()
    {
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

        // Total akumulasi/kumulatif keseluruhan
        $total_pemasukan_umum = $db->table('transaksi_umum')
            ->where('jenis', 'pemasukan')
            ->selectSum('jumlah')
            ->get()
            ->getRowArray();

        $total_pengeluaran_umum = $db->table('transaksi_umum')
            ->where('jenis', 'pengeluaran')
            ->selectSum('jumlah')
            ->get()
            ->getRowArray();

        // Perhitungan nilai nominal
        $pemasukan_bln = $total_pemasukan['jumlah'] ?? 0;
        $pengeluaran_bln = $total_pengeluaran['jumlah'] ?? 0;
        
        $pemasukan_total = $total_pemasukan_umum['jumlah'] ?? 0;
        $pengeluaran_total = $total_pengeluaran_umum['jumlah'] ?? 0;

        // Saldo Akumulasi Keseluruhan (Kas Umum)
        $saldo_kas_total = $pemasukan_total - $pengeluaran_total;
        
        // Net Cashflow Bulan Ini (Surplus / Defisit)
        $cashflow_bulan_ini = $pemasukan_bln - $pengeluaran_bln;

        // Riwayat transaksi
        $riwayat = $db->table('transaksi_umum')
            ->orderBy('tanggal', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'                  => 'Transaksi Umum',
            'total_pemasukan'        => $pemasukan_bln,
            'total_pengeluaran'      => $pengeluaran_bln,
            'total_pemasukan_umum'   => $pemasukan_total,
            'total_pengeluaran_umum' => $pengeluaran_total,
            'saldo_kas_total'        => $saldo_kas_total,
            'cashflow_bulan_ini'     => $cashflow_bulan_ini,
            'bulan_transaksi'        => date('F Y'),
            'riwayat'                => $riwayat
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

            // Bersihkan semua karakter selain angka
            $jumlahClean = preg_replace('/[^0-9]/', '', $request->getPost('jumlah'));

            $data = [
                'deskripsi' => $request->getPost('deskripsi'),
                'kategori'  => $request->getPost('kategori'),
                'jumlah'    => (float)$jumlahClean,
                'jenis'     => $request->getPost('jenis'),
                'tanggal'   => date('Y-m-d H:i:s')
            ];

            $db = \Config\Database::connect();
            if ($db->table('transaksi_umum')->insert($data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Transaksi berhasil disimpan'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan transaksi'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // ==========================================
    // UPDATE TRANSAKSI UMUM
    // ==========================================
    public function updateTransaksi($id)
    {
        try {
            $db = \Config\Database::connect();
            $request = $this->request;

            // Bersihkan format angka
            $jumlahClean = preg_replace('/[^0-9]/', '', $request->getPost('jumlah'));

            $data = [
                'deskripsi' => $request->getPost('deskripsi'),
                'kategori'  => $request->getPost('kategori'),
                'jumlah'    => (float)$jumlahClean,
                'jenis'     => $request->getPost('jenis'),
            ];

            if ($db->table('transaksi_umum')->where('id', $id)->update($data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Transaksi berhasil diperbarui'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal memperbarui transaksi'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // ==========================================
    // DELETE TRANSAKSI UMUM
    // ==========================================
    public function deleteTransaksi($id)
    {
        try {
            $db = \Config\Database::connect();

            if ($db->table('transaksi_umum')->where('id', $id)->delete()) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Transaksi berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menghapus transaksi'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function reports()
    {
        // 1. Ambil tahun dari request GET, default ke tahun berjalan
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $db = \Config\Database::connect();

        // =========================================================
        // 2. HITUNG MARGIN REALISASI (UANG MASUK DARI ANGSURAN TERBAYAR)
        // =========================================================

        // Murabahah: Ambil total jml_terbayar pada tahun terpilih
        $mrbTerbayar = $db->table('murabahah')
            ->whereIn('status', ['aktif', 'lunas'])
            ->where('YEAR(tanggal)', $tahun)
            ->selectSum('jml_terbayar', 'total')
            ->get()->getRowArray()['total'] ?? 0;

        // Margin murni 10% dari angsuran terbayar
        $margin_murabahah = (float)$mrbTerbayar - ((float)$mrbTerbayar / 1.10);

        // Mudharabah: Ambil total jml_terbayar pada tahun terpilih
        $mdhTerbayar = $db->table('mudharabah')
            ->whereIn('status', ['aktif', 'lunas'])
            ->where('YEAR(tanggal)', $tahun)
            ->selectSum('jml_terbayar', 'total')
            ->get()->getRowArray()['total'] ?? 0;

        $margin_mudharabah = (float)$mdhTerbayar - ((float)$mdhTerbayar / 1.10);

        // 3. Hitung Pemasukan & Pengeluaran Umum
        $pemasukan_umum = $db->table('transaksi_umum')
            ->where('jenis', 'pemasukan')
            ->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah')
            ->get()->getRowArray()['jumlah'] ?? 0;

        $pengeluaran_umum = $db->table('transaksi_umum')
            ->where('jenis', 'pengeluaran')
            ->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah')
            ->get()->getRowArray()['jumlah'] ?? 0;

        // =========================================================
        // 4. KALKULASI SHU TOTAL
        // =========================================================
        $totalPendapatan = $margin_murabahah + $margin_mudharabah + (float)$pemasukan_umum;
        $shu = max(0, $totalPendapatan - (float)$pengeluaran_umum);

        // 5. Pembagian SHU (50% Jasa Modal, 50% Jasa Usaha)
        $jasaModal = $shu * 0.5;
        $jasaUsaha = $shu * 0.5;

        // =========================================================
        // 6. DATA GRAFIK BULANAN (SINKRON DENGAN MARGIN REALISASI)
        // =========================================================
        $pendapatanGrafik = array_fill(0, 12, 0);
        $pengeluaranGrafik = array_fill(0, 12, 0);
        $shuGrafik = array_fill(0, 12, 0);

        for ($m = 1; $m <= 12; $m++) {
            // Pemasukan Umum Bulanan
            $p_m = $db->table('transaksi_umum')
                ->where('jenis', 'pemasukan')
                ->where('MONTH(tanggal)', $m)
                ->where('YEAR(tanggal)', $tahun)
                ->selectSum('jumlah')
                ->get()->getRowArray()['jumlah'] ?? 0;

            // Margin Murabahah Bulanan (10% dari angsuran terbayar di bulan tersebut)
            $mrb_m = $db->table('murabahah')
                ->whereIn('status', ['aktif', 'lunas'])
                ->where('MONTH(tanggal)', $m)
                ->where('YEAR(tanggal)', $tahun)
                ->selectSum('jml_terbayar', 'total')
                ->get()->getRowArray()['total'] ?? 0;
            $margin_mrb_m = (float)$mrb_m - ((float)$mrb_m / 1.10);

            // Margin Mudharabah Bulanan
            $mdh_m = $db->table('mudharabah')
                ->whereIn('status', ['aktif', 'lunas'])
                ->where('MONTH(tanggal)', $m)
                ->where('YEAR(tanggal)', $tahun)
                ->selectSum('jml_terbayar', 'total')
                ->get()->getRowArray()['total'] ?? 0;
            $margin_mdh_m = (float)$mdh_m - ((float)$mdh_m / 1.10);

            // Pengeluaran Umum Bulanan
            $k_m = $db->table('transaksi_umum')
                ->where('jenis', 'pengeluaran')
                ->where('MONTH(tanggal)', $m)
                ->where('YEAR(tanggal)', $tahun)
                ->selectSum('jumlah')
                ->get()->getRowArray()['jumlah'] ?? 0;

            $total_pendapatan_m = (float)$p_m + $margin_mrb_m + $margin_mdh_m;

            $pendapatanGrafik[$m - 1] = $total_pendapatan_m;
            $pengeluaranGrafik[$m - 1] = (float)$k_m;
            $shuGrafik[$m - 1] = max(0, $total_pendapatan_m - (float)$k_m);
        }

        $grafikData = [
            'pendapatan'  => $pendapatanGrafik,
            'pengeluaran' => $pengeluaranGrafik,
            'shu'         => $shuGrafik
        ];

        // 7. Opsi Dropdown Tahun
        $tahunOptions = [];
        for ($i = 0; $i < 5; $i++) {
            $year = date('Y') - $i;
            $tahunOptions[$year] = $year;
        }

        $viewData = [
            'title'             => 'Laporan & Analisis',
            'tahun'             => $tahun,
            'tahunOptions'      => $tahunOptions,
            'shu'               => $shu,
            'margin_murabahah'  => $margin_murabahah,
            'margin_mudharabah' => $margin_mudharabah,
            'pemasukan_umum'    => $pemasukan_umum,
            'pengeluaran_umum'  => $pengeluaran_umum,
            'jasaModal'         => $jasaModal,
            'jasaUsaha'         => $jasaUsaha,
            'grafikData'        => $grafikData
        ];

        return view('layouts/header', $viewData)
            . view('dashboard_admin/reports', $viewData)
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

        // ===== SIMPANAN (Menghitung yang aktif dan lunas) =====
        $totalPokok = $simpananPokokModel->builder()
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
            ->selectSum('jumlah', 'total')
            ->get()->getRowArray()['total'] ?? 0;

        $totalWajib = $simpananWajibModel->builder()
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
            ->selectSum('jumlah', 'total')
            ->get()->getRowArray()['total'] ?? 0;

        $totalSukarela = $simpananSukarelaModel->builder()
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
            ->selectSum('jumlah', 'total')
            ->get()->getRowArray()['total'] ?? 0;

        $totalSimpanan = (float)$totalPokok + (float)$totalWajib + (float)$totalSukarela;


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
            'total' => (float)$totalPokok,
            'tanggal_terakhir' => $simpananPokokModel
                ->where('id_anggota', $id)
                ->whereIn('status', ['aktif', 'lunas'])
                ->orderBy('tanggal', 'DESC')
                ->first()['tanggal'] ?? null
        ];

        $simpanan_wajib = [
            'total' => (float)$totalWajib,
            'setoran_bulanan' => 50000, // Default, bisa disesuaikan
            'tanggal_terakhir' => $simpananWajibModel
                ->where('id_anggota', $id)
                ->whereIn('status', ['aktif', 'lunas'])
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
            ->whereIn('status', ['aktif', 'lunas'])
            ->select("jumlah, 'Setoran Simpanan Pokok' as keterangan, tanggal, 'pemasukan' as type, 'berhasil' as status")
            ->findAll();

        $transaksiWajib = $simpananWajibModel
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
            ->select("jumlah, 'Setoran Simpanan Wajib' as keterangan, tanggal, 'pemasukan' as type, 'berhasil' as status")
            ->findAll();

        $transaksiSukarela = $simpananSukarelaModel
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
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
            ->whereIn('status', ['aktif', 'lunas'])
            ->select("jumlah, 'Simpanan Pokok' as jenis, tanggal, 'masuk' as tipe, 'berhasil' as status")
            ->findAll();

        $transaksiWajib = $simpananWajibModel
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
            ->select("jumlah, 'Simpanan Wajib' as jenis, tanggal, 'masuk' as tipe, 'berhasil' as status")
            ->findAll();

        $transaksiSukarela = $simpananSukarelaModel
            ->where('id_anggota', $id)
            ->whereIn('status', ['aktif', 'lunas'])
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
    $qardModel = new QardModel();
    $murabahahModel = new MurabahahModel();
    $mudharabahModel = new MudharabahModel();

    $data = [
        'title'       => 'Manajemen Angsuran',
        'active_menu' => 'installments',
        // Panggil method model langsung TANPA ->where() di depan
        'qard'       => $qardModel->getAngsuranWithAnggota(),
        'murabahah'  => $murabahahModel->getAngsuranWithAnggota(),
        'mudharabah' => $mudharabahModel->getAngsuranWithAnggota()
    ];

    return view('layouts/header', $data)
        . view('dashboard_admin/installments', $data)
        . view('layouts/footer');
}

    public function bayarAngsuran()
    {
        $jenis        = $this->request->getPost('jenis');
        $id           = $this->request->getPost('id');
        $jumlah_bayar = (float) $this->request->getPost('jumlah_bayar');

        if ($jumlah_bayar <= 0) {
            return redirect()->back()->with('error', 'Nominal pembayaran tidak valid.');
        }

        $db = \Config\Database::connect();
        $detailAngsuranModel = new DetailAngsuranModel();

        $tableMap = [
            'qard'       => ['table' => 'qard', 'pk' => 'id_qard'],
            'murabahah'  => ['table' => 'murabahah', 'pk' => 'id_mr'],
            'mudharabah' => ['table' => 'mudharabah', 'pk' => 'id_md']
        ];

        if (!isset($tableMap[$jenis])) {
            return redirect()->back()->with('error', 'Jenis pembiayaan tidak dikenal.');
        }

        $table = $tableMap[$jenis]['table'];
        $pk    = $tableMap[$jenis]['pk'];

        $pinjaman = $db->table($table)->where($pk, $id)->get()->getRow();

        if (!$pinjaman) {
            return redirect()->back()->with('error', 'Data pinjaman tidak ditemukan.');
        }

        // Hitung hitungan dasar
        $terbayarLama = (float) ($pinjaman->jml_terbayar ?? 0);
        $terbayarBaru = $terbayarLama + $jumlah_bayar;
        $totalPinjam  = (float) $pinjaman->jml_pinjam;
        $totalTenor   = (int) ($pinjaman->jml_angsuran ?? 1);

        // Hitung angsuran ke-berapa via Model Baru
        $countAngsuran = $detailAngsuranModel
            ->where('jenis_pembiayaan', $jenis)
            ->where('id_pembiayaan', $id)
            ->countAllResults();
        $angsuranKe = $countAngsuran + 1;

        // Sisa tenor
        $angsuranPerBulan = $totalPinjam / max($totalTenor, 1);
        $tenorTerbayar    = floor($terbayarBaru / max($angsuranPerBulan, 1));
        $sisaTenor        = max(0, $totalTenor - $tenorTerbayar);

        $updateData = [
            'jml_terbayar' => $terbayarBaru,
            'sisa_tenor'   => $sisaTenor
        ];

        if ($terbayarBaru >= $totalPinjam) {
            $updateData['status']     = 'lunas';
            $updateData['sisa_tenor'] = 0;
        }

        $db->transStart();

        // Update tabel master
        $db->table($table)->where($pk, $id)->update($updateData);

        // Simpan ke detail_angsuran via Model Baru
        $detailAngsuranModel->insert([
            'jenis_pembiayaan' => $jenis,
            'id_pembiayaan'    => $id,
            'id_anggota'       => $pinjaman->id_anggota,
            'angsuran_ke'      => $angsuranKe,
            'jumlah_bayar'     => $jumlah_bayar,
            'tanggal_bayar'    => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === TRUE) {
            return redirect()->back()->with('success', 'Pembayaran angsuran berhasil disimpan!');
        }

        return redirect()->back()->with('error', 'Gagal memproses pembayaran angsuran.');
    }

    public function editAngsuran()
    {
        $idDetail     = $this->request->getPost('id_detail');
        $jumlahBaru   = (float) $this->request->getPost('jumlah_bayar_edit');
        $keterangan   = $this->request->getPost('keterangan');

        if ($jumlahBaru <= 0) {
            return redirect()->back()->with('error', 'Nominal pembayaran tidak valid.');
        }

        $db = \Config\Database::connect();
        $detailModel = new \App\Models\DetailAngsuranModel();

        // 1. Ambil data detail angsuran yang akan diedit
        $angsuranLama = $detailModel->find($idDetail);
        if (!$angsuranLama) {
            return redirect()->back()->with('error', 'Data riwayat angsuran tidak ditemukan.');
        }

        $jenis = $angsuranLama->jenis_pembiayaan ?? $angsuranLama['jenis_pembiayaan'];
        $idPembiayaan = $angsuranLama->id_pembiayaan ?? $angsuranLama['id_pembiayaan'];

        $tableMap = [
            'qard'       => ['table' => 'qard', 'pk' => 'id_qard'],
            'murabahah'  => ['table' => 'murabahah', 'pk' => 'id_mr'],
            'mudharabah' => ['table' => 'mudharabah', 'pk' => 'id_md']
        ];

        if (!isset($tableMap[$jenis])) {
            return redirect()->back()->with('error', 'Jenis pembiayaan tidak valid.');
        }

        $table = $tableMap[$jenis]['table'];
        $pk    = $tableMap[$jenis]['pk'];

        // 2. Ambil data master pinjaman
        $pinjaman = $db->table($table)->where($pk, $idPembiayaan)->get()->getRow();
        if (!$pinjaman) {
            return redirect()->back()->with('error', 'Data master pinjaman tidak ditemukan.');
        }

        $db->transStart();

        // A. Update record di detail_angsuran
        $detailModel->update($idDetail, [
            'jumlah_bayar' => $jumlahBaru,
            'keterangan'   => $keterangan
        ]);

        // B. Hitung ULANG Total Terbayar dari seluruh riwayat detail_angsuran agar presisi
        $totalTerbayarBaru = $detailModel->where('jenis_pembiayaan', $jenis)
            ->where('id_pembiayaan', $idPembiayaan)
            ->selectSum('jumlah_bayar')
            ->first();

        $sumTerbayar  = (float) ($totalTerbayarBaru->jumlah_bayar ?? $totalTerbayarBaru['jumlah_bayar'] ?? 0);
        $totalPinjam  = (float) $pinjaman->jml_pinjam;
        $totalTenor   = (int) ($pinjaman->jml_angsuran ?? 1);

        // C. Hitung ulang sisa tenor dan status
        $angsuranPerBulan = $totalPinjam / max($totalTenor, 1);
        $tenorTerbayar    = floor($sumTerbayar / max($angsuranPerBulan, 1));
        $sisaTenor        = max(0, $totalTenor - $tenorTerbayar);

        $updateMaster = [
            'jml_terbayar' => $sumTerbayar,
            'sisa_tenor'   => $sisaTenor,
            'status'       => ($sumTerbayar >= $totalPinjam) ? 'lunas' : 'aktif'
        ];

        if ($sumTerbayar >= $totalPinjam) {
            $updateMaster['sisa_tenor'] = 0;
        }

        // D. Update tabel master pinjaman
        $db->table($table)->where($pk, $idPembiayaan)->update($updateMaster);

        $db->transComplete();

        if ($db->transStatus() === TRUE) {
            return redirect()->back()->with('success', 'Riwayat angsuran berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui riwayat angsuran.');
    }
    public function getDetailAngsuran()
    {
        $db = \Config\Database::connect();
        $detailAngsuranModel = new \App\Models\DetailAngsuranModel();

        $jenis = $this->request->getGet('jenis');
        $id    = $this->request->getGet('id');

        $tableMap = [
            'qard'       => ['table' => 'qard', 'pk' => 'id_qard', 'prefix' => 'QRD'],
            'murabahah'  => ['table' => 'murabahah', 'pk' => 'id_mr', 'prefix' => 'MRB'],
            'mudharabah' => ['table' => 'mudharabah', 'pk' => 'id_md', 'prefix' => 'MDB']
        ];

        if (!isset($tableMap[$jenis])) {
            return $this->response->setJSON(['error' => 'Jenis pembiayaan tidak valid']);
        }

        $table  = $tableMap[$jenis]['table'];
        $pk     = $tableMap[$jenis]['pk'];
        $prefix = $tableMap[$jenis]['prefix'];

        // Join dengan tabel anggota agar nama_lengkap & nomor_anggota terambil!
        $data = $db->table($table)
            ->select("{$table}.*, anggota.nama_lengkap, anggota.nomor_anggota")
            ->join('anggota', "anggota.id_anggota = {$table}.id_anggota", 'left')
            ->where("{$table}.{$pk}", $id)
            ->get()
            ->getRowArray();

        if ($data) {
            $total_pinjaman = (float)($data['jml_pinjam'] ?? 0);
            $terbayar       = (float)($data['jml_terbayar'] ?? 0);
            $sisa           = max(0, $total_pinjaman - $terbayar);
            $total_tenor    = (int)($data['jml_angsuran'] ?? 1);

            // Akali Nomor Pinjaman Otomatis (Format: QRD-202600001 / MRB-202600005)
            $tanggalAkad     = isset($data['tanggal']) ? date('Y', strtotime($data['tanggal'])) : date('Y');
            $data['no_pinjam_formatted'] = $prefix . '-' . $tanggalAkad . str_pad($data[$pk], 5, '0', STR_PAD_LEFT);

            // Ambil Riwayat Real dari detail_angsuran
            $riwayat = $detailAngsuranModel->where('jenis_pembiayaan', $jenis)
                ->where('id_pembiayaan', $id)
                ->orderBy('angsuran_ke', 'ASC')
                ->findAll();

            return $this->response->setJSON([
                'success'         => true,
                'data'            => $data,
                'sisa_pembayaran' => $sisa,
                'jml_angsuran'    => $total_tenor,
                'history'         => $riwayat
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
