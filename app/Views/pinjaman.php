<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pinjaman</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="<?= base_url('assets/css/pinjaman.css')?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- Header -->
  <!-- Header -->
  <header class="header-pinjam">
    <div class="header-left">
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
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label><br>
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label><br>
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

        
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label><br>
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label><br>
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
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda yakin ingin mengajukan pinjaman ini?</label><br>
  <label><input type="checkbox" class="confirm-checkbox" required> Apakah Anda sudah membaca syarat dan ketentuan?</label><br>
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
    <a href="<?= base_url('anggota/profil')?>">
      <i data-lucide="user"></i>
      <p>Profil</p>
    </a>
  </nav>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>


  <script>
    // Al-Qord
    document.getElementById("alqord-nominal").addEventListener("input",updateAlqord);
    document.getElementById("alqord-bulan").addEventListener("input",updateAlqord);
    function updateAlqord(){
    let raw = document.getElementById("alqord-nominal").value;
    const n = parseInt(raw.replace(/\./g, "")) || 0; // hapus semua titik
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
  const h = parseInt(raw.replace(/\./g, "")) || 0; // hapus titik
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
  const n = parseInt(raw.replace(/\./g, "")) || 0; // hapus titik
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
    // hapus semua karakter non-digit
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
