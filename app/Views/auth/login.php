<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Anggota</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
      </div>
      <h2>Login Anggota</h2>
      
<?php if (session()->getFlashdata('error')): ?>
  <div style="color: red; margin-bottom: 10px;">
    <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

      <!-- FORM LOGIN -->
      <form action="<?= base_url('auth/doLogin') ?>" method="post">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
      </form>

      <p class="register-text">Anggota Baru? 
        <a href="<?= base_url('register') ?>">Daftar</a>
      </p>
    </div>
  </div>
</body>
</html>
