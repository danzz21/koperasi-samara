<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpanan Wajib - Koperasi Syariah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    .card { border-radius: 20px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
    .header { background: linear-gradient(135deg, #198754, #20c997); color: white; border-radius: 0 0 30px 30px; padding: 40px 20px; text-align: center; }
    .header h1 { font-weight: bold; }

     /* Bottom Navigation */
.bottom-nav {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  background: #fff;
  display: flex;
  justify-content: space-around;
  padding: 8px 0;
  border-top: 1px solid #e5e7eb;
}

.bottom-nav a {
  flex: 1;
  text-align: center;
  text-decoration: none;
  color: #6b7280;
  font-size: 12px;
  padding: 6px 0;
}

.bottom-nav a i {
  display: block;
  font-size: 18px;
  margin-bottom: 4px;
}

/* Active menu */
.bottom-nav a.active {
  color: #059669;
  font-weight: 700;
}
  </style>
</head>
<body>

  <div class="header">
    <h1>Simpanan Wajib</h1>
    <p>Koperasi Syariah</p>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card p-4">
      <h3 class="mb-3 text-success">Apa itu Simpanan Wajib?</h3>
      <p>
        <strong>Simpanan Wajib</strong> adalah setoran rutin yang harus dibayarkan oleh setiap anggota koperasi dalam jangka waktu tertentu (biasanya bulanan).
        Simpanan ini bersifat <strong>wajib</strong> selama anggota masih terdaftar sebagai anggota aktif koperasi.
      </p>

      <h4 class="text-success mt-4">Tujuan Simpanan Wajib</h4>
      <ul>
        <li>Menjadi sumber modal koperasi yang berkesinambungan.</li>
        <li>Mendorong kedisiplinan dan rasa tanggung jawab anggota.</li>
        <li>Menjadi tanda keaktifan anggota dalam koperasi.</li>
      </ul>

      <h4 class="text-success mt-4">Perbedaan dengan Simpanan Lain</h4>
      <p>
        - <strong>Simpanan Pokok</strong> dibayar satu kali saat masuk menjadi anggota. <br>
        - <strong>Simpanan Wajib</strong> dibayar rutin setiap periode (contohnya bulanan). <br>
        - <strong>Simpanan Sukarela</strong> sifatnya bebas, sesuai kemampuan dan tidak terikat waktu.
      </p>

      <div class="alert alert-success mt-4">
        <strong>Catatan:</strong> Jumlah Simpanan Wajib ditetapkan dalam AD/ART koperasi dan bisa diputuskan melalui Rapat Anggota Tahunan (RAT).
      </div>
      <footer class="text-center p-3 bg-light">
        <small>&copy; 2025 Koperasi Syariah</small>
      </footer>
    </div>
  </div>


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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
