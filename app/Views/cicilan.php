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

        .status-proses {
            background: #fef3c7;
            color: #92400e;
        }

        .status-tertunda {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-terverifikasi {
            background: #dcfce7;
            color: #166534;
        }

        .status-ditolak {
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
            text-decoration: none;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 14px;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius-sm);
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Info Anggota */
        .info-anggota {
            background: var(--gradient-primary);
            color: white;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: var(--border-radius);
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
                flex-direction: column;
                gap: 1rem;
            }
            
            .cicilan-status {
                align-self: flex-start;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
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

      <div>
        <div class="header-name"><?= htmlspecialchars($anggota['nama_lengkap'] ?? '-') ?></div>
        <div style="font-size:12px;opacity:.9;">ID: <?= htmlspecialchars($anggota['nomor_anggota'] ?? '-') ?></div>
      </div>
    </div>
    <i data-lucide="bell" class="icon"></i>
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

        <!-- Info Anggota -->
        <div class="info-anggota">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; font-size: 16px;"><?= esc($anggota['nama_lengkap']) ?></h4>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">ID: <?= esc($anggota['nomor_anggota']) ?></p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 14px;">Total Semua Pinjaman</p>
                    <h3 style="margin: 5px 0 0 0;">Rp <?= number_format($summary['total_qard'] + $summary['total_murabahah'] + $summary['total_mudharabah'], 0, ',', '.') ?></h3>
                </div>
            </div>
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
                <div class="amount">
                    <?php if ($summary['jatuh_tempo_terdekat']): ?>
                        <?= date('d M', strtotime($summary['jatuh_tempo_terdekat'])) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
                <div class="label">
                    <?php if ($summary['jatuh_tempo_terdekat']): ?>
                        Cicilan berikutnya
                    <?php else: ?>
                        Tidak ada jatuh tempo
                    <?php endif; ?>
                </div>
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

        <!-- Total Masing-masing Pinjaman -->
        <div class="card-grid">
            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="trending-up"></i>
                    <h3>Total Qard Saya</h3>
                </div>
                <div class="amount">Rp <?= number_format($summary['total_qard'], 0, ',', '.') ?></div>
                <div class="label">Total pinjaman Qard aktif</div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="shopping-cart"></i>
                    <h3>Total Murabahah Saya</h3>
                </div>
                <div class="amount">Rp <?= number_format($summary['total_murabahah'], 0, ',', '.') ?></div>
                <div class="label">Total pinjaman Murabahah aktif</div>
            </div>

            <div class="info-card">
                <div class="card-header">
                    <i data-lucide="bar-chart-3"></i>
                    <h3>Total Mudharabah Saya</h3>
                </div>
                <div class="amount">Rp <?= number_format($summary['total_mudharabah'], 0, ',', '.') ?></div>
                <div class="label">Total pinjaman Mudharabah aktif</div>
            </div>
        </div>

       <!-- Di file cicilan.php, cari bagian Action Buttons dan ganti: -->

<!-- Action Buttons -->
<div class="action-buttons">
    <button class="btn btn-primary" onclick="location.href='<?= base_url('anggota/pinjaman') ?>'">
        <i data-lucide="plus"></i>
        Ajukan Pinjaman Baru
    </button>
    <button class="btn btn-outline" onclick="location.href='<?= base_url('anggota/riwayat-cicilan') ?>'">
        <i data-lucide="history"></i>
        Riwayat Cicilan
    </button>
</div>

        <!-- ✅ PEMBAYARAN MENUNGGU VERIFIKASI -->
        <?php if (!empty($data['pembayaran_pending'])): ?>
            <h4 style="margin: 2rem 0 1rem 0; color: var(--dark);">Pembayaran Menunggu Verifikasi</h4>
            <div class="cicilan-list">
                <?php foreach ($data['pembayaran_pending'] as $pending): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title">
                                Pembayaran <?= is_array($pending) ? $pending['jenis_pinjaman'] : $pending->jenis_pinjaman ?> - Angsuran Ke-<?= is_array($pending) ? $pending['angsuran_ke'] : $pending->angsuran_ke ?>
                            </div>
                            <div class="cicilan-detail">
                                <span>Jumlah: Rp <?= number_format(is_array($pending) ? $pending['jumlah_bayar'] : $pending->jumlah_bayar, 0, ',', '.') ?></span>
                                <span>Tanggal: <?= date('d M Y', strtotime(is_array($pending) ? $pending['tanggal_bayar'] : $pending->tanggal_bayar)) ?></span>
                                <?php 
                                $bukti_bayar = is_array($pending) ? $pending['bukti_bayar'] : $pending->bukti_bayar;
                                if ($bukti_bayar): ?>
                                    <span>
                                        <a href="<?= base_url('uploads/bukti_bayar/' . $bukti_bayar) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                                            Lihat Bukti
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 12px; color: var(--gray);">
                                Diajukan: <?= date('d M Y H:i', strtotime(is_array($pending) ? $pending['created_at'] : $pending->created_at)) ?>
                            </div>
                        </div>
                        <div class="cicilan-status status-pending">Menunggu Verifikasi</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Daftar Cicilan Aktif -->
        <?php if (!empty($data['pinjaman_aktif'])): ?>
            <h4 style="margin: 2rem 0 1rem 0; color: var(--dark);">Cicilan Aktif</h4>
            <div class="cicilan-list">
                <?php foreach ($data['pinjaman_aktif'] as $pinjaman): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title"><?= $pinjaman->nama_pinjaman ?></div>
                            <div class="cicilan-detail">
                                <span>Angsuran: <?= $pinjaman->angsuran_berjalan ?? 0 ?>/<?= $pinjaman->tenor ?></span>
                                <span>Jatuh Tempo: <?= date('d M Y', strtotime($pinjaman->jatuh_tempo_berikutnya)) ?></span>
                                <span>Total: Rp <?= number_format($pinjaman->total_pinjaman, 0, ',', '.') ?></span>
                            </div>
                            <div class="cicilan-amount">Rp <?= number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') ?> / bulan</div>
                            <div style="font-size: 12px; color: var(--gray); margin-top: 5px;">
                                Pengajuan: <?= date('d M Y', strtotime($pinjaman->tanggal_pinjaman)) ?>
                                | Terbayar: Rp <?= number_format($pinjaman->total_terbayar, 0, ',', '.') ?>
                            </div>
                            
                            <!-- Tombol Bayar -->
                            <?php if ($pinjaman->bisa_bayar): ?>
                                <div class="action-buttons">
                                    <button class="btn btn-primary btn-sm" 
                                            onclick="bayarCicilan('<?= $pinjaman->jenis ?>', <?= $pinjaman->id ?>, <?= ($pinjaman->angsuran_berjalan ?? 0) + 1 ?>, <?= $pinjaman->angsuran_per_bulan ?>)">
                                        <i data-lucide="credit-card"></i>
                                        Bayar Angsuran Ke-<?= ($pinjaman->angsuran_berjalan ?? 0) + 1 ?>
                                    </button>
                                </div>
                            <?php else: ?>
                                <div style="margin-top: 10px; font-size: 14px; color: var(--success);">
                                    <i data-lucide="check-circle"></i> Semua angsuran telah dibayar
                                </div>
                            <?php endif; ?>
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
        <?php if (!empty($data['pinjaman_lunas'])): ?>
            <h4 style="margin: 2rem 0 1rem 0; color: var(--dark);">Cicilan Selesai</h4>
            <div class="cicilan-list">
                <?php foreach ($data['pinjaman_lunas'] as $pinjaman): ?>
                    <div class="cicilan-item">
                        <div class="cicilan-info">
                            <div class="cicilan-title">Pinjaman <?= $pinjaman->jenis ?></div>
                            <div class="cicilan-detail">
                                <span>Lunas: <?= date('d M Y', strtotime($pinjaman->tanggal_lunas)) ?></span>
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

    <!-- Modal Bayar Cicilan -->
    <div id="modalBayar" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="credit-card"></i>
                Bayar Cicilan
            </h3>
            <form id="formBayar" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="jenis_pinjaman" id="jenis_pinjaman">
                <input type="hidden" name="id_pinjaman" id="id_pinjaman">
                <input type="hidden" name="angsuran_ke" id="angsuran_ke">
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Jumlah Bayar</label>
                    <!-- PERBAIKAN: Ganti type number dengan text untuk menghindari validasi browser -->
                    <input type="text" 
                           name="jumlah_bayar" 
                           id="jumlah_bayar" 
                           class="form-input" 
                           placeholder="Contoh: 500000 atau 500.000"
                           required
                           oninput="formatAngka(this)">
                    <small style="color: var(--gray);">Masukkan jumlah tanpa titik atau dengan titik sebagai pemisah ribuan</small>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bukti Pembayaran</label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-input" accept="image/*,.pdf" required>
                    <small style="color: var(--gray);">Upload bukti transfer (JPG, PNG, PDF) - Maks 2MB</small>
                </div>
                
                <div style="background: #f0fdf9; padding: 1rem; border-radius: var(--border-radius-sm); margin-bottom: 1rem;">
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 14px;">Informasi Pembayaran:</h4>
                    <p style="margin: 0; font-size: 13px; color: var(--gray);" id="infoPembayaran"></p>
                </div>
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="tutupModal()" class="btn btn-outline" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i data-lucide="send"></i>
                        Ajukan Pembayaran
                    </button>
                </div>
            </form>
        </div>
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

<?php if ($showTenorModal): ?>
<style>
  /* === STYLE MODAL TENOR (background berhenti di atas bottom nav) === */
  #tenorOverlay {
    position: fixed;
    top: 0; left: 0; right: 0;
    bottom: 80px; /* kasih jarak sesuai tinggi bottom nav */
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1999; /* di bawah bottom nav */
    pointer-events: auto;
  }

  .bottom-nav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 2001 !important;
  }

  #tenorBox {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    padding: 2rem;
    max-width: 450px;
    width: 90%;
    text-align: center;
    animation: fadeIn 0.3s ease;
    z-index: 2000;
  }

  #tenorBox h2 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
  }

  #tenorBox p {
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    color: #4b5563;
    margin-bottom: 1.5rem;
    line-height: 1.6;
  }

  #tenorBox input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 0.75rem;
    text-align: center;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 1.25rem;
    transition: border 0.2s ease;
  }

  #tenorBox input:focus {
    border-color: #0d9488;
    outline: none;
  }

  #tenorBox button {
    width: 100%;
    background: linear-gradient(135deg, #16a34a, #0d9488);
    border: none;
    border-radius: 12px;
    padding: 0.75rem;
    color: #fff;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s;
  }

  #tenorBox button:hover {
    background: linear-gradient(135deg,  #16a34a, #0d9488);
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<!-- Overlay + Modal -->
<div id="tenorOverlay">
  <div id="tenorBox">
    <h2>Pilih Tenor Simpanan Pokok</h2>
    <p><strong>Apa itu Simpanan Pokok?</strong><br>
      Simpanan Pokok adalah setoran awal wajib sebesar <b>Rp 500.000</b> 
      yang harus dibayarkan oleh setiap anggota baru saat pertama kali menjadi anggota koperasi.<br><br>
      Simpanan ini <b>wajib</b> dan <b>tidak bisa diambil kembali</b> selama masih menjadi anggota.
      Kamu bisa mencicil hingga maksimal <b>10x cicilan</b>.
    </p>

    <form action="<?= base_url('anggota/cicilan/setTenor') ?>" method="post">
      <input type="number" name="tenor" min="1" max="10" placeholder="Pilih tenor (1–10 bulan)" required>
      <button type="submit">Simpan Tenor</button>
    </form>
  </div>
</div>

<script>
  document.body.style.overflow = 'hidden';

  const tenorInput = document.querySelector('input[name="tenor"]');
  tenorInput.addEventListener('input', () => {
    if (tenorInput.value > 10) {
      tenorInput.value = 10;
      alert('Tenor maksimal adalah 10 bulan.');
    }
  });
</script>

<?php endif; ?>

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

        // Fungsi untuk format angka input
        function formatAngka(input) {
            // Hapus karakter non-digit
            let value = input.value.replace(/[^\d]/g, '');
            
            // Format dengan titik sebagai pemisah ribuan
            if (value.length > 3) {
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            input.value = value;
        }

        // Fungsi untuk buka modal bayar
        function bayarCicilan(jenis, idPinjaman, angsuranKe, jumlah) {
            document.getElementById('jenis_pinjaman').value = jenis;
            document.getElementById('id_pinjaman').value = idPinjaman;
            document.getElementById('angsuran_ke').value = angsuranKe;
            
            // Format jumlah untuk display
            const jumlahFormatted = jumlah.toLocaleString('id-ID');
            document.getElementById('infoPembayaran').textContent = 
                `${jenis} - Angsuran Ke-${angsuranKe} - Rp ${jumlahFormatted}`;
            
            // Set nilai default untuk input jumlah bayar (tanpa format)
            document.getElementById('jumlah_bayar').value = jumlah;
            
            document.getElementById('modalBayar').style.display = 'flex';
        }

        // Fungsi tutup modal
        function tutupModal() {
            document.getElementById('modalBayar').style.display = 'none';
            document.getElementById('formBayar').reset();
        }

        // Handle form bayar
        document.getElementById('formBayar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Format ulang jumlah bayar sebelum submit (hapus titik)
            const jumlahBayarInput = document.getElementById('jumlah_bayar');
            let jumlahBayarValue = jumlahBayarInput.value.replace(/\./g, '');
            
            // Validasi jumlah bayar
            if (!jumlahBayarValue || isNaN(jumlahBayarValue) || parseFloat(jumlahBayarValue) <= 0) {
                alert('❌ Jumlah bayar harus lebih dari 0');
                return;
            }
            
            // Update nilai input dengan angka tanpa format
            jumlahBayarInput.value = jumlahBayarValue;
            
            const formData = new FormData(this);
            
            // Tambahkan CSRF token secara manual
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="<?= csrf_token() ?>"]')?.value;
            
            if (csrfToken) {
                formData.append('<?= csrf_token() ?>', csrfToken);
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button dan show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i> Mengajukan...';
            
            // URL yang benar
            const url = '<?= base_url('anggota/cicilan/bayar') ?>';
            console.log('Mengirim request ke:', url);
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    tutupModal();
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('❌ Terjadi kesalahan jaringan: ' + error.message);
            })
            .finally(() => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                lucide.createIcons();
            });
        });

        // Tutup modal ketika klik di luar
        document.getElementById('modalBayar').addEventListener('click', function(e) {
            if (e.target === this) {
                tutupModal();
            }
        });

        // Validasi file size
        document.getElementById('bukti_bayar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) { // 2MB
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
            }
        });
    </script>
</body>
</html>