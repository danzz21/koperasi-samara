<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
      </div>
      <h2>Reset Password</h2>
      <p style="color: #666; margin-bottom: 20px; font-size: 14px;">
        Masukkan username dan nomor handphone Anda untuk reset password
      </p>
      
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('auth/processForgotPassword') ?>" method="post">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required value="<?= old('username') ?>">
        </div>
        <div class="input-group">
          <input type="tel" name="phone" placeholder="Nomor Handphone" required value="<?= old('phone') ?>">
        </div>
        <button type="submit" class="btn-login">Reset Password</button>
      </form>

      <div class="login-links">
        <p class="register-text">
          <a href="<?= base_url('login') ?>">Kembali ke Login</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>