<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpanan Wajib - Koperasi Syariah</title>
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
      --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
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
      padding: 2.5rem 1.5rem;
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
      font-size: 16px;
      opacity: 0.9;
    }

    h3.text-success, h4.text-success {
      color: var(--primary) !important;
      font-weight: 700;
    }

    h3.text-success {
      font-size: 22px;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    h4.text-success {
      font-size: 18px;
      margin-top: 1.5rem;
      margin-bottom: 1rem;
    }

    .alert-success {
      background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
      border: 1px solid var(--primary-light);
      border-radius: var(--border-radius-sm);
      border-left: 4px solid var(--primary);
      color: var(--dark);
    }

    .alert-success strong {
      color: var(--primary-dark);
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

    /* Bottom Navigation - DIPERBAIKI */
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
      position: relative;
      flex: 1;
      text-align: center;
    }

    .bottom-nav a i {
      font-size: 20px;
      margin-bottom: 5px;
      transition: var(--transition);
    }

    .bottom-nav a p {
      font-size: 12px;
      font-weight: 600;
      transition: var(--transition);
    }

    /* Hanya icon Beranda yang diwarnai hijau tanpa background */
    .bottom-nav a.active {
      color: var(--primary);
    }

    .bottom-nav a.active i {
      color: var(--primary);
    }

    .bottom-nav a.active p {
      color: var(--primary);
    }

    /* Hapus background hijau dan indicator dot */
    .bottom-nav a.active {
      background: transparent;
    }

    .bottom-nav a.active::after {
      display: none;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 0.6s ease forwards;
    }

    /* Responsive */
    @media (min-width: 768px) {
      .header h1 {
        font-size: 36px;
      }
    }
    
    @media (max-width: 480px) {
      .header {
        padding: 2rem 1.2rem;
      }
      
      .header h1 {
        font-size: 28px;
      }
      
      .container {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
      }
    }
  </style>
</head>
<body>

  <div class="header fade-in">
    <h1>Simpanan Wajib</h1>
    <p>Koperasi Syariah</p>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card p-4 fade-in">
      <h3 class="mb-3 text-success">
        <i data-lucide="help-circle" width="24" height="24"></i>
        Apa itu Simpanan Wajib?
      </h3>
      <p>
        <strong>Simpanan Wajib</strong> adalah setoran rutin yang harus dibayarkan oleh setiap anggota koperasi dalam jangka waktu tertentu (biasanya bulanan).
        Simpanan ini bersifat <strong>wajib</strong> selama anggota masih terdaftar sebagai anggota aktif koperasi.
      </p>

      <h4 class="text-success mt-4">
        <i data-lucide="target" width="20" height="20"></i>
        Tujuan Simpanan Wajib
      </h4>
      <ul>
        <li>Menjadi sumber modal koperasi yang berkesinambungan.</li>
        <li>Mendorong kedisiplinan dan rasa tanggung jawab anggota.</li>
        <li>Menjadi tanda keaktifan anggota dalam koperasi.</li>
      </ul>

      <h4 class="text-success mt-4">
        <i data-lucide="git-compare" width="20" height="20"></i>
        Perbedaan dengan Simpanan Lain
      </h4>
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
    
    // Animasi scroll
    document.addEventListener('DOMContentLoaded', function() {
      const fadeElements = document.querySelectorAll('.fade-in');
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = 1;
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, { threshold: 0.1 });
      
      fadeElements.forEach(el => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>