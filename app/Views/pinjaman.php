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
    }

    .form-input select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
    }

    .btn-ajukan:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .btn-ajukan:active {
      transform: translateY(0);
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
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header-pinjam">
    <div class="header-left">
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

  <h2 class="page-title">Pinjaman</h2>

  <!-- Tab akad -->
  <div class="tab-akad">
    <button id="tab-alqord" class="active" onclick="showTab('alqord')">Al-Qord</button>
    <button id="tab-murabahah" onclick="showTab('murabahah')">Murabahah</button>
    <button id="tab-mudharabah" onclick="showTab('mudharabah')">Mudharabah</button>
  </div>

  <!-- Al-Qord -->
  <section id="alqord" class="tab-content active">
    <div class="card">
      <div class="card-title">Ajukan Pinjaman Al-Qord</div>
      <form id="form-alqord" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Pinjaman (Maks Rp 4.000.000)</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="alqord-nominal" name="jumlah" placeholder="Masukkan jumlah" required maxlength="10"/>
          </div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="alqord-bulan" name="lama_cicilan" required>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="kv"><span>Cicilan / Bulan</span><span id="alqord-cicilan">-</span></div>
        <input type="hidden" name="jenis" value="qard">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Anda dengan sadar melakukan pengajuan ini.</label>
        </div>

        <button type="submit" class="btn-ajukan">Ajukan Pinjaman</button>
      </form>
    </div>
  </section>

  <!-- Murabahah -->
  <section id="murabahah" class="tab-content">
    <div class="card">
      <div class="card-title">Ajukan Pinjaman Murabahah</div>
      <form id="form-murabahah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Harga Barang</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="murabahah-harga" name="jumlah" placeholder="Masukkan harga" required maxlength="10"/>
          </div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="murabahah-bulan" name="lama_cicilan" required>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="kv"><span>Total + Margin (10%)</span><span id="murabahah-total">-</span></div>
        <div class="kv"><span>Cicilan / Bulan</span><span id="murabahah-cicilan">-</span></div>
        <p class="note">* Akan ditambah margin 10% dari harga.</p>
        <input type="hidden" name="jenis" value="murabahah">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Anda dengan sadar melakukan pengajuan ini.</label>
        </div>

        <button type="submit" class="btn-ajukan">Ajukan Pinjaman</button>
      </form>
    </div>
  </section>

  <!-- Mudharabah -->
  <section id="mudharabah" class="tab-content">
    <div class="card">
      <div class="card-title">Ajukan Pinjaman Mudharabah</div>
      <form id="form-mudharabah" action="<?= base_url('anggota/ajukan-pinjaman') ?>" method="post">
        <div class="form-input">
          <label>Nominal Pinjaman (Maks Rp 4.000.000)</label>
          <div class="input-rupiah">
            <span>Rp</span>
            <input type="text" id="mudharabah-nominal" name="jumlah" placeholder="Masukkan jumlah" required maxlength="10"/>
          </div>
        </div>

        <div class="form-input">
          <label>Lama Cicilan (maks 12 bulan)</label>
          <select id="mudharabah-bulan" name="lama_cicilan" required>
            <option value="" disabled selected>Pilih lama cicilan</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> bulan</option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="kv"><span>Total + Bagi Hasil (10%)</span><span id="mudharabah-total">-</span></div>
        <div class="kv"><span>Cicilan / Bulan</span><span id="mudharabah-cicilan">-</span></div>
        <p class="note">* Pengembalian ditambah bagi hasil 10%.</p>
        <input type="hidden" name="jenis" value="mudharabah">
        
        <div class="form-checkboxes">
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label>
          <label><input type="checkbox" class="confirm-checkbox" required> Anda dengan sadar melakukan pengajuan ini.</label>
        </div>

        <button type="submit" class="btn-ajukan">Ajukan Pinjaman</button>
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

  <script>
    lucide.createIcons();

    // Al-Qord
    document.getElementById("alqord-nominal").addEventListener("input",updateAlqord);
    document.getElementById("alqord-bulan").addEventListener("input",updateAlqord);
    function updateAlqord(){
      let raw = document.getElementById("alqord-nominal").value;
      const n = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("alqord-bulan").value) || 0;
      
      if(n > 0 && b > 0 && b <= 12){
        document.getElementById("alqord-cicilan").textContent = "Rp " + (n/b).toLocaleString();
      } else {
        document.getElementById("alqord-cicilan").textContent = "-";
      }
    }

    // Murabahah
    document.getElementById("murabahah-harga").addEventListener("input", updateMurabahah);
    document.getElementById("murabahah-bulan").addEventListener("input", updateMurabahah);

    function updateMurabahah(){
      let raw = document.getElementById("murabahah-harga").value;
      const h = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("murabahah-bulan").value) || 0;
      const total = h + (h * 0.1);
      
      if(h > 0){
        document.getElementById("murabahah-total").textContent = "Rp " + total.toLocaleString();
        if(b > 0 && b <= 12){
          document.getElementById("murabahah-cicilan").textContent = "Rp " + (total/b).toLocaleString();
        } else {
          document.getElementById("murabahah-cicilan").textContent = "-";
        }
      }
    }

    // Mudharabah
    document.getElementById("mudharabah-nominal").addEventListener("input", updateMudharabah);
    document.getElementById("mudharabah-bulan").addEventListener("input", updateMudharabah);

    function updateMudharabah(){
      let raw = document.getElementById("mudharabah-nominal").value;
      const n = parseInt(raw.replace(/\./g, "")) || 0;
      const b = parseInt(document.getElementById("mudharabah-bulan").value) || 0;
      const total = n + (n * 0.1);
      
      if(n > 0){
        document.getElementById("mudharabah-total").textContent = "Rp " + total.toLocaleString();
        if(b > 0 && b <= 12){
          document.getElementById("mudharabah-cicilan").textContent = "Rp " + (total/b).toLocaleString();
        } else {
          document.getElementById("mudharabah-cicilan").textContent = "-";
        }
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
        const checkboxes = form.querySelectorAll('.confirm-checkbox');
        let allChecked = true;

        checkboxes.forEach(cb => {
          if (!cb.checked) {
            allChecked = false;
          }
        });

        if (!allChecked) {
          e.preventDefault();
          alert('Harap centang semua pernyataan sebelum mengajukan pinjaman.');
        }
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
        } else {
          this.value = '';
        }
      });
    });
  </script>
</body>
</html>