<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinjaman Al-Qordh - Koperasi Syariah</title>
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
    <h1>Pinjaman Al-Qordh</h1>
    <p>Koperasi Syariah</p>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card p-4">
      <h3 class="mb-3 text-success">Apa itu Pinjaman Al-Qordh?</h3>
      <p>
        <strong>Pinjaman Al-Qordh</strong> adalah fasilitas pinjaman kebajikan yang diberikan oleh koperasi kepada anggota 
        tanpa adanya tambahan keuntungan (bunga). Anggota hanya berkewajiban mengembalikan pokok pinjaman sesuai dengan jumlah yang dipinjam.
      </p>

      <h4 class="text-success mt-4">Tujuan Pinjaman Al-Qordh</h4>
      <ul>
        <li>Memberikan bantuan kepada anggota yang membutuhkan dana darurat.</li>
        <li>Meningkatkan solidaritas dan kepedulian sesama anggota.</li>
        <li>Menjaga prinsip keadilan dan keberkahan sesuai syariah.</li>
      </ul>

      <h4 class="text-success mt-4">Ketentuan Pinjaman</h4>
      <p>
        - Tidak dikenakan bunga atau tambahan pembayaran. <br>
        - Jangka waktu pengembalian ditentukan berdasarkan kesepakatan bersama. <br>
        - Anggota hanya wajib mengembalikan pokok pinjaman.
      </p>

      <div class="alert alert-info mt-4">
        <strong>Catatan:</strong> Dalam praktiknya, anggota yang meminjam dianjurkan untuk memberikan 
        <em>shadaqah</em> atau donasi sukarela sesuai kemampuan, sebagai bentuk rasa syukur dan dukungan terhadap keberlangsungan koperasi.
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
