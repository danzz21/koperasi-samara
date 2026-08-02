<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Umum</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        body { font-size: 1rem; }
        .text-xs, .text-sm { font-size: 1rem !important; }
        table th, table td { font-size: 0.95rem !important; }
        label, input, select, button, .modal h3 { font-size: 1rem !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Transaksi Umum</h2>
        <p class="text-gray-600">Kelola pemasukan dan pengeluaran operasional</p>
    </div>

    <!-- Info Cards (4 Card Grid Layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        
        <!-- Card 1: Total Saldo Kas Akumulasi -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 p-5 rounded-xl shadow-md text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Saldo Kas Utama</span>
                <span class="p-2 bg-white/20 rounded-lg text-white">
                    <i class="fas fa-wallet fa-fw"></i>
                </span>
            </div>
            <p class="text-2xl font-black">Rp <?= number_format($saldo_kas_total ?? 0, 0, ',', '.') ?></p>
            <p class="text-xs text-emerald-100 mt-2">Total Sisa Saldo Kumulatif</p>
        </div>

        <!-- Card 2: Cashflow/Surplus Bulan Ini -->
        <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Arus Kas Bulan Ini</span>
                <span class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fas fa-chart-line fa-fw"></i>
                </span>
            </div>
            <p class="text-2xl font-bold <?= ($cashflow_bulan_ini ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' ?>">
                Rp <?= number_format($cashflow_bulan_ini ?? 0, 0, ',', '.') ?>
            </p>
            <p class="text-xs text-gray-500 mt-2 flex items-center justify-between">
                <span><?= esc($bulan_transaksi) ?></span>
                <span class="font-medium text-gray-700"><?= ($cashflow_bulan_ini ?? 0) >= 0 ? 'Surplus' : 'Defisit' ?></span>
            </p>
        </div>

        <!-- Card 3: Pemasukan (Bulan Ini & Total Akumulasi) -->
        <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Pemasukan</span>
                <span class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="fas fa-arrow-down fa-fw"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-green-600">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
            <div class="mt-3 pt-2 border-t border-gray-100 text-xs flex justify-between text-gray-500">
                <span>Bulan Ini (<?= date('M Y') ?>)</span>
                <span class="font-semibold text-gray-700">Total: Rp <?= number_format($total_pemasukan_umum ?? 0, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Card 4: Pengeluaran (Bulan Ini & Total Akumulasi) -->
        <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Pengeluaran</span>
                <span class="p-2 bg-red-50 rounded-lg text-red-600">
                    <i class="fas fa-arrow-up fa-fw"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-red-600">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
            <div class="mt-3 pt-2 border-t border-gray-100 text-xs flex justify-between text-gray-500">
                <span>Bulan Ini (<?= date('M Y') ?>)</span>
                <span class="font-semibold text-gray-700">Total: Rp <?= number_format($total_pengeluaran_umum ?? 0, 0, ',', '.') ?></span>
            </div>
        </div>

    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-md max-w-7xl mx-auto overflow-hidden">
        <div class="p-6 border-b border-gray-200 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h3>
            </div>

            <!-- Filter & Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Jenis:</label>
                        <select id="filterJenis" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="all">Semua Jenis</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Kategori:</label>
                        <select id="filterKategori" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="all">Semua Kategori</option>
                            <option value="Bagi Hasil">Bagi Hasil</option>
                            <option value="Jasa Administrasi">Jasa Administrasi</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Pemeliharaan">Pemeliharaan</option>
                            <option value="Gaji">Gaji</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Cari:</label>
                        <input type="text" id="searchDeskripsi" placeholder="Deskripsi..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm w-40">
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Bulan:</label>
                        <input type="month" id="filterBulan" value="<?= date('Y-m') ?>" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    </div>

                    <button onclick="resetFilter()" class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm flex items-center">
                        <i class="fas fa-refresh mr-1"></i>Reset
                    </button>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <button onclick="openModal('incomeModal')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
                        <i class="fas fa-plus mr-2"></i>Pemasukan
                    </button>
                    <button onclick="openModal('expenseModal')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm">
                        <i class="fas fa-minus mr-2"></i>Pengeluaran
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                    <?php if (!empty($riwayat)): ?>
                        <?php foreach ($riwayat as $transaksi): ?>
                        <tr class="table-row" 
                            data-jenis="<?= esc($transaksi['jenis']) ?>" 
                            data-kategori="<?= esc($transaksi['kategori']) ?>" 
                            data-deskripsi="<?= esc(strtolower($transaksi['deskripsi'])) ?>"
                            data-bulan="<?= date('Y-m', strtotime($transaksi['tanggal'])) ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d M Y', strtotime($transaksi['tanggal'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= esc($transaksi['deskripsi']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= esc($transaksi['kategori']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($transaksi['jumlah'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $transaksi['jenis'] === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($transaksi['jenis']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button onclick="editTransaksi(<?= htmlspecialchars(json_encode($transaksi)) ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteTransaksi(<?= $transaksi['id'] ?>)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada transaksi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div id="emptyState" class="hidden p-8 text-center">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada data yang sesuai dengan filter</p>
            <button onclick="resetFilter()" class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                Tampilkan Semua Data
            </button>
        </div>
    </div>

    <!-- Modal Pemasukan -->
    <div id="incomeModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Input Pemasukan</h3>
            <form id="formPemasukan" class="space-y-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="jenis" value="pemasukan">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                    <input type="text" name="deskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Bagi Hasil">Bagi Hasil</option>
                        <option value="Jasa Administrasi">Jasa Administrasi</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                    <input type="number" name="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" min="1000" required>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('incomeModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pengeluaran -->
    <div id="expenseModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Input Pengeluaran</h3>
            <form id="formPengeluaran" class="space-y-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="jenis" value="pengeluaran">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                    <input type="text" name="deskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                    <input type="number" name="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" min="1000" required>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('expenseModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT TRANSAKSI -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Transaksi</h3>
            <form id="formEdit" class="space-y-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="edit_id" name="id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis *</label>
                    <select id="edit_jenis" name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                    <input type="text" id="edit_deskripsi" name="deskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select id="edit_kategori" name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <option value="Bagi Hasil">Bagi Hasil</option>
                        <option value="Jasa Administrasi">Jasa Administrasi</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                    <input type="number" id="edit_jumlah" name="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" min="1000" required>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('editModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    let isSubmitting = false;

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            const form = modal.querySelector('form');
            if (form) form.reset();
        }
    }

    // Save Transaksi Baru
    function saveTransaksi(formElement) {
        if (isSubmitting) return;

        const submitBtn = formElement.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        isSubmitting = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        const formData = new FormData(formElement);

        fetch('<?= base_url('admin/saveTransaksi') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(data.message, 'success');
                closeModal(formElement.closest('.modal').id);
                setTimeout(() => location.reload(), 800);
            } else {
                alert('Gagal: ' + data.message);
                isSubmitting = false;
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi.');
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Open Modal & Populate Edit Data
    function editTransaksi(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_jenis').value = data.jenis;
        document.getElementById('edit_deskripsi').value = data.deskripsi;
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_jumlah').value = data.jumlah;
        openModal('editModal');
    }

    // Update Transaksi AJAX
    function updateTransaksi(formElement) {
        if (isSubmitting) return;

        const id = document.getElementById('edit_id').value;
        const submitBtn = formElement.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        isSubmitting = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
        submitBtn.disabled = true;

        const formData = new FormData(formElement);

        fetch('<?= base_url('admin/updateTransaksi') ?>/' + id, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(data.message, 'success');
                closeModal('editModal');
                setTimeout(() => location.reload(), 800);
            } else {
                alert('Gagal: ' + data.message);
                isSubmitting = false;
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate.');
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Delete Transaksi AJAX
    function deleteTransaksi(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) return;

        fetch('<?= base_url('admin/deleteTransaksi') ?>/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus.');
        });
    }

    // Filter Table
    function applyFilter() {
        const jenisFilter = document.getElementById('filterJenis').value;
        const kategoriFilter = document.getElementById('filterKategori').value;
        const searchFilter = document.getElementById('searchDeskripsi').value.toLowerCase();
        const bulanFilter = document.getElementById('filterBulan').value;
        
        const rows = document.querySelectorAll('.table-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const jenis = row.getAttribute('data-jenis');
            const kategori = row.getAttribute('data-kategori');
            const deskripsi = row.getAttribute('data-deskripsi');
            const bulan = row.getAttribute('data-bulan');
            
            const jenisMatch = jenisFilter === 'all' || jenis === jenisFilter;
            const kategoriMatch = kategoriFilter === 'all' || kategori === kategoriFilter;
            const searchMatch = searchFilter === '' || deskripsi.includes(searchFilter);
            const bulanMatch = bulanFilter === '' || bulan === bulanFilter;
            
            if (jenisMatch && kategoriMatch && searchMatch && bulanMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        const emptyState = document.getElementById('emptyState');
        const tableBody = document.getElementById('tableBody');
        
        if (visibleCount === 0) {
            if (tableBody) tableBody.style.display = 'none';
            if (emptyState) emptyState.classList.remove('hidden');
        } else {
            if (tableBody) tableBody.style.display = '';
            if (emptyState) emptyState.classList.add('hidden');
        }
    }

    function resetFilter() {
        document.getElementById('filterJenis').value = 'all';
        document.getElementById('filterKategori').value = 'all';
        document.getElementById('searchDeskripsi').value = '';
        document.getElementById('filterBulan').value = '<?= date('Y-m') ?>';
        applyFilter();
    }

    // Event Listeners Setup
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('formPemasukan')?.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            saveTransaksi(this);
        });

        document.getElementById('formPengeluaran')?.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            saveTransaksi(this);
        });

        document.getElementById('formEdit')?.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            updateTransaksi(this);
        });

        document.getElementById('filterJenis')?.addEventListener('change', applyFilter);
        document.getElementById('filterKategori')?.addEventListener('change', applyFilter);
        document.getElementById('searchDeskripsi')?.addEventListener('input', applyFilter);
        document.getElementById('filterBulan')?.addEventListener('change', applyFilter);
    });

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 text-white font-semibold ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
</script>
</body>
</html>