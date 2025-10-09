<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Umum</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Transaksi Umum</h2>
        <p class="text-gray-600">Kelola pemasukan dan pengeluaran operasional</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Total Pemasukan Bulan Ini</h3>
            <p class="text-3xl font-bold text-green-600">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
            <div class="mt-2 flex items-center">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span class="text-sm text-green-600">+12% dari bulan lalu</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Total Pengeluaran Bulan Ini</h3>
            <p class="text-3xl font-bold text-red-600">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
            <div class="mt-2 flex items-center">
                <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                <span class="text-sm text-red-600">-5% dari bulan lalu</span>
            </div>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-md max-w-7xl mx-auto overflow-hidden">
    <div class="p-6 border-b border-gray-200 space-y-4">
        
        <!-- Baris 1: Judul -->
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h3>
        </div>

        <!-- Baris 2: Filter & Tombol -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            
            <!-- Bagian Kiri: Filter -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter Jenis -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Jenis:</label>
                    <select id="filterJenis" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <option value="all">Semua Jenis</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Filter Kategori -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Kategori:</label>
                    <select id="filterKategori" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <option value="all">Semua Kategori</option>
                        <option value="Bagi Hasil">Bagi Hasil</option>
                        <option value="Jasa Administrasi">Jasa Administrasi</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <!-- Search Deskripsi -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Cari:</label>
                    <input type="text" id="searchDeskripsi" placeholder="Deskripsi..." 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm w-40">
                </div>

                <!-- Filter Bulan -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Bulan:</label>
                    <input type="month" id="filterBulan" value="<?= date('Y-m') ?>" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>

                <!-- Tombol Reset -->
                <button onclick="resetFilter()" 
                    class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm flex items-center">
                    <i class="fas fa-refresh mr-1"></i>Reset
                </button>
            </div>

            <!-- Bagian Kanan: Tombol Tambah -->
            <div class="flex items-center gap-2 ml-auto">
                <button onclick="openModal('incomeModal')" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i>Pemasukan
                </button>
                <button onclick="openModal('expenseModal')" 
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm">
                    <i class="fas fa-minus mr-2"></i>Pengeluaran
                </button>
            </div>

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
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada transaksi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
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

    <script>
        // ========== FILTER FUNCTIONS ==========
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
            
            // Toggle empty state
            const emptyState = document.getElementById('emptyState');
            const tableBody = document.getElementById('tableBody');
            
            if (visibleCount === 0) {
                tableBody.style.display = 'none';
                emptyState.classList.remove('hidden');
            } else {
                tableBody.style.display = '';
                emptyState.classList.add('hidden');
            }
            
            console.log('Filter applied:', {
                jenis: jenisFilter,
                kategori: kategoriFilter,
                search: searchFilter,
                bulan: bulanFilter,
                visible: visibleCount
            });
        }
        
        function resetFilter() {
            document.getElementById('filterJenis').value = 'all';
            document.getElementById('filterKategori').value = 'all';
            document.getElementById('searchDeskripsi').value = '';
            document.getElementById('filterBulan').value = '<?= date('Y-m') ?>';
            applyFilter();
        }

        // ========== MODAL FUNCTIONS ==========
        function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById(id).classList.remove("flex");
            resetForm(id);
        }

        function resetForm(modalId) {
            const form = document.querySelector(`#${modalId} form`);
            if (form) {
                form.reset();
            }
        }

        // ========== FORM SUBMISSION ==========
        document.getElementById('formPemasukan').addEventListener('submit', function(e) {
            e.preventDefault();
            saveTransaksi(this);
        });

        document.getElementById('formPengeluaran').addEventListener('submit', function(e) {
            e.preventDefault();
            saveTransaksi(this);
        });

        function saveTransaksi(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            const formData = new FormData(form);

            fetch('<?= base_url('admin/saveTransaksi') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    alert('Transaksi berhasil disimpan!');
                    const modalId = form.closest('.modal').id;
                    closeModal(modalId);
                    location.reload();
                } else {
                    alert('Gagal menyimpan transaksi: ' + response.message);
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan jaringan');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // ========== INITIALIZATION ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Event listeners untuk filter
            document.getElementById('filterJenis').addEventListener('change', applyFilter);
            document.getElementById('filterKategori').addEventListener('change', applyFilter);
            document.getElementById('searchDeskripsi').addEventListener('input', applyFilter);
            document.getElementById('filterBulan').addEventListener('change', applyFilter);
            
            // Auto-format currency inputs
            const amountInputs = document.querySelectorAll('input[name="jumlah"]');
            amountInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    formatCurrency(this);
                });
                
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/\D/g, '');
                });
            });
        });

        // Format currency
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                input.value = value;
            }
        }
    </script>
</body>
</html>