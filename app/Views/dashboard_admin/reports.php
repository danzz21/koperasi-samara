<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan dan Analisis Koperasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Pilihan Tahun -->
        <div class="mb-6 bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Laporan & Analisis Koperasi</h1>
                    <p class="text-gray-600 text-lg">Laporan keuangan dan analisis kinerja koperasi Anda</p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <form method="GET" action="" class="flex items-center">
                        <label for="tahun" class="mr-3 text-gray-700 font-medium">Pilih Tahun:</label>
                        <select name="tahun" id="tahun" onchange="this.form.submit()" 
                                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <?php foreach ($tahunOptions as $year): ?>
                                <option value="<?= $year ?>" <?= $year == $tahun ? 'selected' : '' ?>>
                                    Tahun <?= $year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Grid Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Laporan Keuangan -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-lg card-hover">
                    <div class="flex items-center mb-6">
                        <div class="bg-emerald-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-file-invoice-dollar text-emerald-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <button class="w-full text-left p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-300">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold text-emerald-800 text-lg">Neraca</span>
                                    <p class="text-sm text-emerald-600 mt-1">Per 31 Desember <?= $tahun ?></p>
                                </div>
                                <div class="bg-white p-2 rounded-lg">
                                    <i class="fas fa-download text-emerald-600"></i>
                                </div>
                            </div>
                        </button>
                        
                        <button class="w-full text-left p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold text-blue-800 text-lg">Laba Rugi</span>
                                    <p class="text-sm text-blue-600 mt-1">Tahun <?= $tahun ?></p>
                                </div>
                                <div class="bg-white p-2 rounded-lg">
                                    <i class="fas fa-download text-blue-600"></i>
                                </div>
                            </div>
                        </button>
                        
                        <button class="w-full text-left p-4 bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-300">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold text-purple-800 text-lg">Arus Kas</span>
                                    <p class="text-sm text-purple-600 mt-1">Tahun <?= $tahun ?></p>
                                </div>
                                <div class="bg-white p-2 rounded-lg">
                                    <i class="fas fa-download text-purple-600"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- SHU -->
            <div class="bg-white p-6 rounded-2xl shadow-lg card-hover">
                <div class="flex items-center mb-6">
                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-pie text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Sisa Hasil Usaha</h3>
                </div>
                
                <div class="text-center mb-6">
                    <p class="text-4xl font-bold text-emerald-600 mb-2">
                        Rp <?= number_format($shu, 0, ',', '.') ?>
                    </p>
                    <p class="text-gray-600">SHU Tahun <?= $tahun ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-emerald-50 p-3 rounded-lg text-center">
                        <p class="font-medium text-gray-700 text-sm">Jasa Modal</p>
                        <p class="text-emerald-600 font-semibold">Rp <?= number_format($jasaModal, 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg text-center">
                        <p class="font-medium text-gray-700 text-sm">Jasa Usaha</p>
                        <p class="text-blue-600 font-semibold">Rp <?= number_format($jasaUsaha, 0, ',', '.') ?></p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-3">Detail Pendapatan & Pengeluaran</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Margin Murabahah</span>
                            <span class="font-medium">Rp <?= number_format($margin_murabahah, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Margin Mudharabah</span>
                            <span class="font-medium">Rp <?= number_format($margin_mudharabah, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pemasukan Umum</span>
                            <span class="font-medium">Rp <?= number_format($pemasukan_umum, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pengeluaran Umum</span>
                            <span class="font-medium">Rp <?= number_format($pengeluaran_umum, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grafik -->
        <div class="bg-white p-6 rounded-2xl shadow-lg card-hover">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Grafik Perkembangan Koperasi</h3>
            </div>
            
            <div class="h-80">
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi grafik dengan data dinamis dari PHP
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            
            // Data dari PHP (dikonversi ke format JavaScript)
            const pendapatanData = <?= json_encode($grafikData['pendapatan'] ?? []) ?>;
            const shuData = <?= json_encode($grafikData['shu'] ?? []) ?>;
            const pengeluaranData = <?= json_encode($grafikData['pengeluaran'] ?? []) ?>;
            
            // Format data untuk Chart.js (dalam juta Rupiah)
            const formatDalamJuta = (data) => data.map(value => (value / 1000000).toFixed(2));
            
            const progressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Pendapatan (Juta Rp)',
                            data: formatDalamJuta(pendapatanData),
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 3
                        },
                        {
                            label: 'SHU (Juta Rp)',
                            data: formatDalamJuta(shuData),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 3
                        },
                        {
                            label: 'Pengeluaran (Juta Rp)',
                            data: formatDalamJuta(pengeluaranData),
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                },
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Perkembangan Koperasi Tahun <?= $tahun ?>',
                            font: {
                                size: 16
                            },
                            padding: {
                                bottom: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai (Juta Rp)',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                callback: function(value) {
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        });

        // Fungsi untuk tombol download (dummy)
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                const reportType = this.querySelector('.font-semibold').textContent;
                alert(`Fitur download untuk ${reportType} akan segera tersedia`);
            });
        });
    </script>
</body>
</html>