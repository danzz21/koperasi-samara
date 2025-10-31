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

        /* Saldo Container */
        .saldo-container {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            margin-top: 0;
            flex-wrap: wrap;
        }

        .saldo-card {
            flex: 1;
            background: white;
            border-radius: var(--border-radius);
            padding: 1.8rem 1.5rem;
            position: relative;
            box-shadow: var(--shadow);
            min-width: 170px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .saldo-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .saldo-card.simpanan {
            background: var(--gradient-primary);
            color: white;
        }

        .saldo-card.pinjaman {
            background: var(--gradient-secondary);
            color: white;
        }

        .saldo-card .card-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .saldo-card .card-icon i {
            font-size: 24px;
        }

        .saldo-card .label {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .saldo-card .amount {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
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

        /* Menu Container */
        .menu-container {
            padding: 0 1.5rem 2rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .menu-item {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem 1rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient-primary);
        }

        .menu-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .menu-item a {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .menu-item i {
            font-size: 32px;
            color: var(--primary);
            transition: var(--transition);
        }

        .menu-item:hover i {
            transform: scale(1.2);
            color: var(--secondary);
        }

        .menu-item p {
            font-size: 16px;
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
            .saldo-container {
                flex-direction: column;
            }
            
            .saldo-card {
                min-width: 100%;
            }
            
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .header {
                padding: 1.2rem 1.2rem 0.8rem;
            }
            
            .saldo-container, .section-title, .section-subtitle, .menu-container {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }
            
            .menu-item {
                padding: 1.5rem 0.8rem;
                min-height: 120px;
            }
            
            .menu-item i {
                font-size: 28px;
            }
            
            .menu-item p {
                font-size: 14px;
            }
        }

        @media (min-width: 481px) and (max-width: 767px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }
            
            .menu-item {
                padding: 1.8rem 1rem;
            }
        }

        @media (min-width: 768px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 25px;
            }
            
            .saldo-card .amount {
                font-size: 28px;
            }
            
            .menu-item {
                padding: 2.5rem 1.5rem;
                min-height: 160px;
            }
            
            .menu-item i {
                font-size: 36px;
            }
            
            .menu-item p {
                font-size: 18px;
            }
        }

        @media (min-width: 1024px) {
            .menu-container {
                max-width: 1000px;
                margin: 0 auto;
            }
            
            .menu-grid {
                gap: 30px;
            }
            
            .menu-item {
                padding: 3rem 2rem;
                min-height: 180px;
            }
            
            .menu-item i {
                font-size: 40px;
            }
            
            .menu-item p {
                font-size: 20px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="profile">
            <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
                <img id="preview" src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
            <?php else: ?>
                <?php 
                    $firstLetter = strtoupper(substr($anggota['nama_lengkap'], 0, 1));
                    $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
                    $bgColor = $colors[crc32($anggota['nomor_anggota']) % count($colors)];
                ?>
                <div class="profile-avatar" style="background:<?= $bgColor ?>;">
                    <?= $firstLetter ?>
                </div>
            <?php endif; ?>
            <div class="profile-info">
                <div class="header-name"><?= esc($anggota['nama_lengkap']) ?></div>
                <div class="header-id">ID: <?= esc($anggota['nomor_anggota']) ?></div>
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

    <!-- Card Saldo & Pinjaman -->
    <div class="saldo-container">
        <!-- Card Simpanan -->
        <section class="saldo-card simpanan fade-in">
            <div class="card-icon">
                <i class="fas fa-sack-dollar"></i>
            </div>
            <p class="label">
                <i data-lucide="trending-up" width="16" height="16"></i>
                Total Simpanan
            </p>
            <h2 class="amount">Rp <?= number_format($total_saldo, 0, ',', '.') ?></h2>
        </section>

        <!-- Card Pinjaman -->
        <section class="saldo-card pinjaman fade-in delay-1">
            <div class="card-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <p class="label">
                <i data-lucide="trending-down" width="16" height="16"></i>
                Total Pinjaman
            </p>
            <h2 class="amount">Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></h2>
        </section>
    </div>

    <!-- Menu Section -->
    <h3 class="section-title">
        <i data-lucide="grid"></i>
        Layanan Koperasi
    </h3>
    <p class="section-subtitle">Kelola simpanan dan pinjaman Anda dengan mudah</p>
    
    <div class="menu-container">
        <div class="menu-grid">
            <div class="menu-item fade-in">
                <a href="<?= base_url('anggota/sim_pokok') ?>">
                    <i class="fas fa-landmark"></i>
                    <p>Simpanan Pokok</p>
                </a>
            </div>
            <div class="menu-item fade-in delay-1">
                <a href="<?= base_url('anggota/sim_wajib') ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Simpanan Wajib</p>
                </a>
            </div>
            <div class="menu-item fade-in delay-2">
                <a href="<?= base_url('anggota/sim_sukarela') ?>">
                    <i class="fas fa-gift"></i>
                    <p>Simpanan Sukarela</p>
                </a>
            </div>
            <div class="menu-item fade-in delay-3">
                <a href="<?= base_url('anggota/pin_alqordh') ?>">
                    <i class="fas fa-handshake"></i>
                    <p>Al-Qordhu</p>
                </a>
            </div>
            <div class="menu-item fade-in delay-4">
                <a href="<?= base_url('anggota/pin_murobahah') ?>">
                    <i class="fas fa-file-contract"></i>
                    <p>Murobahah</p>
                </a>
            </div>
            <div class="menu-item fade-in delay-5">
                <a href="<?= base_url('anggota/pin_mudhorobah') ?>">
                    <i class="fas fa-chart-line"></i>
                    <p>Mudhorobah</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Nav -->
<nav class="bottom-nav">
    <a href="<?= base_url('anggota/dashboard') ?>" class="active">
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
    <!-- TAMBAHKAN MENU CICILAN DI SINI -->
    <a href="<?= base_url('anggota/cicilan') ?>">
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
        
        // Tambahkan efek interaktif pada menu item
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // Animasi scroll
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            fadeElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>