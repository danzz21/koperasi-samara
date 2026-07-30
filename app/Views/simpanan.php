<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Simpanan Koperasi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    :root {
      --primary: #10b981;
      --primary-dark: #059669;
      --secondary: #06b6d4;
      --warning: #f59e0b;
      --danger: #ef4444;
      --dark: #0f172a;
      --gray: #64748b;
      --gray-light: #e2e8f0;
      --border-radius: 18px;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
      --gradient-primary: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
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
    }

    .header-simpan {
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

    .header-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header-info img, .profile-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .profile-avatar {
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 17px;
    }

    .header-name {
      font-weight: 700;
      font-size: 15px;
      line-height: 1.2;
    }

    .page-title {
      font-size: 20px;
      font-weight: 800;
      text-align: center;
      margin: 1.25rem 0 1rem;
      color: var(--dark);
    }

    .tab-simpanan {
      display: flex;
      background: white;
      margin: 0 1rem 1.25rem;
      border-radius: 14px;
      padding: 4px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.04);
      border: 1px solid #e2e8f0;
    }

    .tab-simpanan button {
      flex: 1;
      padding: 0.7rem;
      border: none;
      background: transparent;
      color: var(--gray);
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      border-radius: 10px;
      transition: all 0.2s ease;
    }

    .tab-simpanan button.active {
      color: white;
      background: var(--gradient-primary);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .tab-content {
      display: none;
      padding: 0 1rem;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.25s ease;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      padding: 1.25rem;
      margin-bottom: 1rem;
      box-shadow: var(--shadow);
      border: 1px solid #f1f5f9;
    }

    .card-title {
      font-size: 15px;
      font-weight: 800;
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
      margin-bottom: 0.6rem;
    }

    .k { color: var(--gray); font-size: 13px; font-weight: 500; }
    .v { color: var(--dark); font-weight: 700; font-size: 14px; }

    .badge {
      padding: 0.25rem 0.6rem;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .badge-lunas { background: #dcfce7; color: #15803d; }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-ditolak { background: #fee2e2; color: #b91c1c; }
    .badge-gaji { background: #e0f2fe; color: #0369a1; }

    .divider { height: 1px; background: #f1f5f9; margin: 0.8rem 0; }

    .bill {
      display: flex;
      align-items: center;
      padding: 0.8rem 0;
      border-bottom: 1px dashed #e2e8f0;
    }

    .bill:last-child { border-bottom: none; }

    .bill-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.8rem;
      background: #ecfdf5;
      color: var(--primary);
    }

    .bill-main { flex: 1; }
    .bill-title { font-weight: 700; font-size: 13px; color: var(--dark); }
    .bill-sub { font-size: 11px; color: var(--gray); }
    .bill-amount { text-align: right; }
    .nominal { font-weight: 800; font-size: 13px; color: var(--dark); margin-bottom: 2px; }

    .btn-setor {
      width: 100%;
      padding: 0.85rem;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: 14px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
      margin-bottom: 0.5rem;
      transition: transform 0.15s ease;
    }

    .btn-setor:active { transform: scale(0.98); }

    .note { text-align: center; color: var(--gray); font-size: 11px; margin-bottom: 1.25rem; }

    /* Modal Glassmorphism UI */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 1.5rem;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      position: relative;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .close {
      position: absolute;
      right: 18px; top: 14px;
      font-size: 22px;
      font-weight: bold;
      color: var(--gray);
      cursor: pointer;
    }

    .modal-info-box {
      background: #f0fdf4;
      border: 1px dashed #34d399;
      padding: 10px 12px;
      border-radius: 12px;
      margin-bottom: 1rem;
      font-size: 12px;
      color: #065f46;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .form-group { margin-bottom: 1rem; }
    .form-group label {
      font-weight: 700;
      font-size: 12px;
      display: block;
      margin-bottom: 0.4rem;
      color: var(--dark);
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--gray-light);
      border-radius: 10px;
      font-size: 13px;
    }

    .input-error-msg {
      color: #ef4444;
      font-size: 11px;
      font-weight: 600;
      margin-top: 4px;
      display: none;
    }

    .btn-submit {
      width: 100%;
      background: var(--gradient-primary);
      color: white;
      padding: 0.85rem;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-submit:disabled {
      background: #cbd5e1;
      cursor: not-allowed;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0; left: 0; right: 0;
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

    .bottom-nav a p { font-size: 10px; font-weight: 600; margin-top: 2px; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Header Profil -->
  <header class="header-simpan">
    <div class="header-info">
      <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
        <img src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
      <?php else: ?>
        <?php 
          $firstLetter = strtoupper(substr($nama ?? 'A', 0, 1));
          $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
          $bgColor = $colors[crc32($nomor_anggota ?? '1') % count($colors)];
        ?>
        <div class="profile-avatar" style="background:<?= $bgColor ?>;">
          <?= $firstLetter ?>
        </div>
      <?php endif; ?>

      <div>
        <div class="header-name"><?= htmlspecialchars($nama ?? '-') ?></div>
        <div style="font-size:11px; opacity:.9;">ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></div>
      </div>
    </div>
    <i data-lucide="bell" style="width:20px; height:20px; cursor:pointer;"></i>
  </header>
  
  <h2 class="page-title">Simpanan</h2>

  <!-- Hitung Akumulasi Pokok Yang Benar-Benar Sudah Di-ACC (Aktif) -->
  <?php
    $totalPokok = 0;
    if (!empty($pokok)) {
        foreach ($pokok as $p) {
            $st = strtolower($p['status'] ?? '');
            if (in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui'])) {
                $totalPokok += (float)($p['jumlah'] ?? 0);
            }
        }
    }
    $kekurangan = max(0, 500000 - $totalPokok);
    $isPokokComplete = $totalPokok >= 500000;
  ?>

  <!-- Navigation Tabs -->
  <div class="tab-simpanan">
    <button id="tab-pokok" class="active" onclick="showTab('pokok')">Pokok</button>
    <button id="tab-wajib" onclick="showTab('wajib')">Wajib</button>
    <button id="tab-sukarela" onclick="showTab('sukarela')">Sukarela</button>
  </div>

  <!-- TAB SIMPANAN POKOK -->
  <section id="pokok" class="tab-content active">
    <div class="card">
        <div class="card-title">
            <i data-lucide="landmark" width="18" height="18" style="color:#10b981;"></i>
            Rangkuman Simpanan Pokok
        </div>
        <div class="kv">
            <span class="k">Total Terkumpul</span>
            <span class="v" style="color:#059669;">Rp <?= number_format($totalPokok, 0, ',', '.') ?></span>
        </div>
        <div class="kv">
            <span class="k">Target Wajib</span>
            <span class="v">Rp 500.000</span>
        </div>
        <div class="kv">
            <span class="k">Sisa Kekurangan</span>
            <span class="v" style="color:#d97706;">
                <?= ($kekurangan > 0) ? 'Rp ' . number_format($kekurangan, 0, ',', '.') : 'Lunas' ?>
            </span>
        </div>
        <div class="divider"></div>
        <div class="kv">
            <span class="k">Status Keanggotaan</span>
            <span class="badge <?= $isPokokComplete ? 'badge-lunas' : 'badge-pending' ?>">
                <i class="fa-regular <?= $isPokokComplete ? 'fa-check-circle' : 'fa-clock' ?>"></i>
                <?= $isPokokComplete ? 'Lunas' : 'Belum Lunas' ?>
            </span>
        </div>
        <div class="kv">
            <span class="k">Tenor Dicicil</span>
            <span class="v"><?= $tenor_anggota ? $tenor_anggota . ' Bulan' : 'Belum dipilih' ?></span>
        </div>
    </div>

    <?php if (!$isPokokComplete): ?>
      <button class="btn-setor" onclick="openModal('modalPokok')">
        <i class="fa-solid fa-plus-circle mr-1"></i> Setor Simpanan Pokok
      </button>
      <div class="note">Setoran akan diverifikasi otomatis oleh admin.</div>
    <?php endif; ?>

    <!-- Riwayat Transaksi Pokok -->
    <div class="card">
        <div class="card-title">
            <i data-lucide="history" width="18" height="18" style="color:#0ea5e9;"></i>
            Riwayat Setoran Pokok
        </div>
        <?php if (!empty($pokok)): ?>
          <?php foreach ($pokok as $item): ?>
            <?php 
              $st = strtolower($item['status'] ?? 'pending');
              $badgeClass = in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui']) ? 'badge-lunas' : ($st == 'pending' ? 'badge-pending' : 'badge-ditolak');
              $statusLabel = in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui']) ? 'Terkonfirmasi' : ($st == 'pending' ? 'Pending' : 'Ditolak');
            ?>
            <div class="bill">
                <div class="bill-icon">
                  <i class="fa-solid fa-arrow-down"></i>
                </div>
                <div class="bill-main">
                    <div class="bill-title">Setor Simpanan Pokok</div>
                    <div class="bill-sub"><?= date('d M Y', strtotime($item['tanggal'])) ?></div>
                </div>
                <div class="bill-amount">
                    <div class="nominal">+ Rp <?= number_format($item['jumlah'] ?? 0, 0, ',', '.') ?></div>
                    <span class="badge <?= $badgeClass ?>">
                      <?= $statusLabel ?>
                    </span>
                </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="padding:16px; text-align:center; color:#94a3b8; font-size:12px;">Belum ada riwayat setoran pokok.</div>
        <?php endif; ?>
    </div>
  </section>

  <!-- TAB SIMPANAN WAJIB -->
  <section id="wajib" class="tab-content">
    <div class="card">
      <div class="card-title">
        <i data-lucide="calendar" width="18" height="18" style="color:#10b981;"></i>
        Rangkuman Simpanan Wajib
      </div>
      <div class="kv">
        <span class="k">Nominal per Bulan</span>
        <span class="v">Rp <?= !empty($wajib) ? number_format($wajib[0]['jumlah'] ?? 0, 0, ',', '.') : '0' ?></span>
      </div>
      <div class="kv">
        <span class="k">Sistem Pemotongan</span>
        <span class="badge badge-gaji"><i class="fa-solid fa-money-bill-wave"></i> Potong Gaji</span>
      </div>
    </div>

    <div class="card">
      <div class="card-title">
        <i data-lucide="list-checks" width="18" height="18" style="color:#0ea5e9;"></i>
        Status Bulanan
      </div>
      <?php if (!empty($wajib)): ?>
        <?php foreach ($wajib as $item): ?>
          <div class="bill">
            <div class="bill-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="bill-main">
              <div class="bill-title"><?= date('F Y', strtotime($item['tanggal'])) ?></div>
              <div class="bill-sub">Potongan Otomatis</div>
            </div>
            <div class="bill-amount">
              <div class="nominal">Rp <?= number_format($item['jumlah'] ?? 0, 0, ',', '.') ?></div>
              <span class="badge badge-lunas">Lunas</span>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="padding:16px; text-align:center; color:#94a3b8; font-size:12px;">Belum ada data simpanan wajib.</div>
      <?php endif; ?>
    </div>
  </section>

  <!-- TAB SIMPANAN SUKARELA -->
  <section id="sukarela" class="tab-content">
    <?php
      $totalSukarela = 0;
      if (!empty($sukarela)) {
          foreach ($sukarela as $s) {
              $st = strtolower($s['status'] ?? '');
              if (in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui'])) {
                  $totalSukarela += (float)($s['jumlah'] ?? 0);
              }
          }
      }
    ?>
    <div class="card">
      <div class="card-title">
        <i data-lucide="gift" width="18" height="18" style="color:#10b981;"></i>
        Simpanan Sukarela
      </div>
      <div class="kv">
        <span class="k">Total Saldo Terkumpul</span>
        <span class="v" style="color:#059669;">Rp <?= number_format($totalSukarela, 0, ',', '.') ?></span>
      </div>
    </div>

    <button class="btn-setor" onclick="openModal('modalSukarela')">
      <i class="fa-solid fa-plus-circle mr-1"></i> Setor Simpanan Sukarela
    </button>
    <div class="note">Fleksibel dapat disetor kapan saja.</div>

    <div class="card">
      <div class="card-title">
        <i data-lucide="history" width="18" height="18" style="color:#0ea5e9;"></i>
        Riwayat Setoran Sukarela
      </div>
      <?php if (!empty($sukarela)): ?>
        <?php foreach ($sukarela as $item): ?>
          <?php 
            $st = strtolower($item['status'] ?? 'pending');
            $badgeClass = in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui']) ? 'badge-lunas' : ($st == 'pending' ? 'badge-pending' : 'badge-ditolak');
            $statusLabel = in_array($st, ['aktif', 'lunas', 'berhasil', 'disetujui']) ? 'Terkonfirmasi' : ($st == 'pending' ? 'Pending' : 'Ditolak');
          ?>
          <div class="bill">
            <div class="bill-icon"><i class="fa-solid fa-arrow-down"></i></div>
            <div class="bill-main">
              <div class="bill-title">Setor Sukarela</div>
              <div class="bill-sub"><?= date('d M Y', strtotime($item['tanggal'])) ?></div>
            </div>
            <div class="bill-amount">
              <div class="nominal">+ Rp <?= number_format($item['jumlah'] ?? 0, 0, ',', '.') ?></div>
              <span class="badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="padding:16px; text-align:center; color:#94a3b8; font-size:12px;">Belum ada setoran sukarela.</div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Modal Setoran Pokok dengan Validasi Otomatis -->
  <div id="modalPokok" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalPokok')">&times;</span>
        <h3 style="margin-bottom:0.75rem; font-size:16px; font-weight:800; color:var(--dark);">Setor Simpanan Pokok</h3>
        
        <!-- Info Sisa Kekurangan Otomatis -->
        <div class="modal-info-box">
            <span>Sisa Kekurangan:</span>
            <strong id="labelSisa">Rp <?= number_format($kekurangan, 0, ',', '.') ?></strong>
        </div>

        <form id="formSimpananPokok">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form-group">
                <label>Jumlah Setoran (Rp)</label>
                <input type="number" name="jumlah" id="inputJumlahPokok" required min="10000" max="<?= $kekurangan ?>" placeholder="Masukkan nominal" />
                <div class="input-error-msg" id="errorPokok">Nominal melebihi sisa kekurangan!</div>
            </div>
            <div class="form-group">
                <label>Upload Bukti Transfer</label>
                <input type="file" name="bukti" accept="image/*,application/pdf" required />
            </div>
            <button type="submit" class="btn-submit" id="btnSubmitPokok">Kirim Setoran</button>
        </form>
    </div>
  </div>

  <!-- Modal Setoran Sukarela -->
  <div id="modalSukarela" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalSukarela')">&times;</span>
        <h3 style="margin-bottom:1rem; font-size:16px; font-weight:800; color:var(--dark);">Setor Simpanan Sukarela</h3>
        <form id="formSimpananSukarela">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form-group">
                <label>Jumlah Setoran (Rp)</label>
                <input type="number" name="jumlah" required min="10000" placeholder="Masukkan nominal" />
            </div>
            <div class="form-group">
                <label>Upload Bukti Transfer</label>
                <input type="file" name="bukti" accept="image/*,application/pdf" required />
            </div>
            <button type="submit" class="btn-submit">Kirim Setoran</button>
        </form>
    </div>
  </div>

  <!-- Bottom Navigation -->
  <nav class="bottom-nav">
    <a href="<?= base_url('anggota/dashboard') ?>">
      <i data-lucide="home" style="width:18px; height:18px;"></i>
      <p>Beranda</p>
    </a>
    <a href="<?= base_url('anggota/simpanan') ?>" class="active">
      <i data-lucide="wallet" style="width:18px; height:18px;"></i>
      <p>Simpan</p>
    </a>
    <a href="<?= base_url('anggota/pinjaman') ?>">
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

  <script>
    lucide.createIcons();

    // Sisa Kekurangan dari PHP
    const maxKekurangan = <?= $kekurangan ?>;

    // Detection Real-time Input Jumlah Pokok
    const inputPokok = document.getElementById('inputJumlahPokok');
    const errorPokok = document.getElementById('errorPokok');
    const btnSubmitPokok = document.getElementById('btnSubmitPokok');

    if (inputPokok) {
        inputPokok.addEventListener('input', function() {
            const val = parseFloat(this.value) || 0;
            if (val > maxKekurangan) {
                errorPokok.style.display = 'block';
                btnSubmitPokok.disabled = true;
            } else {
                errorPokok.style.display = 'none';
                btnSubmitPokok.disabled = false;
            }
        });
    }

    // Switch Tabs
    function showTab(name) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-simpanan button').forEach(el => el.classList.remove('active'));
      
      document.getElementById(name).classList.add('active');
      document.getElementById('tab-' + name).classList.add('active');
    }

    function openModal(id) {
      document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
      document.getElementById(id).style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
      }
    }

    // AJAX Form Submit Handling
    document.addEventListener('DOMContentLoaded', function() {
      const formPokok = document.getElementById('formSimpananPokok');
      if (formPokok) {
        formPokok.addEventListener('submit', function(e) {
          e.preventDefault();
          submitForm(this, '<?= base_url('anggota/simpanan/pokok/store') ?>');
        });
      }

      const formSukarela = document.getElementById('formSimpananSukarela');
      if (formSukarela) {
        formSukarela.addEventListener('submit', function(e) {
          e.preventDefault();
          submitForm(this, '<?= base_url('anggota/simpanan/sukarela/store') ?>');
        });
      }

      function submitForm(form, url) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('.btn-submit');
        const origText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';

        fetch(url, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            closeModal(form.closest('.modal').id);
            form.reset();
            location.reload();
          } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
          }
        })
        .catch(err => {
          console.error(err);
          alert('Terjadi kesalahan koneksi.');
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.textContent = origText;
        });
      }
    });
  </script>
</body>
</html>