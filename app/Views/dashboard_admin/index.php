<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama</title>
    <!-- CDN Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Utama</h2>
        <p class="text-gray-600">Ringkasan cepat kondisi koperasi saat ini</p>
    </div>

   
   <!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Anggota Aktif -->
    <div class="card-gradient p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-700 text-sm font-medium">Total Anggota Aktif</p>
                <p class="text-2xl font-bold text-emerald-900"><?= number_format($totalAnggota) ?></p>
            </div>
            <i class="fas fa-users text-4xl text-emerald-500"></i>
        </div>
    </div>

    <!-- Total Simpanan -->
    <div class="card-blue p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-700 text-sm font-medium">Total Simpanan</p>
                <p class="text-2xl font-bold text-blue-900">Rp <?= number_format($totalSimpanan, 0, ',', '.') ?></p>
            </div>
            <i class="fas fa-circle-dollar-to-slot text-4xl text-blue-500"></i>
        </div>
    </div>

    <!-- Pembiayaan Berjalan -->
    <div class="card-purple p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-700 text-sm font-medium">Pembiayaan Berjalan</p>
                <p class="text-2xl font-bold text-purple-900">Rp <?= number_format($totalPembiayaan, 0, ',', '.') ?></p>
            </div>
            <i class="fas fa-hand-holding-usd text-4xl text-purple-500"></i>
        </div>
    </div>

    <!-- Pendapatan Margin Bulanan -->
    <div class="card-orange p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-700 text-sm font-medium">Pendapatan Bulanan</p>
                <p class="text-2xl font-bold text-orange-900">Rp <?= number_format($totalMargin, 0, ',', '.') ?></p>
            </div>
            <i class="fas fa-chart-line text-4xl text-orange-500"></i>
        </div>
    </div>
</div>


    <!-- Charts and Notifications -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart Placeholder -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Perkembangan Simpanan & Pembiayaan</h3>
        <canvas id="growthChart" width="400" height="200"></canvas>
    </div>

    <!-- Notifikasi Penting -->
    <div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Notifikasi Penting</h3>
    <div class="space-y-3">

        <!-- Anggota Baru Pending -->
        <div class="flex items-center p-3 bg-green-50 border-l-4 border-green-500 rounded">
            <i class="fas fa-user-plus text-green-500 mr-3"></i>
            <div>
                <p class="font-medium text-green-800"><?= $pendingCount ?> Anggota Baru</p>
                <p class="text-sm text-green-600">Menunggu verifikasi</p>
                <a href="<?= base_url('admin/pending-members') ?>" class="text-blue-500 underline">Lihat Detail</a>
            </div>
        </div>

        <!-- Simpanan Sukarela Pending -->
        <div class="flex items-center p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded">
            <i class="fas fa-hourglass-half text-yellow-500 mr-3"></i>
            <div>
                <p class="font-medium text-yellow-800"><?= $pendingSimpananCount ?> Simpanan Sukarela Pending</p>
                <p class="text-sm text-yellow-600">Menunggu persetujuan admin</p>
                <a href="<?= base_url('admin/pending-sukarela') ?>" class="text-blue-500 underline">Lihat Detail</a>
            </div>
        </div>

        <!-- Pinjaman Pending -->
        <div class="flex items-center p-3 bg-orange-50 border-l-4 border-orange-500 rounded">
            <i class="fas fa-money-check-alt text-orange-500 mr-3"></i>
            <div>
                <p class="font-medium text-orange-800"><?= $pendingPinjamanCount ?> Pinjaman Pending</p>
                <p class="text-sm text-orange-600">Belum disetujui oleh admin</p>
                <a href="<?= base_url('admin/pending-pinjaman') ?>" class="text-blue-500 underline">Lihat Detail</a>
            </div>
        </div>

        <!-- ✅ PEMBAYARAN CICILAN PENDING - STYLE KONSISTEN -->
        <div class="flex items-center p-3 bg-purple-50 border-l-4 border-purple-500 rounded">
            <i class="fas fa-credit-card text-purple-500 mr-3"></i>
            <div>
                <p class="font-medium text-purple-800"><?= $pendingPembayaranCount ?> Pembayaran Cicilan Pending</p>
                <p class="text-sm text-purple-600">Menunggu verifikasi admin</p>
                <a href="<?= base_url('admin/pembayaran-pending') ?>" class="text-blue-500 underline">Lihat Detail</a>
            </div>
        </div>

    </div>
</div>

            <!-- Placeholder untuk pembiayaan jatuh tempo (jika mau implementasi) -->
            <!-- 
            <div class="flex items-center p-3 bg-red-50 border-l-4 border-red-500 rounded">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <div>
                    <p class="font-medium text-red-800">5 Pembiayaan Jatuh Tempo</p>
                    <p class="text-sm text-red-600">Dalam 3 hari ke depan</p>
                </div>
            </div>
            -->

        </div>
    </div>
</div>


    <!-- Script untuk Chart -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $chartLabels ?>,
                datasets: [
                    {
                        label: 'Simpanan',
                        data: <?= $chartSimpanan ?>,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 128, 0, 0.1)',
                        tension: 0.3
                    },
                    {
                        label: 'Pembiayaan',
                        data: <?= $chartPembiayaan ?>,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
</body>
</html>
