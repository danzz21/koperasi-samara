<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aplikasi Koperasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #06b6d4;
            --dark: #0f172a;
            --gray: #64748b;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            --border-radius: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #f8fafc;
            color: var(--dark);
            min-height: 100vh;
            padding-bottom: 90px;
        }

        /* Header Utama Koperasi */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.1rem 1.25rem;
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile img, .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .profile-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 17px;
        }

        .header-name {
            font-weight: 700;
            font-size: 15px;
            line-height: 1.2;
        }

        .header-id {
            font-size: 11px;
            opacity: 0.9;
        }

        /* Container Card */
        .summary-container {
            padding: 1.25rem 1rem 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .card-box {
            border-radius: var(--border-radius);
            padding: 1.15rem;
            color: white;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-box:active {
            transform: scale(0.98);
        }

        /* Warna Card 1: Emerald Green (Simpanan) */
        .card-simpanan {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        /* Warna Card 2: Teal Cyan (Pinjaman - Senada dengan Header) */
        .card-pinjaman {
            background: linear-gradient(135deg, #06b6d4 0%, #0369a1 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .card-title-text {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .card-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .main-amount {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0,0,0,0.12);
        }

        /* Breakdown Transparan (Glassmorphism Effect) */
        .breakdown-grid {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            text-align: center;
        }

        .breakdown-item-box {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(6px);
            padding: 7px 4px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .b-label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            display: block;
            margin-bottom: 2px;
        }

        .b-val {
            font-size: 11.5px;
            font-weight: 800;
            color: #ffffff;
            white-space: nowrap;
        }

        .sisa-tagihan-box {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            padding: 7px 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Judul Layanan Koperasi */
        .section-title {
            padding: 0.85rem 1rem 0.35rem;
            font-size: 16px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--primary);
            font-size: 18px;
        }

        .menu-container {
            padding: 0 1rem 1.5rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .menu-item {
            background: white;
            border-radius: 16px;
            padding: 1.1rem 0.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #10b981 0%, #06b6d4 100%);
        }

        .menu-item:active {
            transform: scale(0.95);
        }

        .menu-item a {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .menu-item i {
            font-size: 24px;
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-item p {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--dark);
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
            z-index: 100;
            border-radius: 18px 18px 0 0;
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray);
            padding: 4px 10px;
            border-radius: 10px;
        }

        .bottom-nav a.active {
            color: var(--primary);
            background: rgba(16, 185, 129, 0.1);
        }

        .bottom-nav a p {
            font-size: 10px;
            font-weight: 600;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- Header Profil -->
    <header class="header">
        <div class="profile">
            <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
                <img src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
            <?php else: ?>
                <?php 
                    $firstLetter = strtoupper(substr($anggota['nama_lengkap'] ?? 'A', 0, 1));
                    $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
                    $bgColor = $colors[crc32($anggota['nomor_anggota'] ?? '1') % count($colors)];
                ?>
                <div class="profile-avatar" style="background:<?= $bgColor ?>;">
                    <?= $firstLetter ?>
                </div>
            <?php endif; ?>
            <div class="profile-info">
                <div class="header-name"><?= esc($anggota['nama_lengkap'] ?? 'Anggota') ?></div>
                <div class="header-id">ID: <?= esc($anggota['nomor_anggota'] ?? '-') ?></div>
            </div>
        </div>
        <div class="header-actions">
            <i data-lucide="bell" style="width:20px; height:20px; cursor:pointer;"></i>
        </div>
    </header>

    <!-- CARD RINGKASAN SALDO & PINJAMAN -->
    <div class="summary-container">

        <!-- CARD 1: TOTAL SIMPANAN (EMERALD GREEN) -->
        <div class="card-box card-simpanan">
            <div class="card-header-flex">
                <span class="card-title-text">
                    <i class="fas fa-wallet"></i> Total Simpanan
                </span>
                <div class="card-icon-circle">
                    <i class="fas fa-sack-dollar"></i>
                </div>
            </div>
            
            <div class="main-amount">Rp <?= number_format($total_saldo, 0, ',', '.') ?></div>
            
            <div class="breakdown-grid">
                <div class="breakdown-item-box">
                    <span class="b-label">Pokok</span>
                    <span class="b-val">Rp <?= number_format($sim_pokok, 0, ',', '.') ?></span>
                </div>
                <div class="breakdown-item-box">
                    <span class="b-label">Wajib</span>
                    <span class="b-val">Rp <?= number_format($sim_wajib, 0, ',', '.') ?></span>
                </div>
                <div class="breakdown-item-box">
                    <span class="b-label">Sukarela</span>
                    <span class="b-val">Rp <?= number_format($sim_sukarela, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- CARD 2: TOTAL PINJAMAN (TEAL CYAN SENADA HEADER) -->
        <div class="card-box card-pinjaman">
            <div class="card-header-flex">
                <span class="card-title-text">
                    <i class="fas fa-hand-holding-usd"></i> Total Pinjaman
                </span>
                <div class="card-icon-circle">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>

            <div class="main-amount">Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></div>

            <div class="breakdown-grid">
                <div class="breakdown-item-box">
                    <span class="b-label">Qard</span>
                    <span class="b-val">Rp <?= number_format($qard_total, 0, ',', '.') ?></span>
                </div>
                <div class="breakdown-item-box">
                    <span class="b-label">Murabahah</span>
                    <span class="b-val">Rp <?= number_format($muro_total, 0, ',', '.') ?></span>
                </div>
                <div class="breakdown-item-box">
                    <span class="b-label">Mudharabah</span>
                    <span class="b-val">Rp <?= number_format($mudh_total, 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="sisa-tagihan-box">
                <span style="opacity: 0.92;">Sisa Tagihan Belum Terbayar:</span>
                <span style="font-weight: 800; color: #fef08a;">Rp <?= number_format($sisa_kewajiban, 0, ',', '.') ?></span>
            </div>
        </div>

    </div>

    <!-- LAYANAN KOPERASI -->
    <h3 class="section-title">
        <i data-lucide="grid"></i>
        Layanan Koperasi
    </h3>
    
    <div class="menu-container">
        <div class="menu-grid">
            <div class="menu-item">
                <a href="<?= base_url('anggota/sim_pokok') ?>">
                    <i class="fas fa-landmark"></i>
                    <p>Sim. Pokok</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url('anggota/sim_wajib') ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Sim. Wajib</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url('anggota/sim_sukarela') ?>">
                    <i class="fas fa-gift"></i>
                    <p>Sim. Sukarela</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url('anggota/pin_alqordh') ?>">
                    <i class="fas fa-handshake"></i>
                    <p>Al-Qordh</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url('anggota/pin_murobahah') ?>">
                    <i class="fas fa-file-contract"></i>
                    <p>Murobahah</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url('anggota/pin_mudhorobah') ?>">
                    <i class="fas fa-chart-line"></i>
                    <p>Mudhorobah</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="<?= base_url('anggota/dashboard') ?>" class="active">
            <i data-lucide="home" style="width:18px; height:18px;"></i>
            <p>Beranda</p>
        </a>
        <a href="<?= base_url('anggota/simpanan') ?>">
            <i data-lucide="wallet" style="width:18px; height:18px;"></i>
            <p>Simpan</p>
        </a>
        <a href="<?= base_url('anggota/pinjaman') ?>">
            <i data-lucide="hand-coins" style="width:18px; height:18px;"></i>
            <p>Pinjam</p>
        </a>
        <a href="<?= base_url('anggota/cicilan') ?>">
            <i data-lucide="calendar-check" style="width:18px; height:18px;"></i>
            <p>Cicilan</p>
        </a>
        <a href="<?= base_url('anggota/profil') ?>">
            <i data-lucide="user" style="width:18px; height:18px;"></i>
            <p>Profil</p>
        </a>
    </nav>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>