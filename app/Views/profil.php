<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="<?= base_url('assets/css/profil.css')?>">

</head>
<body>

  <!-- Header -->
  <header class="header-profil">
    <?php 
      $firstLetter = strtoupper(substr($nama, 0, 1));
      $colors = ['#16a34a', '#2563eb', '#dc2626', '#9333ea', '#f59e0b', '#0d9488', '#4b5563'];
      $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
    ?>
    <div class="profile-avatar" style="background:<?= $bgColor ?>;">
      <?= $firstLetter ?>
    </div>
  <h2><?= htmlspecialchars($nama ?? '-') ?></h2>
  <p>ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></p>
  </header>

  <!-- Card Info -->
  <div class="card">
    <div class="card-title">Data Pribadi</div>
  <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-value"><?= htmlspecialchars($nama ?? '-') ?></span></div>
  <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($email ?? '-') ?></span></div>
  <div class="info-row"><span class="info-label">No. HP</span><span class="info-value"><?= htmlspecialchars($no_hp ?? '-') ?></span></div>
  <div class="info-row"><span class="info-label">Alamat</span><span class="info-value"><?= htmlspecialchars($alamat ?? '-') ?></span></div>
  </div>

  <div class="card">
    <div class="card-title">Keanggotaan</div>
  <div class="info-row"><span class="info-label">Jenis Anggota</span><span class="info-value"><?= htmlspecialchars($jenis_anggota ?? 'Reguler') ?></span></div>
  <div class="info-row"><span class="info-label">Tanggal Bergabung</span><span class="info-value"><?= htmlspecialchars($tanggal_daftar ?? '-') ?></span></div>
  <div class="info-row"><span class="info-label">Status</span><span class="info-value"><?= htmlspecialchars($status ?? '-') ?></span></div>
  </div>

  <!-- Logout -->
  <form action="<?= base_url('logout') ?>" method="get" style="margin:0;">
    <button type="submit" class="btn-logout"><i data-lucide="log-out"></i> Logout</button>
  </form>

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
    <a href="<?= base_url('anggota/profil')?>" class="active">
      <i data-lucide="user"></i>
      <p>Profil</p>
    </a>
  </nav>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>
</body>
</html>
