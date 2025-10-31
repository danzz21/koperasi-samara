<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpanan Pokok - Koperasi Syariah</title>
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

    /* Hanya icon Beranda yang diwarnai hijau */
    .bottom-nav a.active {
      color: var(--primary);
    }

    .bottom-nav a.active i {
      color: var(--primary);
    }

    .bottom-nav a.active p {
      color: var(--primary);
    }

    /* Hapus indicator dot */
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

      h3.text-success {
        font-size: 22px;
      }

      h4.text-success {
        font-size: 18px;
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

  <div class="header fade-in">
    <h1>Simpanan Pokok</h1>
    <p>Koperasi Syariah</p>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card p-4 fade-in">
      <h3 class="mb-3 text-success">
        <i data-lucide="help-circle" width="24" height="24"></i>
        Apa itu Simpanan Pokok?
      </h3>
      <p>
        <strong>Simpanan Pokok</strong> adalah setoran awal wajib yang harus dibayarkan oleh setiap anggota baru pada saat pertama kali masuk menjadi anggota koperasi. 
        Simpanan ini bersifat <strong>wajib</strong> dan <strong>tidak bisa diambil kembali</strong> selama masih menjadi anggota. Nominal Simpanan Pokok yang harus disetorkan adalah <strong>Rp 500.000</strong> dan bisa dicicil dengan maksimal cicilan <strong>10x cicilan</strong>.
      </p>

      <h4 class="text-success mt-4">
        <i data-lucide="target" width="20" height="20"></i>
        Tujuan Simpanan Pokok
      </h4>
      <ul>
        <li>Menjadi tanda keanggotaan dalam koperasi syariah.</li>
        <li>Modal dasar koperasi untuk mengembangkan usaha bersama.</li>
        <li>Membangun rasa memiliki dan kebersamaan antar anggota.</li>
      </ul>

      <h4 class="text-success mt-4">
        <i data-lucide="git-compare" width="20" height="20"></i>
        Perbedaan dengan Simpanan Lain
      </h4>
      <p>
        Berbeda dengan <strong>Simpanan Wajib</strong> (dibayar rutin tiap bulan) atau <strong>Simpanan Sukarela</strong> (setor sesuai kemampuan),
        <strong>Simpanan Pokok</strong> hanya dibayarkan satu kali saat mendaftar sebagai anggota.
      </p>

      <div class="alert alert-success mt-4">
        <strong>Catatan:</strong> Besaran Simpanan Pokok sudah ditetapkan dalam Anggaran Dasar/Anggaran Rumah Tangga (AD/ART) koperasi.
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