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
    <!-- Back Button -->
    <div class="mb-6">
        <button onclick="goBack()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </button>
    </div>

    <!-- Centered Header -->
    <div class="text-center mb-8">
        <h2 class="text-4xl font-bold text-gray-800 mb-2">Detail Anggota</h2>
        <p class="text-lg text-gray-600">Data-data anggota</p>
    </div>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto p-6">
        <!-- Member Profile Section -->
        <div class="bg-white rounded-2xl p-6 mb-8 card-shadow">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <!-- FOTO PROFIL -->
                    <div class="profile-photo w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold bg-emerald-600">
                        <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
                            <img src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" 
                                alt="Foto <?= esc($anggota['nama_lengkap']) ?>" 
                                class="w-full h-full rounded-full object-cover" />
                        <?php else: ?>
                            <?php 
                            $firstLetter = strtoupper(substr($anggota['nama_lengkap'], 0, 1));
                            $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
                            $bgColor = $colors[crc32($anggota['nomor_anggota']) % count($colors)];
                            ?>
                            <div class="w-full h-full rounded-full flex items-center justify-center text-2xl font-bold text-white" style="background:<?= $bgColor ?>;">
                                <?= $firstLetter ?>
                            </div>
                        <?php endif; ?>
                    </div>


                    <!-- DATA ANGGOTA -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-1"><?= esc($anggota['nama_lengkap'] ?? 'Nama Anggota') ?></h1>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-1 sm:space-y-0 text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-blue-500"></i>
                                <span>No Anggota: <strong><?= esc($anggota['nomor_anggota'] ?? '-') ?></strong></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-green-500"></i>
                                <span>Tanggal Bergabung: 
                                    <strong><?= date('d F Y', strtotime($anggota['tanggal_daftar'] ?? date('Y-m-d'))) ?></strong>
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
                    <button onclick="editMember(<?= $anggota['id_anggota'] ?? 0 ?>)" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>EDIT</span>
                    </button>

                    <button onclick="viewActivity(<?= $anggota['id_anggota'] ?? 0 ?>)" 
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
                            <?= $sisaAngsuran ?? 0 ?> bulan
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
                            Rp <?= number_format($bagi_hasil ?? 0, 0, ',', '.') ?>
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
                        <?php if (!empty($riwayat_transaksi)): ?>
                            <?php foreach ($riwayat_transaksi as $transaksi): ?>
                                <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="<?= $transaksi['type'] === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' ?> p-2 rounded-lg">
                                                <i class="<?= $transaksi['type'] === 'pemasukan' ? 'fas fa-plus text-green-600' : 'fas fa-minus text-red-600' ?>"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    <?= esc($transaksi['keterangan']) ?>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    <?= date('d F Y • H:i', strtotime($transaksi['tanggal'])) ?> WIB
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold <?= $transaksi['type'] === 'pemasukan' ? 'amount-positive' : 'amount-negative' ?>">
                                                <?= $transaksi['type'] === 'pemasukan' ? '+' : '-' ?>Rp <?= number_format($transaksi['jumlah'], 0, ',', '.') ?>
                                            </p>
                                            <p class="text-sm text-gray-500"><?= ucfirst($transaksi['status']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">Belum ada transaksi</p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($riwayat_transaksi) && count($riwayat_transaksi) >= 10): ?>
                        <div class="mt-6 flex justify-center">
                            <button onclick="loadMoreTransactions()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition">
                                Muat Lebih Banyak
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pembiayaan Tab -->
                <div id="content-pembiayaan" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Detail Pembiayaan</h3>
                    <div class="space-y-6">
                        <?php if (!empty($data_pembiayaan)): ?>
                            <?php foreach ($data_pembiayaan as $pembiayaan): ?>
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-800"><?= esc($pembiayaan['jenis_pembiayaan']) ?></h4>
                                            <p class="text-sm text-gray-500">Akad: <?= esc($pembiayaan['akad']) ?> • No: <?= esc($pembiayaan['nomor_pembiayaan']) ?></p>
                                        </div>
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <?= ucfirst($pembiayaan['status']) ?>
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Jumlah Pembiayaan</p>
                                            <p class="font-bold text-lg">Rp <?= number_format($pembiayaan['jumlah_pembiayaan'], 0, ',', '.') ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Margin</p>
                                            <p class="font-bold text-lg"><?= $pembiayaan['margin'] ?>%</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Jangka Waktu</p>
                                            <p class="font-bold text-lg"><?= $pembiayaan['jangka_waktu'] ?> bulan</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Angsuran per Bulan</p>
                                            <p class="font-bold text-lg">Rp <?= number_format($pembiayaan['angsuran_per_bulan'], 0, ',', '.') ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Sisa Angsuran</p>
                                            <p class="font-bold text-lg text-orange-600"><?= $pembiayaan['sisa_tenor'] ?> bulan</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Total Dibayar</p>
                                            <p class="font-bold text-lg">Rp <?= number_format($pembiayaan['total_dibayar'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">Belum ada data pembiayaan</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Simpanan Tab -->
                <div id="content-simpanan" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Detail Simpanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Simpanan Pokok -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Pokok</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp <?= number_format($simpanan_pokok['total'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Terakhir</span>
                                    <span><?= !empty($simpanan_pokok['tanggal_terakhir']) ? date('d M Y', strtotime($simpanan_pokok['tanggal_terakhir'])) : '-' ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status</span>
                                    <span class="text-green-600 font-semibold"><?= ($simpanan_pokok['total'] ?? 0) >= 500000 ? 'Lunas' : 'Aktif' ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Simpanan Wajib -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Wajib</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp <?= number_format($simpanan_wajib['total'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Bulanan</span>
                                    <span>Rp <?= number_format($simpanan_wajib['setoran_bulanan'] ?? 50000, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Setoran Terakhir</span>
                                    <span><?= !empty($simpanan_wajib['tanggal_terakhir']) ? date('d M Y', strtotime($simpanan_wajib['tanggal_terakhir'])) : '-' ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Simpanan Sukarela -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Simpanan Sukarela</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saldo Saat Ini</span>
                                    <span class="font-bold">Rp <?= number_format($simpanan_sukarela['total'] ?? 0, 0, ',', '.') ?></span>
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

                        <!-- Total Bagi Hasil -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">Total Bagi Hasil</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bulan Ini</span>
                                    <span class="font-bold text-green-600">Rp <?= number_format($bagi_hasil_bulan_ini ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Tahun <?= date('Y') ?></span>
                                    <span class="font-bold">Rp <?= number_format($bagi_hasil_tahun_ini ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rata-rata per Bulan</span>
                                    <span>Rp <?= number_format(($bagi_hasil_tahun_ini ?? 0) / max(date('n'), 1), 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Angsuran Tab -->
                <div id="content-angsuran" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Jadwal Angsuran</h3>
                    <div class="space-y-6">
                        <?php if (!empty($jadwal_angsuran)): ?>
                            <?php foreach ($jadwal_angsuran as $pembiayaan): ?>
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <h4 class="font-semibold text-lg text-gray-800 mb-4"><?= esc($pembiayaan['nama_pembiayaan']) ?></h4>
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
                                                <?php for ($i = 1; $i <= $pembiayaan['sisa_tenor']; $i++): ?>
                                                    <?php
                                                        $status = 'belum_jatuh_tempo';
                                                        $status_class = 'bg-gray-100 text-gray-800';
                                                        $status_text = 'Belum Jatuh Tempo';

                                                        if ($i === 1) {
                                                            $status = 'mendatang';
                                                            $status_class = 'bg-orange-100 text-orange-800';
                                                            $status_text = 'Mendatang';
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td class="px-4 py-3"><?= $i ?></td>
                                                        <td class="px-4 py-3">
                                                            <?= date('d M Y', strtotime('+' . $i . ' months', strtotime($pembiayaan['tanggal_pembiayaan']))) ?>
                                                        </td>
                                                        <td class="px-4 py-3 font-semibold">Rp <?= number_format($pembiayaan['angsuran_per_bulan'], 0, ',', '.') ?></td>
                                                        <td class="px-4 py-3">
                                                            <span class="px-2 py-1 rounded-full text-xs <?= $status_class ?>">
                                                                <?= $status_text ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">Belum ada jadwal angsuran</p>
                        <?php endif; ?>
                    </div>
                </div>
                    </div>
                </div>

                <!-- Angsuran Tab -->
                <div id="content-angsuran" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Jadwal Angsuran</h3>
                    <div class="space-y-6">
                        <?php if (!empty($jadwal_angsuran)): ?>
                            <?php foreach ($jadwal_angsuran as $pembiayaan): ?>
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <h4 class="font-semibold text-lg text-gray-800 mb-4"><?= esc($pembiayaan['nama_pembiayaan']) ?></h4>
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
                                                <?php for ($i = 1; $i <= $pembiayaan['sisa_tenor']; $i++): ?>
                                                    <?php
                                                        $status = 'belum_jatuh_tempo';
                                                        $status_class = 'bg-gray-100 text-gray-800';
                                                        $status_text = 'Belum Jatuh Tempo';
                                                        
                                                        if ($i === 1) {
                                                            $status = 'mendatang';
                                                            $status_class = 'bg-orange-100 text-orange-800';
                                                            $status_text = 'Mendatang';
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td class="px-4 py-3"><?= $i ?></td>
                                                        <td class="px-4 py-3">
                                                            <?= date('d M Y', strtotime('+' . $i . ' months', strtotime($pembiayaan['tanggal_pembiayaan']))) ?>
                                                        </td>
                                                        <td class="px-4 py-3 font-semibold">Rp <?= number_format($pembiayaan['angsuran_per_bulan'], 0, ',', '.') ?></td>
                                                        <td class="px-4 py-3">
                                                            <span class="px-2 py-1 rounded-full text-xs <?= $status_class ?>">
                                                                <?= $status_text ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">Belum ada jadwal angsuran</p>
                        <?php endif; ?>
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
        function goBack() {
            window.history.back();
        }

        function goBackToDashboard() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                alert('Mengarahkan ke Dashboard Admin...');
                // window.location.href = 'dashboard-admin.html';
            }, 1000);
        }

        function editMember(id) {
            showLoading();
            setTimeout(() => {
                hideLoading();
                // Redirect to edit member page
                window.location.href = '<?= base_url("admin/edit-member/") ?>' + id;
            }, 500);
        }

        function viewActivity(id) {
            showLoading();
            setTimeout(() => {
                hideLoading();
                // Show activity modal or redirect
                alert('Fitur aktivitas anggota sedang dalam pengembangan');
            }, 500);
        }

        function loadMoreTransactions() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                // Implementasi load more transactions via AJAX
                fetch('<?= base_url("admin/get-more-transactions/" . ($anggota["id_anggota"] ?? 0)) ?>', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.transactions.length > 0) {
                        const transactionList = document.getElementById('transaction-list');
                        data.transactions.forEach(transaction => {
                            const transactionElement = document.createElement('div');
                            transactionElement.className = 'transaction-item border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-150';
                            transactionElement.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="${transaction.type === 'pemasukan' ? 'bg-green-100' : 'bg-red-100'} p-2 rounded-lg">
                                            <i class="${transaction.type === 'pemasukan' ? 'fas fa-plus text-green-600' : 'fas fa-minus text-red-600'}"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">${transaction.keterangan}</p>
                                            <p class="text-sm text-gray-500">${new Date(transaction.tanggal).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})} • ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})} WIB</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold ${transaction.type === 'pemasukan' ? 'amount-positive' : 'amount-negative'}">
                                            ${transaction.type === 'pemasukan' ? '+' : '-'}Rp ${transaction.jumlah.toLocaleString('id-ID')}
                                        </p>
                                        <p class="text-sm text-gray-500">${transaction.status}</p>
                                    </div>
                                </div>
                            `;
                            transactionList.appendChild(transactionElement);
                        });
                    } else {
                        alert('Tidak ada transaksi tambahan');
                    }
                })
                .catch(error => {
                    console.error('Error loading more transactions:', error);
                    alert('Gagal memuat transaksi tambahan');
                });
            }, 1000);
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