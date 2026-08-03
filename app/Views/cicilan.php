<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?> - Koperasi</title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-light: #34d399;
            --primary-dark: #059669;
            --secondary: #06b6d4;
            --secondary-light: #22d3ee;
            --accent: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #cbd5e1;
            --border-radius: 20px;
            --border-radius-sm: 12px;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 10px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f0fdf9 0%, #f0fdf4 100%);
            color: var(--dark);
            min-height: 100vh;
            padding-bottom: 90px;
            line-height: 1.5;
        }

        /* Header Mobile */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.2rem 0.8rem;
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
            border-radius: 0 0 20px 20px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .header-name {
            font-weight: 700;
            font-size: 16px;
            letter-spacing: -0.3px;
        }

        .icon {
            width: 22px;
            height: 22px;
            color: white;
            cursor: pointer;
        }

        /* Section Title & Subtitle */
        .section-title {
            padding: 0.8rem 1.2rem 0.1rem;
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i { color: var(--primary); }

        .section-subtitle {
            padding: 0 1.2rem 0.8rem;
            font-size: 13px;
            color: var(--gray);
        }

        /* Filter Tab Interaktif Horizontal */
        .tab-filter-container {
            display: flex;
            gap: 8px;
            padding: 0 1.2rem 0.8rem;
            overflow-x: auto;
            scrollbar-width: none;
        }
        
        .tab-filter-container::-webkit-scrollbar { display: none; }

        .tab-filter-btn {
            padding: 6px 14px;
            border-radius: 20px;
            background: white;
            border: 1px solid var(--gray-light);
            color: var(--gray);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .tab-filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25);
        }

        /* Container Card Utama */
        .card-container { padding: 0 1.2rem 1.2rem; }

        /* Banner Ringkasan Sisa Pinjaman (Diperbarui) */
        .info-sisa-banner {
            background: var(--gradient-primary);
            color: white;
            padding: 1.1rem;
            margin-bottom: 1rem;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.2);
            text-align: center;
        }

        /* HORIZONTAL SWIPE CARDS */
        .card-grid-slider {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 6px;
            margin-bottom: 1rem;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .card-grid-slider::-webkit-scrollbar { display: none; }

        .info-card-compact {
            min-width: 82%;
            max-width: 85%;
            flex: 0 0 auto;
            scroll-snap-align: start;
            background: white;
            border-radius: 16px;
            padding: 0.9rem 1.1rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
        }

        .card-qard { border-left: 4px solid var(--primary); }
        .card-murabahah { border-left: 4px solid var(--secondary); }
        .card-mudharabah { border-left: 4px solid var(--accent); }

        /* MINI STATS GRID 3 KOLOM SEJAJAR */
        .monitoring-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 1rem;
        }

        .mini-stat-card {
            background: white;
            border-radius: 12px;
            padding: 10px 6px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid var(--gray-light);
        }

        .mini-stat-card h5 {
            font-size: 11px;
            color: var(--gray);
            margin-bottom: 2px;
            font-weight: 600;
        }

        .mini-stat-card .val {
            font-size: 13px;
            font-weight: 800;
            color: var(--dark);
        }

        /* Cicilan List Items */
        .cicilan-list {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.2rem;
        }

        .cicilan-item {
            padding: 1.2rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: var(--transition);
        }

        .cicilan-item:last-child { border-bottom: none; }

        .cicilan-info { flex: 1; }
        .cicilan-title { font-weight: 700; color: var(--dark); margin-bottom: 0.3rem; font-size: 14px; }

        .cicilan-detail {
            font-size: 12px;
            color: var(--gray);
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 0.4rem;
        }

        .cicilan-amount {
            font-weight: 700;
            font-size: 15px;
            color: var(--primary-dark);
            margin-bottom: 0.4rem;
        }

        .cicilan-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-lunas { background: #dcfce7; color: #166534; }
        .status-proses { background: #fef3c7; color: #92400e; }
        .status-pending { background: #dbeafe; color: #1e40af; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.6rem;
        }

        .btn {
            padding: 0.65rem 1.2rem;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-outline { background: transparent; border: 1.5px solid var(--primary); color: var(--primary); }
        .btn-outline:hover { background: var(--primary); color: white; }
        .btn-sm { padding: 0.45rem 0.9rem; font-size: 12px; }

        /* Quick Select Nominal Buttons */
        .quick-nominal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-top: 6px;
        }

        .btn-quick-nominal {
            padding: 7px 8px;
            border: 1px solid var(--primary-light);
            background: #f0fdf9;
            color: var(--primary-dark);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .btn-quick-nominal:hover { background: var(--primary); color: white; }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.08);
            z-index: 100;
            border-radius: 20px 20px 0 0;
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray);
            padding: 4px 10px;
            border-radius: 12px;
        }

        .bottom-nav a.active {
            color: var(--primary);
            background: rgba(16, 185, 129, 0.1);
        }

        .bottom-nav a i { font-size: 18px; margin-bottom: 2px; }
        .bottom-nav a p { font-size: 11px; font-weight: 600; }

        /* Modal Layout */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 480px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-input {
            width: 100%;
            padding: 0.65rem 0.8rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius-sm);
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .cicilan-item { flex-direction: column; gap: 0.8rem; }
            .action-buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .quick-nominal-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Header Mobile -->
    <header class="header">
        <div class="profile">
            <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
                <img id="preview" src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
            <?php else: ?>
                <?php 
                    $firstLetter = strtoupper(substr($anggota['nama_lengkap'] ?? 'A', 0, 1));
                    $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
                    $bgColor = $colors[crc32($anggota['nomor_anggota'] ?? '0') % count($colors)];
                ?>
                <div class="profile-avatar" style="background:<?= $bgColor ?>;">
                    <?= $firstLetter ?>
                </div>
            <?php endif; ?>

            <div>
                <div class="header-name"><?= htmlspecialchars($anggota['nama_lengkap'] ?? '-') ?></div>
                <div style="font-size:11px;opacity:.9;">ID: <?= htmlspecialchars($anggota['nomor_anggota'] ?? '-') ?></div>
            </div>
        </div>
        <i data-lucide="bell" class="icon"></i>
    </header>

    <!-- Judul Halaman -->
    <h3 class="section-title">
        <i data-lucide="calendar-check"></i>
        Manajemen Cicilan
    </h3>
    <p class="section-subtitle">Kelola dan pelajari riwayat kewajiban simpan pinjam Anda</p>

    <!-- Filter Tab Interaktif -->
    <div class="tab-filter-container">
        <button class="tab-filter-btn active" onclick="filterCicilanTab('all')">Semua Transaksi</button>
        <button class="tab-filter-btn" onclick="filterCicilanTab('aktif')">Cicilan Aktif</button>
        <button class="tab-filter-btn" onclick="filterCicilanTab('pending')">Verifikasi Admin</button>
        <button class="tab-filter-btn" onclick="filterCicilanTab('lunas')">Cicilan Lunas</button>
    </div>

    <!-- Main Container -->
    <div class="card-container">

        <!-- BANNER TOTAL SISA PINJAMAN (NAMA & NO ANGGOTA DIHAPUS) -->
        <div class="info-sisa-banner">
            <p style="margin:0; font-size:12px; opacity:0.9; text-transform: uppercase; letter-spacing: 0.5px;">Total Sisa Kewajiban Pinjaman</p>
            <h3 style="margin:4px 0 0 0; font-size:22px; font-weight:800;">
                Rp <?= number_format(($summary['total_qard'] ?? 0) + ($summary['total_murabahah'] ?? 0) + ($summary['total_mudharabah'] ?? 0), 0, ',', '.') ?>
            </h3>
        </div>

        <!-- 1. SLIDER AKAD MENDATAR -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
            <h4 style="color: var(--dark); font-size: 13px; font-weight: 700;">Ringkasan Akad</h4>
            <small style="color: var(--primary); font-weight: 600; font-size: 11px;">Geser &rarr;</small>
        </div>

        <div class="card-grid-slider">
            <!-- Total Qard -->
            <div class="info-card-compact card-qard">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--dark);">Total Qard Saya</span>
                    <span style="font-size: 10px; background: #dcfce7; color: #15803d; padding: 1px 7px; border-radius: 8px; font-weight: 700;">Aktif</span>
                </div>
                <div style="font-size: 18px; font-weight: 800; color: var(--dark);">Rp <?= number_format($summary['total_qard'] ?? 0, 0, ',', '.') ?></div>
                <div style="font-size: 11px; color: var(--gray); margin-top: 2px;">Total pinjaman Qard berjalan</div>
            </div>

            <!-- Total Murabahah -->
            <div class="info-card-compact card-murabahah">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--dark);">Total Murabahah Saya</span>
                    <span style="font-size: 10px; background: #e0f2fe; color: #0369a1; padding: 1px 7px; border-radius: 8px; font-weight: 700;">Aktif</span>
                </div>
                <div style="font-size: 18px; font-weight: 800; color: var(--dark);">Rp <?= number_format($summary['total_murabahah'] ?? 0, 0, ',', '.') ?></div>
                <div style="font-size: 11px; color: var(--gray); margin-top: 2px;">Total pembiayaan jual beli</div>
            </div>

            <!-- Total Mudharabah -->
            <div class="info-card-compact card-mudharabah">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--dark);">Total Mudharabah Saya</span>
                    <span style="font-size: 10px; background: #f3e8ff; color: #6b21a8; padding: 1px 7px; border-radius: 8px; font-weight: 700;">Aktif</span>
                </div>
                <div style="font-size: 18px; font-weight: 800; color: var(--dark);">Rp <?= number_format($summary['total_mudharabah'] ?? 0, 0, ',', '.') ?></div>
                <div style="font-size: 11px; color: var(--gray); margin-top: 2px;">Total pembiayaan bagi hasil</div>
            </div>
        </div>

        <!-- 2. MINI STATS MONITORING TAGIHAN -->
        <div class="monitoring-mini-grid">
            <div class="mini-stat-card">
                <h5>Cicilan Aktif</h5>
                <div class="val" style="color: var(--primary);"><?= $summary['total_pinjaman_aktif'] ?? 0 ?> Unit</div>
            </div>
            <div class="mini-stat-card">
                <h5>Jatuh Tempo</h5>
                <div class="val" style="color: var(--warning); font-size: 11px;">
                    <?= !empty($summary['jatuh_tempo_terdekat']) ? date('d M Y', strtotime($summary['jatuh_tempo_terdekat'])) : '-' ?>
                </div>
            </div>
            <div class="mini-stat-card">
                <h5>Standar/Bln</h5>
                <div class="val" style="font-size: 11px;">Rp <?= number_format(($summary['total_angsuran_bulanan'] ?? 0) / 1000, 0) ?>k</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons" style="margin-bottom: 1.5rem;">
            <button class="btn btn-primary" onclick="location.href='<?= base_url('anggota/pinjaman') ?>'" style="flex:1;">
                <i data-lucide="plus" style="width:16px;"></i> Pinjaman Baru
            </button>
            <button class="btn btn-outline" onclick="location.href='<?= base_url('anggota/riwayat-cicilan') ?>'" style="flex:1;">
                <i data-lucide="history" style="width:16px;"></i> Riwayat Transaksi
            </button>
        </div>

        <!-- SECTION: Pembayaran Menunggu Verifikasi Admin -->
        <?php if (!empty($data['pembayaran_pending'])): ?>
            <div class="cicilan-group-item group-pending">
                <h4 style="margin: 0.8rem 0 0.5rem 0; color: var(--dark); font-size: 14px;">Menunggu Verifikasi Admin</h4>
                <div class="cicilan-list">
                    <?php foreach ($data['pembayaran_pending'] as $pending): ?>
                        <?php 
                            $p_jenis = is_array($pending) ? $pending['jenis_pinjaman'] : $pending->jenis_pinjaman;
                            $p_angsuran = is_array($pending) ? $pending['angsuran_ke'] : $pending->angsuran_ke;
                            $p_jumlah = is_array($pending) ? $pending['jumlah_bayar'] : $pending->jumlah_bayar;
                            $p_tgl = is_array($pending) ? $pending['tanggal_bayar'] : $pending->tanggal_bayar;
                            $p_bukti = is_array($pending) ? $pending['bukti_bayar'] : $pending->bukti_bayar;
                        ?>
                        <div class="cicilan-item">
                            <div class="cicilan-info">
                                <div class="cicilan-title">Pembayaran <?= esc($p_jenis) ?> (Angsuran Ke-<?= $p_angsuran ?>)</div>
                                <div class="cicilan-detail">
                                    <span>Nominal: <strong>Rp <?= number_format($p_jumlah, 0, ',', '.') ?></strong></span>
                                    <span>Tgl: <?= date('d M Y', strtotime($p_tgl)) ?></span>
                                    <?php if ($p_bukti): ?>
                                        <span><a href="<?= base_url('uploads/bukti_bayar/' . $p_bukti) ?>" target="_blank" style="color: var(--primary); font-weight: 600;">Lihat Bukti</a></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="cicilan-status status-pending">Diproses Admin</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION: Daftar Cicilan Aktif -->
        <?php if (!empty($data['pinjaman_aktif'])): ?>
            <div class="cicilan-group-item group-aktif">
                <h4 style="margin: 0.8rem 0 0.5rem 0; color: var(--dark); font-size: 14px;">Daftar Cicilan Aktif</h4>
                <div class="cicilan-list">
                    <?php foreach ($data['pinjaman_aktif'] as $pinjaman): ?>
                        <?php 
                            $sisa_kewajiban = ($pinjaman->total_pinjaman ?? 0) - ($pinjaman->total_terbayar ?? 0);
                        ?>
                        <div class="cicilan-item">
                            <div class="cicilan-info">
                                <div class="cicilan-title"><?= esc($pinjaman->nama_pinjaman) ?></div>
                                <div class="cicilan-detail">
                                    <span>Progres: <strong><?= $pinjaman->angsuran_berjalan ?? 0 ?>/<?= $pinjaman->tenor ?> Bln</strong></span>
                                    <span>Jatuh Tempo: <?= date('d M Y', strtotime($pinjaman->jatuh_tempo_berikutnya)) ?></span>
                                </div>
                                <div class="cicilan-amount">
                                    Rp <?= number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') ?> <small style="font-weight: normal; color: var(--gray); font-size: 11px;">/ bulan</small>
                                </div>
                                <div style="font-size: 11px; color: var(--gray); margin-top: 2px;">
                                    Sisa Kewajiban: <strong style="color: var(--dark);">Rp <?= number_format($sisa_kewajiban, 0, ',', '.') ?></strong>
                                </div>
                                
                                <?php if ($pinjaman->bisa_bayar): ?>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="bayarCicilanFleksibel('<?= $pinjaman->jenis ?>', <?= $pinjaman->id ?>, <?= ($pinjaman->angsuran_berjalan ?? 0) + 1 ?>, <?= $pinjaman->angsuran_per_bulan ?>, <?= $sisa_kewajiban ?>)">
                                            <i data-lucide="credit-card" style="width:14px;"></i> Bayar Fleksibel
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div style="margin-top: 6px; font-size: 12px; color: var(--success); font-weight: 600;">
                                        <i data-lucide="check-circle" style="width: 14px;"></i> Angsuran lunas / diproses
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="cicilan-status status-proses">Berjalan</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 1.5rem; color: var(--gray); background: white; border-radius: 16px; box-shadow: var(--shadow);">
                <i data-lucide="calendar-x" style="width: 36px; height: 36px; margin-bottom: 0.3rem; opacity: 0.5;"></i>
                <p style="font-size: 13px;">Tidak ada cicilan aktif saat ini</p>
            </div>
        <?php endif; ?>

        <!-- SECTION: Cicilan Selesai -->
        <?php if (!empty($data['pinjaman_lunas'])): ?>
            <div class="cicilan-group-item group-lunas" style="margin-top: 1rem;">
                <h4 style="margin: 0.8rem 0 0.5rem 0; color: var(--dark); font-size: 14px;">Cicilan Selesai (Lunas)</h4>
                <div class="cicilan-list">
                    <?php foreach ($data['pinjaman_lunas'] as $pinjaman): ?>
                        <div class="cicilan-item">
                            <div class="cicilan-info">
                                <div class="cicilan-title">Pinjaman <?= esc($pinjaman->jenis) ?></div>
                                <div class="cicilan-detail">
                                    <span>Tgl Lunas: <?= date('d M Y', strtotime($pinjaman->tanggal_lunas)) ?></span>
                                    <span>Tenor: <?= $pinjaman->tenor ?> Bln</span>
                                </div>
                            </div>
                            <div class="cicilan-status status-lunas">Lunas</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Modal Bayar Cicilan Fleksibel -->
    <div id="modalBayar" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; color: var(--dark); font-size: 16px;">
                <i data-lucide="credit-card"></i> Bayar Cicilan Fleksibel
            </h3>
            <form id="formBayar" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="jenis_pinjaman" id="jenis_pinjaman">
                <input type="hidden" name="id_pinjaman" id="id_pinjaman">
                <input type="hidden" name="angsuran_ke" id="angsuran_ke">
                
                <div style="margin-bottom: 0.8rem;">
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 12px;">Pilihan Nominal Cepat:</label>
                    <div class="quick-nominal-grid">
                        <button type="button" class="btn-quick-nominal" id="btn1Bulan" onclick="setNominalFleksibel(this.dataset.val)">1 Bulan</button>
                        <button type="button" class="btn-quick-nominal" id="btn2Bulan" onclick="setNominalFleksibel(this.dataset.val)">2 Bulan</button>
                        <button type="button" class="btn-quick-nominal" id="btnPelunasan" onclick="setNominalFleksibel(this.dataset.val)">Pelunasan Sisa</button>
                    </div>
                </div>

                <div style="margin-bottom: 0.8rem;">
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 12px;">Jumlah Bayar (Rp)</label>
                    <input type="text" name="jumlah_bayar" id="jumlah_bayar" class="form-input" placeholder="Contoh: 500.000" required oninput="hitungSimulasiBayar(this)">
                    <small style="color: var(--gray); font-size: 11px;">Bebas mengetik nominal sesuai kemampuan pembayaran Anda.</small>
                </div>
                
                <div style="margin-bottom: 0.8rem;">
                    <label style="display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 12px;">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-input" accept="image/*,.pdf" required>
                </div>
                
                <div style="background: #f0fdf9; padding: 0.8rem; border-radius: var(--border-radius-sm); margin-bottom: 1rem; border: 1px solid var(--primary-light);">
                    <h4 style="margin: 0 0 0.2rem 0; font-size: 12px; color: var(--primary-dark); font-weight: 700;">Simulasi Pembayaran:</h4>
                    <p style="margin: 0; font-size: 11px; color: var(--dark);" id="simulasiText">Masukkan nominal untuk estimasi otomatis.</p>
                </div>
                
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" onclick="tutupModal()" class="btn btn-outline" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i data-lucide="send" style="width:14px;"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="<?= base_url('anggota/dashboard') ?>"><i data-lucide="home"></i><p>Beranda</p></a>
        <a href="<?= base_url('anggota/simpanan') ?>"><i data-lucide="wallet"></i><p>Simpan</p></a>
        <a href="<?= base_url('anggota/pinjaman') ?>"><i data-lucide="hand-coins"></i><p>Pinjam</p></a>
        <a href="<?= base_url('anggota/cicilan') ?>" class="active"><i data-lucide="calendar-check"></i><p>Cicilan</p></a>
        <a href="<?= base_url('anggota/profil') ?>"><i data-lucide="user"></i><p>Profil</p></a>
    </nav>

    <script>
        lucide.createIcons();

        let currentAngsuranPerBulan = 0;
        let currentSisaKewajiban = 0;

        function filterCicilanTab(status) {
            document.querySelectorAll('.tab-filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const gAktif = document.querySelector('.group-aktif');
            const gPending = document.querySelector('.group-pending');
            const gLunas = document.querySelector('.group-lunas');

            if (status === 'all') {
                if (gAktif) gAktif.style.display = 'block';
                if (gPending) gPending.style.display = 'block';
                if (gLunas) gLunas.style.display = 'block';
            } else if (status === 'aktif') {
                if (gAktif) gAktif.style.display = 'block';
                if (gPending) gPending.style.display = 'none';
                if (gLunas) gLunas.style.display = 'none';
            } else if (status === 'pending') {
                if (gAktif) gAktif.style.display = 'none';
                if (gPending) gPending.style.display = 'block';
                if (gLunas) gLunas.style.display = 'none';
            } else if (status === 'lunas') {
                if (gAktif) gAktif.style.display = 'none';
                if (gPending) gPending.style.display = 'none';
                if (gLunas) gLunas.style.display = 'block';
            }
        }

        function bayarCicilanFleksibel(jenis, idPinjaman, angsuranKe, standarBulanan, sisaKewajiban) {
            document.getElementById('jenis_pinjaman').value = jenis;
            document.getElementById('id_pinjaman').value = idPinjaman;
            document.getElementById('angsuran_ke').value = angsuranKe;
            
            currentAngsuranPerBulan = standarBulanan;
            currentSisaKewajiban = sisaKewajiban;

            document.getElementById('btn1Bulan').dataset.val = standarBulanan;
            document.getElementById('btn2Bulan').dataset.val = standarBulanan * 2;
            document.getElementById('btnPelunasan').dataset.val = sisaKewajiban;

            setNominalFleksibel(standarBulanan);

            document.getElementById('modalBayar').style.display = 'flex';
        }

        function setNominalFleksibel(nominal) {
            const input = document.getElementById('jumlah_bayar');
            input.value = parseInt(nominal).toLocaleString('id-ID');
            hitungSimulasiBayar(input);
        }

        function hitungSimulasiBayar(input) {
            let rawValue = input.value.replace(/[^\d]/g, '');
            if (rawValue.length > 3) {
                input.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                input.value = rawValue;
            }

            let nominal = parseFloat(rawValue) || 0;
            const simEl = document.getElementById('simulasiText');

            if (nominal <= 0) {
                simEl.innerHTML = 'Masukkan nominal pembayaran valid.';
                return;
            }

            let porsiBulan = (nominal / currentAngsuranPerBulan).toFixed(1);
            let sisaBaru = currentSisaKewajiban - nominal;
            if (sisaBaru < 0) sisaBaru = 0;

            simEl.innerHTML = `Setara dengan <strong>${porsiBulan}x Angsuran Bulanan</strong>.<br>Sisa Kewajiban Setelah Bayar: <strong>Rp ${sisaBaru.toLocaleString('id-ID')}</strong>`;
        }

        function tutupModal() {
            document.getElementById('modalBayar').style.display = 'none';
            document.getElementById('formBayar').reset();
        }

        document.getElementById('formBayar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jumlahInput = document.getElementById('jumlah_bayar');
            let rawVal = jumlahInput.value.replace(/\./g, '');
            
            if (!rawVal || parseFloat(rawVal) <= 0) {
                alert('Jumlah bayar tidak boleh kosong!');
                return;
            }
            
            jumlahInput.value = rawVal;
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            fetch('<?= base_url('anggota/cicilan/bayar') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    tutupModal();
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(err => alert('❌ Error: ' + err.message))
            .finally(() => submitBtn.disabled = false);
        });

        document.getElementById('modalBayar').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    </script>
</body>
</html>