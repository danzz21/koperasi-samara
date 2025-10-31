<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?> - Koperasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Gunakan CSS yang sama dari halaman cicilan */
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
            line-height: 1.6;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.5rem 1rem;
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
            gap: 14px;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .header-name {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.3px;
        }

        .header-id {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .icon {
            width: 24px;
            height: 24px;
            color: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .icon:hover {
            transform: scale(1.1);
        }

        /* Section Title */
        .section-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-subtitle {
            padding: 0 1.5rem 1.5rem;
            font-size: 15px;
            color: var(--gray);
            margin-top: -5px;
        }

        /* Card Container */
        .card-container {
            padding: 0 1.5rem 1.5rem;
        }

        /* Cicilan List */
        .cicilan-list {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .cicilan-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: var(--transition);
        }

        .cicilan-item:last-child {
            border-bottom: none;
        }

        .cicilan-item:hover {
            background: #f8fafc;
        }

        .cicilan-info {
            flex: 1;
        }

        .cicilan-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .cicilan-detail {
            font-size: 14px;
            color: var(--gray);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .cicilan-amount {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 0.5rem;
        }

        .cicilan-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-lunas {
            background: #dcfce7;
            color: #166534;
        }

        .status-terverifikasi {
            background: #dcfce7;
            color: #166534;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
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
            padding: 15px 0;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.08);
            z-index: 100;
            border-radius: 25px 25px 0 0;
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray);
            transition: var(--transition);
            padding: 8px 12px;
            border-radius: 16px;
        }

        .bottom-nav a.active {
            color: var(--primary);
            background: rgba(16, 185, 129, 0.1);
        }

        .bottom-nav a i {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .bottom-nav a p {
            font-size: 12px;
            font-weight: 600;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            margin: 1rem 1.5rem;
            transition: var(--transition);
        }

        .back-button:hover {
            background: var(--primary-dark);
            transform: translateX(-5px);
        }

        @media (max-width: 480px) {
            .header {
                padding: 1.2rem 1.2rem 0.8rem;
            }
            
            .section-title, .section-subtitle, .card-container {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }
            
            .cicilan-item {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .cicilan-status {
                align-self: flex-start;
            }
            
            .cicilan-detail {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="profile">
            <?php 
            $firstLetter = strtoupper(substr($anggota['nama_lengkap'] ?? 'A', 0, 1));
            $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
            $bgColor = $colors[crc32($anggota['nomor_anggota'] ?? 'default') % count($colors)];
            ?>
            <div class="profile-avatar" style="background:<?= $bgColor ?>;">
                <?= $firstLetter ?>
            </div>
            <div class="profile-info">
                <div class="header-name"><?= esc($anggota['nama_lengkap'] ?? 'Anggota') ?></div>
                <div class="header-id">ID: <?= esc($anggota['nomor_anggota'] ?? 'ANG00001') ?></div>
            </div>
        </div>
        <i data-lucide="history" class="icon"></i>
    </header>

    <!-- Back Button -->
    <a href="<?= base_url('anggota/cicilan') ?>" class="back-button">
        <i data-lucide="arrow-left"></i>
        Kembali ke Cicilan
    </a>

    <!-- Section Title -->
    <h3 class="section-title">
        <i data-lucide="file-text"></i>
        Riwayat Cicilan
    </h3>
    <p class="section-subtitle">Lihat riwayat pembayaran dan cicilan yang sudah selesai</p>

    <!-- Card Container -->
    <div class="card-container">
        <!-- Riwayat Pembayaran -->
        <?php if (!empty($riwayat_pembayaran)): ?>
            <h4 style="margin: 1rem 0; color: var(--dark);">Riwayat Pembayaran</h4>
            <div class="cicilan-list">
                <?php foreach ($riwayat_pembayaran as $riwayat): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title">
                                Pembayaran <?= is_array($riwayat) ? $riwayat['jenis_pinjaman'] : $riwayat->jenis_pinjaman ?> - Angsuran Ke-<?= is_array($riwayat) ? $riwayat['angsuran_ke'] : $riwayat->angsuran_ke ?>
                            </div>
                            <div class="cicilan-detail">
                                <span>Jumlah: Rp <?= number_format(is_array($riwayat) ? $riwayat['jumlah_bayar'] : $riwayat->jumlah_bayar, 0, ',', '.') ?></span>
                                <span>Tanggal: <?= date('d M Y', strtotime(is_array($riwayat) ? $riwayat['tanggal_bayar'] : $riwayat->tanggal_bayar)) ?></span>
                                <?php 
                                $bukti_bayar = is_array($riwayat) ? $riwayat['bukti_bayar'] : $riwayat->bukti_bayar;
                                if ($bukti_bayar): ?>
                                    <span>
                                        <a href="<?= base_url('uploads/bukti_bayar/' . $bukti_bayar) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                                            Lihat Bukti
                                        </a>
                                    </span>
                                <?php endif; ?>
                                <?php 
                                $status = is_array($riwayat) ? $riwayat['status'] : $riwayat->status;
                                $alasan_penolakan = is_array($riwayat) ? ($riwayat['alasan_penolakan'] ?? '') : ($riwayat->alasan_penolakan ?? '');
                                if ($status === 'ditolak' && !empty($alasan_penolakan)): ?>
                                    <span style="color: var(--danger);">
                                        Alasan: <?= $alasan_penolakan ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 12px; color: var(--gray);">
                                Diajukan: <?= date('d M Y H:i', strtotime(is_array($riwayat) ? $riwayat['created_at'] : $riwayat->created_at)) ?>
                                <?php if ($status !== 'pending'): ?>
                                    | Diproses: <?= date('d M Y H:i', strtotime(is_array($riwayat) ? $riwayat['created_at'] : $riwayat->created_at)) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="cicilan-status <?= $status === 'diverifikasi' ? 'status-terverifikasi' : 'status-ditolak' ?>">
                            <?= $status === 'diverifikasi' ? 'Terverifikasi' : 'Ditolak' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem; color: var(--gray);">
                <i data-lucide="file-x" style="width: 64px; height: 64px; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Belum Ada Riwayat Pembayaran</h4>
                <p>Riwayat pembayaran akan muncul di sini setelah pembayaran Anda diverifikasi.</p>
            </div>
        <?php endif; ?>

        <!-- Cicilan Lunas -->
        <?php if (!empty($pinjaman_lunas)): ?>
            <h4 style="margin: 2rem 0 1rem 0; color: var(--dark);">Cicilan Selesai</h4>
            <div class="cicilan-list">
                <?php foreach ($pinjaman_lunas as $pinjaman): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title">Pinjaman <?= $pinjaman->jenis ?></div>
                            <div class="cicilan-detail">
                                <span>Lunas: <?= date('d M Y', strtotime($pinjaman->tanggal_lunas)) ?></span>
                                <span>Durasi: <?= $pinjaman->tenor ?> bulan</span>
                                <span>Total: Rp <?= number_format($pinjaman->total_pinjaman, 0, ',', '.') ?></span>
                            </div>
                            <div class="cicilan-amount">Rp <?= number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') ?> / bulan</div>
                        </div>
                        <div class="cicilan-status status-lunas">Lunas</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="<?= base_url('anggota/dashboard') ?>">
            <i data-lucide="home"></i>
            <p>Beranda</p>
        </a>
        <a href="<?= base_url('anggota/simpanan') ?>">
            <i data-lucide="wallet"></i>
            <p>Simpan</p>
        </a>
        <a href="<?= base_url('anggota/pinjaman') ?>">
            <i data-lucide="hand-coins"></i>
            <p>Pinjam</p>
        </a>
        <a href="<?= base_url('anggota/cicilan') ?>" class="active">
            <i data-lucide="calendar-check"></i>
            <p>Cicilan</p>
        </a>
        <a href="<?= base_url('anggota/profil') ?>">
            <i data-lucide="user"></i>
            <p>Profil</p>
        </a>
    </nav>

    <script>
        lucide.createIcons();
        
        // Animasi untuk card
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.cicilan-item');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>