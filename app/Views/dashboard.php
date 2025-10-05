<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Aplikasi Koperasi</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>" />
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
  .saldo-container {
    display: flex;
    gap: 1rem;
    padding: 0 1rem;
    margin-top: 1rem;
    flex-wrap: wrap; /* biar responsif di layar kecil */
  }

  .saldo-card {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    padding: 1rem;
    position: relative;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    min-width: 140px;
  }

  .saldo-card .motif {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0.1;
    width: 50px;
  }

  .saldo-card .motif img {
    width: 100%;
  }

  .saldo-card .label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
  }

  .saldo-card .amount {
    font-size: 20px;
    font-weight: bold;
    color: #222;
  }
</style>

</head>
<body>

  <!-- Header -->
  <header class="header">
    <div class="profile">
      <?php 
        $firstLetter = strtoupper(substr($anggota['nama_lengkap'], 0, 1));
        $colors = ['#16a34a', '#2563eb', '#dc2626', '#9333ea', '#f59e0b', '#0d9488', '#4b5563'];
        $bgColor = $colors[crc32($anggota['nomor_anggota']) % count($colors)];
      ?>
      <div class="profile-avatar" style="background:<?= $bgColor ?>;">
        <?= $firstLetter ?>
      </div>
      <div>
        <div class="header-name"><?= esc($anggota['nama_lengkap']) ?></div>
        <div style="font-size:12px;opacity:.9;">ID: <?= esc($anggota['nomor_anggota']) ?></div>
      </div>
    </div>
    <i data-lucide="bell" class="icon"></i>
  </header>

  <!-- Card Saldo -->
  <!-- Card Saldo & Pinjaman (Sampingan) -->
<div class="saldo-container">
  <!-- Card Saldo -->
  <section class="saldo-card">
    <div class="motif">
      <img src="https://i.ibb.co/xM3RStn/motif.png" alt="motif" />
    </div>
    <p class="label">Total Simpanan</p>
    <h2 class="amount">Rp <?= number_format($total_saldo, 0, ',', '.') ?></h2>
  </section>

  <!-- Card Pinjaman -->
  <section class="saldo-card">
    <div class="motif">
      <img src="https://i.ibb.co/xM3RStn/motif.png" alt="motif" />
    </div>
    <p class="label">Total Pinjaman</p>
    <h2 class="amount">Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></h2>
  </section>
</div>



  <!-- Menu -->
  <h3>Apa itu simpan dan pinjam?</h3>
  <section class="menu">
    <div class="menu-item">
      <a href="<?= base_url('anggota/sim_pokok') ?>">
        <i data-lucide="wallet"></i>
        <p>Simpanan Pokok</p>
      </a>
    </div>
    <div class="menu-item">
      <a href="<?= base_url('anggota/sim_wajib') ?>">
        <i data-lucide="calendar"></i>
        <p>Simpanan Wajib</p>
      </a>
    </div>
    <div class="menu-item">
      <a href="<?= base_url('anggota/sim_sukarela') ?>">
        <i data-lucide="gift"></i>
        <p>Simpanan Sukarela</p>
      </a>
    </div>
    <div class="menu-item">
      <a href="<?= base_url('anggota/pin_alqordh') ?>">
        <i data-lucide="check-square"></i>
        <p>Al-Qordhu</p>
      </a>
    </div>
    <div class="menu-item">
      <a href="<?= base_url('anggota/pin_murobahah') ?>">
        <i data-lucide="wallet"></i>
        <p>Murobahah</p>
      </a>
    </div>
    <div class="menu-item">
      <a href="<?= base_url('anggota/pin_mudhorobah') ?>">
        <i data-lucide="check-square"></i>
        <p>Mudhorobah</p>
      </a>
    </div>
  </section>

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
    <a href="<?= base_url('anggota/profil') ?>">
      <i data-lucide="user"></i>
      <p>Profil</p>
    </a>
  </nav>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
