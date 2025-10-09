<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Anggota</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome untuk icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.login-container {
  width: 100%;
  max-width: 400px;
  padding: 20px;
}

.login-card {
  background: white;
  padding: 40px 30px;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.logo {
  margin-bottom: 20px;
}

.logo img {
  max-width: 80px;
  height: auto;
}

h2 {
  color: #333;
  margin-bottom: 30px;
  font-weight: 600;
}

.input-group {
  position: relative;
  margin-bottom: 20px;
}

.input-group input {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #e1e1e1;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.input-group input:focus {
  outline: none;
  border-color: #667eea;
}

.password-group {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #666;
}

.toggle-password:hover {
  color: #333;
}


.login-links {
  margin-top: 20px;
}

.forgot-password {
  margin-bottom: 10px;
}

.forgot-password a, .register-text a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.forgot-password a:hover, .register-text a:hover {
  text-decoration: underline;
}

.alert {
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
  font-size: 14px;
}

.alert-error {
  background-color: #fee;
  color: #c33;
  border: 1px solid #fcc;
}

.alert-success {
  background-color: #efe;
  color: #363;
  border: 1px solid #cfc;
}
</style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
      </div>
      <h2>Login Anggota</h2>
      
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>

      <!-- FORM LOGIN -->
      <form action="<?= base_url('auth/doLogin') ?>" method="post">
        <div class="input-group">
          <input type="text" name="username" placeholder="Username" required value="<?= old('username') ?>">
        </div>
        <div class="input-group password-group">
          <input type="password" name="password" id="password" placeholder="Password" required>
          <span class="toggle-password" onclick="togglePassword()">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <button type="submit" class="btn-login">Login</button>
      </form>

      <div class="login-links">
        <p class="forgot-password">
          <a href="<?= base_url('auth/forgotPassword') ?>">Lupa Password?</a>
        </p>
        <p class="register-text">Anggota Baru? 
          <a href="<?= base_url('register') ?>">Daftar</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const icon = document.querySelector('.toggle-password i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
</html>