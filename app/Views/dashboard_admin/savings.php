<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpanan</title>
</head>
<body>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Simpanan</h2>
        <p class="text-gray-600">Kelola simpanan anggota koperasi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Pokok</h3>
            <p class="text-2xl font-bold text-emerald-600">Rp <?= number_format($totalPokok, 0, ',', '.') ?></p>
            <p class="text-sm text-gray-600">Total dari <?= $anggotaPokok ?> anggota</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Wajib</h3>
            <p class="text-2xl font-bold text-blue-600">Rp <?= number_format($totalWajib, 0, ',', '.') ?></p>
            <p class="text-sm text-gray-600">Bulan ini</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Sukarela</h3>
            <p class="text-2xl font-bold text-purple-600">Rp <?= number_format($totalSukarela, 0, ',', '.') ?></p>
            <p class="text-sm text-gray-600">Bulan ini</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Transaksi Simpanan</h3>
            <button onclick="openModal('savingsModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Input Simpanan
            </button>
        </div>
        
        <!-- Filter Section -->
        <div class="mt-4 flex gap-4">
            <div class="w-1/3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Jenis</label>
                <select id="filterJenis" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="all">Semua Jenis</option>
                    <option value="pokok">Pokok</option>
                    <option value="wajib">Wajib</option>
                    <option value="sukarela">Sukarela</option>
                </select>
            </div>
            <div class="w-1/3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Anggota</label>
                <select id="filterAnggota" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="all">Semua Anggota</option>
                    <?php foreach($anggotaList ?? [] as $anggota): ?>
                        <option value="<?= $anggota['id_anggota'] ?>"><?= $anggota['nama_lengkap'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button onclick="loadSimpanan()" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="simpananTableBody">
                <!-- Data akan diisi oleh JavaScript -->
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Loading data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <!-- MODAL INPUT SIMPANAN -->
    <div id="savingsModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Input Simpanan</h3>
            <form id="formSimpanan" class="space-y-4">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
                    <div class="relative">
                        <input id="anggotaSearch" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Cari nama anggota..." autocomplete="off">
                        <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 max-h-60 overflow-y-auto hidden"></div>
                        <button type="button" id="semuaAnggotaBtn" class="absolute right-0 top-0 mt-2 mr-2 bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs border border-blue-300 hover:bg-blue-200">Semua Anggota</button>
                        <input type="hidden" id="anggotaSelect" name="id_anggota">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
                    <select id="jenisSelect" name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <option value="">Pilih Jenis</option>
                        <option value="pokok">Simpanan Pokok</option>
                        <option value="wajib">Simpanan Wajib</option>
                        <option value="sukarela">Simpanan Sukarela</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                    <input id="jumlahInput" name="jumlah" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" min="0" required>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('savingsModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="simpanBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
// Modal functions
// Pastikan semua function ada
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
    }
    resetForm();
}

function resetForm() {
    const form = document.getElementById('formSimpanan');
    if (form) form.reset();
    
    const anggotaSelect = document.getElementById('anggotaSelect');
    if (anggotaSelect) anggotaSelect.value = '';
    
    const anggotaResults = document.getElementById('anggotaResults');
    if (anggotaResults) anggotaResults.classList.add('hidden');
    
    const anggotaSearch = document.getElementById('anggotaSearch');
    if (anggotaSearch) anggotaSearch.value = '';
    
    const semuaBtn = document.getElementById('semuaAnggotaBtn');
    if (semuaBtn) {
        semuaBtn.classList.remove('bg-emerald-600', 'text-white');
        semuaBtn.classList.add('bg-blue-100', 'text-blue-700');
        semuaBtn.textContent = 'Semua Anggota';
    }
}

// Helper format rupiah
function formatRupiah(angka) {
    const number = parseInt(angka) || 0;
    return 'Rp ' + number.toLocaleString('id-ID');
}

// Setup autocomplete dengan error handling
function setupAnggotaSearch() {
    const input = document.getElementById('anggotaSearch');
    const results = document.getElementById('anggotaResults');
    const hidden = document.getElementById('anggotaSelect');
    const semuaBtn = document.getElementById('semuaAnggotaBtn');

    if (!input || !results || !hidden || !semuaBtn) {
        console.error('Elemen autocomplete tidak ditemukan');
        return;
    }

    // Toggle semua anggota
    semuaBtn.addEventListener('click', function() {
        if (hidden.value === 'all') {
            hidden.value = '';
            input.value = '';
            input.disabled = false;
            input.placeholder = 'Cari nama anggota...';
            semuaBtn.textContent = 'Semua Anggota';
            semuaBtn.classList.remove('bg-emerald-600', 'text-white');
            semuaBtn.classList.add('bg-blue-100', 'text-blue-700');
        } else {
            hidden.value = 'all';
            input.value = 'SEMUA ANGGOTA';
            input.disabled = true;
            semuaBtn.textContent = 'Pilih Spesifik';
            semuaBtn.classList.remove('bg-blue-100', 'text-blue-700');
            semuaBtn.classList.add('bg-emerald-600', 'text-white');
        }
        results.classList.add('hidden');
    });

    // Autocomplete search
    input.addEventListener('input', function() {
        if (hidden.value === 'all') return;
        
        const q = input.value.trim();
        if (!q) {
            results.innerHTML = '';
            results.classList.add('hidden');
            hidden.value = '';
            return;
        }

        fetch('<?= base_url('admin/search-anggota') ?>?q=' + encodeURIComponent(q))
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                results.innerHTML = '';
                if (!data || data.length === 0) {
                    results.innerHTML = '<div class="px-3 py-2 text-gray-500">Tidak ada anggota ditemukan</div>';
                    results.classList.remove('hidden');
                    return;
                }

                data.forEach(a => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-emerald-100 cursor-pointer border-b border-gray-100';
                    div.innerHTML = `
                        <div class="font-medium">${a.nama_lengkap || a.nama}</div>
                        <div class="text-xs text-gray-500">${a.no_ktp || ''}</div>
                    `;
                    div.dataset.id = a.id_anggota || a.id;

                    div.addEventListener('click', function() {
                        input.value = a.nama_lengkap || a.nama;
                        hidden.value = a.id_anggota || a.id;
                        results.classList.add('hidden');
                    });

                    results.appendChild(div);
                });

                results.classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error search anggota:', err);
                results.innerHTML = '<div class="px-3 py-2 text-red-500">Error loading data</div>';
                results.classList.remove('hidden');
            });
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (input && !input.contains(e.target) && results && !results.contains(e.target)) {
            results.classList.add('hidden');
        }
    });
}

// Load data simpanan dengan error handling
function loadSimpanan() {
    const jenis = document.getElementById('filterJenis')?.value || 'all';
    const anggota = document.getElementById('filterAnggota')?.value || 'all';
    
    const tbody = document.getElementById('simpananTableBody');
    if (!tbody) {
        console.error('Table body tidak ditemukan');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

    let url = `<?= base_url('admin/getSimpananList') ?>?jenis=${jenis}`;
    if (anggota && anggota !== 'all') {
        url += `&id_anggota=${anggota}`;
    }

    console.log('Fetching from:', url);

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data simpanan
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            data.forEach(row => {
                const statusClass = (row.status === 'aktif' || row.status === 'paid') ? 
                    'bg-green-100 text-green-800' : 
                    'bg-yellow-100 text-yellow-800';
                
                const statusText = row.status === 'aktif' ? 'Aktif' : 
                                 row.status === 'paid' ? 'Dibayar' : 
                                 row.status === 'pending' ? 'Pending' : 
                                 row.status || 'Aktif';
                
                const namaAnggota = row.nama_lengkap || row.anggota || '-';
                const tanggal = row.tanggal || '-';
                const jenisSimpanan = row.jenis || '-';
                const jumlah = row.jumlah || 0;

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tanggal}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${namaAnggota}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="capitalize">${jenisSimpanan}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            ${formatRupiah(jumlah)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error('Error loading simpanan:', err);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-red-500">
                        Gagal memuat data: ${err.message}
                    </td>
                </tr>
            `;
        });
}

// Submit form dengan debug
function setupFormSubmit() {
    const form = document.getElementById('formSimpanan');
    if (!form) {
        console.error('Form tidak ditemukan');
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('simpanBtn');
        if (!submitBtn) {
            console.error('Submit button tidak ditemukan');
            return;
        }

        const originalText = submitBtn.innerHTML;
        
        // Ambil data form
        const formData = new FormData(this);
        const data = {
            id_anggota: document.getElementById('anggotaSelect')?.value,
            jenis: document.getElementById('jenisSelect')?.value,
            jumlah: document.getElementById('jumlahInput')?.value
        };
        
        console.log('Data form:', data);

        // Validasi
        if (!data.id_anggota || !data.jenis || !data.jumlah) {
            alert('Harap lengkapi semua field!');
            return;
        }

        // Loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        fetch('<?= base_url('admin/inputSimpanan') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(result => {
            console.log('Response data:', result);
            
            if (result.success) {
                alert('Simpanan berhasil disimpan!');
                closeModal('savingsModal');
                loadSimpanan();
            } else {
                alert('Gagal menyimpan: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Error: ' + err.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Initialize dengan error handling
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    try {
        setupAnggotaSearch();
        setupFormSubmit();
        loadSimpanan();
        
        // Event listeners untuk filter
        const filterJenis = document.getElementById('filterJenis');
        const filterAnggota = document.getElementById('filterAnggota');
        
        if (filterJenis) filterJenis.addEventListener('change', loadSimpanan);
        if (filterAnggota) filterAnggota.addEventListener('change', loadSimpanan);
        
        console.log('Initialization completed');
    } catch (error) {
        console.error('Initialization error:', error);
    }
});
</script>
<script>
// Debug function
function testConnection() {
    console.log('Testing connection to getSimpananList...');
    fetch('<?= base_url('admin/getSimpananList') ?>?jenis=all')
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Test data:', data);
        })
        .catch(err => {
            console.error('Test error:', err);
        });
}

// Panggil test saat load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, testing connection...');
    testConnection();
    setupAnggotaSearch();
    loadSimpanan();
});
</script>
</body>
</html>