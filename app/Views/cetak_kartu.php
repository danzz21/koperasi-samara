<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Cetak Kartu Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    @media print {
      body * {
        visibility: hidden;
      }
      .kartu-anggota, .kartu-anggota * {
        visibility: visible;
      }
      .kartu-anggota {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
      }
      .no-print {
        display: none !important;
      }
    }

    body {
      font-family: 'Arial', sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      max-width: 400px;
      width: 100%;
    }

    .kartu-anggota {
      background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
      border-radius: 20px;
      padding: 30px;
      color: white;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }

    .kartu-anggota::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200px;
      height: 200px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
    }

    .kartu-anggota::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -30%;
      width: 150px;
      height: 150px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }

    .header-kartu {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      position: relative;
      z-index: 2;
    }

    .logo {
      font-weight: bold;
      font-size: 18px;
    }

    .status-anggota {
      background: rgba(255,255,255,0.2);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }

    .foto-profil {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid white;
      margin: 0 auto 20px;
      overflow: hidden;
      position: relative;
      z-index: 2;
    }

    .foto-profil img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .foto-profil div {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #059669;
      color: white;
      font-size: 24px;
      font-weight: bold;
    }

    .info-anggota {
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .nama-anggota {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .nomor-anggota {
      font-size: 14px;
      opacity: 0.9;
      margin-bottom: 20px;
    }

    .detail-info {
      background: rgba(255,255,255,0.1);
      border-radius: 15px;
      padding: 20px;
      margin-top: 20px;
      backdrop-filter: blur(10px);
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 12px;
    }

    .info-row:last-child {
      margin-bottom: 0;
    }

    .info-label {
      opacity: 0.8;
    }

    .info-value {
      font-weight: bold;
    }

    .footer-kartu {
      margin-top: 20px;
      text-align: center;
      font-size: 10px;
      opacity: 0.7;
      position: relative;
      z-index: 2;
    }

    .barcode {
      margin-top: 15px;
      padding: 10px;
      background: white;
      border-radius: 10px;
      display: inline-block;
    }

    .barcode-placeholder {
      width: 200px;
      height: 40px;
      background: #333;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: monospace;
      letter-spacing: 2px;
    }

    .action-buttons {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }

    .btn-print {
      background: #10b981;
      color: white;
    }

    .btn-back {
      background: #6b7280;
      color: white;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Kartu Anggota -->
    <div class="kartu-anggota">
      <div class="header-kartu">
        <div class="logo">KOPERASI SAMARA</div>
        <div class="status-anggota">AKTIF</div>
      </div>

      <div class="foto-profil">
        <?php if (!empty($anggota['photo']) && file_exists(FCPATH . 'uploads/profile/' . $anggota['photo'])): ?>
          <img src="<?= base_url('uploads/profile/' . $anggota['photo']) ?>" alt="Foto Profil">
        <?php else: ?>
          <?php 
            $firstLetter = strtoupper(substr($nama, 0, 1));
            $colors = ['#10b981', '#06b6d4', '#0ea5e9', '#8b5cf6', '#f59e0b'];
            $bgColor = $colors[crc32($nomor_anggota) % count($colors)];
          ?>
          <div style="background:<?= $bgColor ?>">
            <?= $firstLetter ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="info-anggota">
        <div class="nama-anggota"><?= htmlspecialchars($nama ?? '-') ?></div>
        <div class="nomor-anggota">ID: <?= htmlspecialchars($nomor_anggota ?? '-') ?></div>
        


<!-- Di bagian detail info kartu -->
<div class="detail-info">
    <div class="info-row">
        <span class="info-label">Jenis Anggota:</span>
        <span class="info-value"><?= htmlspecialchars($jenis_anggota ?? 'Reguler') ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Bergabung:</span>
        <span class="info-value"><?= htmlspecialchars($tanggal_daftar ?? '-') ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Status:</span>
        <span class="info-value"><?= htmlspecialchars($status ?? 'Aktif') ?></span>
    </div>
    <?php if (!empty($no_rek) && $no_rek != '-'): ?>
    <div class="info-row">
        <span class="info-label">No. Rek:</span>
        <span class="info-value"><?= htmlspecialchars($no_rek) ?></span>
    </div>
    <?php endif; ?>
    <?php if (!empty($atasnama_rekening) && $atasnama_rekening != '-'): ?>
    <div class="info-row">
        <span class="info-label">Atas Nama:</span>
        <span class="info-value"><?= htmlspecialchars($atasnama_rekening) ?></span>
    </div>
    <?php endif; ?>
</div>

      <div class="footer-kartu">
        <div class="barcode">
          <div class="barcode-placeholder">
            <?= str_pad($nomor_anggota ?? '000000', 12, '0', STR_PAD_LEFT) ?>
          </div>
        </div>
        <div style="margin-top: 10px;">Kartu ini berlaku selama status keanggotaan aktif</div>
      </div>
    </div>

    <!-- Tombol Action -->
    <div class="action-buttons no-print">
      <button class="btn btn-print" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 6 2 18 2 18 9"></polyline>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
          <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
        Cetak Kartu
      </button>
      <button class="btn btn-back" onclick="window.history.back()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
      </button>
    </div>
  </div>

  <script>
    // Auto print jika diinginkan
    // window.onload = function() {
    //   window.print();
    // }
  </script>
</body>
</html> 