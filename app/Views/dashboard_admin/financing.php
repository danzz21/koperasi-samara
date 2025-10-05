
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Peminjaman</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card Aktif -->
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
        <div class="text-emerald-600 text-3xl mb-2"><i class="fas fa-check-circle"></i></div>
        <h3 class="text-md font-bold text-gray-800">Pembiayaan Aktif</h3>
        <p class="text-2xl font-bold text-emerald-600 mt-2"><?= $total_aktif ?></p>
        <p class="text-sm text-gray-600">Total Rp <?= number_format($total_jumlah, 0, ',', '.') ?></p>
    </div>

    <!-- Card Menunggu -->
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
        <div class="text-yellow-500 text-3xl mb-2"><i class="fas fa-clock"></i></div>
        <h3 class="text-md font-bold text-gray-800">Menunggu Persetujuan</h3>
        <p class="text-2xl font-bold text-yellow-500 mt-2"><?= $total_menunggu ?></p>
        <p class="text-sm text-gray-600">Pengajuan baru</p>
    </div>

    <!-- Card Jatuh Tempo -->
    <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
        <div class="text-red-600 text-3xl mb-2"><i class="fas fa-exclamation-triangle"></i></div>
        <h3 class="text-md font-bold text-gray-800">Jatuh Tempo</h3>
        <p class="text-2xl font-bold text-red-600 mt-2"><?= $total_jatuh_tempo ?></p>
        <p class="text-sm text-gray-600">3 hari ke depan</p>
    </div>
</div>



    <div class="bg-white rounded-xl shadow-md max-w-7xl mx-auto overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Daftar Pembiayaan</h3>
            <button onclick="openModal('financingModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Pengajuan Baru
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($pembiayaan as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['id']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['nama_lengkap']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['akad']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm <?= $item['status'] === 'Aktif' ? 'text-green-600' : ($item['status'] === 'Menunggu' ? 'text-yellow-600' : 'text-red-600') ?>">
                            <?= esc($item['status']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="financingModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Pengajuan Pembiayaan</h3>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>Pilih Anggota</option>
                            <option>Ahmad Fauzi</option>
                            <option>Siti Aminah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Akad Syariah</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>Murabahah</option>
                            <option>Mudharabah</option>
                            <option>Ijarah</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembiayaan</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenor (Bulan)</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="3"></textarea>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('financingModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                        Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>

</body>
</html>
