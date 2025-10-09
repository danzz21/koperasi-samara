<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan dan Analisis</title>
    <!-- Menambahkan Tailwind CSS untuk styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Menambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Menambahkan Chart.js untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Laporan & Analisis</h2>
            <p class="text-gray-600">Laporan keuangan dan analisis kinerja koperasi</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Keuangan</h3>
                <div class="space-y-3">
                    <button class="w-full text-left p-3 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-emerald-800">Neraca</span>
                            <i class="fas fa-download text-emerald-600"></i>
                        </div>
                        <p class="text-sm text-emerald-600">Per 31 Januari 2024</p>
                    </button>
                    <button class="w-full text-left p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-blue-800">Laba Rugi</span>
                            <i class="fas fa-download text-blue-600"></i>
                        </div>
                        <p class="text-sm text-blue-600">Januari 2024</p>
                    </button>
                    <button class="w-full text-left p-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-purple-800">Arus Kas</span>
                            <i class="fas fa-download text-purple-600"></i>
                        </div>
                        <p class="text-sm text-purple-600">Januari 2024</p>
                    </button>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Sisa Hasil Usaha (SHU)</h3>
                <div class="text-center">
                    <p class="text-4xl font-bold text-emerald-600 mb-2">Rp 1,610,000</p>
                    <p class="text-gray-600 mb-4">SHU Tahun 2023</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center">
                            <p class="font-medium text-gray-800">Jasa Modal</p>
                            <p class="text-emerald-600">Rp 805,000</p>
                        </div>
                        <div class="text-center">
                            <p class="font-medium text-gray-800">Jasa Usaha</p>
                            <p class="text-emerald-600">Rp 805,000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Grafik Perkembangan Koperasi</h3>
            <div class="h-80">
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi grafik
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            
            // Data dummy untuk grafik
            const progressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Pendapatan (Juta Rp)',
                            data: [12, 19, 15, 22, 18, 25, 28, 30, 27, 32, 35, 40],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'SHU (Juta Rp)',
                            data: [5, 8, 6, 10, 8, 12, 14, 15, 13, 16, 18, 20],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Perkembangan Koperasi Tahun 2023'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai (Juta Rp)'
                            }
                        }
                    }
                }
            });
        });

        // Fungsi untuk tombol download (dummy)
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                const reportType = this.querySelector('.font-medium').textContent;
                alert(`Fitur download untuk ${reportType} akan segera tersedia`);
            });
        });
    </script>
</body>
</html>