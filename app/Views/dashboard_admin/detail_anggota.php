<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Detail Anggota' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.12);
        }
        .tab-active {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }
        .tab-inactive {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .tab-inactive:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .transaction-item {
            transition: all 0.2s ease;
        }
        .transaction-item:hover {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 pb-12">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        
        <!-- Top Navigation Bar -->
        <div class="mb-6 flex justify-between items-center">
            <button onclick="goBack()" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-xl border border-gray-200 shadow-sm transition-all duration-200 cursor-pointer">
                <i class="fas fa-arrow-left mr-2 text-emerald-600"></i>
                Kembali ke Daftar Anggota
            </button>

            <div class="flex space-x-3">
                <a href="<?= base_url('admin/edit-member/' . $anggota['id_anggota']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center shadow-sm">
                    <i class="fas fa-edit mr-2"></i>Edit Data Anggota
                </a>
            </div>
        </div>

        <!-- Header Profile Anggota -->
        <div class="bg-white rounded-2xl p-6 mb-6 card-shadow border border-emerald-100/60 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
            
            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6 text-center md:text-left">
                    
                    <!-- Foto Profil -->
                    <div class="w-24 h-24 rounded-2xl border-4 border-white shadow-md overflow-hidden bg-emerald-800 text-amber-300 flex items-center justify-center shrink-0">
                        <?php 
                            $fotoProfil = !empty($anggota['photo']) ? $anggota['photo'] : (!empty($anggota['foto_diri']) ? $anggota['foto_diri'] : null);
                        ?>
                        <?php if (!empty($fotoProfil) && file_exists(FCPATH . 'uploads/' . $fotoProfil)): ?>
                            <img src="<?= base_url('uploads/' . $fotoProfil) ?>" alt="Foto Diri" class="w-full h-full object-cover" />
                        <?php else: ?>
                            <span class="text-4xl font-black"><?= strtoupper(substr($anggota['nama_lengkap'] ?? 'A', 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Detail Nama & Info Singkat -->
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <h1 class="text-2xl font-black text-gray-800 tracking-tight"><?= esc($anggota['nama_lengkap'] ?? '-') ?></h1>
                            <?php
                                $status = strtolower($anggota['status'] ?? 'pending');
                                $statusBadge = match($status) {
                                    'aktif'    => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'ditolak'  => 'bg-rose-100 text-rose-800 border-rose-300',
                                    default    => 'bg-amber-100 text-amber-800 border-amber-300'
                                };
                            ?>
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase border <?= $statusBadge ?>">
                                <?= ucfirst($status) ?>
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-y-2 gap-x-6 text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-id-card text-emerald-600 mr-2"></i>
                                No. Anggota: <strong class="ml-1 text-gray-800"><?= esc($anggota['nomor_anggota'] ?? '-') ?></strong>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-fingerprint text-emerald-600 mr-2"></i>
                                NIK: <strong class="ml-1 text-gray-800"><?= esc($anggota['no_ktp'] ?? '-') ?></strong>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-emerald-600 mr-2"></i>
                                Bergabung: <strong class="ml-1 text-gray-800"><?= !empty($anggota['tanggal_daftar']) ? date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-' ?></strong>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-y-2 gap-x-6 text-xs text-gray-500 pt-1">
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                Username: <strong class="ml-1 text-gray-700"><?= esc($anggota['username'] ?? '-') ?></strong>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>
                                <?= esc($anggota['no_hp'] ?? '-') ?>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                <?= esc($anggota['email'] ?? '-') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-right w-full md:w-auto">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status Verifikasi</p>
                    <p class="text-sm font-bold text-emerald-700 mt-0.5"><i class="fas fa-shield-alt mr-1"></i> Terverifikasi Sistem</p>
                </div>
            </div>
        </div>

        <!-- 4 Stat Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="stats-card bg-white p-5 rounded-2xl card-shadow border-l-4 border-emerald-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Simpanan</p>
                    <h3 class="text-xl font-black text-gray-800 mt-1">Rp <?= number_format($totalSimpanan ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>

            <div class="stats-card bg-white p-5 rounded-2xl card-shadow border-l-4 border-blue-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pembiayaan</p>
                    <h3 class="text-xl font-black text-gray-800 mt-1">Rp <?= number_format($totalPembiayaan ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
            </div>

            <div class="stats-card bg-white p-5 rounded-2xl card-shadow border-l-4 border-amber-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sisa Angsuran</p>
                    <h3 class="text-xl font-black text-gray-800 mt-1"><?= $sisaAngsuran ?? 0 ?> Bulan</h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>

            <div class="stats-card bg-white p-5 rounded-2xl card-shadow border-l-4 border-teal-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimasi Bagi Hasil</p>
                    <h3 class="text-xl font-black text-emerald-600 mt-1">Rp <?= number_format($bagi_hasil ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
        </div>


        <!-- SEKSI 1: BIODATA, PEKERJAAN, REKENING BANK & DOKUMEN FOTO -->
        <div class="bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-emerald-800 to-teal-700 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-base flex items-center gap-2">
                    <i class="fas fa-user-check text-amber-300"></i> Detail Profil & Dokumen Berkas
                </h3>
                <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-medium">Sesuai Database</span>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Kolom 1: Data Pribadi & Kontak -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fas fa-address-card mr-1"></i> Data Pribadi & Kontak
                    </h4>
                    
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">NIK (No. KTP):</span>
                            <span class="font-bold text-gray-800"><?= esc($anggota['no_ktp'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Jenis Kelamin:</span>
                            <span class="font-semibold text-gray-800"><?= ucfirst(esc($anggota['jenis_kelamin'] ?? '-')) ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Pekerjaan:</span>
                            <span class="font-bold text-gray-800"><?= esc($anggota['pekerjaan'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Instansi / Perusahaan:</span>
                            <span class="font-semibold text-gray-800"><?= esc($anggota['instansi'] ?? '-') ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">No. HP Utama:</span>
                            <span class="font-bold text-emerald-700"><?= esc($anggota['no_hp'] ?? '-') ?></span>
                        </div>
                        <div class="pt-2">
                            <span class="text-gray-500 block mb-1">Alamat Lengkap:</span>
                            <p class="font-medium text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-100 leading-relaxed">
                                <?= esc($anggota['alamat'] ?? '-') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Informasi Rekening Bank -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fas fa-university mr-1"></i> Rekening Bank Pembayaran
                    </h4>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-4 rounded-xl border border-emerald-100 space-y-3">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-800 uppercase">Jenis / Nama Bank</span>
                            <p class="text-sm font-black text-emerald-900"><?= !empty($anggota['jenis_bank']) ? strtoupper(esc($anggota['jenis_bank'])) : 'Belum diisi' ?></p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-emerald-800 uppercase">Nomor Rekening</span>
                            <p class="text-base font-black tracking-wider text-gray-800"><?= esc($anggota['no_rek'] ?? '-') ?></p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-emerald-800 uppercase">Atas Nama Rekening</span>
                            <p class="text-xs font-bold text-gray-700"><?= esc($anggota['atasnama_rekening'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Preview Dokumen Berkas Upload (Foto KTP, Foto Diri, Foto Diri + KTP) -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fas fa-file-image mr-1"></i> Berkas Upload Diri & KTP
                    </h4>

                    <div class="grid grid-cols-3 gap-2">
                        <!-- Foto KTP -->
                        <div class="bg-gray-50 p-2 rounded-xl border border-gray-200 text-center flex flex-col justify-between">
                            <p class="text-[10px] font-bold text-gray-600 mb-1.5"><i class="fas fa-id-card text-emerald-600"></i> KTP</p>
                            <div class="h-24 bg-gray-200 rounded-lg overflow-hidden relative group border border-gray-300">
                                <?php if (!empty($anggota['foto_ktp']) && file_exists(FCPATH . 'uploads/' . $anggota['foto_ktp'])): ?>
                                    <img src="<?= base_url('uploads/' . $anggota['foto_ktp']) ?>" alt="KTP" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" onclick="openLightbox('<?= base_url('uploads/' . $anggota['foto_ktp']) ?>', 'Foto KTP')" />
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-1">
                                        <i class="fas fa-image text-xl mb-1"></i>
                                        <span class="text-[9px]">Kosong</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Foto Diri -->
                        <div class="bg-gray-50 p-2 rounded-xl border border-gray-200 text-center flex flex-col justify-between">
                            <p class="text-[10px] font-bold text-gray-600 mb-1.5"><i class="fas fa-user text-blue-600"></i> Pas Foto</p>
                            <div class="h-24 bg-gray-200 rounded-lg overflow-hidden relative group border border-gray-300">
                                <?php if (!empty($anggota['foto_diri']) && file_exists(FCPATH . 'uploads/' . $anggota['foto_diri'])): ?>
                                    <img src="<?= base_url('uploads/' . $anggota['foto_diri']) ?>" alt="Foto Diri" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" onclick="openLightbox('<?= base_url('uploads/' . $anggota['foto_diri']) ?>', 'Pas Foto Diri')" />
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-1">
                                        <i class="fas fa-image text-xl mb-1"></i>
                                        <span class="text-[9px]">Kosong</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Foto Diri + KTP -->
                        <div class="bg-gray-50 p-2 rounded-xl border border-gray-200 text-center flex flex-col justify-between">
                            <p class="text-[10px] font-bold text-gray-600 mb-1.5"><i class="fas fa-camera text-purple-600"></i> Selfie KTP</p>
                            <div class="h-24 bg-gray-200 rounded-lg overflow-hidden relative group border border-gray-300">
                                <?php if (!empty($anggota['foto_diri_ktp']) && file_exists(FCPATH . 'uploads/' . $anggota['foto_diri_ktp'])): ?>
                                    <img src="<?= base_url('uploads/' . $anggota['foto_diri_ktp']) ?>" alt="Selfie KTP" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" onclick="openLightbox('<?= base_url('uploads/' . $anggota['foto_diri_ktp']) ?>', 'Foto Diri Pegang KTP')" />
                                <?php else: ?>
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-1">
                                        <i class="fas fa-image text-xl mb-1"></i>
                                        <span class="text-[9px]">Kosong</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- SEKSI 2: TAB RIWAYAT KEUANGAN -->
        <div class="bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden">
            <!-- Tab Buttons -->
            <div class="bg-gray-50 p-3 border-b border-gray-200 flex flex-wrap gap-2">
                <button onclick="switchTab('transaksi')" id="tab-transaksi" class="tab-button tab-active px-5 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Riwayat Transaksi</span>
                </button>
                <button onclick="switchTab('pembiayaan')" id="tab-pembiayaan" class="tab-button tab-inactive px-5 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-credit-card"></i>
                    <span>Pembiayaan</span>
                </button>
                <button onclick="switchTab('simpanan')" id="tab-simpanan" class="tab-button tab-inactive px-5 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-piggy-bank"></i>
                    <span>Simpanan</span>
                </button>
                <button onclick="switchTab('angsuran')" id="tab-angsuran" class="tab-button tab-inactive px-5 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-calendar-check"></i>
                    <span>Jadwal Angsuran</span>
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <!-- 1. Transaksi Tab -->
                <div id="content-transaksi" class="tab-content">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-gray-800">Mutasi Transaksi Anggota</h4>
                    </div>

                    <div class="space-y-3" id="transaction-list">
                        <?php if (!empty($riwayat_transaksi)): ?>
                            <?php foreach ($riwayat_transaksi as $transaksi): ?>
                                <div class="transaction-item border border-gray-200 rounded-xl p-3.5 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="<?= ($transaksi['type'] ?? 'pemasukan') === 'pemasukan' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' ?> p-2.5 rounded-xl">
                                            <i class="<?= ($transaksi['type'] ?? 'pemasukan') === 'pemasukan' ? 'fas fa-arrow-down' : 'fas fa-arrow-up' ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-xs text-gray-800"><?= esc($transaksi['keterangan'] ?? 'Transaksi') ?></p>
                                            <p class="text-[11px] text-gray-400 mt-0.5"><?= !empty($transaksi['tanggal']) ? date('d M Y • H:i', strtotime($transaksi['tanggal'])) : '-' ?> WIB</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-extrabold text-xs <?= ($transaksi['type'] ?? 'pemasukan') === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' ?>">
                                            <?= ($transaksi['type'] ?? 'pemasukan') === 'pemasukan' ? '+' : '-' ?>Rp <?= number_format($transaksi['jumlah'] ?? 0, 0, ',', '.') ?>
                                        </p>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold mt-0.5"><?= ucfirst(esc($transaksi['status'] ?? 'berhasil')) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-receipt text-3xl mb-2 opacity-50"></i>
                                <p class="text-xs">Belum ada riwayat transaksi</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. Pembiayaan Tab -->
                <div id="content-pembiayaan" class="tab-content hidden">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Daftar Akad Pembiayaan</h4>
                    <div class="space-y-4">
                        <?php if (!empty($data_pembiayaan)): ?>
                            <?php foreach ($data_pembiayaan as $pembiayaan): ?>
                                <div class="border border-gray-200 rounded-xl p-5 hover:border-emerald-200 transition-colors">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h5 class="font-bold text-sm text-gray-800"><?= esc($pembiayaan['jenis_pembiayaan'] ?? 'Pembiayaan') ?></h5>
                                            <p class="text-xs text-gray-500">Akad: <?= esc($pembiayaan['akad'] ?? '-') ?> • No: <?= esc($pembiayaan['nomor_pembiayaan'] ?? '-') ?></p>
                                        </div>
                                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-[11px] font-bold">
                                            <?= ucfirst(esc($pembiayaan['status'] ?? 'aktif')) ?>
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs pt-2 border-t border-gray-100">
                                        <div>
                                            <span class="text-gray-400 block">Plafond</span>
                                            <span class="font-bold text-gray-800">Rp <?= number_format($pembiayaan['jumlah_pembiayaan'] ?? 0, 0, ',', '.') ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block">Jangka Waktu</span>
                                            <span class="font-bold text-gray-800"><?= esc($pembiayaan['jangka_waktu'] ?? 0) ?> Bulan</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block">Angsuran / Bln</span>
                                            <span class="font-bold text-gray-800">Rp <?= number_format($pembiayaan['angsuran_per_bulan'] ?? 0, 0, ',', '.') ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block">Sisa Tenor</span>
                                            <span class="font-bold text-amber-600"><?= esc($pembiayaan['sisa_tenor'] ?? 0) ?> Bulan</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-hand-holding-usd text-3xl mb-2 opacity-50"></i>
                                <p class="text-xs">Belum ada pembiayaan aktif</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 3. Simpanan Tab -->
                <div id="content-simpanan" class="tab-content hidden">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Rincian Simpanan Koperasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="border border-gray-200 p-4 rounded-xl">
                            <span class="text-xs font-bold text-gray-400 uppercase">Simpanan Pokok</span>
                            <p class="text-lg font-black text-gray-800 mt-1">Rp <?= number_format($simpanan_pokok['total'] ?? 0, 0, ',', '.') ?></p>
                            <p class="text-[11px] text-emerald-600 font-semibold mt-2"><i class="fas fa-check-circle"></i> Sesuai Ketentuan</p>
                        </div>
                        <div class="border border-gray-200 p-4 rounded-xl">
                            <span class="text-xs font-bold text-gray-400 uppercase">Simpanan Wajib</span>
                            <p class="text-lg font-black text-gray-800 mt-1">Rp <?= number_format($simpanan_wajib['total'] ?? 0, 0, ',', '.') ?></p>
                            <p class="text-[11px] text-gray-500 mt-2">Rutin Setiap Bulan</p>
                        </div>
                        <div class="border border-gray-200 p-4 rounded-xl">
                            <span class="text-xs font-bold text-gray-400 uppercase">Simpanan Sukarela</span>
                            <p class="text-lg font-black text-emerald-600 mt-1">Rp <?= number_format($simpanan_sukarela['total'] ?? 0, 0, ',', '.') ?></p>
                            <p class="text-[11px] text-blue-600 font-semibold mt-2"><i class="fas fa-university"></i> BISA DITARIK</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Angsuran Tab -->
                <div id="content-angsuran" class="tab-content hidden">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Jadwal Angsuran Mendatang</h4>
                    <?php if (!empty($jadwal_angsuran)): ?>
                        <?php foreach ($jadwal_angsuran as $pembiayaan): ?>
                            <div class="border border-gray-200 rounded-xl p-4 mb-4">
                                <h5 class="font-bold text-xs text-gray-800 mb-3"><?= esc($pembiayaan['nama_pembiayaan'] ?? 'Pembiayaan') ?></h5>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs">
                                        <thead class="bg-gray-50 text-gray-500">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Bulan Ke</th>
                                                <th class="px-3 py-2 text-left">Jatuh Tempo</th>
                                                <th class="px-3 py-2 text-left">Nominal</th>
                                                <th class="px-3 py-2 text-left">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <?php for ($i = 1; $i <= ($pembiayaan['sisa_tenor'] ?? 0); $i++): ?>
                                                <tr>
                                                    <td class="px-3 py-2 font-bold"><?= $i ?></td>
                                                    <td class="px-3 py-2"><?= date('d M Y', strtotime('+' . $i . ' months')) ?></td>
                                                    <td class="px-3 py-2 font-semibold">Rp <?= number_format($pembiayaan['angsuran_per_bulan'] ?? 0, 0, ',', '.') ?></td>
                                                    <td class="px-3 py-2">
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $i === 1 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' ?>">
                                                            <?= $i === 1 ? 'Jatuh Tempo Berikutnya' : 'Belum Jatuh Tempo' ?>
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
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-calendar-check text-3xl mb-2 opacity-50"></i>
                            <p class="text-xs">Tidak ada jadwal angsuran aktif</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- LIGHTBOX PREVIEW MODAL DOKUMEN -->
    <div id="lightboxModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4" onclick="closeLightbox()">
        <div class="relative max-w-3xl w-full bg-white rounded-2xl overflow-hidden p-2 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-3 border-b border-gray-100">
                <h3 id="lightboxTitle" class="text-xs font-bold text-gray-800">Preview Dokumen</h3>
                <button onclick="closeLightbox()" class="text-gray-400 hover:text-gray-600 text-lg w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-2 flex justify-center bg-black/5 min-h-[300px] items-center">
                <img id="lightboxImage" src="" alt="Dokumen Preview" class="max-h-[75vh] w-auto object-contain rounded-lg shadow-md" />
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));
            
            const tabs = document.querySelectorAll('.tab-button');
            tabs.forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('tab-inactive');
            });
            
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            const activeTab = document.getElementById(`tab-${tabName}`);
            activeTab.classList.remove('tab-inactive');
            activeTab.classList.add('tab-active');
        }

        function goBack() {
            window.history.back();
        }

        function openLightbox(imgSrc, title) {
            document.getElementById('lightboxImage').src = imgSrc;
            document.getElementById('lightboxTitle').innerText = title;
            document.getElementById('lightboxModal').classList.remove('hidden');
            document.getElementById('lightboxModal').classList.add('flex');
        }

        function closeLightbox() {
            document.getElementById('lightboxModal').classList.add('hidden');
            document.getElementById('lightboxModal').classList.remove('flex');
        }
    </script>
</body>
</html>