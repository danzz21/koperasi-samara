<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Dashboard Admin' ?> - Koperasi Syariah</title>
  <!-- Favicon / Logo Icon Browser -->
  <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
  <style>
    .islamic-pattern {
      background-color: #f8fafc;
    }
    .header-glow {
      box-shadow: 0 4px 15px -2px rgba(16, 185, 129, 0.1);
    }
  </style>
</head>
<body class="bg-gray-50 islamic-pattern min-h-screen text-gray-800">

<?php
  // Menghitung total semua pengajuan pending untuk notifikasi
  $totalPending = ($pendingCount ?? 0) 
                + ($pendingSimpananPokokCount ?? 0) 
                + ($pendingSimpananCount ?? 0) 
                + ($pendingPinjamanCount ?? 0) 
                + ($pendingPembayaranCount ?? 0);
?>

<!-- Top Header Bar Modern -->
<header class="bg-white sticky top-0 z-40 header-glow border-b border-emerald-100">
  <!-- Top Accent Bar (Emas & Hijau) -->
  <div class="h-1 w-full bg-gradient-to-r from-emerald-800 via-emerald-600 to-amber-400"></div>

  <div class="flex items-center justify-between px-6 py-3.5">
    
    <!-- Brand Title & Logo -->
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center overflow-hidden border border-emerald-100 shadow-sm shrink-0">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Koperasi" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="w-full h-full bg-emerald-700 text-amber-300 font-extrabold text-lg hidden items-center justify-center">KS</div>
      </div>

      <div>
        <div class="flex items-center space-x-2">
          <h1 class="text-xl font-bold text-gray-800 tracking-tight leading-none">Koperasi Syariah <span class="text-emerald-700">K-Samara</span></h1>
          <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-300/60 uppercase tracking-widest hidden sm:inline-block">Syariah</span>
        </div>
        <p class="text-xs text-gray-500 font-medium mt-1">Dashboard Administrasi</p>
      </div>
    </div>

    <!-- Right Controls (Notifikasi Dynamic & Dropdown User) -->
    <div class="flex items-center space-x-4">
      
      <!-- ================= TOMBOL NOTIFIKASI INTERAKTIF ================= -->
      <div class="relative group">
        <button class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-emerald-50 text-gray-600 hover:text-emerald-600 flex items-center justify-center transition-all duration-200 border border-gray-200/80 relative">
          <i class="fas fa-bell text-base"></i>
          
          <?php if ($totalPending > 0): ?>
            <!-- Badge Angka + Efek Ping Menyala -->
            <span class="absolute -top-1 -right-1 flex h-5 w-5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-5 w-5 bg-rose-600 text-white text-[10px] font-extrabold items-center justify-center border-2 border-white">
                <?= $totalPending > 99 ? '99+' : $totalPending ?>
              </span>
            </span>
          <?php endif; ?>
        </button>

        <!-- FLYOUT DROPDOWN CARD NOTIFIKASI -->
        <div class="absolute right-0 top-full pt-2 w-80 z-50 hidden group-hover:block transition-all duration-200">
          <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            
            <!-- Header Notifikasi -->
            <div class="px-4 py-3 bg-gradient-to-r from-emerald-800 to-teal-700 text-white flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <i class="fas fa-bell text-amber-300 text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Pengajuan Masuk</span>
              </div>
              <span class="bg-amber-400 text-emerald-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full">
                <?= $totalPending ?> Menunggu
              </span>
            </div>

            <!-- List Item Pengajuan -->
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
              
              <!-- 1. Anggota Baru -->
              <a href="<?= base_url('admin/pending-members') ?>" class="flex items-center justify-between p-3 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-user-plus text-xs"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Verifikasi Anggota Baru</p>
                    <p class="text-[10px] text-gray-500">Pendaftaran akun anggota</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-xs font-extrabold rounded-md <?= ($pendingCount ?? 0) > 0 ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-400' ?>">
                  <?= $pendingCount ?? 0 ?>
                </span>
              </a>

              <!-- 2. Simpanan Pokok -->
              <a href="<?= base_url('admin/pending-simpanan-pokok') ?>" class="flex items-center justify-between p-3 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-landmark text-xs"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Simpanan Pokok</p>
                    <p class="text-[10px] text-gray-500">Setoran pokok registrasi</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-xs font-extrabold rounded-md <?= ($pendingSimpananPokokCount ?? 0) > 0 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400' ?>">
                  <?= $pendingSimpananPokokCount ?? 0 ?>
                </span>
              </a>

              <!-- 3. Simpanan Sukarela -->
              <a href="<?= base_url('admin/pending-sukarela') ?>" class="flex items-center justify-between p-3 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-wallet text-xs"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Simpanan Sukarela</p>
                    <p class="text-[10px] text-gray-500">Setoran simpanan sukarela</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-xs font-extrabold rounded-md <?= ($pendingSimpananCount ?? 0) > 0 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400' ?>">
                  <?= $pendingSimpananCount ?? 0 ?>
                </span>
              </a>

              <!-- 4. Pengajuan Pinjaman -->
              <a href="<?= base_url('admin/pending-pinjaman') ?>" class="flex items-center justify-between p-3 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-file-invoice-dollar text-xs"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Pengajuan Pinjaman</p>
                    <p class="text-[10px] text-gray-500">Permohonan akad pembiayaan</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-xs font-extrabold rounded-md <?= ($pendingPinjamanCount ?? 0) > 0 ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-400' ?>">
                  <?= $pendingPinjamanCount ?? 0 ?>
                </span>
              </a>

              <!-- 5. Pembayaran Cicilan -->
              <a href="<?= base_url('admin/pembayaran-pending') ?>" class="flex items-center justify-between p-3 hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-credit-card text-xs"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Pembayaran Cicilan</p>
                    <p class="text-[10px] text-gray-500">Konfirmasi angsuran pinjaman</p>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-xs font-extrabold rounded-md <?= ($pendingPembayaranCount ?? 0) > 0 ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-400' ?>">
                  <?= $pendingPembayaranCount ?? 0 ?>
                </span>
              </a>

            </div>

            <!-- Footer Card Dropdown -->
            <div class="px-4 py-2 bg-gray-50 text-center border-t border-gray-100">
              <span class="text-[10px] text-gray-500 font-semibold">Klik kategori untuk memproses pengajuan</span>
            </div>

          </div>
        </div>

      </div>

      <div class="h-7 w-px bg-gray-200 hidden sm:block"></div>

      <!-- Dropdown Profile -->
      <div class="relative group">
        <div class="flex items-center space-x-3 cursor-pointer p-1.5 rounded-xl hover:bg-emerald-50/60 transition-all duration-200">
          
          <div class="w-9 h-9 rounded-xl bg-emerald-800 text-amber-300 border-2 border-amber-400 flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
            <i class="fas fa-user-shield text-xs"></i>
          </div>

          <div class="hidden md:block text-left">
            <p class="text-xs font-bold text-gray-800 leading-tight">Admin Koperasi</p>
            <p class="text-[10px] font-medium text-emerald-600">Administrator</p>
          </div>

          <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:rotate-180"></i>
        </div>

        <!-- Floating Dropdown Menu -->
        <div class="absolute right-0 top-full pt-1.5 w-52 z-50 hidden group-hover:block transition-all duration-200">
          <div class="bg-white rounded-2xl shadow-xl py-2 border border-gray-100">
            
            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl mb-1">
              <p class="text-xs font-bold text-gray-800">Admin Koperasi</p>
              <p class="text-[10px] text-gray-500 truncate">admin@ksamara.com</p>
            </div>

            <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-semibold transition-colors mx-1 rounded-xl">
              <i class="fas fa-user w-5 text-center text-emerald-600 mr-2"></i>
              Profil Saya
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-semibold transition-colors mx-1 rounded-xl">
              <i class="fas fa-cog w-5 text-center text-emerald-600 mr-2"></i>
              Pengaturan
            </a>

            <div class="border-t border-gray-100 my-1"></div>

            <a href="<?= base_url('logout') ?>" 
               onclick="return confirmLogout()"
               class="flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-bold transition-colors mx-1 rounded-xl">
              <i class="fas fa-sign-out-alt w-5 text-center mr-2"></i>
              Logout
            </a>
          </div>
        </div>

      </div>

    </div>
  </div>
</header>

<div class="flex">
  <?= $this->include('layouts/sidebar') ?>
  <main class="flex-1 p-6 md:p-8">

<script>
function confirmLogout() {
  return confirm('Apakah Anda yakin ingin logout dari Dashboard Koperasi Syariah?');
}
</script>