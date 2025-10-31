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
    // ========== GLOBAL FUNCTIONS ==========
    
    // Format currency
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
            input.value = value;
        }
    }

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
        
        // Reset form validation styles
        const form = document.querySelector(`#${id} form`);
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.style.borderColor = '';
            });
        }
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
            // Reset validation styles
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.style.borderColor = '';
            });
        }
    }

    // ========== FORM VALIDATION ==========
    function validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'red';
                
                // Scroll ke field yang error
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                field.style.borderColor = '';
            }
        });
        
        return isValid;
    }

    // ========== FORM SUBMISSION ==========
    function saveTransaksi(form) {
        console.log('saveTransaksi function called');
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Validasi form sebelum submit
        if (!validateForm(form)) {
            alert('Harap isi semua field yang wajib diisi!');
            return;
        }
        
        // Loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        const formData = new FormData(form);
        
        // Debug: lihat data yang akan dikirim
        console.log('Data yang dikirim:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }

        fetch('<?= base_url('admin/saveTransaksi') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(response => {
            console.log('Response data:', response);
            if (response.status === 'success') {
                // Sweet alert atau notifikasi yang lebih baik
                showNotification('Transaksi berhasil disimpan!', 'success');
                const modalId = form.closest('.modal').id;
                closeModal(modalId);
                
                // Refresh halaman setelah 1 detik
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                throw new Error(response.message || 'Terjadi kesalahan saat menyimpan');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showNotification('Gagal menyimpan transaksi: ' + err.message, 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // ========== NOTIFICATION ==========
    function showNotification(message, type = 'info') {
        // Buat notifikasi sederhana
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Hapus notifikasi setelah 3 detik
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // ========== EVENT LISTENER SETUP ==========
    function setupFormHandlers() {
        // Form Pemasukan
        const formPemasukan = document.getElementById('formPemasukan');
        if (formPemasukan) {
            formPemasukan.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form pemasukan submitted');
                saveTransaksi(this);
            });
        }

        // Form Pengeluaran
        const formPengeluaran = document.getElementById('formPengeluaran');
        if (formPengeluaran) {
            formPengeluaran.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form pengeluaran submitted');
                saveTransaksi(this);
            });
        }
        
        // Enter key support untuk semua input field
        const allInputs = document.querySelectorAll('#formPemasukan input, #formPengeluaran input, #formPemasukan select, #formPengeluaran select');
        allInputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (form) {
                        saveTransaksi(form);
                    }
                }
            });
        });
    }

    // ========== INITIALIZATION ==========
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');
        
        // Setup filter event listeners
        const filterJenis = document.getElementById('filterJenis');
        const filterKategori = document.getElementById('filterKategori');
        const searchDeskripsi = document.getElementById('searchDeskripsi');
        const filterBulan = document.getElementById('filterBulan');
        
        if (filterJenis) filterJenis.addEventListener('change', applyFilter);
        if (filterKategori) filterKategori.addEventListener('change', applyFilter);
        if (searchDeskripsi) searchDeskripsi.addEventListener('input', applyFilter);
        if (filterBulan) filterBulan.addEventListener('change', applyFilter);
        
        // Setup form handlers
        setupFormHandlers();
        
        // Auto-format currency inputs
        const amountInputs = document.querySelectorAll('input[name="jumlah"]');
        amountInputs.forEach(input => {
            input.addEventListener('blur', function() {
                formatCurrency(this);
            });
            
            input.addEventListener('focus', function() {
                this.value = this.value.replace(/\D/g, '');
            });
            
            // Prevent negative values
            input.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = '';
                }
            });
        });
        
        // Close modal ketika klik di luar modal
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
        
        // Escape key untuk close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.flex');
                if (openModal) {
                    closeModal(openModal.id);
                }
            }
        });
        
        console.log('Initialization complete');
    });

    // ========== ERROR HANDLING ==========
    window.addEventListener('error', function(e) {
        console.error('Global error:', e.error);
    });
</script>
</body>
</html>