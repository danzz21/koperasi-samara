<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Simpanan</title>
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
    .header-simpan {
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

    .header-info {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .header-info img {
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

    /* Tabs */
    .tab-simpanan {
      display: flex;
      background: white;
      margin: 0 1.5rem 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .tab-simpanan button {
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

    .tab-simpanan button.active {
      color: var(--primary);
      background: rgba(16, 185, 129, 0.1);
    }

    .tab-simpanan button.active::after {
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

    .tab-simpanan button:hover:not(.active) {
      background: rgba(16, 185, 129, 0.05);
      color: var(--primary-dark);
    }

    .tab-simpanan button.disabled {
      opacity: 0.5;
      cursor: not-allowed;
      pointer-events: none;
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
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
    }

    .card-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .kv {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .k {
      color: var(--gray);
      font-size: 14px;
    }

    .v {
      color: var(--dark);
      font-weight: 600;
      font-size: 15px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .badge {
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .badge-lunas {
      background: rgba(16, 185, 129, 0.1);
      color: var(--primary);
    }

    .badge-belum {
      background: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .badge-ditolak {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .badge-gaji {
      background: rgba(6, 182, 212, 0.1);
      color: var(--secondary);
    }

    .divider {
      height: 1px;
      background: var(--gray-light);
      margin: 1rem 0;
    }

    /* Bill Items */
    .bill {
      display: flex;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid var(--gray-light);
    }

    .bill:last-child {
      border-bottom: none;
    }

    .bill-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      background: rgba(16, 185, 129, 0.1);
      color: var(--primary);
    }

    .bill-main {
      flex: 1;
    }

    .bill-title {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 2px;
    }

    .bill-sub {
      font-size: 12px;
      color: var(--gray);
    }

    .bill-amount {
      text-align: right;
    }

    .nominal {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 4px;
    }

    /* Button Setor */
    .btn-setor {
      width: calc(100% - 3rem);
      margin: 0 1.5rem 1rem;
      padding: 1rem;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: var(--shadow);
    }

    .btn-setor:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .btn-setor.disabled {
      opacity: 0.5;
      cursor: not-allowed;
      pointer-events: none;
    }

    .note {
      text-align: center;
      color: var(--gray);
      font-size: 14px;
      margin-bottom: 1.5rem;
      padding: 0 1.5rem;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
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
      padding: 2rem;
      border-radius: var(--border-radius);
      width: 90%;
      max-width: 400px;
      position: relative;
      box-shadow: var(--shadow-lg);
    }

    .close {
      color: var(--gray);
      position: absolute;
      right: 20px;
      top: 15px;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      transition: var(--transition);
    }

    .close:hover {
      color: var(--danger);
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      font-weight: 600;
      display: block;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .btn-submit {
      width: 100%;
      background: var(--gradient-primary);
      color: white;
      padding: 1rem;
      border: none;
      border-radius: var(--border-radius-sm);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow);
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
    /* Responsive */
    @media (max-width: 480px) {
      .header-simpan, .tab-simpanan, .tab-content {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
      }
      
      .btn-setor {
        width: calc(100% - 2.4rem);
        margin: 0 1.2rem 1rem;
      }
      
      .page-title {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header-simpan">
    <div class="header-info">
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
        <div class="header-name"><?= htmlspecialchars($nama ?? '-') ?></div>
        <div style="font-size:12px;opacity:.9;">ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></div>
      </div>
    </div>
    <i data-lucide="bell" class="icon"></i>
  </header>
  
  <h2 class="page-title">Simpanan</h2>
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <i data-lucide="check-circle" class="alert-icon"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
  
  <!-- Alert untuk simpanan pokok belum mencukupi -->
  <?php
    $totalPokok = array_sum(array_column($pokok, 'jumlah'));
    $isPokokComplete = $totalPokok >= 500000;
  ?>
  
  <?php if (!$isPokokComplete): ?>
    <div class="alert alert-warning">
      <i data-lucide="alert-triangle" class="alert-icon"></i>
      <div>Simpanan Pokok belum mencapai Rp 500.000. Anda belum dapat mengakses Simpanan Wajib dan Sukarela.</div>
    </div>
  <?php endif; ?>

  <!-- ===== Tabs ===== -->
  <div class="tab-simpanan">
    <button id="tab-pokok" class="active" onclick="showTab('pokok')">Pokok</button>
    <button id="tab-wajib" onclick="showTab('wajib')" <?= !$isPokokComplete ? 'class="disabled"' : '' ?>>Wajib</button>
    <button id="tab-sukarela" onclick="showTab('sukarela')" <?= !$isPokokComplete ? 'class="disabled"' : '' ?>>Sukarela</button>
  </div>

  <!-- ===== POKOK ===== -->
  <section id="pokok" class="tab-content active">
    <div class="card">
        <div class="card-title">
            <i data-lucide="landmark" width="20" height="20"></i>
            Rangkuman Simpanan Pokok
        </div>
        <div class="kv">
            <span class="k">Total Simpanan</span>
            <span class="v">
                Rp <?= number_format($totalPokok, 0, ',', '.') ?>
            </span>
        </div>
        <div class="kv">
            <span class="k">Cicilan</span>
            <span class="v"><?= count($pokok) ?>x</span>
        </div>
        <div class="kv">
            <span class="k">Angsuran / Bulan</span>
            <span class="v">
                <?php
                    $cicilanPerBulan = 0;
                    if (count($pokok) > 0 && $tenor_anggota > 0) {
                        $cicilanPerBulan = 500000 / $tenor_anggota; // 500rb dibagi tenor
                    }
                ?>
                Rp <?= number_format($cicilanPerBulan, 0, ',', '.') ?>
            </span>
        </div>
        <div class="row" style="margin-top:8px;">
            <span class="k">Status</span>
            <span class="badge <?= $isPokokComplete ? 'badge-lunas' : 'badge-belum' ?>">
                <i class="fa-regular fa-clock"></i>
                <?= $isPokokComplete ? 'Lunas' : 'Belum Bayar' ?>
            </span>
        </div>
        <div class="divider"></div>
        <div class="row">
            <span class="k">Tenor Dipilih</span>
            <span class="v"><?= $tenor_anggota ? $tenor_anggota . ' Bulan' : 'Belum dipilih' ?></span>
        </div>
    </div>

    <!-- Jadwal Cicilan -->
    <div class="card">
        <div class="card-title">
            <i data-lucide="calendar" width="20" height="20"></i>
            Jadwal Cicilan
        </div>
        <?php 
        // Tampilkan jadwal cicilan berdasarkan tenor
        if ($tenor_anggota && $tenor_anggota > 0): 
            $cicilanPerBulan = 500000 / $tenor_anggota;
            for ($i = 1; $i <= $tenor_anggota; $i++): 
                $existingCicilan = $pokok[$i-1] ?? null;
        ?>
            <div class="bill">
                <div class="bill-icon"><i class="fa-solid fa-calendar-day"></i></div>
                <div class="bill-main">
                    <div class="bill-title">Bulan ke-<?= $i ?></div>
                    <div class="bill-sub">Cicilan ke-<?= $i ?> • Potong gaji</div>
                </div>
                <div class="bill-amount">
                    <div class="nominal">Rp <?= number_format($cicilanPerBulan, 0, ',', '.') ?></div>
                    <span class="badge <?= $existingCicilan ? 'badge-lunas' : 'badge-belum' ?>">
                        <i class="fa-regular <?= $existingCicilan ? 'fa-check' : 'fa-clock' ?>"></i>
                        <?= $existingCicilan ? 'Lunas' : 'Belum' ?>
                    </span>
                </div>
            </div>
        <?php endfor; ?>
        <?php elseif (empty($pokok)): ?>
            <div style="padding:16px;text-align:center;color:#888;">
                <?php if ($tenor_anggota): ?>
                    Tenor: <?= $tenor_anggota ?> Bulan - Belum ada cicilan
                <?php else: ?>
                    Pilih tenor terlebih dahulu
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
  </section>

  <!-- ===== WAJIB ===== -->
  <section id="wajib" class="tab-content">
    <?php if (!$isPokokComplete): ?>
      <div class="card">
        <div style="padding: 2rem; text-align: center;">
          <i data-lucide="lock" width="48" height="48" style="color: var(--warning); margin-bottom: 1rem;"></i>
          <h3 style="margin-bottom: 1rem; color: var(--dark);">Akses Dibatasi</h3>
          <p style="color: var(--gray);">Simpanan Wajib belum dapat diakses karena Simpanan Pokok belum mencapai Rp 500.000.</p>
          <p style="color: var(--gray); margin-top: 0.5rem;">Silakan lunasi Simpanan Pokok terlebih dahulu.</p>
        </div>
      </div>
    <?php else: ?>
      <div class="card">
        <div class="card-title">
          <i data-lucide="calendar" width="20" height="20"></i>
          Rangkuman Simpanan Wajib
        </div>
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
        <div class="card-title">
          <i data-lucide="list-checks" width="20" height="20"></i>
          Status Bulanan
        </div>
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
    <?php endif; ?>
  </section>

  <!-- ===== SUKARELA ===== -->
  <section id="sukarela" class="tab-content">
    <?php if (!$isPokokComplete): ?>
      <div class="card">
        <div style="padding: 2rem; text-align: center;">
          <i data-lucide="lock" width="48" height="48" style="color: var(--warning); margin-bottom: 1rem;"></i>
          <h3 style="margin-bottom: 1rem; color: var(--dark);">Akses Dibatasi</h3>
          <p style="color: var(--gray);">Simpanan Sukarela belum dapat diakses karena Simpanan Pokok belum mencapai Rp 500.000.</p>
          <p style="color: var(--gray); margin-top: 0.5rem;">Silakan lunasi Simpanan Pokok terlebih dahulu.</p>
        </div>
      </div>
    <?php else: ?>
      <div class="card">
        <div class="card-title">
          <i data-lucide="gift" width="20" height="20"></i>
          Simpanan Sukarela
        </div>
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

      <!-- Button Setor -->
      <button class="btn-setor <?= !$isPokokComplete ? 'disabled' : '' ?>" <?= !$isPokokComplete ? 'disabled' : '' ?>>
        <i class="fa-solid fa-circle-plus"></i> &nbsp; + Setor Simpanan
      </button>
      <div class="note">Penarikan tidak tersedia untuk Simpanan Sukarela.</div>

      <div class="card">
        <div class="card-title">
          <i data-lucide="history" width="20" height="20"></i>
          Riwayat Setoran
        </div>
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
    <?php endif; ?>
  </section>

  <!-- Modal Input Setoran Sukarela -->
  <div id="modalSukarela" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3 style="margin-bottom:1rem;color:var(--dark);">Setor Simpanan Sukarela</h3>
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
    <a href="<?= base_url('anggota/cicilan') ?>">
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

    <form action="<?= base_url('anggota/simpanan/setTenor') ?>" method="post">
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

    // Cek apakah simpanan pokok sudah mencapai 500.000
    const isPokokComplete = <?= $isPokokComplete ? 'true' : 'false' ?>;
    
    function showTab(name){
      // Jika mencoba akses tab wajib atau sukarela tapi pokok belum lunas
      if ((name === 'wajib' || name === 'sukarela') && !isPokokComplete) {
        alert('Simpanan Pokok belum mencapai Rp 500.000. Anda belum dapat mengakses Simpanan Wajib dan Sukarela.');
        return;
      }
      
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

    if (btn) {
      btn.onclick = () => {
        if (!isPokokComplete) {
          alert('Simpanan Pokok belum mencapai Rp 500.000. Anda belum dapat mengakses Simpanan Sukarela.');
          return;
        }
        modal.style.display = "block";
      }
    }

    if (span) {
      span.onclick = () => {
        modal.style.display = "none";
      }
    }

    window.onclick = (event) => {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    
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