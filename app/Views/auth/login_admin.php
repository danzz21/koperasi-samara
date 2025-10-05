<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin K-Samara - Login</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap');

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(135deg, #1abc9c, #16a085);
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }

    .login-wrapper {
      background: rgba(255, 255, 255, 0.12);
      padding: 45px 55px;
      border-radius: 18px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      width: 380px;
      backdrop-filter: blur(14px);
      text-align: center;
      transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .login-wrapper:hover {
      transform: translateY(-7px);
      box-shadow: 0 30px 50px rgba(0, 0, 0, 0.45);
    }

    .login-wrapper h1 {
      font-weight: 700;
      font-size: 2.6rem;
      letter-spacing: 3px;
      margin-bottom: 28px;
      text-shadow: 0 3px 8px rgba(0, 0, 0, 0.35);
      user-select: none;
      animation: fadeInDown 1s ease forwards;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input[type="text"],
    input[type="password"] {
      padding: 16px 20px;
      margin-bottom: 26px;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 300;
      outline: none;
      transition: box-shadow 0.3s ease;
      color: #333;
    }

    input::placeholder {
      color: #999;
      font-weight: 300;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      box-shadow: 0 0 12px #16a085;
    }

    button {
      background: #16a085;
      color: white;
      padding: 16px 0;
      border: none;
      border-radius: 12px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 7px 20px rgba(22, 160, 133, 0.65);
    }

    button:hover {
      background: #138d75;
      box-shadow: 0 10px 30px rgba(19, 141, 117, 0.85);
    }

    .error-message {
      background: rgba(255, 71, 71, 0.9);
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 24px;
      font-weight: 700;
      color: #fff;
      box-shadow: 0 6px 12px rgba(255, 45, 45, 0.75);
      text-align: center;
      user-select: none;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <h2>Admin Koperasi Samara</h2>

    <?php if (session()->getFlashdata('error')): ?>
      <p class="error-message"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/doLogin') ?>">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
