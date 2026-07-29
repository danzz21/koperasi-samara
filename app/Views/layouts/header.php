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

<!-- Top Header Bar Modern -->
<header class="bg-white sticky top-0 z-40 header-glow border-b border-emerald-100">
  <!-- Top Accent Bar (Emas & Hijau) -->
  <div class="h-1 w-full bg-gradient-to-r from-emerald-800 via-emerald-600 to-amber-400"></div>

  <div class="flex items-center justify-between px-6 py-3.5">
    
    <!-- Brand Title & Logo (Ukuran Pas Seperti Semula) -->
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center overflow-hidden border border-emerald-100 shadow-sm shrink-0">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Koperasi" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <!-- Fallback jika gambar logo belum ada -->
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

    <!-- Right Controls (Notifikasi & Dropdown User) -->
    <div class="flex items-center space-x-4">
      
      <!-- Tombol Notifikasi Bel -->
      <div class="relative group">
        <button class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-emerald-50 text-gray-600 hover:text-emerald-600 flex items-center justify-center transition-all duration-200 border border-gray-200/80">
          <i class="fas fa-bell text-sm"></i>
        </button>
        <!-- Badge Angka -->
        <span class="absolute -top-1 -right-1 bg-rose-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold border border-white">3</span>
      </div>

      <div class="h-7 w-px bg-gray-200 hidden sm:block"></div>

      <!-- Dropdown Profile dengan Logout (Foto Dummy) -->
      <div class="relative group">
        <div class="flex items-center space-x-3 cursor-pointer p-1.5 rounded-xl hover:bg-emerald-50/60 transition-all duration-200">
          
          <!-- Avatar Dummy SVG Ringkas -->
          <div class="w-9 h-9 rounded-xl bg-emerald-800 text-amber-300 border-2 border-amber-400 flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
            <i class="fas fa-user-shield text-xs"></i>
          </div>

          <div class="hidden md:block text-left">
            <p class="text-xs font-bold text-gray-800 leading-tight">Admin Koperasi</p>
            <p class="text-[10px] font-medium text-emerald-600">Administrator</p>
          </div>

          <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:rotate-180"></i>
        </div>

        <!-- Floating Dropdown Menu Card (Fixed Hover Gap) -->
        <div class="absolute right-0 top-full pt-1.5 w-52 z-50 hidden group-hover:block transition-all duration-200">
          <div class="bg-white rounded-2xl shadow-xl py-2 border border-gray-100">
            
            <!-- Header Mini User Info -->
            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl mb-1">
              <p class="text-xs font-bold text-gray-800">Admin Koperasi</p>
              <p class="text-[10px] text-gray-500 truncate">admin@ksamara.com</p>
            </div>

            <!-- Menu Link Items -->
            <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-semibold transition-colors mx-1 rounded-xl">
              <i class="fas fa-user w-5 text-center text-emerald-600 mr-2"></i>
              Profil Saya
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-semibold transition-colors mx-1 rounded-xl">
              <i class="fas fa-cog w-5 text-center text-emerald-600 mr-2"></i>
              Pengaturan
            </a>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- TOMBOL LOGOUT -->
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
// JavaScript untuk handle logout dengan konfirmasi
function confirmLogout() {
  return confirm('Apakah Anda yakin ingin logout dari Dashboard Koperasi Syariah?');
}
</script>