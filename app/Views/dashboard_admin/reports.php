<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dan Analisi</title>
</head>
<body>
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
                    <canvas id="progressChart" width="400" height="200"></canvas>
                </div>
                
</body>
</html>