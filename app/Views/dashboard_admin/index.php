<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama</title>
    <!-- CDN Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-gradient {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
        }
        .card-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
            border: 1px solid #3b82f6;
        }
        .card-purple {
            background: linear-gradient(135deg, #e9d5ff 0%, #c4b5fd 100%);
            border: 1px solid #8b5cf6;
        }
        .card-orange {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
            border: 1px solid #f59e0b;
        }
        .notification-card {
            transition: all 0.3s ease;
        }
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Header -->
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
                <i class="fas fa-users text-3xl text-emerald-500 opacity-80"></i>
            </div>
        </div>

        <!-- Total Simpanan -->
        <div class="card-blue p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 text-sm font-medium">Total Simpanan</p>
                    <p class="text-2xl font-bold text-blue-900">Rp <?= number_format($totalSimpanan, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-piggy-bank text-3xl text-blue-500 opacity-80"></i>
            </div>
        </div>

        <!-- Pembiayaan Berjalan -->
        <div class="card-purple p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-700 text-sm font-medium">Pembiayaan Berjalan</p>
                    <p class="text-2xl font-bold text-purple-900">Rp <?= number_format($totalPembiayaan, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl text-purple-500 opacity-80"></i>
            </div>
        </div>

        <!-- Pendapatan Margin Bulanan -->
        <div class="card-orange p-6 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-700 text-sm font-medium">Pendapatan Bulanan</p>
                    <p class="text-2xl font-bold text-orange-900">Rp <?= number_format($totalMargin, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-chart-line text-3xl text-orange-500 opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Charts and Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Perkembangan Simpanan & Pembiayaan</h3>
            <div class="h-64">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Notifikasi Penting -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Notifikasi Penting</h3>
            <div class="space-y-4">
                <!-- Anggota Baru Pending -->
                <a href="<?= base_url('admin/pending-members') ?>" class="block notification-card">
                    <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-user-plus text-green-500 text-xl mr-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-green-800"><?= $pendingCount ?> Anggota Baru</p>
                            <p class="text-sm text-green-600">Menunggu verifikasi</p>
                        </div>
                        <i class="fas fa-chevron-right text-green-400"></i>
                    </div>
                </a>

                <!-- Simpanan Sukarela Pending -->
                <a href="<?= base_url('admin/pending-sukarela') ?>" class="block notification-card">
                    <div class="flex items-center p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg hover:bg-yellow-100 transition-colors">
                        <i class="fas fa-hourglass-half text-yellow-500 text-xl mr-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-yellow-800"><?= $pendingSimpananCount ?> Simpanan Sukarela</p>
                            <p class="text-sm text-yellow-600">Menunggu persetujuan admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-yellow-400"></i>
                    </div>
                </a>

                <!-- Simpanan Pokok Pending -->
                <a href="<?= base_url('admin/pending-simpanan-pokok') ?>" class="block notification-card">
                    <div class="flex items-center p-4 bg-amber-50 border-l-4 border-amber-500 rounded-lg hover:bg-amber-100 transition-colors">
                        <i class="fas fa-landmark text-amber-500 text-xl mr-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-amber-800"><?= $pendingSimpananPokokCount ?> Simpanan Pokok</p>
                            <p class="text-sm text-amber-600">Menunggu verifikasi</p>
                        </div>
                        <i class="fas fa-chevron-right text-amber-400"></i>
                    </div>
                </a>

                <!-- Pinjaman Pending -->
                <a href="<?= base_url('admin/pending-pinjaman') ?>" class="block notification-card">
                    <div class="flex items-center p-4 bg-orange-50 border-l-4 border-orange-500 rounded-lg hover:bg-orange-100 transition-colors">
                        <i class="fas fa-money-check-alt text-orange-500 text-xl mr-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-orange-800"><?= $pendingPinjamanCount ?> Pinjaman</p>
                            <p class="text-sm text-orange-600">Belum disetujui oleh admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-orange-400"></i>
                    </div>
                </a>

                <!-- Pembayaran Cicilan Pending -->
                <a href="<?= base_url('admin/pembayaran-pending') ?>" class="block notification-card">
                    <div class="flex items-center p-4 bg-purple-50 border-l-4 border-purple-500 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-credit-card text-purple-500 text-xl mr-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-purple-800"><?= $pendingPembayaranCount ?> Pembayaran Cicilan</p>
                            <p class="text-sm text-purple-600">Menunggu verifikasi admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-purple-400"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="<?= base_url('admin/members') ?>" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-users text-blue-500 mr-3"></i>
                <span class="font-medium text-blue-800">Kelola Anggota</span>
            </a>
            <a href="<?= base_url('admin/savings') ?>" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-piggy-bank text-green-500 mr-3"></i>
                <span class="font-medium text-green-800">Kelola Simpanan</span>
            </a>
            <a href="<?= base_url('admin/financing') ?>" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-hand-holding-usd text-purple-500 mr-3"></i>
                <span class="font-medium text-purple-800">Kelola Pembiayaan</span>
            </a>
            <a href="<?= base_url('admin/transactions') ?>" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-receipt text-orange-500 mr-3"></i>
                <span class="font-medium text-orange-800">Transaksi Umum</span>
            </a>
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
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pembiayaan',
                            data: <?= $chartPembiayaan ?>,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</body>
</html>