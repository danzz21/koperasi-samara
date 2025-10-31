<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Anggota</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

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
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, 
        rgba(16, 185, 129, 0.15) 0%, 
        rgba(6, 182, 212, 0.15) 50%, 
        rgba(14, 165, 233, 0.1) 100%);
      color: var(--dark);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      line-height: 1.6;
      position: relative;
    }

    /* Background pattern yang subtle */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
      z-index: -1;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 50px 40px;
      border-radius: var(--border-radius);
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.8);
      text-align: center;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 6px;
      background: var(--gradient-primary);
    }

    .logo {
      margin-bottom: 25px;
    }

    .logo img {
      max-width: 90px;
      height: auto;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    }

    h2 {
      color: var(--dark);
      margin-bottom: 35px;
      font-weight: 600;
      font-size: 28px;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid var(--gray-light);
      border-radius: var(--border-radius-sm);
      font-size: 15px;
      transition: var(--transition);
      background: var(--light);
      font-family: 'Poppins', sans-serif;
    }

    .input-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
      transform: translateY(-2px);
    }

    .input-group input::placeholder {
      color: var(--gray);
    }

    .password-group {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--gray);
      transition: var(--transition);
    }

    .toggle-password:hover {
      color: var(--primary);
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: var(--border-radius-sm);
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-family: 'Poppins', sans-serif;
      margin-top: 10px;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
    }

    .btn-login:active {
      transform: translateY(-1px);
    }

    .login-links {
      margin-top: 30px;
    }

    .forgot-password {
      margin-bottom: 15px;
    }

    .forgot-password a, .register-text a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
      font-size: 16px;
    }

    .forgot-password a:hover, .register-text a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .register-text {
      color: var(--gray);
      font-size: 16px;
      font-weight: 500;
    }

    .alert {
      padding: 15px;
      border-radius: var(--border-radius-sm);
      margin-bottom: 20px;
      font-size: 14px;
      font-weight: 500;
      transition: var(--transition);
    }

    .alert-error {
      background-color: #fef2f2;
      color: var(--danger);
      border: 1px solid #fecaca;
    }

    .alert-success {
      background-color: #f0fdf4;
      color: var(--success);
      border: 1px solid #bbf7d0;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 40px 25px;
      }
      
      h2 {
        font-size: 24px;
      }
      
      .input-group input {
        padding: 14px 16px;
      }
      
      .forgot-password a, .register-text a {
        font-size: 15px;
      }
      
      .register-text {
        font-size: 15px;
      }
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
          <a href="https://wa.me/6287865567513?text=Assalamualaikum%20admin,%20saya%20ingin%20mereset%20password" target="_blank">
            Lupa Password?
          </a>
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