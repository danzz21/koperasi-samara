<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Bootstrap CSS for Modal -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

    /* Header Profil */
    .header-profil {
      background: var(--gradient-primary);
      color: white;
      padding: 2rem 1.5rem;
      text-align: center;
      border-radius: 0 0 30px 30px;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
      position: relative;
    }

    .back-btn {
      position: absolute;
      top: 1.5rem;
      left: 1.5rem;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 0.5rem 1rem;
      border-radius: var(--border-radius-sm);
      cursor: pointer;
      transition: var(--transition);
      backdrop-filter: blur(10px);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-1px);
    }

    .profile-avatar {
      position: relative;
      display: inline-block;
      margin-bottom: 1rem;
    }

    .profile-avatar img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar div {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 32px;
      font-weight: bold;
      border: 4px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .header-profil h2 {
      font-size: 24px;
      font-weight: 800;
      margin: 0.5rem 0 0.2rem 0;
      letter-spacing: -0.5px;
    }

    .header-profil p {
      margin: 0;
      opacity: 0.9;
      font-size: 14px;
    }

    /* Form Styles */
    .card {
      background: white;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin: 1rem 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.5);
      transition: var(--transition);
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
    }

    .card-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    .form-label {
      display: block;
      color: var(--gray);
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      font-size: 14px;
      transition: var(--transition);
      background: var(--light);
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      background: white;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }

    .error-message {
      color: var(--danger);
      font-size: 12px;
      margin-top: 0.25rem;
      display: block;
    }

    .success-message {
      color: var(--success);
      font-size: 12px;
      margin-top: 0.25rem;
      display: block;
    }

    .password-toggle {
      position: relative;
    }

    .password-toggle-icon {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--gray);
    }

    /* PIN Section */
    .pin-section {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--gray-light);
    }

    .pin-status {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .pin-status-label {
      font-weight: 600;
      color: var(--dark);
    }

    .pin-status-value {
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
    }

    .pin-status-active {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .pin-status-inactive {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    /* Button Styles */
    .btn-group {
      display: flex;
      gap: 1rem;
      margin: 1rem 1.5rem;
    }

    .btn {
      flex: 1;
      padding: 12px 24px;
      border: none;
      border-radius: var(--border-radius-sm);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      font-size: 14px;
    }

    .btn-primary {
      background: var(--gradient-primary);
      color: white;
      box-shadow: var(--shadow);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
      background: var(--gray-light);
      color: var(--dark);
    }

    .btn-secondary:hover {
      background: var(--gray);
      color: white;
    }

    .btn-warning {
      background: var(--warning);
      color: white;
      box-shadow: var(--shadow);
    }

    .btn-warning:hover {
      background: #d97706;
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* Modal Custom Styles */
    .modal-content {
      border-radius: var(--border-radius);
      border: none;
      box-shadow: var(--shadow-lg);
    }

    .modal-header {
      background: var(--light);
      border-bottom: 1px solid var(--gray-light);
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .modal-footer {
      background: var(--light);
      border-top: 1px solid var(--gray-light);
      border-radius: 0 0 var(--border-radius) var(--border-radius);
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

    /* Responsive */
    @media (max-width: 480px) {
      .header-profil {
        padding: 1.5rem 1.2rem;
      }
      
      .card {
        margin: 1rem 1.2rem;
      }
      
      .back-btn {
        padding: 0.4rem 0.8rem;
      }
      
      .profile-avatar img,
      .profile-avatar div {
        width: 90px;
        height: 90px;
      }
      
      .header-profil h2 {
        font-size: 22px;
      }
    }

    @media (min-width: 768px) {
      .header-profil {
        padding: 2.5rem 1.5rem;
      }
      
      .profile-avatar img,
      .profile-avatar div {
        width: 120px;
        height: 120px;
      }
      
      .header-profil h2 {
        font-size: 26px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header-profil">
    <!-- Tombol Kembali -->
    <a href="<?= base_url('anggota/profil') ?>" class="back-btn">
      <i data-lucide="arrow-left"></i>
      <span>Kembali</span>
    </a>

    <div class="profile-avatar">
      <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
        <img src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
      <?php else: ?>
        <?php 
          $firstLetter = strtoupper(substr($nama, 0, 1));
          $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
          $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
        ?>
        <div style="background:<?= $bgColor ?>">
          <?= $firstLetter ?>
        </div>
      <?php endif; ?>
    </div>

    <h2>Edit Profil</h2>
    <p>ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></p>
  </header>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="card">
      <div style="color: var(--success); display: flex; align-items: center; gap: 10px;">
        <i data-lucide="check-circle" style="color: var(--success);"></i>
        <span><?= session()->getFlashdata('success') ?></span>
      </div>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="card">
      <div style="color: var(--danger); display: flex; align-items: center; gap: 10px;">
        <i data-lucide="alert-circle" style="color: var(--danger);"></i>
        <span><?= session()->getFlashdata('error') ?></span>
      </div>
    </div>
  <?php endif; ?>

  <!-- Form Data Pribadi -->
  <form action="<?= base_url('anggota/profil/update') ?>" method="post">
    <!-- Tambahkan CSRF Token -->
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    
    <div class="card">
        <div class="card-title">
            <i data-lucide="user" width="20" height="20"></i>
            Data Pribadi
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-input" value="<?= old('nama_lengkap', $anggota['nama_lengkap'] ?? '') ?>" required>
            <?php if (session('errors.nama_lengkap')): ?>
                <span class="error-message"><?= session('errors.nama_lengkap') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="<?= old('email', $anggota['email'] ?? '') ?>" required>
            <?php if (session('errors.email')): ?>
                <span class="error-message"><?= session('errors.email') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="tel" name="no_hp" class="form-input" value="<?= old('no_hp', $anggota['no_hp'] ?? '') ?>" required>
            <?php if (session('errors.no_hp')): ?>
                <span class="error-message"><?= session('errors.no_hp') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-input form-textarea" required><?= old('alamat', $anggota['alamat'] ?? '') ?></textarea>
            <?php if (session('errors.alamat')): ?>
                <span class="error-message"><?= session('errors.alamat') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Jenis Bank</label>
            <select name="jenis_bank" class="form-input">
                <option value="">Pilih Jenis Bank</option>
                <option value="BCA" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'BCA' ? 'selected' : '' ?>>BCA</option>
                <option value="BRI" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'BRI' ? 'selected' : '' ?>>BRI</option>
                <option value="BNI" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'BNI' ? 'selected' : '' ?>>BNI</option>
                <option value="Mandiri" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                <option value="BSI" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'BSI' ? 'selected' : '' ?>>BSI</option>
                <option value="CIMB Niaga" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'CIMB Niaga' ? 'selected' : '' ?>>CIMB Niaga</option>
                <option value="Bank Jatim" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'Bank Jatim' ? 'selected' : '' ?>>Bank Jatim</option>
                <option value="Bank Jateng" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'Bank Jateng' ? 'selected' : '' ?>>Bank Jateng</option>
                <option value="Bank DKI" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'Bank DKI' ? 'selected' : '' ?>>Bank DKI</option>
                <option value="Bank Lainnya" <?= old('jenis_bank', $anggota['jenis_bank'] ?? '') == 'Bank Lainnya' ? 'selected' : '' ?>>Bank Lainnya</option>
            </select>
            <?php if (session('errors.jenis_bank')): ?>
                <span class="error-message"><?= session('errors.jenis_bank') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">No. Rekening</label>
            <input type="text" name="no_rek" class="form-input" value="<?= old('no_rek', $anggota['no_rek'] ?? '') ?>" placeholder="Masukkan nomor rekening">
            <?php if (session('errors.no_rek')): ?>
                <span class="error-message"><?= session('errors.no_rek') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Atas Nama Rekening</label>
            <input type="text" name="atasnama_rekening" class="form-input" value="<?= old('atasnama_rekening', $anggota['atasnama_rekening'] ?? '') ?>" placeholder="Nama pemilik rekening">
            <?php if (session('errors.atasnama_rekening')): ?>
                <span class="error-message"><?= session('errors.atasnama_rekening') ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section PIN -->
    <div class="card">
        <div class="card-title">
            <i data-lucide="key" width="20" height="20"></i>
            Pengaturan PIN
        </div>

        <div class="pin-section">
            <div class="pin-status">
                <span class="pin-status-label">Status PIN:</span>
                <span class="pin-status-value <?= $hasPin ? 'pin-status-active' : 'pin-status-inactive' ?>">
                    <?= $hasPin ? '✓ Sudah Ada' : '✗ Belum Dibuat' ?>
                </span>
            </div>

            <?php if ($hasPin): ?>
                <button type="button" class="btn btn-warning" style="width: 100%;" onclick="showChangePinModal()">
                    <i data-lucide="refresh-cw"></i>
                    Ubah PIN
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-primary" style="width: 100%;" onclick="showCreatePinModal()">
                    <i data-lucide="key"></i>
                    Buat PIN Baru
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Password Section -->
    <div class="card">
        <div class="card-title">
            <i data-lucide="lock" width="20" height="20"></i>
            Ubah Password
        </div>

        <div class="form-group">
            <label class="form-label">Password Baru</label>
            <div class="password-toggle">
                <input type="password" name="password" id="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah">
                <span class="password-toggle-icon" onclick="togglePassword('password', this)">
                    <i data-lucide="eye"></i>
                </span>
            </div>
            <?php if (session('errors.password')): ?>
                <span class="error-message"><?= session('errors.password') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <div class="password-toggle">
                <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Ulangi password baru">
                <span class="password-toggle-icon" onclick="togglePassword('confirm_password', this)">
                    <i data-lucide="eye"></i>
                </span>
            </div>
            <?php if (session('errors.confirm_password')): ?>
                <span class="error-message"><?= session('errors.confirm_password') ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="btn-group">
        <a href="<?= base_url('anggota/profil') ?>" class="btn btn-secondary">
            <i data-lucide="x"></i>
            Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save"></i>
            Simpan Perubahan
        </button>
    </div>
  </form>

  <!-- Modal untuk Buat PIN Baru -->
  <div class="modal fade" id="createPinModal" tabindex="-1" aria-labelledby="createPinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPinModalLabel">Buat PIN Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="createPinForm" action="<?= base_url('anggota/profil/update-pin') ?>" method="POST">
          <div class="modal-body">
            <p>Buat PIN 6 digit untuk keamanan transaksi:</p>
            <div class="form-group">
              <label class="form-label">PIN Baru (6 digit)</label>
              <input type="password" class="form-control" id="new_pin" name="new_pin" 
                     placeholder="Masukkan 6 digit PIN" maxlength="6" 
                     pattern="\d{6}" title="Harus 6 digit angka" required>
            </div>
            <div class="form-group">
              <label class="form-label">Konfirmasi PIN</label>
              <input type="password" class="form-control" id="confirm_new_pin" name="confirm_new_pin" 
                     placeholder="Konfirmasi 6 digit PIN" maxlength="6" 
                     pattern="\d{6}" title="Harus 6 digit angka" required>
            </div>
            <div id="pinMessage" class="mt-3" style="display: none;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Buat PIN</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal untuk Ubah PIN -->
  <div class="modal fade" id="changePinModal" tabindex="-1" aria-labelledby="changePinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePinModalLabel">Ubah PIN</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="changePinForm" action="<?= base_url('anggota/profil/change-pin') ?>" method="POST">
          <div class="modal-body">
            <p>Ubah PIN 6 digit Anda:</p>
            <div class="form-group">
              <label class="form-label">PIN Lama</label>
              <input type="password" class="form-control" id="old_pin" name="old_pin" 
                     placeholder="Masukkan PIN lama" maxlength="6" 
                     pattern="\d{6}" title="Harus 6 digit angka" required>
            </div>
            <div class="form-group">
              <label class="form-label">PIN Baru (6 digit)</label>
              <input type="password" class="form-control" id="new_pin_change" name="new_pin" 
                     placeholder="Masukkan 6 digit PIN baru" maxlength="6" 
                     pattern="\d{6}" title="Harus 6 digit angka" required>
            </div>
            <div class="form-group">
              <label class="form-label">Konfirmasi PIN Baru</label>
              <input type="password" class="form-control" id="confirm_new_pin_change" name="confirm_new_pin" 
                     placeholder="Konfirmasi 6 digit PIN baru" maxlength="6" 
                     pattern="\d{6}" title="Harus 6 digit angka" required>
            </div>
            <div id="changePinMessage" class="mt-3" style="display: none;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Ubah PIN</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bottom Nav -->
  <nav class="bottom-nav">
    <a href="<?= base_url('anggota/dashboard')?>">
      <i data-lucide="home"></i>
      <p>Beranda</p>
    </a>
    <a href="<?= base_url('anggota/simpanan')?>">
      <i data-lucide="wallet"></i>
      <p>Simpan</p>
    </a>
    <a href="<?= base_url('anggota/pinjaman')?>">
      <i data-lucide="hand-coins"></i>
      <p>Pinjam</p>
    </a>
    <a href="<?= base_url('anggota/cicilan') ?>">
      <i data-lucide="calendar-check"></i>
      <p>Cicilan</p>
    </a>
    <a href="<?= base_url('anggota/profil')?>" class="active">
      <i data-lucide="user"></i>
      <p>Profil</p>
    </a>
  </nav>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    lucide.createIcons();
    
    function togglePassword(inputId, icon) {
      const input = document.getElementById(inputId);
      const eyeIcon = icon.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.setAttribute('data-lucide', 'eye-off');
      } else {
        input.type = 'password';
        eyeIcon.setAttribute('data-lucide', 'eye');
      }
      lucide.createIcons();
    }
    
    function showCreatePinModal() {
      const modal = new bootstrap.Modal(document.getElementById('createPinModal'));
      modal.show();
    }
    
    function showChangePinModal() {
      const modal = new bootstrap.Modal(document.getElementById('changePinModal'));
      modal.show();
    }
    
    // Handle Create PIN Form
    document.getElementById('createPinForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const newPin = document.getElementById('new_pin').value;
      const confirmPin = document.getElementById('confirm_new_pin').value;
      const messageDiv = document.getElementById('pinMessage');
      
      // Validasi
      if (newPin.length !== 6) {
        showMessage('PIN harus 6 digit', 'danger', messageDiv);
        return;
      }
      
      if (newPin !== confirmPin) {
        showMessage('PIN tidak cocok', 'danger', messageDiv);
        return;
      }
      
      // Submit form
      $.ajax({
        url: this.action,
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            showMessage(response.message, 'success', messageDiv);
            setTimeout(() => {
              location.reload();
            }, 1500);
          } else {
            showMessage(response.message, 'danger', messageDiv);
          }
        },
        error: function() {
          showMessage('Terjadi kesalahan. Silakan coba lagi.', 'danger', messageDiv);
        }
      });
    });
    
    // Handle Change PIN Form
    document.getElementById('changePinForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const oldPin = document.getElementById('old_pin').value;
      const newPin = document.getElementById('new_pin_change').value;
      const confirmPin = document.getElementById('confirm_new_pin_change').value;
      const messageDiv = document.getElementById('changePinMessage');
      
      // Validasi
      if (oldPin.length !== 6 || newPin.length !== 6) {
        showMessage('PIN harus 6 digit', 'danger', messageDiv);
        return;
      }
      
      if (newPin !== confirmPin) {
        showMessage('PIN baru tidak cocok', 'danger', messageDiv);
        return;
      }
      
      // Submit form
      $.ajax({
        url: this.action,
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            showMessage(response.message, 'success', messageDiv);
            setTimeout(() => {
              location.reload();
            }, 1500);
          } else {
            showMessage(response.message, 'danger', messageDiv);
          }
        },
        error: function() {
          showMessage('Terjadi kesalahan. Silakan coba lagi.', 'danger', messageDiv);
        }
      });
    });
    
    function showMessage(message, type, messageDiv) {
      messageDiv.innerHTML = `
        <div class="alert alert-${type}" role="alert">
          ${message}
        </div>
      `;
      messageDiv.style.display = 'block';
      
      // Auto hide setelah 5 detik
      setTimeout(() => {
        messageDiv.style.display = 'none';
      }, 5000);
    }
    
    // Clear modal form saat ditutup
    document.getElementById('createPinModal')?.addEventListener('hidden.bs.modal', function () {
      document.getElementById('new_pin').value = '';
      document.getElementById('confirm_new_pin').value = '';
      document.getElementById('pinMessage').innerHTML = '';
      document.getElementById('pinMessage').style.display = 'none';
    });
    
    document.getElementById('changePinModal')?.addEventListener('hidden.bs.modal', function () {
      document.getElementById('old_pin').value = '';
      document.getElementById('new_pin_change').value = '';
      document.getElementById('confirm_new_pin_change').value = '';
      document.getElementById('changePinMessage').innerHTML = '';
      document.getElementById('changePinMessage').style.display = 'none';
    });
  </script>
</body>
</html>