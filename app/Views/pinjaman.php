<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Pinjaman</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
      --dark: #0f172a;
      --light: #f8fafc;
      --gray: #64748b;
      --gray-light: #e2e8f0;
      --border-radius: 20px;
      --border-radius-sm: 12px;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
      --shadow-lg: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
      line-height: 1.6;
    }

    /* HEADER UTAMA KOPERASI */
    .header-pinjam {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.1rem 1.25rem;
      background: var(--gradient-primary);
      color: white;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
      position: sticky;
      top: 0;
      z-index: 100;
      border-radius: 0 0 20px 20px;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header-left img,
    .profile-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.8);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .profile-avatar {
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 17px;
    }

    .profile-info {
      display: flex;
      flex-direction: column;
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

    .header-actions {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .notification-wrapper {
      position: relative;
    }

    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: var(--danger);
      color: white;
      border-radius: 50%;
      width: 16px;
      height: 16px;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    .icon {
      width: 20px;
      height: 20px;
      color: white;
      cursor: pointer;
      transition: var(--transition);
    }

    .icon:hover {
      transform: scale(1.1);
    }

    .page-title-container {
      padding: 1.25rem 1rem 0.5rem;
      text-align: center;
    }

    .page-title {
      font-size: 20px;
      font-weight: 800;
      color: var(--dark);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .page-subtitle {
      font-size: 12px;
      color: var(--gray);
      margin-top: 2px;
    }

    .alert {
      padding: 0.9rem 1.2rem;
      margin: 0.75rem 1rem;
      border-radius: 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      animation: slideDown 0.3s ease-out;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .alert-warning {
      background: #fffbeb;
      color: #b45309;
      border-left: 4px solid var(--warning);
    }

    .alert-success {
      background: #ecfdf5;
      color: #047857;
      border-left: 4px solid var(--success);
    }

    .alert-danger {
      background: #fef2f2;
      color: #b91c1c;
      border-left: 4px solid var(--danger);
    }

    .alert-info {
      background: #f0f9ff;
      color: #0369a1;
      border-left: 4px solid var(--accent);
    }

    .alert-icon {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      margin-top: 2px;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .success-message {
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      border-radius: var(--border-radius);
      padding: 1.25rem 1rem;
      margin: 1rem;
      text-align: center;
      box-shadow: var(--shadow);
      animation: slideDown 0.5s ease-out;
    }

    .success-icon {
      font-size: 2.5rem;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
    }

    .success-title {
      font-size: 1.1rem;
      font-weight: 800;
      color: #064e3b;
      margin-bottom: 0.25rem;
    }

    .success-desc {
      color: #047857;
      font-size: 0.85rem;
      margin-bottom: 0.75rem;
    }

    .tab-akad {
      display: flex;
      background: #e2e8f0;
      padding: 4px;
      margin: 0.5rem 1rem 1.25rem;
      border-radius: 16px;
      gap: 4px;
    }

    .tab-akad button {
      flex: 1;
      padding: 0.75rem 0.5rem;
      border: none;
      background: transparent;
      color: var(--gray);
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: var(--transition);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .tab-akad button.active {
      color: var(--primary-dark);
      background: white;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .tab-content {
      display: none;
      padding: 0 1rem;
    }

    .tab-content.active {
      display: block;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      padding: 1.25rem;
      margin-bottom: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
      border: 1px solid #f1f5f9;
      transition: var(--transition);
      position: relative;
    }

    .card.disabled {
      opacity: 0.75;
      pointer-events: none;
    }

    .card.disabled::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(2px);
      border-radius: var(--border-radius);
      z-index: 10;
    }

    .akad-info-box {
      background: #f0fdf4;
      border: 1px dashed #bbf7d0;
      border-radius: var(--border-radius-sm);
      padding: 0.85rem 1rem;
      margin-bottom: 1.25rem;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .akad-info-box i {
      font-size: 20px;
      color: var(--primary);
    }

    .akad-info-text {
      font-size: 12px;
      color: #166534;
      line-height: 1.4;
    }

    .card-title {
      font-size: 16px;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-input {
      margin-bottom: 1.1rem;
    }

    .form-input label {
      display: block;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 0.4rem;
      font-size: 13px;
    }

    .input-rupiah {
      display: flex;
      align-items: center;
      border: 1.5px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      overflow: hidden;
      transition: var(--transition);
      background: white;
    }

    .input-rupiah:focus-within {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .input-rupiah span {
      padding: 0.75rem 0.9rem;
      background: #f8fafc;
      color: var(--gray);
      font-weight: 700;
      font-size: 14px;
      border-right: 1px solid var(--gray-light);
    }

    .input-rupiah input {
      flex: 1;
      padding: 0.75rem 0.9rem;
      border: none;
      outline: none;
      font-size: 15px;
      font-weight: 700;
      color: var(--dark);
    }

    .form-input select,
    .form-input textarea {
      width: 100%;
      padding: 0.75rem 0.9rem;
      border: 1.5px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      font-size: 14px;
      font-weight: 600;
      color: var(--dark);
      background: white;
      transition: var(--transition);
    }

    .form-input select:focus,
    .form-input textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .character-count {
      font-size: 11px;
      color: var(--gray);
      text-align: right;
      margin-top: 4px;
      font-weight: 600;
    }

    .note {
      font-size: 11px;
      color: var(--gray);
      margin-top: 4px;
      font-style: italic;
    }

    .calc-summary-card {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      border-radius: 16px;
      padding: 1rem 1.15rem;
      color: white;
      margin: 1.25rem 0;
      box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
    }

    .calc-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12);
      padding-bottom: 8px;
      margin-bottom: 10px;
    }

    .calc-title {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #94a3b8;
      font-weight: 700;
    }

    .calc-badge {
      background: rgba(16, 185, 129, 0.2);
      color: #34d399;
      font-size: 10px;
      padding: 2px 8px;
      border-radius: 20px;
      font-weight: 700;
      border: 1px solid rgba(52, 211, 153, 0.3);
    }

    .calc-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 6px;
      font-size: 12px;
      color: #cbd5e1;
    }

    .calc-row.total-row {
      margin-top: 8px;
      padding-top: 8px;
      border-top: 1px dashed rgba(255, 255, 255, 0.15);
      color: white;
      font-weight: 800;
      font-size: 15px;
    }

    .calc-value-highlight {
      color: #34d399;
      font-weight: 800;
    }

    .terms-box {
      background: #f8fafc;
      border-radius: 14px;
      padding: 0.9rem;
      border: 1px solid #e2e8f0;
      margin-bottom: 1.25rem;
    }

    .terms-box-title {
      font-size: 12px;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .form-checkboxes label {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 8px;
      font-size: 12px;
      color: var(--dark);
      cursor: pointer;
      font-weight: 500;
      line-height: 1.4;
    }

    .form-checkboxes input[type="checkbox"] {
      margin-top: 2px;
      accent-color: var(--primary);
      width: 15px;
      height: 15px;
    }

    .btn-ajukan {
      width: 100%;
      padding: 0.9rem;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: 16px;
      font-weight: 800;
      font-size: 15px;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      position: relative;
      z-index: 20;
    }

    .btn-ajukan:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    }

    .btn-ajukan:disabled {
      background: #cbd5e1;
      color: #64748b;
      cursor: not-allowed;
      box-shadow: none;
    }

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

    .error-message {
      color: var(--danger);
      font-size: 11px;
      font-weight: 600;
      margin-top: 4px;
      display: none;
    }

    .input-error {
      border-color: var(--danger) !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    .modal-content {
      border-radius: 20px;
      border: none;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
    }

    .modal-header {
      background: #f8fafc;
      border-bottom: 1px solid #f1f5f9;
      padding: 1rem 1.25rem;
    }

    .modal-title {
      font-size: 15px;
      font-weight: 800;
      color: var(--dark);
    }

    .modal-body {
      padding: 1.25rem;
    }

    .modal-footer {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 0.85rem 1.25rem;
    }
  </style>
</head>

<body>

  <!-- HEADER UTAMA KOPERASI (NAMA USER SINKRON DENGAN ANGGOTA) -->
  <header class="header-pinjam">
    <div class="header-left">
      <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
        <img id="preview" src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
      <?php elseif (!empty($user['photo']) && file_exists(FCPATH . 'uploads/profile/' . $user['photo'])): ?>
        <img id="preview" src="<?= base_url('uploads/profile/' . $user['photo']) ?>" alt="Foto Profil">
      <?php else: ?>
        <?php
        $namaUser = $anggota['nama_lengkap'] ?? $user['nama_lengkap'] ?? $nama ?? 'User';
        $firstLetter = strtoupper(substr($namaUser, 0, 1));
        $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
        $uniqueId = $anggota['nomor_anggota'] ?? $nomor_anggota ?? 'default';
        $bgColor = $colors[crc32($uniqueId) % count($colors)];
        ?>
        <div class="profile-avatar" style="background:<?= $bgColor ?>;">
          <?= $firstLetter ?>
        </div>
      <?php endif; ?>

      <div class="profile-info">
        <div class="header-name"><?= htmlspecialchars($anggota['nama_lengkap'] ?? $user['nama_lengkap'] ?? $nama ?? 'User') ?></div>
        <div class="header-id">ID: <?= htmlspecialchars($anggota['nomor_anggota'] ?? $nomor_anggota ?? '-') ?></div>
      </div>
    </div>

    <div class="header-actions">
      <div class="notification-wrapper">
        <i data-lucide="bell" class="icon"></i>
        <div class="notification-badge">3</div>
      </div>
      <i data-lucide="settings" class="icon"></i>
    </div>
  </header>

  <div class="page-title-container">
    <h2 class="page-title"><i data-lucide="hand-coins" style="color: var(--primary);"></i> Layanan Pinjaman</h2>
    <p class="page-subtitle">Pengajuan pembiayaan syariah secara cepat dan transparan</p>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <i data-lucide="check-circle" class="alert-icon"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
      <i data-lucide="alert-circle" class="alert-icon"></i>
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <?php if (isset($hasNoRekening) && !$hasNoRekening): ?>
    <div class="alert alert-info">
      <i data-lucide="credit-card" class="alert-icon"></i>
      <div>
        <strong>Nomor Rekening Belum Lengkap</strong>
        <div style="font-size: 12px; margin-top: 2px;">
          Lengkapi data rekening bank Anda terlebih dahulu di menu Profil agar dana pinjaman dapat dicairkan.
        </div>
        <a href="<?= base_url('anggota/profil/edit') ?>" style="display: inline-flex; align-items: center; gap:4px; margin-top: 8px; padding: 6px 12px; background: var(--accent); color: white; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 12px;">
          <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i> Edit Profil Sekarang
        </a>
      </div>
    </div>
  <?php endif; ?>

  <!-- Active Loan / Pending Loan Warning (DIBEDAKAN UI DUA STATUS) -->
  <?php if (isset($loanStatus) && $loanStatus === 'pending'): ?>
    <div class="alert alert-warning alert-persistent">
      <i data-lucide="clock" class="alert-icon"></i>
      <div>
        <strong>Pengajuan Sedang Menunggu Verifikasi Admin</strong>
        <div style="font-size: 12px; margin-top: 2px;">
          Pengajuan pembiayaan Anda telah diterima dan saat ini sedang ditinjau oleh tim admin koperasi. Form pengajuan baru akan dibuka kembali setelah pengajuan ini disetujui atau ditolak.
        </div>
      </div>
    </div>
  <?php elseif (isset($loanStatus) && $loanStatus === 'aktif'): ?>
    <div class="alert alert-danger alert-persistent">
      <i data-lucide="alert-circle" class="alert-icon"></i>
      <div>
        <strong>Pinjaman Masih Berjalan (Aktif)</strong>
        <div style="font-size: 12px; margin-top: 2px;">
          Anda masih memiliki kontrak pinjaman aktif yang sedang berjalan. Harap selesaikan/lunasi angsuran pinjaman Anda terlebih dahulu sebelum membuat pengajuan baru.
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('pinjaman_success')): ?>
    <div class="success-message">
      <div class="success-icon">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div class="success-title">Pengajuan Berhasil Dikirim!</div>
      <div class="success-desc">Permohonan Anda sedang dalam tahap peninjauan oleh tim admin koperasi.</div>
      <div style="font-size: 11px; color: #047857; font-weight: 700;">
        <i class="bi bi-clock-history mr-1"></i> Estimasi proses verifikasi: 1 - 3 hari kerja
      </div>
    </div>
  <?php endif; ?>

  <div class="tab-akad">
    <button id="tab-alqord" class="active" data-tab="alqord"><i data-lucide="heart-handshake" style="width: 16px;"></i> Al-Qord</button>
    <button id="tab-murabahah" data-tab="murabahah"><i data-lucide="shopping-bag" style="width: 16px;"></i> Murabahah</button>
    <button id="tab-mudharabah" data-tab="mudharabah"><i data-lucide="trending-up" style="width: 16px;"></i> Mudharabah</button>
  </div>

  <?php
  $hasNoRekening = isset($hasNoRekening) ? $hasNoRekening : false;
  $loanStatus = isset($loanStatus) ? $loanStatus : 'none';

  $isDisabled = ($loanStatus !== 'none') || !$hasNoRekening;
  $disabledAttr = $isDisabled ? 'disabled' : '';
  $cardDisabledClass = $isDisabled ? 'disabled' : '';

  $disabledText = '';
  if ($loanStatus === 'pending') {
    $disabledText = '⏳ Menunggu ACC / Verifikasi Admin';
  } elseif ($loanStatus === 'aktif') {
    $disabledText = '⛔ Pinjaman Aktif Masih Berjalan';
  } elseif (!$hasNoRekening) {
    $disabledText = '📝 Lengkapi Rekening di Profil';
  }
  ?>

  <!-- SECTION 1: AL-QORD (LIMIT MAX 4 JUTA) -->
  <section id="alqord" class="tab-content active">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="akad-info-box">
        <i data-lucide="info"></i>
        <div class="akad-info-text">
          <b>Akad Al-Qordh (Pinjaman Kebajikan):</b> Pinjaman dana darurat murni tanpa ada imbalan atau penambahan margin/bunga.
        </div>
      </div>

      <div class="card-title"><i data-lucide="file-edit" style="color:var(--primary); width:18px;"></i> Form Pengajuan Al-Qord</div>

      <form id="form-alqord" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Pinjaman <span class="text-xs text-gray-400 font-normal">(Maks Rp 4.000.000)</span></label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="alqord-nominal" name="jumlah" placeholder="0" required maxlength="10" data-max="4000000" <?= $disabledAttr ?> />
          </div>
          <div class="error-message" id="alqord-error">Maksimal pengajuan Al-Qord adalah Rp 4.000.000</div>
        </div>

        <div class="form-input">
          <label>Jangka Waktu Cicilan</label>
          <select id="alqord-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>-- Pilih lama tenor --</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> Bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Tujuan Penggunaan Pinjaman</label>
          <textarea
            id="alqord-deskripsi"
            name="deskripsi"
            placeholder="Jelaskan kebutuhan pengajuan pinjaman (misal: biaya sekolah, perbaikan rumah, dll)"
            required
            maxlength="500"
            <?= $disabledAttr ?>></textarea>
          <div class="character-count" id="alqord-charcount">0/500 karakter</div>
          <div class="note">* Minimal 10 karakter penjelasan deskripsi.</div>
        </div>

        <div class="calc-summary-card">
          <div class="calc-header">
            <span class="calc-title">Simulasi Angsuran</span>
            <span class="calc-badge">Tanpa Bunga / Margin</span>
          </div>
          <div class="calc-row">
            <span>Angsuran Pokok / Bulan</span>
            <span id="alqord-cicilan" class="calc-value-highlight">-</span>
          </div>
        </div>

        <input type="hidden" name="jenis" value="qard">

        <div class="terms-box">
          <div class="terms-box-title"><i data-lucide="shield-check" style="width:16px; color:var(--primary);"></i> Persetujuan Anggota</div>
          <div class="form-checkboxes">
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya menyatakan data nominal dan keperluan pengajuan sudah benar.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya telah membaca & menyetujui ketentuan akad Al-Qordh.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Pengajuan ini dibuat secara sadar tanpa paksaan pihak lain.</label>
            <div style="margin-top: 6px;">
              <a href="<?= base_url('anggota/pin_alqordh') ?>" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 11px;"><i data-lucide="external-link" style="width:12px; display:inline;"></i> BACA SK & SYARAT LENGKAP</a>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '<i data-lucide="send" style="width:18px;"></i> Kirim Pengajuan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>

  <!-- SECTION 2: MURABAHAH (LIMIT MAX 10 JUTA) -->
  <section id="murabahah" class="tab-content">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="akad-info-box" style="background:#f0f9ff; border-color:#bae6fd;">
        <i data-lucide="info" style="color:var(--secondary);"></i>
        <div class="akad-info-text" style="color:#0369a1;">
          <b>Akad Murabahah (Jual Beli):</b> Pembiayaan barang dengan pengembalian ditambah margin keuntungan transparan sebesar 10%.
        </div>
      </div>

      <div class="card-title"><i data-lucide="shopping-cart" style="color:var(--secondary); width:18px;"></i> Form Pengajuan Murabahah</div>

      <form id="form-murabahah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Harga Barang <span class="text-xs text-gray-400 font-normal">(Maks Rp 10.000.000)</span></label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="murabahah-harga" name="jumlah" placeholder="0" required maxlength="12" data-max="10000000" <?= $disabledAttr ?> />
          </div>
          <div class="error-message" id="murabahah-error">Maksimal pembiayaan Murabahah adalah Rp 10.000.000</div>
        </div>

        <div class="form-input">
          <label>Jangka Waktu Cicilan</label>
          <select id="murabahah-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>-- Pilih lama tenor --</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> Bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Deskripsi & Spesifikasi Barang</label>
          <textarea
            id="murabahah-deskripsi"
            name="deskripsi"
            placeholder="Jelaskan jenis barang yang dibeli (contoh: Laptop kerja, Peralatan Usaha, Mesin Cuci, dll.)"
            required
            maxlength="500"
            <?= $disabledAttr ?>></textarea>
          <div class="character-count" id="murabahah-charcount">0/500 karakter</div>
          <div class="note">* Minimal 10 karakter deskripsi barang.</div>
        </div>

        <div class="calc-summary-card">
          <div class="calc-header">
            <span class="calc-title">Simulasi Murabahah</span>
            <span class="calc-badge" style="background:rgba(6,182,212,0.2); color:#22d3ee; border-color:rgba(34,211,238,0.3);">Margin Jual Beli 10%</span>
          </div>
          <div class="calc-row">
            <span>Total Pembayaran (+ Margin)</span>
            <span id="murabahah-total" style="font-weight:700; color:white;">-</span>
          </div>
          <div class="calc-row total-row">
            <span>Cicilan / Bulan</span>
            <span id="murabahah-cicilan" class="calc-value-highlight">-</span>
          </div>
        </div>

        <input type="hidden" name="jenis" value="murabahah">

        <div class="terms-box">
          <div class="terms-box-title"><i data-lucide="shield-check" style="width:16px; color:var(--primary);"></i> Persetujuan Anggota</div>
          <div class="form-checkboxes">
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya menyatakan rincian pembiayaan barang ini akurat.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya sepakat dengan skema harga jual & margin 10% Murabahah.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Pengajuan ini dibuat secara sadar tanpa paksaan pihak lain.</label>
            <div style="margin-top: 6px;">
              <a href="<?= base_url('anggota/pin_murobahah') ?>" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 11px;"><i data-lucide="external-link" style="width:12px; display:inline;"></i> BACA SK & SYARAT LENGKAP</a>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '<i data-lucide="send" style="width:18px;"></i> Kirim Pengajuan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>

  <!-- SECTION 3: MUDHARABAH (LIMIT MAX 20 JUTA) -->
  <section id="mudharabah" class="tab-content">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="akad-info-box" style="background:#faf5ff; border-color:#e9d5ff;">
        <i data-lucide="info" style="color:#a855f7;"></i>
        <div class="akad-info-text" style="color:#7e22ce;">
          <b>Akad Mudharabah (Kerjasama Usaha):</b> Pembiayaan modal usaha dengan penyertaan nisbah bagi hasil sebesar 10%.
        </div>
      </div>

      <div class="card-title"><i data-lucide="briefcase" style="color:#a855f7; width:18px;"></i> Form Pengajuan Mudharabah</div>

      <form id="form-mudharabah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Modal Usaha <span class="text-xs text-gray-400 font-normal">(Maks Rp 20.000.000)</span></label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="mudharabah-nominal" name="jumlah" placeholder="0" required maxlength="12" data-max="20000000" <?= $disabledAttr ?> />
          </div>
          <div class="error-message" id="mudharabah-error">Maksimal pembiayaan Mudharabah adalah Rp 20.000.000</div>
        </div>

        <div class="form-input">
          <label>Jangka Waktu Kerjasama</label>
          <select id="mudharabah-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>-- Pilih lama tenor --</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> Bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Deskripsi Proyek / Usaha</label>
          <textarea
            id="mudharabah-deskripsi"
            name="deskripsi"
            placeholder="Jelaskan proyek usaha yang dibiayai (misal: Modal stok barang toko, usaha catering, dll)"
            required
            maxlength="500"
            <?= $disabledAttr ?>></textarea>
          <div class="character-count" id="mudharabah-charcount">0/500 karakter</div>
          <div class="note">* Minimal 10 karakter deskripsi usaha.</div>
        </div>

        <div class="calc-summary-card">
          <div class="calc-header">
            <span class="calc-title">Simulasi Mudharabah</span>
            <span class="calc-badge" style="background:rgba(168,85,247,0.2); color:#c084fc; border-color:rgba(192,132,252,0.3);">Nisbah Bagi Hasil 10%</span>
          </div>
          <div class="calc-row">
            <span>Total Pengembalian (+ Bagi Hasil)</span>
            <span id="mudharabah-total" style="font-weight:700; color:white;">-</span>
          </div>
          <div class="calc-row total-row">
            <span>Cicilan / Bulan</span>
            <span id="mudharabah-cicilan" class="calc-value-highlight">-</span>
          </div>
        </div>

        <input type="hidden" name="jenis" value="mudharabah">

        <div class="terms-box">
          <div class="terms-box-title"><i data-lucide="shield-check" style="width:16px; color:var(--primary);"></i> Persetujuan Anggota</div>
          <div class="form-checkboxes">
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya menyatakan informasi usaha yang disampaikan benar.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Saya sepakat dengan nisbah bagi hasil 10% yang ditentukan.</label>
            <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Pengajuan ini dibuat secara sadar tanpa paksaan pihak lain.</label>
            <div style="margin-top: 6px;">
              <a href="<?= base_url('anggota/pin_mudhorobah') ?>" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 11px;"><i data-lucide="external-link" style="width:12px; display:inline;"></i> BACA SK & SYARAT LENGKAP</a>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '<i data-lucide="send" style="width:18px;"></i> Kirim Pengajuan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>

  <!-- Bottom Navigation Menu -->
  <nav class="bottom-nav">
    <a href="<?= base_url('anggota/dashboard') ?>">
      <i data-lucide="home" style="width:18px; height:18px;"></i>
      <p>Beranda</p>
    </a>
    <a href="<?= base_url('anggota/simpanan') ?>">
      <i data-lucide="wallet" style="width:18px; height:18px;"></i>
      <p>Simpan</p>
    </a>
    <a href="<?= base_url('anggota/pinjaman') ?>" class="active">
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

  <!-- MODAL VERIFIKASI PIN -->
  <div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pinModalLabel"><i data-lucide="lock" style="width:16px; display:inline; color:var(--primary);"></i> Verifikasi PIN Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="pinVerificationForm" action="<?= base_url('pinjaman/process-after-pin') ?>" method="POST">
          <div class="modal-body">
            <p style="font-size:13px; color:var(--gray);">Masukkan 6 digit PIN keamanan akun Anda untuk otorisasi pengajuan pinjaman:</p>
            <div class="mb-3">
              <label for="pin" class="form-label" style="font-weight:700; font-size:12px;">PIN KOPERASI (6 Digit)</label>
              <input type="password" class="form-control text-center font-bold" id="pin" name="pin"
                placeholder="******" maxlength="6"
                style="letter-spacing: 6px; font-size: 20px; border-radius: 12px;"
                pattern="\d{6}" title="Harus 6 digit angka" required>
              <div class="invalid-feedback" id="pinError"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light btn-sm font-bold" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-emerald btn-sm font-bold text-white" style="background:var(--primary);">Verifikasi & Ajukan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ==================== KARTU RIWAYAT & STATUS PENGAJUAN TERAKHIR ==================== -->
  <?php if (!empty($riwayatPinjaman)): ?>
    <div style="padding: 0 1rem; margin-bottom: 1.25rem;">
      <div style="background: white; border-radius: var(--border-radius); padding: 1.1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
          <span style="font-size: 13px; font-weight: 800; color: var(--dark); display: flex; align-items: center; gap: 6px;">
            <i data-lucide="history" style="width:16px; color:var(--primary);"></i> Status Pengajuan Pinjaman
          </span>
          <span style="font-size: 11px; color: var(--gray); font-weight: 600;">Terbaru</span>
        </div>

        <?php foreach ($riwayatPinjaman as $p): ?>
          <?php
          // Normalisasi string status ke huruf kecil
          $st = strtolower(trim($p['status']));
          $statusBadge = '';
          $statusBoxBg = '';

          if (in_array($st, ['pending', 'menunggu', 'waiting'])) {
            $statusBadge = '<span style="background:#fef3c7; color:#d97706; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; border:1px solid #fcd34d; display:inline-flex; align-items:center; gap:4px;"><i class="fas fa-clock fa-spin"></i> Menunggu ACC</span>';
            $statusBoxBg = '#fffbeb';
          } elseif (in_array($st, ['aktif', 'disetujui', 'approved', 'berjalan'])) {
            $statusBadge = '<span style="background:#dcfce7; color:#15803d; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; border:1px solid #86efac; display:inline-flex; align-items:center; gap:4px;"><i class="fas fa-check-circle"></i> Disetujui (Aktif)</span>';
            $statusBoxBg = '#f0fdf4';
          } elseif (in_array($st, ['lunas', 'selesai', 'paid'])) {
            $statusBadge = '<span style="background:#e0f2fe; color:#0369a1; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; border:1px solid #bae6fd; display:inline-flex; align-items:center; gap:4px;"><i class="fas fa-check-double"></i> Lunas</span>';
            $statusBoxBg = '#f0f9ff';
          } else {
            // Hanya jika benar-benar berstatus ditolak / rejected
            $statusBadge = '<span style="background:#fee2e2; color:#dc2626; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; border:1px solid #fca5a5; display:inline-flex; align-items:center; gap:4px;"><i class="fas fa-times-circle"></i> Ditolak</span>';
            $statusBoxBg = '#fef2f2';
          }
          ?>

          <div style="background: <?= $statusBoxBg ?>; border-radius: 12px; padding: 0.85rem; margin-bottom: 8px; border: 1px solid rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <div>
                <span style="font-size: 13px; font-weight: 800; color: var(--dark);"><?= esc($p['akad']) ?></span>
                <div style="font-size: 11px; color: var(--gray); font-weight: 600; margin-top: 2px;">
                  Tenor: <b><?= esc($p['tenor']) ?> Bulan</b> • Tanggal: <?= date('d M Y', strtotime($p['tanggal'])) ?>
                </div>
              </div>
              <div style="text-align: right;">
                <div style="font-size: 14px; font-weight: 800; color: var(--dark);">Rp <?= number_format($p['nominal'], 0, ',', '.') ?></div>
                <div style="margin-top: 4px;"><?= $statusBadge ?></div>
              </div>
            </div>

            <?php if (in_array($st, ['ditolak', 'rejected']) && !empty($p['alasan'])): ?>
              <div style="margin-top: 8px; font-size: 11px; color: #b91c1c; background: rgba(239, 68, 68, 0.08); padding: 6px 10px; border-radius: 8px;">
                <i class="fas fa-info-circle mr-1"></i> <b>Alasan Ditolak:</b> <?= esc($p['alasan']) ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- MODAL BUAT PIN -->
  <div class="modal fade" id="createPinModal" tabindex="-1" aria-labelledby="createPinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPinModalLabel"><i data-lucide="key" style="width:16px; display:inline; color:var(--primary);"></i> Buat PIN Keamanan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="createPinForm">
          <div class="modal-body">
            <p style="font-size:13px; color:var(--gray);">Buat 6 digit PIN rahasia untuk mengamankan transaksi pengajuan pinjaman Anda.</p>
            <div class="mb-3">
              <label for="new_pin" class="form-label" style="font-weight:700; font-size:12px;">PIN Baru (6 Digit)</label>
              <input type="password" class="form-control text-center font-bold" id="new_pin" name="new_pin"
                placeholder="******" maxlength="6" required style="letter-spacing: 6px; font-size: 18px; border-radius: 12px;"
                pattern="\d{6}" title="Harus 6 digit angka">
            </div>
            <div class="mb-3">
              <label for="confirm_pin" class="form-label" style="font-weight:700; font-size:12px;">Konfirmasi PIN Baru</label>
              <input type="password" class="form-control text-center font-bold" id="confirm_pin" name="confirm_pin"
                placeholder="******" maxlength="6" required style="letter-spacing: 6px; font-size: 18px; border-radius: 12px;"
                pattern="\d{6}" title="Harus 6 digit angka">
            </div>
            <div id="pinMessage" class="alert" style="display:none;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light btn-sm font-bold" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-emerald btn-sm font-bold text-white" style="background:var(--primary);">Simpan PIN</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS Scripts Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    if (typeof jQuery === 'undefined') {
      const script = document.createElement('script');
      script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
      script.onload = function() {
        initializeApp();
      };
      document.head.appendChild(script);
    } else {
      $(document).ready(function() {
        initializeApp();
      });
    }

    function initializeApp() {
      console.log('Initializing application JS logic...');

      window.showTab = function(name) {
        document.querySelectorAll('.tab-content').forEach(el => {
          el.classList.remove('active');
        });
        const tabContent = document.getElementById(name);
        if (tabContent) {
          tabContent.classList.add('active');
        }

        document.querySelectorAll('.tab-akad button').forEach(el => {
          el.classList.remove('active');
        });
        const tabButton = document.getElementById('tab-' + name);
        if (tabButton) {
          tabButton.classList.add('active');
        }
      };

      lucide.createIcons();

      // KONFIGURASI LIMIT MAKSIMAL NOMINAL PINJAMAN PER AKAD
      const LIMITS = {
        alqord: 4000000, // Max 4 Juta
        murabahah: 10000000, // Max 10 Juta
        mudharabah: 20000000 // Max 20 Juta
      };

      const isDisabled = <?= $isDisabled ? 'true' : 'false' ?>;
      const hasNoRekening = <?= $hasNoRekening ? 'true' : 'false' ?>;

      function validateNominal(input, errorElement, maxLimit) {
        const raw = input.value.replace(/\./g, "");
        const nominal = parseInt(raw) || 0;

        if (nominal > maxLimit) {
          if (errorElement) errorElement.style.display = 'block';
          input.parentElement.classList.add('input-error');
          return false;
        } else {
          if (errorElement) errorElement.style.display = 'none';
          input.parentElement.classList.remove('input-error');
          return true;
        }
      }

      function updateCharacterCount(textarea, counter) {
        const length = textarea.value.length;
        if (counter) {
          counter.textContent = `${length}/500 karakter`;
          if (length > 450) {
            counter.style.color = 'var(--warning)';
          } else if (length > 490) {
            counter.style.color = 'var(--danger)';
          } else {
            counter.style.color = 'var(--gray)';
          }
        }
      }

      // 1. Al-Qord Listener (Max 4 Juta)
      const alqordNominal = document.getElementById("alqord-nominal");
      const alqordBulan = document.getElementById("alqord-bulan");
      const alqordDeskripsi = document.getElementById("alqord-deskripsi");

      if (alqordNominal) {
        alqordNominal.addEventListener("input", function() {
          validateNominal(this, document.getElementById("alqord-error"), LIMITS.alqord);
          updateAlqord();
        });
      }
      if (alqordBulan) alqordBulan.addEventListener("change", updateAlqord);
      if (alqordDeskripsi) {
        alqordDeskripsi.addEventListener("input", function() {
          updateCharacterCount(this, document.getElementById("alqord-charcount"));
        });
      }

      function updateAlqord() {
        const nominalInput = document.getElementById("alqord-nominal");
        const bulanSelect = document.getElementById("alqord-bulan");
        const cicilanSpan = document.getElementById("alqord-cicilan");
        if (!nominalInput || !bulanSelect || !cicilanSpan) return;

        let raw = nominalInput.value;
        const n = parseInt(raw.replace(/\./g, "")) || 0;
        const b = parseInt(bulanSelect.value) || 0;

        if (n > 0 && b > 0 && b <= 12 && n <= LIMITS.alqord) {
          const cicilan = Math.round(n / b);
          cicilanSpan.textContent = "Rp " + cicilan.toLocaleString('id-ID');
        } else {
          cicilanSpan.textContent = "-";
        }
      }

      // 2. Murabahah Listener (Max 10 Juta)
      const murabahahHarga = document.getElementById("murabahah-harga");
      const murabahahBulan = document.getElementById("murabahah-bulan");
      const murabahahDeskripsi = document.getElementById("murabahah-deskripsi");

      if (murabahahHarga) {
        murabahahHarga.addEventListener("input", function() {
          validateNominal(this, document.getElementById("murabahah-error"), LIMITS.murabahah);
          updateMurabahah();
        });
      }
      if (murabahahBulan) murabahahBulan.addEventListener("change", updateMurabahah);
      if (murabahahDeskripsi) {
        murabahahDeskripsi.addEventListener("input", function() {
          updateCharacterCount(this, document.getElementById("murabahah-charcount"));
        });
      }

      function updateMurabahah() {
        const hargaInput = document.getElementById("murabahah-harga");
        const bulanSelect = document.getElementById("murabahah-bulan");
        const totalSpan = document.getElementById("murabahah-total");
        const cicilanSpan = document.getElementById("murabahah-cicilan");
        if (!hargaInput || !bulanSelect || !totalSpan || !cicilanSpan) return;

        let raw = hargaInput.value;
        const h = parseInt(raw.replace(/\./g, "")) || 0;
        const b = parseInt(bulanSelect.value) || 0;

        if (h > 0 && h <= LIMITS.murabahah) {
          const total = Math.round(h + (h * 0.1));
          totalSpan.textContent = "Rp " + total.toLocaleString('id-ID');
          if (b > 0) {
            const cicilan = Math.round(total / b);
            cicilanSpan.textContent = "Rp " + cicilan.toLocaleString('id-ID');
          } else {
            cicilanSpan.textContent = "-";
          }
        } else {
          totalSpan.textContent = "-";
          cicilanSpan.textContent = "-";
        }
      }

      // 3. Mudharabah Listener (Max 20 Juta)
      const mudharabahNominal = document.getElementById("mudharabah-nominal");
      const mudharabahBulan = document.getElementById("mudharabah-bulan");
      const mudharabahDeskripsi = document.getElementById("mudharabah-deskripsi");

      if (mudharabahNominal) {
        mudharabahNominal.addEventListener("input", function() {
          validateNominal(this, document.getElementById("mudharabah-error"), LIMITS.mudharabah);
          updateMudharabah();
        });
      }
      if (mudharabahBulan) mudharabahBulan.addEventListener("change", updateMudharabah);
      if (mudharabahDeskripsi) {
        mudharabahDeskripsi.addEventListener("input", function() {
          updateCharacterCount(this, document.getElementById("mudharabah-charcount"));
        });
      }

      function updateMudharabah() {
        const nominalInput = document.getElementById("mudharabah-nominal");
        const bulanSelect = document.getElementById("mudharabah-bulan");
        const totalSpan = document.getElementById("mudharabah-total");
        const cicilanSpan = document.getElementById("mudharabah-cicilan");
        if (!nominalInput || !bulanSelect || !totalSpan || !cicilanSpan) return;

        let raw = nominalInput.value;
        const n = parseInt(raw.replace(/\./g, "")) || 0;
        const b = parseInt(bulanSelect.value) || 0;

        if (n > 0 && n <= LIMITS.mudharabah) {
          const total = Math.round(n + (n * 0.1));
          totalSpan.textContent = "Rp " + total.toLocaleString('id-ID');
          if (b > 0) {
            const cicilan = Math.round(total / b);
            cicilanSpan.textContent = "Rp " + cicilan.toLocaleString('id-ID');
          } else {
            cicilanSpan.textContent = "-";
          }
        } else {
          totalSpan.textContent = "-";
          cicilanSpan.textContent = "-";
        }
      }

      // Tab switcher event
      document.querySelectorAll('.tab-akad button[data-tab]').forEach(button => {
        button.addEventListener('click', function() {
          showTab(this.getAttribute('data-tab'));
        });
      });

      function saveFormDataToLocalStorage(form) {
        const formData = {};
        const formElements = form.elements;
        for (let i = 0; i < formElements.length; i++) {
          const element = formElements[i];
          if (element.name && element.type !== 'button' && element.type !== 'submit') {
            formData[element.name] = (element.type === 'checkbox') ? element.checked : element.value;
          }
        }
        formData['timestamp'] = Date.now();
        localStorage.setItem('pending_pinjaman', JSON.stringify(formData));
        return formData;
      }

      function restoreFormDataToPinModal() {
        const savedData = JSON.parse(localStorage.getItem('pending_pinjaman') || '{}');
        if (savedData && Object.keys(savedData).length > 0) {
          const pinForm = document.getElementById('pinVerificationForm');
          if (!pinForm) return;

          const existingHiddenInputs = pinForm.querySelectorAll('input[name^="pinjaman_"]');
          existingHiddenInputs.forEach(el => el.remove());

          for (const [key, value] of Object.entries(savedData)) {
            if (key !== 'timestamp') {
              const hiddenInput = document.createElement('input');
              hiddenInput.type = 'hidden';
              hiddenInput.name = 'pinjaman_' + key;
              hiddenInput.value = value;
              pinForm.appendChild(hiddenInput);
            }
          }
        }
      }

      // Form Confirmation & Submit Handler
      document.querySelectorAll('form[id^="form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();

          if (isDisabled) {
            if (!hasNoRekening) {
              alert('Harap lengkapi nomor rekening di menu Profil terlebih dahulu.');
              window.location.href = '<?= base_url('anggota/profil/edit') ?>';
            } else {
              alert('Anda memiliki pinjaman aktif yang belum lunas.');
            }
            return;
          }

          const checkboxes = form.querySelectorAll('.confirm-checkbox');
          let allChecked = true;
          checkboxes.forEach(cb => {
            if (!cb.checked) allChecked = false;
          });

          const nominalInput = form.querySelector('input[type="text"]');
          const rawNominal = nominalInput.value.replace(/\./g, "");
          const nominal = parseInt(rawNominal) || 0;

          // Deteksi batas limit sesuai form akad
          const jenisAkad = form.querySelector('input[name="jenis"]').value;
          let currentMaxLimit = LIMITS.alqord;
          if (jenisAkad === 'murabahah') currentMaxLimit = LIMITS.murabahah;
          else if (jenisAkad === 'mudharabah') currentMaxLimit = LIMITS.mudharabah;

          const deskripsiInput = form.querySelector('textarea');
          const deskripsi = deskripsiInput.value.trim();

          if (!allChecked) {
            alert('Harap centang semua pernyataan persetujuan.');
            return;
          }

          if (nominal > currentMaxLimit) {
            alert('Nominal pinjaman melebihi batas maksimum Rp ' + currentMaxLimit.toLocaleString('id-ID'));
            return;
          }

          if (nominal <= 0) {
            alert('Nominal pinjaman harus lebih dari 0');
            return;
          }

          if (deskripsi.length < 10) {
            alert('Deskripsi penggunaan pinjaman minimal 10 karakter');
            deskripsiInput.focus();
            return;
          }

          const submitBtn = form.querySelector('.btn-ajukan');
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '⏳ Processing...';
          submitBtn.disabled = true;

          saveFormDataToLocalStorage(form);

          $.ajax({
            url: '<?= base_url("pinjaman/validateBeforeSubmit") ?>',
            type: 'GET',
            success: function(response) {
              if (response.canSubmit) {
                if (response.hasPin) {
                  const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
                  restoreFormDataToPinModal();

                  $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function() {
                      pinModal.show();
                    },
                    error: function() {
                      alert('Gagal menyimpan data pengajuan.');
                      submitBtn.innerHTML = originalText;
                      submitBtn.disabled = false;
                    }
                  });
                } else {
                  const createPinModal = new bootstrap.Modal(document.getElementById('createPinModal'));
                  createPinModal.show();
                  submitBtn.innerHTML = originalText;
                  submitBtn.disabled = false;
                }
              } else {
                let errorMsg = 'Gagal mengajukan pinjaman:\n';
                response.messages.forEach(function(msg) {
                  if (msg) errorMsg += '- ' + msg + '\n';
                });
                alert(errorMsg);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
              }
            },
            error: function() {
              alert('Terjadi kesalahan validasi.');
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          });
        });
      });

      // PIN Verification Handler
      const pinVerificationForm = document.getElementById('pinVerificationForm');
      if (pinVerificationForm) {
        pinVerificationForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const pin = document.getElementById('pin').value;
          const pinError = document.getElementById('pinError');

          if (pin.length !== 6) {
            pinError.textContent = 'PIN harus 6 digit';
            pinError.style.display = 'block';
            return;
          }

          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '⏳ Memproses...';
          submitBtn.disabled = true;

          $.ajax({
            url: '<?= base_url("pinjaman/verify-pin") ?>',
            type: 'POST',
            data: {
              pin: pin
            },
            success: function(response) {
              if (response.success) {
                pinVerificationForm.submit();
              } else {
                pinError.textContent = response.message;
                pinError.style.display = 'block';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
              }
            },
            error: function() {
              pinError.textContent = 'Kesalahan saat memverifikasi PIN';
              pinError.style.display = 'block';
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          });
        });
      }

      // Create PIN Handler
      const createPinForm = document.getElementById('createPinForm');
      if (createPinForm) {
        createPinForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const newPin = document.getElementById('new_pin').value;
          const confirmPin = document.getElementById('confirm_pin').value;

          if (newPin.length !== 6 || newPin !== confirmPin) {
            showPinMessage(newPin.length !== 6 ? 'PIN harus 6 digit' : 'Konfirmasi PIN tidak cocok', 'danger');
            return;
          }

          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '⏳ Membuat PIN...';
          submitBtn.disabled = true;

          $.ajax({
            url: '<?= base_url("pinjaman/create-pin") ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
              if (response.success) {
                showPinMessage(response.message, 'success');
                setTimeout(function() {
                  const createPinModal = bootstrap.Modal.getInstance(document.getElementById('createPinModal'));
                  const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
                  if (createPinModal) createPinModal.hide();
                  restoreFormDataToPinModal();
                  pinModal.show();
                  submitBtn.innerHTML = originalText;
                  submitBtn.disabled = false;
                }, 1500);
              } else {
                showPinMessage(response.message, 'danger');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
              }
            },
            error: function() {
              showPinMessage('Gagal membuat PIN baru', 'danger');
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          });
        });
      }

      function showPinMessage(message, type) {
        const messageDiv = document.getElementById('pinMessage');
        if (messageDiv) {
          messageDiv.className = 'alert alert-' + type;
          messageDiv.textContent = message;
          messageDiv.style.display = 'block';
          setTimeout(() => {
            messageDiv.style.display = 'none';
          }, 3000);
        }
      }

      function formatRupiah(angka) {
        if (!angka) return '';
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // Format Input Rupiah Realtime Sesuai Limit Masing-Masing
      document.querySelectorAll('input[id$="-nominal"], input[id$="-harga"]').forEach(input => {
        input.addEventListener('input', function() {
          let value = this.value.replace(/\D/g, '');
          const form = this.closest('form');
          const jenisAkad = form.querySelector('input[name="jenis"]').value;

          let currentMax = LIMITS.alqord;
          if (jenisAkad === 'murabahah') currentMax = LIMITS.murabahah;
          else if (jenisAkad === 'mudharabah') currentMax = LIMITS.mudharabah;

          if (value) {
            this.value = formatRupiah(value);
            const nominal = parseInt(value) || 0;
            const errorId = this.id.replace('-nominal', '-error').replace('-harga', '-error');
            const errorElement = document.getElementById(errorId);

            if (nominal > currentMax) {
              if (errorElement) errorElement.style.display = 'block';
              this.parentElement.classList.add('input-error');
            } else {
              if (errorElement) errorElement.style.display = 'none';
              this.parentElement.classList.remove('input-error');
            }
          } else {
            this.value = '';
            const errorId = this.id.replace('-nominal', '-error').replace('-harga', '-error');
            const errorElement = document.getElementById(errorId);
            if (errorElement) errorElement.style.display = 'none';
            this.parentElement.classList.remove('input-error');
          }
        });
      });

      document.querySelectorAll('textarea').forEach(textarea => {
        const id = textarea.id;
        const counterId = id.replace('-deskripsi', '-charcount');
        const counter = document.getElementById(counterId);
        if (counter) updateCharacterCount(textarea, counter);
      });

      updateAlqord();
      updateMurabahah();
      updateMudharabah();

      setTimeout(() => {
        // Alert dengan class .alert-persistent tidak akan dihapus
        document.querySelectorAll('.alert:not(.alert-persistent)').forEach(alert => {
          if (alert.style) {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
              if (alert.parentElement) alert.remove();
            }, 500);
          }
        });
      }, 5000);

      <?php if (session()->getFlashdata('show_pin_modal')): ?>
        setTimeout(function() {
          restoreFormDataToPinModal();
          $.ajax({
            url: '<?= base_url("pinjaman/validateBeforeSubmit") ?>',
            type: 'GET',
            success: function(response) {
              if (response.hasPin) {
                const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
                pinModal.show();
              } else {
                const createPinModal = new bootstrap.Modal(document.getElementById('createPinModal'));
                createPinModal.show();
              }
            }
          });
        }, 1000);
      <?php endif; ?>

      const pinModalElement = document.getElementById('pinModal');
      if (pinModalElement) {
        pinModalElement.addEventListener('hidden.bs.modal', function() {
          const pinInput = document.getElementById('pin');
          if (pinInput) pinInput.value = '';
          localStorage.removeItem('pending_pinjaman');
        });
      }

      const createPinModalElement = document.getElementById('createPinModal');
      if (createPinModalElement) {
        createPinModalElement.addEventListener('hidden.bs.modal', function() {
          const newPinInput = document.getElementById('new_pin');
          const confirmPinInput = document.getElementById('confirm_pin');
          if (newPinInput) newPinInput.value = '';
          if (confirmPinInput) confirmPinInput.value = '';
          localStorage.removeItem('pending_pinjaman');
        });
      }

      const successMessage = document.querySelector('.success-message');
      if (successMessage) {
        localStorage.removeItem('pending_pinjaman');
      }
    }
  </script>
</body>

</html>