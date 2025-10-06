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
      <div class="w-12 h-12 rounded-lg overflow-hidden">
        <img src="<?= base_url('images/logo.jpeg') ?>" alt="Logo Koperasi" class="w-full h-full object-cover">
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
      <div class="flex items-center space-x-2">
        <img src="" alt="Foto Admin" class="w-10 h-10 rounded-full">
        <div>
          <p class="text-sm font-medium text-gray-800">Admin Koperasi</p>
          <p class="text-xs text-gray-600">Administrator</p>
        </div>
        <button id="logoutButton" onclick="location.href='<?php echo site_url('/logout'); ?>'" class="ml-4 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">Logout</button>
      </div>
    </div>
  </div>
</header>
<div class="flex">
  <?= $this->include('layouts/sidebar') ?>
  <main class="flex-1 p-6">
