<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Dashboard Admin' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50 islamic-pattern">
<header class="bg-white shadow-lg border-b-4 border-emerald-500">
  <div class="flex items-center justify-between px-6 py-4">
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 rounded-lg overflow-hidden transform scale-150">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Koperasi" class="w-full h-full object-cover">
      </div>

      <div>
        <h1 class="text-2xl font-bold text-gray-800">Koperasi Syariah K-Samara</h1>
        <p class="text-sm text-gray-600">Dashboard Administrasi</p>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <div class="relative">
        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer"></i>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs notification-badge">3</span>
      </div>
      
      <!-- Dropdown Profile dengan Logout -->
      <div class="relative group">
        <div class="flex items-center space-x-2 cursor-pointer">
          <img src="<?= base_url('assets/images/danzz.png') ?>" alt="Foto Admin" class="w-10 h-10 rounded-full object-cover">
          <div>
            <p class="text-sm font-medium text-gray-800">Admin Koperasi</p>
            <p class="text-xs text-gray-600">Administrator</p>
          </div>
          <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
        </div>
        
        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-user mr-2"></i>Profile
          </a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-cog mr-2"></i>Settings
          </a>
          <div class="border-t border-gray-100"></div>
          
          <!-- FIXED LOGOUT LINK -->
          <a href="<?= base_url('logout') ?>" 
             onclick="return confirmLogout()"
             class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cursor-pointer">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="flex">
  <?= $this->include('layouts/sidebar') ?>
  <main class="flex-1 p-6">

<script>
// JavaScript untuk handle logout dengan konfirmasi
function confirmLogout() {
    return confirm('Apakah Anda yakin ingin logout dari admin?');
}

// Jika ingin menggunakan form POST, gunakan ini:
// function logoutAdmin() {
//     if (confirm('Apakah Anda yakin ingin logout dari admin?')) {
//         // Buat form dinamis untuk POST request
//         const form = document.createElement('form');
//         form.method = 'POST';
//         form.action = '<?= base_url("logout/admin") ?>';
        
//         // Tambahkan CSRF token
//         const csrfToken = document.createElement('input');
//         csrfToken.type = 'hidden';
//         csrfToken.name = '<?= csrf_token() ?>';
//         csrfToken.value = '<?= csrf_hash() ?>';
//         form.appendChild(csrfToken);
        
//         document.body.appendChild(form);
//         form.submit();
//     }
//     return false;
// }
</script>