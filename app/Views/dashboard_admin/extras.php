<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Tambahan</title>
</head>
<body>
    <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Fitur Tambahan</h2>
                    <p class="text-gray-600">Fitur pendukung operasional koperasi</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-search text-4xl text-emerald-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Pencarian Cepat</h3>
                            <p class="text-gray-600 mb-4">Cari anggota dan transaksi dengan mudah</p>
                            <input type="text" placeholder="Cari..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-download text-4xl text-blue-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Export/Import Data</h3>
                            <p class="text-gray-600 mb-4">Kelola data dengan file Excel atau CSV</p>
                            <div class="space-y-2">
                                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Export Data
                                </button>
                                <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    Import Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-bell text-4xl text-purple-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Notifikasi</h3>
                            <p class="text-gray-600 mb-4">Pengaturan notifikasi WhatsApp & Email</p>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="form-checkbox text-emerald-600">
                                    <span class="ml-2 text-sm">WhatsApp</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="form-checkbox text-emerald-600">
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-database text-4xl text-orange-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Backup & Restore</h3>
                            <p class="text-gray-600 mb-4">Kelola backup database sistem</p>
                            <div class="space-y-2">
                                <button class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                    Backup Now
                                </button>
                                <button class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                    Restore
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-history text-4xl text-red-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Audit Log</h3>
                            <p class="text-gray-600 mb-4">Riwayat aktivitas pengguna sistem</p>
                            <button class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Lihat Log
                            </button>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <div class="text-center">
                            <i class="fas fa-cog text-4xl text-gray-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Pengaturan Sistem</h3>
                            <p class="text-gray-600 mb-4">Konfigurasi umum aplikasi</p>
                            <button class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Konfigurasi
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>