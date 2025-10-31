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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Pokok</h3>
            <p class="text-2xl font-bold text-emerald-600">Rp <?= number_format($totalPokok, 0, ',', '.') ?></p>
            <p class="text-sm text-gray-600">Total dari <?= $anggotaPokok ?> anggota</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Anggota Lunas</h3>
            <p class="text-2xl font-bold text-green-600"><?= $anggotaLunas ?? 0 ?></p>
            <p class="text-sm text-gray-600">Simpanan Pokok Lunas</p>
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
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

        <!-- Jenis Simpanan -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
            <select id="jenisSelect" name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">Pilih Jenis</option>
            <option value="pokok">Simpanan Pokok</option>
            <option value="wajib">Simpanan Wajib</option>
            <option value="sukarela">Simpanan Sukarela</option>
            </select>
        </div>

        <!-- Tenor (khusus pokok) -->
        <div id="tenorGroup" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tenor (bulan)</label>
            <select id="tenorSelect" name="tenor" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            <option value="">Pilih Tenor</option>
            <option value="1">1 Bulan</option>
            <option value="2">2 Bulan</option>
            <option value="5">5 Bulan</option>
            </select>
        </div>

        <!-- Anggota -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
            <div class="relative">
            <input id="anggotaSearch" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Cari nama anggota..." autocomplete="off">
            <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 max-h-60 overflow-y-auto hidden"></div>
            <button type="button" id="semuaAnggotaBtn" class="absolute right-0 top-0 mt-2 mr-2 bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs border border-blue-300 hover:bg-blue-200 hidden">Semua Anggota</button>
            <input type="hidden" id="anggotaSelect" name="id_anggota">
            </div>
            <div id="anggotaInfo" class="mt-1 text-xs text-gray-500 hidden"></div>
        </div>

        <!-- Jumlah -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
            <input id="jumlahInput" name="jumlah" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="1" required>
        </div>

        <div class="flex space-x-3 pt-4">
            <button type="button" onclick="closeModal('savingsModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600">Batal</button>
            <button type="submit" id="simpanBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700">Simpan</button>
        </div>
        </form>
    </div>
    </div>

<script>


// === LOGIKA BARU UNTUK FORM ===
document.addEventListener('DOMContentLoaded', () => {
  const jenisSelect = document.getElementById('jenisSelect');
  const tenorGroup = document.getElementById('tenorGroup');
  const tenorSelect = document.getElementById('tenorSelect');
  const semuaBtn = document.getElementById('semuaAnggotaBtn');
  const jumlahInput = document.getElementById('jumlahInput');
  const anggotaSearch = document.getElementById('anggotaSearch');
  const anggotaSelect = document.getElementById('anggotaSelect');
  const anggotaInfo = document.getElementById('anggotaInfo');

  // Event saat pilih jenis simpanan
  jenisSelect.addEventListener('change', () => {
    const jenis = jenisSelect.value;
    resetDynamicFields();

    if (jenis === 'pokok') {
      tenorGroup.classList.remove('hidden');
      semuaBtn.classList.remove('hidden');
      jumlahInput.disabled = true;
    } 
    else if (jenis === 'wajib') {
      tenorGroup.classList.add('hidden');
      semuaBtn.classList.remove('hidden');
      jumlahInput.value = 50000;
      jumlahInput.disabled = false;
    } 
    else if (jenis === 'sukarela') {
      tenorGroup.classList.add('hidden');
      semuaBtn.classList.add('hidden');
      jumlahInput.value = '';
      jumlahInput.disabled = false;
    }
  });

  // Event saat pilih tenor di simpanan pokok
  tenorSelect.addEventListener('change', () => {
    const tenor = parseInt(tenorSelect.value);
    if (!isNaN(tenor) && tenor > 0) {
      jumlahInput.value = 500000 / tenor;
    }
  });

  // Klik tombol semua anggota
//   semuaBtn.addEventListener('click', () => {
//     const jenis = jenisSelect.value;
//     const tenor = tenorSelect.value;
    
//     if (jenis === 'pokok') {
//       if (!tenor) {
//         alert('Pilih tenor dulu!');
//         return;
//       }
//       anggotaSelect.value = 'tenor_' + tenor;
//       anggotaSearch.value = 'Semua anggota (tenor ' + tenor + ')';
//       anggotaSearch.disabled = true;
//       anggotaInfo.innerHTML = `Semua anggota dengan tenor ${tenor} bulan akan terpilih.`;
//       anggotaInfo.classList.remove('hidden');
//     } 
//     else if (jenis === 'wajib') {
//       anggotaSelect.value = 'all';
//       anggotaSearch.value = 'Semua anggota';
//       anggotaSearch.disabled = true;
//       anggotaInfo.innerHTML = `Semua anggota akan terpilih.`;
//       anggotaInfo.classList.remove('hidden');
//     }
//   });

  // Reset dinamis
  function resetDynamicFields() {
    tenorSelect.value = '';
    tenorGroup.classList.add('hidden');
    anggotaSelect.value = '';
    anggotaSearch.value = '';
    anggotaSearch.disabled = false;
    anggotaInfo.innerHTML = '';
    anggotaInfo.classList.add('hidden');
    jumlahInput.value = '';
    jumlahInput.disabled = false;
  }
});
// Global variables
let isSubmitting = false;

// Modal functions
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        resetPokokInfo();
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
    if (anggotaResults) {
        anggotaResults.innerHTML = '';
        anggotaResults.classList.add('hidden');
    }
    
    const anggotaSearch = document.getElementById('anggotaSearch');
    if (anggotaSearch) {
        anggotaSearch.value = '';
        anggotaSearch.disabled = false;
        anggotaSearch.placeholder = 'Cari nama anggota...';
    }
    
    const semuaBtn = document.getElementById('semuaAnggotaBtn');
    if (semuaBtn) {
        semuaBtn.classList.remove('bg-emerald-600', 'text-white');
        semuaBtn.classList.add('bg-blue-100', 'text-blue-700');
        semuaBtn.textContent = 'Semua Anggota';
    }
    
    const anggotaInfo = document.getElementById('anggotaInfo');
    if (anggotaInfo) {
        anggotaInfo.classList.add('hidden');
        anggotaInfo.innerHTML = '';
    }
    
    resetPokokInfo();
    
    // Reset submit state
    isSubmitting = false;
    const simpanBtn = document.getElementById('simpanBtn');
    if (simpanBtn) {
        simpanBtn.disabled = false;
        simpanBtn.innerHTML = 'Simpan';
    }
}

function resetPokokInfo() {
    const pokokInfo = document.getElementById('pokokInfo');
    const pokokDetail = document.getElementById('pokokDetail');
    const jumlahInput = document.getElementById('jumlahInput');
    
    if (pokokInfo) pokokInfo.classList.add('hidden');
    if (pokokDetail) pokokDetail.innerHTML = '';
    if (jumlahInput) {
        jumlahInput.disabled = false;
        jumlahInput.removeAttribute('max');
        jumlahInput.min = '1';
    }
}

// Helper format rupiah
function formatRupiah(angka) {
    const number = parseInt(angka) || 0;
    return 'Rp ' + number.toLocaleString('id-ID');
}

// Check simpanan pokok status
function checkSimpananPokok() {
    const jenis = document.getElementById('jenisSelect').value;
    const idAnggota = document.getElementById('anggotaSelect').value;
    const jumlahInput = document.getElementById('jumlahInput');
    
    if (jenis === 'pokok' && idAnggota && idAnggota !== 'all') {
        // Cek status simpanan pokok anggota
        fetch(`<?= base_url('admin/checkSimpananPokok/') ?>${idAnggota}`)
            .then(res => res.json())
            .then(data => {
                const pokokInfo = document.getElementById('pokokInfo');
                const pokokDetail = document.getElementById('pokokDetail');
                const simpanBtn = document.getElementById('simpanBtn');
                
                if (data.success) {
                    pokokInfo.classList.remove('hidden');
                    
                    if (data.isLunas) {
                        // Sudah lunas
                        pokokInfo.className = 'bg-red-50 border border-red-200 rounded-md p-3';
                        pokokDetail.innerHTML = `
                            <div class="text-red-700">
                                <strong><i class="fas fa-exclamation-triangle mr-1"></i>ANGGOTA SUDAH LUNAS!</strong><br>
                                Total simpanan: ${formatRupiah(data.total)}<br>
                                <strong>Tidak dapat input simpanan pokok lagi.</strong>
                            </div>
                        `;
                        simpanBtn.disabled = true;
                        jumlahInput.disabled = true;
                    } else {
                        // Belum lunas, tampilkan sisa
                        pokokInfo.className = 'bg-blue-50 border border-blue-200 rounded-md p-3';
                        pokokDetail.innerHTML = `
                            <div class="text-blue-700">
                                <strong>Informasi Simpanan Pokok:</strong><br>
                                • Total simpanan: ${formatRupiah(data.total)}<br>
                                • Batas maksimal: ${formatRupiah(data.max_limit)}<br>
                                • <strong>Sisa yang bisa diinput: ${formatRupiah(data.sisa)}</strong>
                            </div>
                        `;
                        simpanBtn.disabled = false;
                        jumlahInput.disabled = false;
                        
                        // Set max value pada input jumlah
                        if (data.sisa > 0) {
                            jumlahInput.max = data.sisa;
                        } else {
                            jumlahInput.disabled = true;
                            simpanBtn.disabled = true;
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Error checking simpanan pokok:', err);
            });
    } else if (jenis === 'pokok' && idAnggota === 'all') {
        // Untuk semua anggota
        const pokokInfo = document.getElementById('pokokInfo');
        const pokokDetail = document.getElementById('pokokDetail');
        pokokInfo.classList.remove('hidden');
        pokokInfo.className = 'bg-yellow-50 border border-yellow-200 rounded-md p-3';
        pokokDetail.innerHTML = `
            <div class="text-yellow-700">
                <strong><i class="fas fa-info-circle mr-1"></i>Input untuk Semua Anggota:</strong><br>
                • Simpanan akan diinput hanya untuk anggota yang belum mencapai Rp 500.000<br>
                • Anggota yang sudah lunas akan dilewati secara otomatis
            </div>
        `;
        document.getElementById('simpanBtn').disabled = false;
        document.getElementById('jumlahInput').disabled = false;
        document.getElementById('jumlahInput').removeAttribute('max');
    } else {
        resetPokokInfo();
        document.getElementById('simpanBtn').disabled = false;
        document.getElementById('jumlahInput').disabled = false;
        document.getElementById('jumlahInput').removeAttribute('max');
    }
}

// Validasi real-time pada input jumlah
function setupJumlahValidation() {
    const jumlahInput = document.getElementById('jumlahInput');
    if (!jumlahInput) return;
    
    jumlahInput.addEventListener('input', function() {
        const jenis = document.getElementById('jenisSelect').value;
        const idAnggota = document.getElementById('anggotaSelect').value;
        
        if (jenis === 'pokok' && idAnggota && idAnggota !== 'all') {
            const currentJumlah = parseInt(this.value) || 0;
            const maxJumlah = parseInt(this.max) || 0;
            
            if (currentJumlah > maxJumlah && maxJumlah > 0) {
                this.value = maxJumlah;
                alert(`Jumlah tidak boleh melebihi sisa simpanan pokok: ${formatRupiah(maxJumlah)}`);
            }
        }
    });
}

// Setup autocomplete
function setupAnggotaSearch() {
    const input = document.getElementById('anggotaSearch');
    const results = document.getElementById('anggotaResults');
    const hidden = document.getElementById('anggotaSelect');
    const semuaBtn = document.getElementById('semuaAnggotaBtn');
    const anggotaInfo = document.getElementById('anggotaInfo');

    if (!input || !results || !hidden || !semuaBtn) return;

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
            if (anggotaInfo) {
                anggotaInfo.classList.add('hidden');
                anggotaInfo.innerHTML = '';
            }
        } else {
            hidden.value = 'all';
            input.value = 'SEMUA ANGGOTA';
            input.disabled = true;
            semuaBtn.textContent = 'Pilih Spesifik';
            semuaBtn.classList.remove('bg-blue-100', 'text-blue-700');
            semuaBtn.classList.add('bg-emerald-600', 'text-white');
            if (anggotaInfo) {
                anggotaInfo.innerHTML = 'Simpanan akan diinput untuk semua anggota yang belum lunas';
                anggotaInfo.classList.remove('hidden');
            }
        }
        results.classList.add('hidden');
        checkSimpananPokok();
    });

    // Autocomplete search
    input.addEventListener('input', function() {
        if (hidden.value === 'all') return;
        
        const q = input.value.trim();
        if (!q) {
            results.innerHTML = '';
            results.classList.add('hidden');
            hidden.value = '';
            if (anggotaInfo) {
                anggotaInfo.classList.add('hidden');
                anggotaInfo.innerHTML = '';
            }
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
                        
                        // Show anggota info
                        if (anggotaInfo) {
                            anggotaInfo.innerHTML = `Anggota terpilih: ${a.nama_lengkap || a.nama}`;
                            anggotaInfo.classList.remove('hidden');
                        }
                        
                        checkSimpananPokok();
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

// Load data simpanan
function loadSimpanan() {
    const jenis = document.getElementById('filterJenis')?.value || 'all';
    const anggota = document.getElementById('filterAnggota')?.value || 'all';
    
    const tbody = document.getElementById('simpananTableBody');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

    let url = `<?= base_url('admin/getSimpananList') ?>?jenis=${jenis}`;
    if (anggota && anggota !== 'all') {
        url += `&id_anggota=${anggota}`;
    }

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(data => {
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
                // Status styling
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = row.status || 'Aktif';
                
                if (row.status === 'lunas') {
                    statusClass = 'bg-green-100 text-green-800';
                    statusText = 'LUNAS';
                } else if (row.status === 'aktif') {
                    statusClass = 'bg-blue-100 text-blue-800';
                    statusText = 'Aktif';
                } else if (row.status === 'pending') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    statusText = 'Pending';
                }
                
                const namaAnggota = row.nama_lengkap || '-';
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
                        Gagal memuat data
                    </td>
                </tr>
            `;
        });
}

// Submit form dengan double submission protection
function setupFormSubmit() {
    const form = document.getElementById('formSimpanan');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            console.log('Already submitting, please wait...');
            return;
        }
        
        const submitBtn = document.getElementById('simpanBtn');
        if (!submitBtn) return;

        const originalText = submitBtn.innerHTML;
        
        // Ambil data form
        const formData = new FormData(this);
        const data = {
            id_anggota: document.getElementById('anggotaSelect')?.value,
            jenis: document.getElementById('jenisSelect')?.value,
            jumlah: document.getElementById('jumlahInput')?.value
        };
        
        // Validasi
        if (!data.id_anggota || !data.jenis || !data.jumlah) {
            alert('Harap lengkapi semua field!');
            return;
        }

        if (parseInt(data.jumlah) <= 0) {
            alert('Jumlah simpanan harus lebih dari 0!');
            return;
        }

        // Set submitting state
        isSubmitting = true;
        
        // Loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        fetch('<?= base_url('admin/inputSimpanan') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(result => {
            console.log('Response:', result);
            if (result.success) {
                alert(result.message || 'Simpanan berhasil disimpan!');
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
            // Reset submitting state
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing savings page...');
    
    setupAnggotaSearch();
    setupFormSubmit();
    setupJumlahValidation();
    loadSimpanan();
    
    // Event listeners
    const filterJenis = document.getElementById('filterJenis');
    const filterAnggota = document.getElementById('filterAnggota');
    const jenisSelect = document.getElementById('jenisSelect');
    
    if (filterJenis) filterJenis.addEventListener('change', loadSimpanan);
    if (filterAnggota) filterAnggota.addEventListener('change', loadSimpanan);
    if (jenisSelect) {
        jenisSelect.addEventListener('change', function() {
            checkSimpananPokok();
        });
    }
    
    // Juga panggil checkSimpananPokok ketika anggota berubah
    const anggotaSelect = document.getElementById('anggotaSelect');
    if (anggotaSelect) {
        // Gunakan event listener untuk perubahan value
        anggotaSelect.addEventListener('change', function() {
            checkSimpananPokok();
        });
    }
    
    console.log('Savings page initialized');
});
</script>
</body>
</html>