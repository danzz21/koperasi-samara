<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password Berhasil</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
      </div>
      <h2>Reset Password Berhasil</h2>
      
      <div class="alert alert-success">
        <p><strong>Password baru Anda:</strong></p>
        <h3 style="margin: 15px 0; color: #333; background: #f8f9fa; padding: 10px; border-radius: 5px;">
          <?= session()->getFlashdata('new_password') ?>
        </h3>
        <p style="font-size: 14px; margin-top: 15px;">
          Silakan login dengan password baru ini dan ubah password Anda setelah login.
        </p>
      </div>

      <div class="login-links">
        <a href="<?= base_url('login') ?>" class="btn-login" style="text-decoration: none; display: block;">
          Login Sekarang
        </a>
      </div>
    </div>
  </div>
</body>
</html>