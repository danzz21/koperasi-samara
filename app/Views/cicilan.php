<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?> - Koperasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* CSS styles tetap sama seperti sebelumnya */
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

        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        .header-actions {
            display: flex;
            gap: 15px;
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

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .section-title i {
            color: var(--primary);
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

        .summary-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .info-card .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .info-card .card-header i {
            font-size: 20px;
            color: var(--primary);
        }

        .info-card .card-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
        }

        .info-card .amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .info-card .label {
            font-size: 14px;
            color: var(--gray);
        }

        /* Cicilan List */
        .cicilan-list {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .cicilan-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: between;
            align-items: center;
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
            margin-bottom: 0.25rem;
        }

        .cicilan-detail {
            font-size: 14px;
            color: var(--gray);
            display: flex;
            gap: 1rem;
        }

        .cicilan-amount {
            font-weight: 700;
            font-size: 16px;
        }

        .cicilan-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-lunas {
            background: #dcfce7;
            color: #166534;
        }

        .status-proses {
            background: #fef3c7;
            color: #92400e;
        }

        .status-tertunda {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
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
            position: relative;
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

        /* Responsive */
        @media (max-width: 480px) {
            .header {
                padding: 1.2rem 1.2rem 0.8rem;
            }
            
            .section-title, .section-subtitle, .card-container {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }
            
            .card-grid {
                grid-template-columns: 1fr;
            }
            
            .cicilan-item {
                padding: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
<header class="header">
    <div class="profile">
        <?php 
        // Gunakan foto_diri jika ada, jika tidak gunakan foto, jika tidak ada gunakan default
        $foto_anggota = null;
        if (!empty($anggota['foto_diri']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['foto_diri'])) {
            $foto_anggota = $anggota['foto_diri'];
        } elseif (!empty($anggota['foto']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['foto'])) {
            $foto_anggota = $anggota['foto'];
        }
        ?>
        
        <?php if ($foto_anggota): ?>
            <img id="preview" src="<?= base_url('uploads/profile/' . $foto_anggota) ?>" alt="Foto Profil">
        <?php else: ?>
            <?php 
                // Pastikan $anggota ada dan memiliki nama_lengkap
                $nama_lengkap = isset($anggota['nama_lengkap']) ? $anggota['nama_lengkap'] : 'Anggota';
                $nomor_anggota = isset($anggota['nomor_anggota']) ? $anggota['nomor_anggota'] : 'ANG00001';
                
                $firstLetter = strtoupper(substr($nama_lengkap, 0, 1));
                $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
                $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
            ?>
            <div class="profile-avatar" style="background:<?= $bgColor ?>;">
                <?= $firstLetter ?>
            </div>
        <?php endif; ?>
        <div class="profile-info">
            <div class="header-name"><?= esc(isset($anggota['nama_lengkap']) ? $anggota['nama_lengkap'] : 'Anggota') ?></div>
            <div class="header-id">ID: <?= esc(isset($anggota['nomor_anggota']) ? $anggota['nomor_anggota'] : 'ANG00001') ?></div>
        </div>
    </div>
    <div class="header-actions">
        <div style="position: relative;">
            <i data-lucide="bell" class="icon"></i>
            <div class="notification-badge">3</div>
        </div>
        <i data-lucide="settings" class="icon"></i>
    </div>
</header>

    <!-- Section Title -->
    <h3 class="section-title">
        <i data-lucide="calendar-check"></i>
        Manajemen Cicilan
    </h3>
    <p class="section-subtitle">Kelola dan pantau cicilan pinjaman Anda</p>

    <!-- Card Container -->
    <div class="card-container">
        <!-- Summary Card -->
        <div class="summary-card">
            <h3>Ringkasan Cicilan</h3>
            <p>Total cicilan aktif dan riwayat pembayaran Anda</p>
        </div>

        <!-- Info Cards -->
        <div class="card-grid">
            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="clock"></i>
                    <h3>Cicilan Berjalan</h3>
                </div>
                <div class="amount"><?= $summary['total_pinjaman_aktif'] ?> Pinjaman</div>
                <div class="label">Total cicilan aktif</div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="calendar"></i>
                    <h3>Jatuh Tempo</h3>
                </div>
                <div class="amount"><?= $summary['jatuh_tempo_terdekat'] ? date('d M Y', strtotime($summary['jatuh_tempo_terdekat'])) : '-' ?></div>
                <div class="label">Cicilan berikutnya</div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="dollar-sign"></i>
                    <h3>Total Cicilan</h3>
                </div>
                <div class="amount">Rp <?= number_format($summary['total_angsuran_bulanan'], 0, ',', '.') ?></div>
                <div class="label">Per bulan</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-primary" onclick="location.href='<?= base_url('anggota/pengajuan-pinjaman') ?>'">
                <i data-lucide="plus"></i>
                Ajukan Pinjaman Baru
            </button>
            <button class="btn btn-outline" onclick="location.href='<?= base_url('anggota/riwayat-cicilan') ?>'">
                <i data-lucide="history"></i>
                Riwayat Cicilan
            </button>
        </div>

        <!-- Daftar Cicilan Aktif -->
        <?php if (!empty($pinjaman_aktif)): ?>
            <h4 style="margin: 2rem 0 1rem 0; color: var(--dark);">Cicilan Aktif</h4>
            <div class="cicilan-list">
                <?php foreach ($pinjaman_aktif as $pinjaman): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title">Pinjaman <?= $pinjaman->jenis ?></div>
                            <div class="cicilan-detail">
                                <span>Angsuran: <?= $pinjaman->angsuran_berjalan ?? 0 ?>/<?= $pinjaman->tenor ?></span>
                                <span>Jatuh Tempo: <?= date('d M Y', strtotime('+1 month')) ?></span>
                            </div>
                            <div class="cicilan-amount">Rp <?= number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') ?> / bulan</div>
                        </div>
                        <div class="cicilan-status status-proses">Berjalan</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 2rem; color: var(--gray);">
                <i data-lucide="calendar-x" style="width: 48px; height: 48px; margin-bottom: 1rem;"></i>
                <p>Tidak ada cicilan aktif</p>
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
                                <span>Lunas: <?= date('d M Y', strtotime($pinjaman->updated_at)) ?></span>
                                <span>Durasi: <?= $pinjaman->tenor ?> bulan</span>
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
            const cards = document.querySelectorAll('.info-card, .cicilan-item');
            
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

        // Fungsi untuk bayar cicilan
        function bayarCicilan(jenis, pinjamanId, angsuranKe) {
            if (confirm('Apakah Anda yakin ingin membayar cicilan ini?')) {
                fetch('<?= base_url('anggota/cicilan/bayar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        jenis_pinjaman: jenis,
                        pinjaman_id: pinjamanId,
                        angsuran_ke: angsuranKe
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Pembayaran berhasil!');
                        location.reload();
                    } else {
                        alert('Gagal melakukan pembayaran: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat melakukan pembayaran');
                });
            }
        }
    </script>
</body>
</html>