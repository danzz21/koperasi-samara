<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="w-full h-full object-cover">
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Login Admin</h2>
            <p class="text-gray-600 mt-2">Koperasi Syariah K-Samara</p>
        </div>
        
        <form action="<?= base_url('auth/process_login_admin') ?>" method="POST">
            <?= csrf_field() ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="username">Username</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                    <input type="text" id="username" name="username" 
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Masukkan username" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" for="password">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    <input type="password" id="password" name="password" 
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Masukkan password" required>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-semibold hover:bg-emerald-700 transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="<?= base_url() ?>" class="text-emerald-600 hover:text-emerald-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>