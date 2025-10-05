<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Simpanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Pakai style global kamu -->
  <link rel="stylesheet" href="<?= base_url('assets/css/simpanan.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .modal {
  display: none;
  position: fixed;
  z-index: 100;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  position: relative;
}

.close {
  color: #aaa;
  position: absolute;
  right: 20px;
  top: 15px;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-weight: 600;
  display: block;
  margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.btn-submit {
  background-color: #16a34a;
  color: white;
  padding: 10px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-submit:hover {
  background-color: #15803d;
}

.profile-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  border: 2px solid #fff;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 20px;
  margin-right: 10px;
}

  </style>
</head>
<body>
  <!-- Header -->
  <header class="header-simpan">
    <div class="header-info">
    <?php 
      $firstLetter = strtoupper(substr($nama, 0, 1));
      $colors = ['#16a34a', '#2563eb', '#dc2626', '#9333ea', '#f59e0b', '#0d9488', '#4b5563'];
      $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
    ?>
    <div class="profile-avatar" style="background:<?= $bgColor ?>;">
      <?= $firstLetter ?>
    </div>

      <div>
        <div class="header-name"><?= htmlspecialchars($nama ?? '-') ?></div>
        <div style="font-size:12px;opacity:.9;">ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></div>
      </div>
    </div>
    <i data-lucide="bell" class="icon"></i>
  </header>
  <h2 class="page-title">Simpanan</h2>



  <!-- ===== Tabs ===== -->
  <div class="tab-simpanan">
    <button id="tab-pokok" class="active" onclick="showTab('pokok')">Pokok</button>
    <button id="tab-wajib" onclick="showTab('wajib')">Wajib</button>
    <button id="tab-sukarela" onclick="showTab('sukarela')">Sukarela</button>
  </div>

  <!-- ===== POKOK ===== -->
  <section id="pokok" class="tab-content active">
    <div class="card">
      <div class="card-title">Rangkuman Simpanan Pokok</div>
      <div class="kv"><span class="k">Total Simpanan</span>
        <span class="v">
          Rp <?= number_format(array_sum(array_column($pokok, 'jumlah')), 0, ',', '.') ?>
        </span>
      </div>
      <div class="kv"><span class="k">Cicilan</span>
        <span class="v"><?= count($pokok) ?>x</span>
      </div>
      <div class="kv"><span class="k">Angsuran / Bulan</span>
        <span class="v">
          Rp <?= count($pokok) > 0 ? number_format($pokok[0]['jumlah'], 0, ',', '.') : '0' ?>
        </span>
      </div>
      <div class="row" style="margin-top:8px;">
        <span class="k">Status</span>
        <span class="badge <?= (end($pokok)['status'] ?? '') == 'lunas' ? 'badge-lunas' : 'badge-belum' ?>">
          <i class="fa-regular fa-clock"></i>
          <?= (end($pokok)['status'] ?? '') == 'lunas' ? 'Lunas' : 'Berjalan' ?>
        </span>
      </div>
      <div class="divider"></div>
      <div class="row">
        <span class="k">Sumber Pembayaran</span>
        <span class="badge badge-gaji"><i class="fa-solid fa-money-bill-wave"></i> Potong Gaji</span>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Jadwal Cicilan</div>
      <?php foreach ($pokok as $i => $item): ?>
        <div class="bill">
          <div class="bill-icon"><i class="fa-solid fa-calendar-day"></i></div>
          <div class="bill-main">
            <div class="bill-title"><?= date('F Y', strtotime($item['tanggal'])) ?></div>
            <div class="bill-sub">Cicilan ke-<?= $i+1 ?> • Potong gaji</div>
          </div>
          <div class="bill-amount">
            <div class="nominal">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></div>
            <span class="badge <?= $item['status']=='lunas' ? 'badge-lunas' : 'badge-belum' ?>">
              <i class="fa-regular <?= $item['status']=='lunas' ? 'fa-check' : 'fa-clock' ?>"></i>
              <?= ucfirst($item['status']) ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($pokok)): ?>
        <div style="padding:16px;text-align:center;color:#888;">Belum ada data simpanan pokok.</div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== WAJIB ===== -->
  <section id="wajib" class="tab-content">
    <div class="card">
      <div class="card-title">Rangkuman Simpanan Wajib</div>
      <div class="kv"><span class="k">Nominal / Bulan</span>
        <span class="v">
          Rp <?= count($wajib) > 0 ? number_format($wajib[0]['jumlah'], 0, ',', '.') : '0' ?>
        </span>
      </div>
      <div class="row" style="margin-top:8px;">
        <span class="k">Sumber</span>
        <span class="badge badge-gaji"><i class="fa-solid fa-money-bill-wave"></i> Potong Gaji</span>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Status Bulanan</div>
      <?php foreach ($wajib as $item): ?>
        <div class="bill">
          <div class="bill-icon"><i class="fa-solid fa-calendar-week"></i></div>
          <div class="bill-main">
            <div class="bill-title"><?= date('F Y', strtotime($item['tanggal'])) ?></div>
            <div class="bill-sub">Pembayaran otomatis</div>
          </div>
          <div class="bill-amount">
            <div class="nominal">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></div>
            <span class="badge <?= $item['status']=='lunas' ? 'badge-lunas' : 'badge-belum' ?>">
              <i class="fa-regular <?= $item['status']=='lunas' ? 'fa-check' : 'fa-clock' ?>"></i>
              <?= ucfirst($item['status']) ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($wajib)): ?>
        <div style="padding:16px;text-align:center;color:#888;">Belum ada data simpanan wajib.</div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== SUKARELA (Setor saja) ===== -->
  <section id="sukarela" class="tab-content">
    <div class="card">
      <div class="card-title">Simpanan Sukarela</div>
      <?php
  $sukarela_approved = array_filter($sukarela, fn($item) => $item['status'] === 'aktif');
  $total_sukarela = array_sum(array_column($sukarela_approved, 'jumlah'));
?>
<div class="kv"><span class="k">Saldo Saat Ini</span>
  <span class="v">
    Rp <?= number_format($total_sukarela, 0, ',', '.') ?>
  </span>
</div>

      <div class="kv"><span class="k">Keterangan</span>
        <span class="v" style="color:var(--gray-700);font-weight:600;">Setor kapan saja</span>
      </div>
    </div>

    <!-- Hanya setor -->
    <button class="btn-setor"><i class="fa-solid fa-circle-plus"></i> &nbsp; + Setor Simpanan</button>
    <div class="note">Penarikan tidak tersedia untuk Simpanan Sukarela.</div>

    <div class="card">
      <div class="card-title">Riwayat Setoran</div>
      <?php foreach ($sukarela as $item): ?>
        <div class="bill">
          <div class="bill-icon" style="background:#f0fdf4;color:#16a34a;">
            <i class="fa-solid fa-arrow-down"></i>
          </div>
          <div class="bill-main">
            <div class="bill-title">Setor</div>
            <div class="bill-sub"><?= date('d M Y', strtotime($item['tanggal'])) ?> • Ref: SR-<?= date('md', strtotime($item['tanggal'])) ?></div>
          </div>
          <div class="bill-amount">
            <div class="nominal">+ Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></div>
            <span class="badge 
  <?= $item['status']=='aktif' ? 'badge-lunas' : ($item['status']=='pending' ? 'badge-belum' : 'badge-ditolak') ?>">
  <i class="fa-regular 
     <?= $item['status']=='aktif' ? 'fa-check' : ($item['status']=='pending' ? 'fa-clock' : 'fa-xmark') ?>">
  </i>
  <?= $item['status']=='aktif' ? 'Terkonfirmasi' : ($item['status']=='pending' ? 'Belum' : 'Ditolak') ?>
</span>

          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($sukarela)): ?>
        <div style="padding:16px;text-align:center;color:#888;">Belum ada setoran sukarela.</div>
      <?php endif; ?>
    </div>
  </section>
<!-- Modal Input Setoran Sukarela -->
<div id="modalSukarela" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Setor Simpanan Sukarela</h3>
    <form action="<?= base_url('anggota/simpanan/sukarela/store') ?>" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label for="jumlah">Jumlah Setoran (Rp)</label>
        <input type="number" name="jumlah" id="jumlah" required />
      </div>
      <div class="form-group">
        <label for="bukti">Upload Bukti Transfer</label>
        <input type="file" name="bukti" id="bukti" accept="image/*,application/pdf" required />
      </div>
      <button type="submit" class="btn-submit">Kirim Setoran</button>
    </form>
  </div>
</div>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
      <a href="<?= base_url('anggota/dashboard') ?>">
        <i data-lucide="home"></i>
        <p>Beranda</p>
      </a>
      <a href="<?= base_url('anggota/simpanan') ?>" class="active">
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


  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>
  <script>
    function showTab(name){
      // content
      document.querySelectorAll('.tab-content').forEach(el=>{
        el.classList.remove('active');
      });
      document.getElementById(name).classList.add('active');

      // tabs
      document.querySelectorAll('.tab-simpanan button').forEach(el=>el.classList.remove('active'));
      if(name==='pokok') document.getElementById('tab-pokok').classList.add('active');
      if(name==='wajib') document.getElementById('tab-wajib').classList.add('active');
      if(name==='sukarela') document.getElementById('tab-sukarela').classList.add('active');
    }

  // Modal logic
  const modal = document.getElementById('modalSukarela');
  const btn = document.querySelector('.btn-setor');
  const span = document.querySelector('.close');

  btn.onclick = () => {
    modal.style.display = "block";
  }

  span.onclick = () => {
    modal.style.display = "none";
  }

  window.onclick = (event) => {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }


  </script>
</body>
</html>
