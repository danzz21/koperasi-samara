<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Anggota - Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #175211ff 0%, #01a056 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .stats-card {
            transition: all 0.3s ease;  
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .tab-active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .tab-inactive {
            background: rgba(255,255,255,0.7);
            color: #666;
        }
        .transaction-item {
            transition: all 0.3s ease;
        }
        .transaction-item:hover {
            background-color: #f8fafc;
            transform: translateX(5px);
        }
        .profile-photo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .status-badge {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .amount-positive {
            color: #10b981;
        }
        .amount-negative {
            color: #ef4444;
        }
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Detail Anggota</h2>
        <p class="text-gray-600">Data-data anggota</p>
    </div>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto p-6">
        <!-- Member Profile Section -->
<div class="bg-white rounded-2xl p-6 mb-8 card-shadow">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
        <div class="flex items-center space-x-6">
            <!-- FOTO PROFIL -->
            <div class="profile-photo w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold bg-emerald-600">
                <?php if (!empty($anggota['foto_diri']) && file_exists(FCPATH . 'uploads/foto_diri/' . $anggota['foto_diri'])): ?>
                    <img src="<?= base_url('uploads/foto_diri/' . $anggota['foto_diri']) ?>" 
                         alt="Foto <?= esc($anggota['nama_lengkap']) ?>" 
                         class="w-full h-full rounded-full object-cover" />
                <?php else: ?>
                    <div class="w-full h-full rounded-full flex items-center justify-center text-2xl font-bold text-white bg-emerald-500">
                        <?= strtoupper(substr($anggota['nama_lengkap'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- DATA ANGGOTA -->
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1"><?= esc($anggota['nama_lengkap']) ?></h1>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-1 sm:space-y-0 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-id-card text-blue-500"></i>
                        <span>No Anggota: <strong><?= esc($anggota['id_anggota']) ?></strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-green-500"></i>
                        <span>Tanggal Bergabung: 
                            <strong><?= date('d F Y', strtotime($anggota['tanggal_daftar'])) ?></strong>
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php
                            $status = strtolower($anggota['status'] ?? 'menunggu');
                            $warna = match($status) {
                                'aktif' => 'bg-green-500',
                                'nonaktif' => 'bg-red-500',
                                default => 'bg-yellow-500'
                            };
                        ?>
                        <span class="status-badge px-3 py-1 rounded-full text-white text-xs font-semibold <?= $warna ?>">
                            <?= ucfirst($status) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="flex space-x-3">
            <button onclick="editMember(<?= $anggota['id_anggota'] ?>)" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition flex items-center space-x-2">
                <i class="fas fa-edit"></i>
                <span>EDIT</span>
            </button>

            <button onclick="viewActivity(<?= $anggota['id_anggota'] ?>)" 
                    class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition flex items-center space-x-2">
                <i class="fas fa-chart-line"></i>
                <span>ACTIVITY</span>
            </button>
        </div>
    </div>
</div>


        <!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Simpanan -->
    <div class="stats-card bg-white rounded-2xl p-6 card-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-yellow-100 p-3 rounded-xl">
                <i class="fas fa-piggy-bank text-yellow-600 text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Total Simpanan</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp <?= number_format($totalSimpanan ?? 0, 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Total Pembiayaan -->
    <div class="stats-card bg-white rounded-2xl p-6 card-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 p-3 rounded-xl">
                <i class="fas fa-hand-holding-usd text-blue-600 text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Total Pembiayaan</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp <?= number_format($totalPembiayaan ?? 0, 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Sisa Angsuran -->
    <div class="stats-card bg-white rounded-2xl p-6 card-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-red-100 p-3 rounded-xl">
                <i class="fas fa-clock text-red-600 text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Sisa Angsuran</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp <?= number_format($sisaAngsuran ?? 0, 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Bagi Hasil -->
    <div class="stats-card bg-white rounded-2xl p-6 card-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 p-3 rounded-xl">
                <i class="fas fa-coins text-green-600 text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Bagi Hasil</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp <?= number_format($bagiHasil ?? 0, 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
</div>


        <!-- Tabs and Content -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Tab Navigation -->
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-6">
                <div class="flex flex-wrap gap-2">
                    <button onclick="switchTab('transaksi')" id="tab-transaksi" class="tab-button tab-active px-6 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Transaksi</span>
                    </button>
                    <button onclick="switchTab('pembiayaan')" id="tab-pembiayaan" class="tab-button tab-inactive px-6 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2">
                        <i class="fas fa-credit-card"></i>
                        <span>Pembiayaan</span>
                    </button>
                    <button onclick="switchTab('simpanan')" id="tab-simpanan" class="tab-button tab-inactive px-6 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2">
                        <i class="fas fa-wallet"></i>
                        <span>Simpanan</span>
                    </button>
                    <button onclick="switchTab('angsuran')" id="tab-angsuran" class="tab-button tab-inactive px-6 py-3 rounded-lg font-semibold transition-all flex items-center space-x-2">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Angsuran</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Transaksi Tab -->
                <div id="content-transaksi" class="tab-content">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h3>
                        <div class="flex items-center space-x-3">
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option>Semua Transaksi</option>
                                <option>Simpanan</option>
                                <option>Penarikan</option>
                                <option>Pembiayaan</option>
                            </select>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">
                                <i class="fas fa-download mr-2"></i>Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-4" id="transaction-list">
                        <div class="space-y-4">
                            <?php if (!empty($transaksi)): ?>
                                <?php foreach ($transaksi as $t): ?>
                                    <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-150">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="bg-green-100 p-2 rounded-lg">
                                                    <i class="fas fa-plus text-green-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800">
                                                        Setoran <?= esc($t['jenis']) ?>
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        <?= date('d F Y • H:i', strtotime($t['tanggal'])) ?> WIB
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-green-600">
                                                    +Rp <?= number_format($t['jumlah'], 0, ',', '.') ?>
                                                </p>
                                                <p class="text-sm text-gray-500"><?= ucfirst($t['status']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-500 text-center">Belum ada transaksi simpanan.</p>
                            <?php endif; ?>
                        </div>


                        <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-credit-card text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Pencairan Pembiayaan</p>
                                        <p class="text-sm text-gray-500">20 Januari 2025 • 14:15 WIB</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold amount-negative">-Rp 50,000,000</p>
                                    <p class="text-sm text-gray-500">Berhasil</p>
                                </div>
                            </div>
                        </div>

                        <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-piggy-bank text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Setoran Simpanan Wajib</p>
                                        <p class="text-sm text-gray-500">18 Januari 2025 • 09:20 WIB</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold amount-positive">+Rp 1,000,000</p>
                                    <p class="text-sm text-gray-500">Berhasil</p>
                                </div>
                            </div>
                        </div>

                        <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-coins text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Bagi Hasil Bulan Desember</p>
                                        <p class="text-sm text-gray-500">15 Januari 2025 • 16:00 WIB</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold amount-positive">+Rp 850,000</p>
                                    <p class="text-sm text-gray-500">Berhasil</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <button onclick="loadMoreTransactions()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition">
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>

                <!-- Pembiayaan Tab -->
                <div id="content-pembiayaan" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Detail Pembiayaan</h3>
                    <div class="space-y-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="font-semibold text-lg text-gray-800">Pembiayaan Modal Usaha</h4>
                                    <p class="text-sm text-gray-500">Akad: Murabahah • No: PMB-2024-0891</p>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aktif</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Jumlah Pembiayaan</p>
                                    <p class="font-bold text-lg">Rp 50,000,000</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Margin</p>
                                    <p class="font-bold text-lg">10% / tahun</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jangka Waktu</p>
                                    <p class="font-bold text-lg">24 bulan</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Angsuran per Bulan</p>
                                    <p class="font-bold text-lg">Rp 2,420,000</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sisa Angsuran</p>
                                    <p class="font-bold text-lg text-orange-600">18 bulan</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Dibayar</p>
                                    <p class="font-bold text-lg">Rp 14,520,000</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="font-semibold text-lg text-gray-800">Pembiayaan Renovasi Rumah</h4>
                                    <p class="text-sm text-gray-500">Akad: Istishna • No: PMB-2024-0456</p>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aktif</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Jumlah Pembiayaan</p>
                                    <p class="font-bold text-lg">Rp 40,500,000</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Margin</p>
                                    <p class="font-bold text-lg">10% / tahun</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jangka Waktu</p>
                                    <p class="font-bold text-lg">36 bulan</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Angsuran per Bulan</p>
                                    <p class="font-bold text-lg">Rp 1,350,000</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sisa Angsuran</p>
                                    <p class="font-bold text-lg text-orange-600">30 bulan</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Dibayar</p>
                                    <p class="font-bold text-lg">Rp 8,100,000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simpanan Tab -->
                <div id="content-simpanan" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Detail Simpanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Pokok</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp 10,000,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Terakhir</span>
                                    <span>22 Jan 2025</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status</span>
                                    <span class="text-green-600 font-semibold">Aktif</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Wajib</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp 24,000,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Bulanan</span>
                                    <span>Rp 1,000,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Terakhir</span>
                                    <span>18 Jan 2025</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Sukarela</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp 11,500,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dapat Ditarik</span>
                                    <span class="text-green-600">Ya</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nisbah Bagi Hasil</span>
                                    <span>60:40</span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Total Bagi Hasil</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bulan Ini</span>
                                    <span class="font-bold text-green-600">Rp 450,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Tahun 2025</span>
                                    <span class="font-bold">Rp 5,500,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rata-rata per Bulan</span>
                                    <span>Rp 458,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Angsuran Tab -->
                <div id="content-angsuran" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Jadwal Angsuran</h3>
                    <div class="space-y-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Pembiayaan Modal Usaha</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Bulan Ke</th>
                                            <th class="px-4 py-3 text-left">Tanggal Jatuh Tempo</th>
                                            <th class="px-4 py-3 text-left">Angsuran</th>
                                            <th class="px-4 py-3 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-3">1</td>
                                            <td class="px-4 py-3">25 Feb 2025</td>
                                            <td class="px-4 py-3 font-semibold">Rp 2,420,000</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs">Mendatang</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3">2</td>
                                            <td class="px-4 py-3">25 Mar 2025</td>
                                            <td class="px-4 py-3 font-semibold">Rp 2,420,000</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Belum Jatuh Tempo</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3">3</td>
                                            <td class="px-4 py-3">25 Apr 2025</td>
                                            <td class="px-4 py-3 font-semibold">Rp 2,420,000</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Belum Jatuh Tempo</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Pembiayaan Renovasi Rumah</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left">Bulan Ke</th>
                                            <th class="px-4 py-3 text-left">Tanggal Jatuh Tempo</th>
                                            <th class="px-4 py-3 text-left">Angsuran</th>
                                            <th class="px-4 py-3 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-3">1</td>
                                            <td class="px-4 py-3">28 Feb 2025</td>
                                            <td class="px-4 py-3 font-semibold">Rp 1,350,000</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs">Mendatang</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3">2</td>
                                            <td class="px-4 py-3">28 Mar 2025</td>
                                            <td class="px-4 py-3 font-semibold">Rp 1,350,000</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Belum Jatuh Tempo</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                <span>Memuat data...</span>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab-button');
            tabs.forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('tab-inactive');
            });
            
            // Show selected tab content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Add active class to selected tab
            document.getElementById(`tab-${tabName}`).classList.remove('tab-inactive');
            document.getElementById(`tab-${tabName}`).classList.add('tab-active');
        }

        // Navigation functions
        function goBackToDashboard() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                alert('Mengarahkan ke Dashboard Admin...');
                // window.location.href = 'dashboard-admin.html';
            }, 1000);
        }

        function editMember() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                alert('Membuka form edit anggota...');
                // window.location.href = 'edit-anggota.html';
            }, 1000);
        }

        function viewActivity() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                alert('Menampilkan aktivitas anggota...');
                // window.location.href = 'activity-anggota.html';
            }, 1000);
        }

        function loadMoreTransactions() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                const transactionList = document.getElementById('transaction-list');
                
                // Add more transactions
                const newTransactions = [
                    {
                        type: 'payment',
                        title: 'Pembayaran Angsuran Modal Usaha',
                        date: '12 Januari 2025 • 11:45 WIB',
                        amount: '-Rp 2,420,000',
                        status: 'Berhasil',
                        icon: 'fas fa-money-bill-wave',
                        iconClass: 'bg-red-100 text-red-600',
                        amountClass: 'amount-negative'
                    },
                    {
                        type: 'deposit',
                        title: 'Setoran Simpanan Sukarela',
                        date: '10 Januari 2025 • 15:20 WIB',
                        amount: '+Rp 5,000,000',
                        status: 'Berhasil',
                        icon: 'fas fa-plus',
                        iconClass: 'bg-green-100 text-green-600',
                        amountClass: 'amount-positive'
                    }
                ];

                newTransactions.forEach(transaction => {
                    const transactionElement = document.createElement('div');
                    transactionElement.className = 'transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md';
                    transactionElement.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="${transaction.iconClass} p-2 rounded-lg">
                                    <i class="${transaction.icon}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">${transaction.title}</p>
                                    <p class="text-sm text-gray-500">${transaction.date}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold ${transaction.amountClass}">${transaction.amount}</p>
                                <p class="text-sm text-gray-500">${transaction.status}</p>
                            </div>
                        </div>
                    `;
                    transactionList.appendChild(transactionElement);
                });
            }, 1500);
        }

        // Loading modal functions
        function showLoading() {
            document.getElementById('loadingModal').classList.remove('hidden');
            document.getElementById('loadingModal').classList.add('flex');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.add('hidden');
            document.getElementById('loadingModal').classList.remove('flex');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 100);

            // Initialize first tab
            switchTab('transaksi');

            // Add hover effects to statistics cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Simulate real-time updates
            setInterval(() => {
                const statusElements = document.querySelectorAll('.status-badge');
                statusElements.forEach(element => {
                    element.style.animation = 'pulse 2s infinite';
                });
            }, 10000);
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideLoading();
            }
            
            // Tab navigation with number keys
            if (e.key >= '1' && e.key <= '4') {
                const tabs = ['transaksi', 'pembiayaan', 'simpanan', 'angsuran'];
                const tabIndex = parseInt(e.key) - 1;
                if (tabs[tabIndex]) {
                    switchTab(tabs[tabIndex]);
                }
            }
        });
    </script>
</body>
</html>

