<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil</title>
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

    .logout-form {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
    }

    .logout-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 0.5rem 1rem;
      border-radius: var(--border-radius-sm);
      cursor: pointer;
      transition: var(--transition);
      backdrop-filter: blur(10px);
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-1px);
    }

    .logout-btn h6 {
      margin: 0;
      font-size: 14px;
      font-weight: 600;
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

    .edit-icon {
      position: absolute;
      bottom: 5px;
      right: 5px;
      background: var(--primary);
      color: white;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
      border: 2px solid white;
    }

    .edit-icon:hover {
      background: var(--primary-dark);
      transform: scale(1.1);
    }

    #file-upload {
      display: none;
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

    /* Card Styles */
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

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem 0;
      border-bottom: 1px solid var(--gray-light);
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .info-label {
      color: var(--gray);
      font-size: 14px;
      font-weight: 500;
    }

    .info-value {
      color: var(--dark);
      font-weight: 600;
      font-size: 14px;
      text-align: right;
    }

    /* Button Styles */
    .btn-group {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin: 1rem 1.5rem;
    }

    .btn {
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
      text-align: center;
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
      background: #f8fafc;
      color: var(--dark);
      border: 2px solid var(--gray-light);
    }

    .btn-secondary:hover {
      background: var(--gray-light);
    }

    /* Alert Styles */
    .alert {
      padding: 12px 16px;
      border-radius: var(--border-radius-sm);
      margin: 1rem 1.5rem;
      font-weight: 500;
    }

    .alert-success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
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
      
      .logout-btn {
        padding: 0.4rem 0.8rem;
      }
      
      .logout-btn h6 {
        font-size: 12px;
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
      
      .btn-group {
        flex-direction: row;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header-profil">
    <!-- Tombol Logout -->
    <form action="<?= base_url('logout') ?>" method="get" class="logout-form">
      <button type="submit" class="logout-btn">
        <i data-lucide="log-out"></i>
        <h6>Log out</h6>
      </button>
    </form>

    <form id="formFoto" action="<?= base_url('anggota/profil/updateFoto') ?>" method="post" enctype="multipart/form-data">
      <div class="profile-avatar">
        <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
          <img id="preview" src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
        <?php else: ?>
          <?php 
            $firstLetter = strtoupper(substr($nama, 0, 1));
            $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
            $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
          ?>
          <div id="preview" style="background:<?= $bgColor ?>">
            <?= $firstLetter ?>
          </div>
        <?php endif; ?>

        <!-- Tombol edit -->
        <label for="file-upload" class="edit-icon">
          <i data-lucide="image"></i>
        </label>
        <input id="file-upload" type="file" name="photo" accept="image/*" onchange="uploadFoto(event)">
      </div>
    </form>

    <h2><?= htmlspecialchars($nama ?? '-') ?></h2>
    <p>ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></p>
  </header>

  <!-- Alert Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

 <!-- Card Info -->
<!-- Card Info -->
<div class="card">
    <div class="card-title">
        <i data-lucide="user" width="20" height="20"></i>
        Data Pribadi
    </div>
    <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-value"><?= htmlspecialchars($nama ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($email ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">No. HP</span><span class="info-value"><?= htmlspecialchars($no_hp ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">Alamat</span><span class="info-value"><?= htmlspecialchars($alamat ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">Jenis Bank</span><span class="info-value"><?= htmlspecialchars($jenis_bank ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">No. Rekening</span><span class="info-value"><?= htmlspecialchars($no_rek ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">Atas Nama Rekening</span><span class="info-value"><?= htmlspecialchars($atasnama_rekening ?? '-') ?></span></div>
</div>

  <div class="card">
    <div class="card-title">
      <i data-lucide="users" width="20" height="20"></i>
      Keanggotaan
    </div>
    <div class="info-row"><span class="info-label">Jenis Anggota</span><span class="info-value"><?= htmlspecialchars($jenis_anggota ?? 'Reguler') ?></span></div>
    <div class="info-row"><span class="info-label">Tanggal Bergabung</span><span class="info-value"><?= htmlspecialchars($tanggal_daftar ?? '-') ?></span></div>
    <div class="info-row"><span class="info-label">Status</span><span class="info-value"><?= htmlspecialchars($status ?? '-') ?></span></div>
  </div>

  <!-- Button Group -->
  <div class="btn-group">
    <button class="btn btn-primary" onclick="window.location.href='<?= base_url('anggota/profil/edit') ?>'">
      <i data-lucide="edit-3"></i>
      Edit Profil
    </button>
    <button class="btn btn-secondary" onclick="window.location.href='<?= base_url('anggota/profil/cetak-kartu') ?>'">
      <i data-lucide="credit-card"></i>
      Cetak Kartu Anggota
    </button>
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

  <script>
    lucide.createIcons();
    
    function uploadFoto(event) {
      const fileInput = event.target;
      const form = document.getElementById('formFoto');

      // preview dulu
      const reader = new FileReader();
      reader.onload = function(){
        const preview = document.getElementById('preview');
        if(preview.tagName.toLowerCase() === 'img'){
          preview.src = reader.result;
        } else {
          preview.style.backgroundImage = "url('" + reader.result + "')";
          preview.style.backgroundSize = "cover";
          preview.textContent = '';
        }
      }
      reader.readAsDataURL(fileInput.files[0]);

      // langsung submit
      form.submit();
    }
  </script>
</body>
</html>