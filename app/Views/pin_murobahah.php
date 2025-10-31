<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinjaman Murabahah - Koperasi Syariah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    body {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #f0fdf9 0%, #f0fdf4 100%);
      color: var(--dark);
      min-height: 100vh;
      padding-bottom: 80px;
      line-height: 1.6;
    }

    .card {
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.5);
      transition: var(--transition);
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
    }

    .header {
      background: var(--gradient-primary);
      color: white;
      border-radius: 0 0 30px 30px;
      padding: 3rem 1.5rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
    }

    .header h1 {
      font-weight: 800;
      font-size: 32px;
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    .header p {
      font-size: 18px;
      opacity: 0.9;
    }

    h3.text-success, h4.text-success {
      color: var(--primary) !important;
      font-weight: 700;
    }

    h3.text-success {
      font-size: 24px;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    h4.text-success {
      font-size: 20px;
      margin-top: 1.5rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-primary {
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border: 1px solid var(--secondary-light);
      border-radius: var(--border-radius-sm);
      border-left: 4px solid var(--secondary);
      color: var(--dark);
    }

    .alert-primary strong {
      color: var(--secondary);
    }

    ul {
      list-style: none;
      padding: 0;
      margin: 1rem 0;
    }

    ul li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--gray-light);
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    ul li:last-child {
      border-bottom: none;
    }

    ul li:before {
      content: "✓";
      color: var(--primary);
      font-weight: bold;
      margin-right: 8px;
    }

    footer.bg-light {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
      border-radius: var(--border-radius-sm);
      margin-top: 2rem;
    }

    /* Bottom Navigation - Tetap sama */
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

    .bottom-nav a.active {
      color: #059669;
      font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .header {
        padding: 2.5rem 1.2rem;
      }
      
      .header h1 {
        font-size: 28px;
      }

      .header p {
        font-size: 16px;
      }
      
      .container {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
      }
    }

    @media (min-width: 768px) {
      .header h1 {
        font-size: 36px;
      }

      .header p {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Pinjaman Murabahah</h1>
    <p>Koperasi Syariah</p>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card p-4">
      <h3 class="mb-3 text-success">
        <i data-lucide="help-circle" width="24" height="24"></i>
        Apa itu Murabahah?
      </h3>
      <p>
        <strong>Murabahah</strong> adalah akad jual beli di mana koperasi membeli suatu barang kemudian menjualnya kembali kepada anggota dengan harga jual
        yang telah ditambah margin keuntungan. Harga dan margin disepakati di awal, sehingga <strong>transparan dan adil</strong> bagi kedua belah pihak.
      </p>

      <h4 class="text-success mt-4">
        <i data-lucide="list" width="20" height="20"></i>
        Karakteristik Murabahah
      </h4>
      <ul>
        <li>Koperasi membeli barang sesuai kebutuhan anggota.</li>
        <li>Harga pokok + margin keuntungan disepakati sejak awal akad.</li>
        <li>Anggota dapat membayar secara tunai atau angsuran.</li>
        <li>Tidak ada tambahan bunga, karena margin sudah jelas di depan.</li>
      </ul>

      <h4 class="text-success mt-4">
        <i data-lucide="calculator" width="20" height="20"></i>
        Contoh Sederhana
      </h4>
      <p>
        Misalnya, seorang anggota membutuhkan laptop. Koperasi membeli laptop seharga Rp 5.000.000, lalu menjual kembali ke anggota dengan margin Rp 500.000.  
        Maka total harga jual = Rp 5.500.000, yang bisa dibayar tunai atau diangsur sesuai kesepakatan.
      </p>

      <div class="alert alert-primary mt-4">
        <strong>Catatan:</strong> Murabahah berbeda dengan kredit konvensional. Tidak ada bunga atau denda yang menjerat, semua harga dan keuntungan
        sudah jelas sejak akad dibuat.
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