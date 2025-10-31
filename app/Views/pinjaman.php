<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pinjaman</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }
  
    body {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #f0fdf9 0%, #f0fdf4 100%);
      color: var(--dark);
      min-height: 100vh;
      padding-bottom: 80px;
      line-height: 1.6;
    }

    /* Header */
    .header-pinjam {
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

    .header-left {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .header-left img {
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

    .header-name {
      font-weight: 700;
      font-size: 18px;
      letter-spacing: -0.3px;
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

    .page-title {
      font-size: 24px;
      font-weight: 800;
      text-align: center;
      margin: 1.5rem 0;
      color: var(--dark);
    }

    /* Alert Styles */
    .alert {
      padding: 1rem 1.5rem;
      margin: 1rem 1.5rem;
      border-radius: var(--border-radius);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideDown 0.3s ease-out;
    }

    .alert-warning {
      background: linear-gradient(135deg, #fef3c7 0%, #fef7cd 100%);
      color: #92400e;
      border-left: 4px solid var(--warning);
    }

    .alert-success {
      background: linear-gradient(135deg, #d1fae5 0%, #dcfce7 100%);
      color: #065f46;
      border-left: 4px solid var(--success);
    }

    .alert-danger {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      color: #991b1b;
      border-left: 4px solid var(--danger);
    }

    .alert-icon {
      width: 24px;
      height: 24px;
      flex-shrink: 0;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Tabs */
    .tab-akad {
      display: flex;
      background: white;
      margin: 0 1.5rem 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .tab-akad button {
      flex: 1;
      padding: 1rem;
      border: none;
      background: transparent;
      color: var(--gray);
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: var(--transition);
      position: relative;
    }

    .tab-akad button.active {
      color: var(--primary);
      background: rgba(16, 185, 129, 0.1);
    }

    .tab-akad button.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 30px;
      height: 3px;
      background: var(--primary);
      border-radius: 2px;
    }

    .tab-akad button:hover:not(.active) {
      background: rgba(16, 185, 129, 0.05);
      color: var(--primary-dark);
    }

    /* Content */
    .tab-content {
      display: none;
      padding: 0 1.5rem;
    }

    .tab-content.active {
      display: block;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin-bottom: 1rem;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.5);
      transition: var(--transition);
      position: relative;
    }

    .card.disabled {
      opacity: 0.7;
      pointer-events: none;
    }

    .card.disabled::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.8);
      border-radius: var(--border-radius);
      z-index: 1;
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
    }

    .card-title {
      font-size: 20px;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 1.5rem;
      text-align: center;
    }

    /* Form Styles */
    .form-input {
      margin-bottom: 1.2rem;
    }

    .form-input label {
      display: block;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 0.5rem;
      font-size: 14px;
    }

    .input-rupiah {
      display: flex;
      align-items: center;
      border: 1px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      overflow: hidden;
      transition: var(--transition);
    }

    .input-rupiah:focus-within {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .input-rupiah span {
      padding: 0.8rem 1rem;
      background: var(--light);
      color: var(--gray);
      font-weight: 600;
      border-right: 1px solid var(--gray-light);
    }

    .input-rupiah input {
      flex: 1;
      padding: 0.8rem;
      border: none;
      outline: none;
      font-size: 16px;
      font-weight: 600;
      color: var(--dark);
    }

    .input-rupiah input:disabled,
    .form-input select:disabled,
    .form-input textarea:disabled {
      background-color: #f8fafc;
      color: #94a3b8;
      cursor: not-allowed;
    }

    .form-input select {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      font-size: 16px;
      font-weight: 600;
      color: var(--dark);
      background: white;
      transition: var(--transition);
      cursor: pointer;
    }

    .form-input select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Textarea Styles */
    .form-input textarea {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      font-size: 14px;
      color: var(--dark);
      background: white;
      transition: var(--transition);
      resize: vertical;
      min-height: 80px;
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      line-height: 1.5;
    }

    .form-input textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-input textarea::placeholder {
      color: var(--gray-light);
    }

    .character-count {
      font-size: 12px;
      color: var(--gray);
      text-align: right;
      margin-top: 4px;
    }

    .character-count.warning {
      color: var(--warning);
    }

    .character-count.danger {
      color: var(--danger);
    }

    .kv {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
      padding: 0.8rem 0;
      border-bottom: 1px solid var(--gray-light);
    }

    .kv:last-child {
      border-bottom: none;
    }

    .kv span:first-child {
      color: var(--gray);
      font-size: 14px;
    }

    .kv span:last-child {
      color: var(--dark);
      font-weight: 700;
      font-size: 15px;
    }

    .note {
      font-size: 12px;
      color: var(--gray);
      margin: 1rem 0;
      text-align: center;
      font-style: italic;
    }

    /* Checkboxes */
    .form-checkboxes {
      margin: 1.5rem 0;
      padding: 1rem;
      background: rgba(16, 185, 129, 0.05);
      border-radius: var(--border-radius-sm);
      border-left: 4px solid var(--primary);
    }

    .form-checkboxes label {
      display: flex;
      align-items: flex-start;
      gap: 0.8rem;
      margin-bottom: 0.8rem;
      font-size: 14px;
      color: var(--dark);
      cursor: pointer;
    }

    .form-checkboxes input[type="checkbox"] {
      margin-top: 0.2rem;
      accent-color: var(--primary);
    }

    .form-checkboxes input[type="checkbox"]:disabled {
      accent-color: var(--gray);
      cursor: not-allowed;
    }

    /* Button Ajukan */
    .btn-ajukan {
      width: 100%;
      padding: 1rem;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: var(--shadow);
      position: relative;
      z-index: 2;
    }

    .btn-ajukan:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .btn-ajukan:active:not(:disabled) {
      transform: translateY(0);
    }

    .btn-ajukan:disabled {
      background: var(--gray-light);
      color: var(--gray);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Bottom Navigation */
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
      flex: 1;
      text-align: center;
    }

    .bottom-nav a i {
      font-size: 20px;
      margin-bottom: 5px;
    }

    .bottom-nav a p {
      font-size: 12px;
      font-weight: 600;
    }

    .bottom-nav a.active {
      color: var(--primary);
      background: rgba(16, 185, 129, 0.1);
    }

    /* Error Message */
    .error-message {
      color: var(--danger);
      font-size: 12px;
      margin-top: 5px;
      display: none;
    }

    .input-error {
      border-color: var(--danger) !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    /* Loading State */
    .loading {
      opacity: 0.6;
      pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .header-pinjam, .tab-akad, .tab-content {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
      }
      
      .page-title {
        font-size: 22px;
      }
      
      .card-title {
        font-size: 18px;
      }
      
      .alert {
        margin: 1rem 1.2rem;
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <header class="header-pinjam">
    <div class="header-left">
        <?php if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/profile/' . $user['photo'])): ?>
            <img id="preview" src="<?= base_url('uploads/profile/' . $user['photo']) ?>" alt="Foto Profil">
        <?php else: ?>
        <?php 
            $firstLetter = strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1));
            $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
            // Gunakan nomor_anggota dari table anggota atau ID sebagai fallback
            $uniqueId = $anggota['nomor_anggota'] ?? $user['id'] ?? 'default';
            $bgColor = $colors[crc32($uniqueId) % count($colors)];
        ?>
        <div class="profile-avatar" style="background:<?= $bgColor ?>;">
            <?= $firstLetter ?>
        </div>
        <?php endif; ?>

        <div>
            <div class="header-name"><?= htmlspecialchars($nama ?? 'User') ?></div>
            <div style="font-size:12px;opacity:.9;">ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></div>
        </div>
    </div>
    <i data-lucide="bell" class="icon"></i>
  </header>
  <h2 class="page-title">Pinjaman</h2>

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

  <!-- Alert No Rekening -->
  <?php if (isset($hasNoRekening) && !$hasNoRekening): ?>
    <div class="alert alert-info">
      <i data-lucide="info" class="alert-icon"></i>
      <div>
        <strong>Nomor Rekening Belum Diisi</strong>
        <div style="font-size: 14px; margin-top: 4px;">
          Anda belum mengisi nomor rekening. Harap lengkapi data rekening di menu Profil terlebih dahulu sebelum mengajukan pinjaman.
          <br>
          <a href="<?= base_url('anggota/profil/edit') ?>" class="btn-update-rekening" style="display: inline-block; margin-top: 8px; padding: 8px 16px; background: var(--accent); color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i data-lucide="edit-3" style="width: 16px; height: 16px; margin-right: 5px;"></i>
            Ke Menu Profil
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Active Loan Warning -->
  <?php if (isset($hasActiveLoan) && $hasActiveLoan): ?>
    <div class="alert alert-warning">
      <i data-lucide="alert-triangle" class="alert-icon"></i>
      <div>
        <strong>Pinjaman Aktif Ditemukan</strong>
        <div style="font-size: 14px; margin-top: 4px;">Anda sudah memiliki pinjaman yang aktif. Silakan selesaikan pinjaman terlebih dahulu sebelum mengajukan pinjaman baru.</div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Tab akad -->
  <div class="tab-akad">
    <button id="tab-alqord" class="active" onclick="showTab('alqord')">Al-Qord</button>
    <button id="tab-murabahah" onclick="showTab('murabahah')">Murabahah</button>
    <button id="tab-mudharabah" onclick="showTab('mudharabah')">Mudharabah</button>
  </div>

  <?php
  // Helper variables
  $hasNoRekening = isset($hasNoRekening) ? $hasNoRekening : false;
  $hasActiveLoan = isset($hasActiveLoan) ? $hasActiveLoan : false;
  
  // Disabled jika: memiliki pinjaman aktif ATAU belum isi no rekening
  $isDisabled = $hasActiveLoan || !$hasNoRekening;
  $disabledAttr = $isDisabled ? 'disabled' : '';
  $cardDisabledClass = $isDisabled ? 'disabled' : '';
  
  // Text untuk tombol disabled
  $disabledText = '';
  if ($hasActiveLoan) {
      $disabledText = '⛔ Pinjaman Aktif Ditemukan';
  } elseif (!$hasNoRekening) {
      $disabledText = '📝 Lengkapi Nomor Rekening';
  }
  ?>

  <!-- Al-Qord -->
  <section id="alqord" class="tab-content active">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="card-title">Ajukan Pinjaman Al-Qord</div>
      <form id="form-alqord" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Pinjaman (Maks Rp 4.000.000)</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="alqord-nominal" name="jumlah" placeholder="Masukkan jumlah" required maxlength="10" data-max="4000000" <?= $disabledAttr ?>/>
          </div>
          <div class="error-message" id="alqord-error">Maksimal pinjaman adalah Rp 4.000.000</div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="alqord-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Deskripsi Penggunaan Pinjaman</label>
          <textarea 
            id="alqord-deskripsi" 
            name="deskripsi" 
            placeholder="Jelaskan untuk apa pinjaman ini akan digunakan (contoh: untuk biaya pendidikan, modal usaha, renovasi rumah, dll.)" 
            required
            maxlength="500"
            <?= $disabledAttr ?>
          ></textarea>
          <div class="character-count" id="alqord-charcount">0/500 karakter</div>
          <div class="note">* Wajib diisi. Minimal 10 karakter.</div>
        </div>

        <div class="kv"><span>Cicilan / Bulan</span><span id="alqord-cicilan">-</span></div>
        <input type="hidden" name="jenis" value="qard">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Anda dengan sadar melakukan pengajuan ini.</label>
          <a href="<?= base_url('anggota/pin_alqordh')?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">📋 Syarat dan Persetujuan</a>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '✅ Ajukan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>

  <!-- Murabahah -->
  <section id="murabahah" class="tab-content">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="card-title">Ajukan Pinjaman Murabahah</div>
      <form id="form-murabahah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Harga Barang (Maks Rp 4.000.000)</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="murabahah-harga" name="jumlah" placeholder="Masukkan harga" required maxlength="10" data-max="4000000" <?= $disabledAttr ?>/>
          </div>
          <div class="error-message" id="murabahah-error">Maksimal pinjaman adalah Rp 4.000.000</div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="murabahah-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Deskripsi Barang yang Dibeli</label>
          <textarea 
            id="murabahah-deskripsi" 
            name="deskripsi" 
            placeholder="Jelaskan barang apa yang akan dibeli dengan pinjaman ini (contoh: laptop untuk kerja, mesin cuci, peralatan elektronik, dll.)" 
            required
            maxlength="500"
            <?= $disabledAttr ?>
          ></textarea>
          <div class="character-count" id="murabahah-charcount">0/500 karakter</div>
          <div class="note">* Wajib diisi. Minimal 10 karakter.</div>
        </div>

        <div class="kv"><span>Total + Margin (10%)</span><span id="murabahah-total">-</span></div>
        <div class="kv"><span>Cicilan / Bulan</span><span id="murabahah-cicilan">-</span></div>
        <p class="note">* Akan ditambah margin 10% dari harga.</p>
        <input type="hidden" name="jenis" value="murabahah">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Anda dengan sadar melakukan pengajuan ini.</label>
          <a href="<?= base_url('anggota/pin_murobahah')?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">📋 Syarat dan Persetujuan</a>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '✅ Ajukan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>

  <!-- Mudharabah -->
  <section id="mudharabah" class="tab-content">
    <div class="card <?= $cardDisabledClass ?>">
      <div class="card-title">Ajukan Pinjaman Mudharabah</div>
      <form id="form-mudharabah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Pinjaman (Maks Rp 4.000.000)</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="mudharabah-nominal" name="jumlah" placeholder="Masukkan jumlah" required maxlength="10" data-max="4000000" <?= $disabledAttr ?>/>
          </div>
          <div class="error-message" id="mudharabah-error">Maksimal pinjaman adalah Rp 4.000.000</div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="mudharabah-bulan" name="lama_cicilan" required <?= $disabledAttr ?>>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-input">
          <label>Deskripsi Usaha / Proyek</label>
          <textarea 
            id="mudharabah-deskripsi" 
            name="deskripsi" 
            placeholder="Jelaskan usaha atau proyek apa yang akan dibiayai dengan pinjaman ini (contoh: modal usaha warung, proyek jasa kontraktor, dagang online, dll.)" 
            required
            maxlength="500"
            <?= $disabledAttr ?>
          ></textarea>
          <div class="character-count" id="mudharabah-charcount">0/500 karakter</div>
          <div class="note">* Wajib diisi. Minimal 10 karakter.</div>
        </div>

        <div class="kv"><span>Total + Bagi Hasil (10%)</span><span id="mudharabah-total">-</span></div>
        <div class="kv"><span>Cicilan / Bulan</span><span id="mudharabah-cicilan">-</span></div>
        <p class="note">* Pengembalian ditambah bagi hasil 10%.</p>
        <input type="hidden" name="jenis" value="mudharabah">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required <?= $disabledAttr ?>> Anda dengan sadar melakukan pengajuan ini.</label>
          <a href="<?= base_url('anggota/pin_mudhorobah')?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">📋 Syarat dan Persetujuan</a>
        </div>

        <button type="submit" class="btn-ajukan" <?= $disabledAttr ?>>
          <?= $isDisabled ? $disabledText : '✅ Ajukan Pinjaman' ?>
        </button>
      </form>
    </div>
  </section>



  <!-- Bottom Navigation -->
  <nav class="bottom-nav">
    <a href="<?= base_url('anggota/dashboard')?>">
      <i data-lucide="home"></i>
      <p>Beranda</p>
    </a>
    <a href="<?= base_url('anggota/simpanan')?>">
      <i data-lucide="wallet"></i>
      <p>Simpan</p>
    </a>
    <a href="<?= base_url('anggota/pinjaman')?>" class="active">
      <i data-lucide="hand-coins"></i>
      <p>Pinjam</p>
    </a>
    <a href="<?= base_url('anggota/cicilan') ?>">
      <i data-lucide="calendar-check"></i>
      <p>Cicilan</p>
    </a>
    <a href="<?= base_url('anggota/profil')?>">
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

  #tenorBox select {
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

  #tenorBox select:focus {
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
    background: linear-gradient(135deg, #16a34a, #0d9488);
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
      Kamu bisa mencicil <strong>1 bulan</strong>, <strong>2 bulan</strong>, atau <strong>5 bulan.</strong>
    </p>

    <form action="<?= base_url('anggota/pinjaman/setTenor') ?>" method="post">
      <select name="tenor" required>
        <option value="">-- Pilih Tenor --</option>
        <option value="1">1 Bulan</option>
        <option value="2">2 Bulan</option>
        <option value="5">5 Bulan</option>
      </select>
      <button type="submit">Simpan Tenor</button>
    </form>
  </div>
</div>

<script>
  document.body.style.overflow = 'hidden';
</script>
<?php endif; ?>


  <script>
      lucide.createIcons();

    const MAX_PINJAMAN = 4000000;
    const isDisabled = <?= $isDisabled ? 'true' : 'false' ?>;
    const hasNoRekening = <?= $hasNoRekening ? 'true' : 'false' ?>;

    // Fungsi validasi nominal
    function validateNominal(input, errorElement) {
      const raw = input.value.replace(/\./g, "");
      const nominal = parseInt(raw) || 0;
      
      if (nominal > MAX_PINJAMAN) {
        errorElement.style.display = 'block';
        input.parentElement.classList.add('input-error');
        return false;
      } else {
        errorElement.style.display = 'none';
        input.parentElement.classList.remove('input-error');
        return true;
      }
    }

    // Fungsi update character count
    function updateCharacterCount(textarea, counter) {
      const length = textarea.value.length;
      counter.textContent = `${length}/500 karakter`;
      
      if (length > 450) {
        counter.classList.add('warning');
        counter.classList.remove('danger');
      } else if (length > 490) {
        counter.classList.add('danger');
        counter.classList.remove('warning');
      } else {
        counter.classList.remove('warning', 'danger');
      }
    }

    // Al-Qord
    document.getElementById("alqord-nominal").addEventListener("input", function() {
      validateNominal(this, document.getElementById("alqord-error"));
      updateAlqord();
    });
    
    document.getElementById("alqord-bulan").addEventListener("input", updateAlqord);
    
    document.getElementById("alqord-deskripsi").addEventListener("input", function() {
      updateCharacterCount(this, document.getElementById("alqord-charcount"));
    });

    function updateAlqord(){
      let raw = document.getElementById("alqord-nominal").value;
      const n = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("alqord-bulan").value) || 0;
      
      if(n > 0 && b > 0 && b <= 12 && n <= MAX_PINJAMAN){
        const cicilan = Math.round(n / b);
        document.getElementById("alqord-cicilan").textContent = "Rp " + cicilan.toLocaleString();
      } else {
        document.getElementById("alqord-cicilan").textContent = "-";
      }
    }

    // Murabahah
    document.getElementById("murabahah-harga").addEventListener("input", function() {
      validateNominal(this, document.getElementById("murabahah-error"));
      updateMurabahah();
    });
    
    document.getElementById("murabahah-bulan").addEventListener("input", updateMurabahah);

    document.getElementById("murabahah-deskripsi").addEventListener("input", function() {
      updateCharacterCount(this, document.getElementById("murabahah-charcount"));
    });

    function updateMurabahah(){
      let raw = document.getElementById("murabahah-harga").value;
      const h = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("murabahah-bulan").value) || 0;
      
      if(h > 0 && h <= MAX_PINJAMAN){
        const total = Math.round(h + (h * 0.1));
        const cicilan = Math.round(total / b);
        document.getElementById("murabahah-total").textContent = "Rp " + total.toLocaleString();
        document.getElementById("murabahah-cicilan").textContent = "Rp " + cicilan.toLocaleString();
      } else {
        document.getElementById("murabahah-total").textContent = "-";
        document.getElementById("murabahah-cicilan").textContent = "-";
      }
    }

    // Mudharabah
    document.getElementById("mudharabah-nominal").addEventListener("input", function() {
      validateNominal(this, document.getElementById("mudharabah-error"));
      updateMudharabah();
    });
    
    document.getElementById("mudharabah-bulan").addEventListener("input", updateMudharabah);

    document.getElementById("mudharabah-deskripsi").addEventListener("input", function() {
      updateCharacterCount(this, document.getElementById("mudharabah-charcount"));
    });

    function updateMudharabah(){
      let raw = document.getElementById("mudharabah-nominal").value;
      const n = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("mudharabah-bulan").value) || 0;
      
      if(n > 0 && n <= MAX_PINJAMAN){
        const total = Math.round(n + (n * 0.1));
        const cicilan = Math.round(total / b);
        document.getElementById("mudharabah-total").textContent = "Rp " + total.toLocaleString();
        document.getElementById("mudharabah-cicilan").textContent = "Rp " + cicilan.toLocaleString();
      } else {
        document.getElementById("mudharabah-total").textContent = "-";
        document.getElementById("mudharabah-cicilan").textContent = "-";
      }
    }

    // Tab switch
    function showTab(name){
      document.querySelectorAll('.tab-content').forEach(el=>el.classList.remove('active'));
      document.getElementById(name).classList.add('active');
      document.querySelectorAll('.tab-akad button').forEach(el=>el.classList.remove('active'));
      document.getElementById('tab-'+name).classList.add('active');
    }

    // Konfirmasi sebelum submit form pinjaman
      document.querySelectorAll('form[id^="form-"]').forEach(function(form){
      form.addEventListener('submit', function(e){
        if (isDisabled) {
          e.preventDefault();
          if (!hasNoRekening) {
            alert('Harap lengkapi nomor rekening di menu Profil terlebih dahulu sebelum mengajukan pinjaman.');
            // Redirect ke profil
            window.location.href = '<?= base_url('anggota/profil/edit') ?>';
          } else {
            alert('Anda memiliki pinjaman aktif. Silakan selesaikan pinjaman terlebih dahulu sebelum mengajukan pinjaman baru.');
          }
          return;
        }

        const checkboxes = form.querySelectorAll('.confirm-checkbox');
        let allChecked = true;

        checkboxes.forEach(cb => {
          if (!cb.checked) {
            allChecked = false;
          }
        });

        // Validasi nominal
        const nominalInput = form.querySelector('input[type="text"]');
        const rawNominal = nominalInput.value.replace(/\./g, "");
        const nominal = parseInt(rawNominal) || 0;

        // Validasi deskripsi
        const deskripsiInput = form.querySelector('textarea');
        const deskripsi = deskripsiInput.value.trim();

        if (!allChecked) {
          e.preventDefault();
          alert('Harap centang semua pernyataan sebelum mengajukan pinjaman.');
          return;
        }

        if (nominal > MAX_PINJAMAN) {
          e.preventDefault();
          alert('Nominal pinjaman melebihi batas maksimum Rp 4.000.000');
          return;
        }

        if (nominal <= 0) {
          e.preventDefault();
          alert('Nominal pinjaman harus lebih dari 0');
          return;
        }

        if (deskripsi === '') {
          e.preventDefault();
          alert('Harap isi deskripsi penggunaan pinjaman');
          deskripsiInput.focus();
          return;
        }

        if (deskripsi.length < 10) {
          e.preventDefault();
          alert('Deskripsi penggunaan pinjaman minimal 10 karakter');
          deskripsiInput.focus();
          return;
        }

        // Show loading state
        const submitBtn = form.querySelector('.btn-ajukan');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '⏳ Mengajukan...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form doesn't submit
        setTimeout(() => {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }, 3000);
      });
    });

    function formatRupiah(angka) {
      return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.querySelectorAll('input[id$="-nominal"], input[id$="-harga"]').forEach(input => {
      input.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value) {
          this.value = formatRupiah(value);
          
          // Validasi real-time
          const nominal = parseInt(value) || 0;
          const errorId = this.id.replace('-nominal', '-error').replace('-harga', '-error');
          const errorElement = document.getElementById(errorId);
          
          if (nominal > MAX_PINJAMAN) {
            errorElement.style.display = 'block';
            this.parentElement.classList.add('input-error');
          } else {
            errorElement.style.display = 'none';
            this.parentElement.classList.remove('input-error');
          }
        } else {
          this.value = '';
          const errorId = this.id.replace('-nominal', '-error').replace('-harga', '-error');
          const errorElement = document.getElementById(errorId);
          errorElement.style.display = 'none';
          this.parentElement.classList.remove('input-error');
        }
      });
    });

    // Validasi real-time untuk textarea
    document.querySelectorAll('textarea').forEach(textarea => {
      textarea.addEventListener('input', function() {
        const length = this.value.trim().length;
        if (length > 0 && length < 10) {
          this.classList.add('input-error');
        } else {
          this.classList.remove('input-error');
        }
      });
    });

    // Initialize character counts
    document.querySelectorAll('textarea').forEach(textarea => {
      const id = textarea.id;
      const counterId = id.replace('-deskripsi', '-charcount');
      const counter = document.getElementById(counterId);
      if (counter) {
        updateCharacterCount(textarea, counter);
      }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>
</body>
</html>