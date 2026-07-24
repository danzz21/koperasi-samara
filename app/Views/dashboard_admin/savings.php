<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpanan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    /* Tab Navigation Styles */
    .savings-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .savings-tab-btn:hover {
        border-color: #10b981;
        color: #10b981;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .savings-tab-btn.active {
        border-color: #10b981;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Stat Card Styles */
    .savings-stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .savings-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    /* Jenis Selection Buttons in Modal */
    .jenis-select-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .jenis-select-btn div {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .jenis-select-btn.active div {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .jenis-select-btn i {
        display: block;
    }

    /* Table row hover effect */
    tbody tr {
        transition: background-color 0.2s ease;
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Jenis badge styling */
    .jenis-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .jenis-badge.pokok {
        background: #dcfce7;
        color: #15803d;
    }

    .jenis-badge.wajib {
        background: #dbeafe;
        color: #1e40af;
    }

    .jenis-badge.sukarela {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .btn-detail {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .btn-detail:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    
    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
    }

    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 8px;
    }
    </style>
</head>
<body>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Simpanan</h2>
        <p class="text-gray-600">Kelola simpanan anggota koperasi</p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-2 mb-6 flex-wrap">
        <button class="savings-tab-btn active" data-tab="pokok" onclick="switchTab('pokok')">
            <i class="fas fa-piggy-bank mr-2"></i>Pokok
        </button>
        <button class="savings-tab-btn" data-tab="wajib" onclick="switchTab('wajib')">
            <i class="fas fa-receipt mr-2"></i>Wajib
        </button>
        <button class="savings-tab-btn" data-tab="sukarela" onclick="switchTab('sukarela')">
            <i class="fas fa-heart mr-2"></i>Sukarela
        </button>
        <button class="savings-tab-btn" data-tab="all" onclick="switchTab('all')">
            <i class="fas fa-list mr-2"></i>Semua
        </button>
    </div>

    <!-- Stat Cards (Tetap 4 Card Selalu Tampil) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Card Simpanan Pokok -->
        <div class="savings-stat-card bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-xl shadow-md border-l-4 border-emerald-600" onclick="switchTab('pokok')">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-emerald-600 font-semibold">Simpanan Pokok</p>
                    <p class="text-2xl font-bold text-emerald-700">Rp <?= number_format($totalPokok ?? 0, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-piggy-bank text-3xl text-emerald-300"></i>
            </div>
            <p class="text-xs text-emerald-600">Total dari <?= $anggotaPokok ?? 0 ?> anggota</p>
            <div class="mt-2 pt-2 border-t border-emerald-200">
                <span class="text-xs font-semibold text-emerald-700">Lunas: <?= $anggotaLunas ?? 0 ?> anggota</span>
            </div>
        </div>

        <!-- Card Simpanan Wajib -->
        <div class="savings-stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md border-l-4 border-blue-600" onclick="switchTab('wajib')">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-blue-600 font-semibold">Simpanan Wajib</p>
                    <p class="text-2xl font-bold text-blue-700">Rp <?= number_format($totalWajib ?? 0, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-receipt text-3xl text-blue-300"></i>
            </div>
            <p class="text-xs text-blue-600">Akumulasi wajib</p>
            <div class="mt-2 pt-2 border-t border-blue-200">
                <span class="text-xs font-semibold text-blue-700">Rutin setiap bulan</span>
            </div>
        </div>

        <!-- Card Simpanan Sukarela -->
        <div class="savings-stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md border-l-4 border-purple-600" onclick="switchTab('sukarela')">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-purple-600 font-semibold">Simpanan Sukarela</p>
                    <p class="text-2xl font-bold text-purple-700">Rp <?= number_format($totalSukarela ?? 0, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-heart text-3xl text-purple-300"></i>
            </div>
            <p class="text-xs text-purple-600">Akumulasi sukarela</p>
            <div class="mt-2 pt-2 border-t border-purple-200">
                <span class="text-xs font-semibold text-purple-700">Opsional & fleksibel</span>
            </div>
        </div>

        <!-- Card Total -->
        <div class="savings-stat-card bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md border-l-4 border-gray-600" onclick="switchTab('all')">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total Semua Simpanan</p>
                    <p class="text-2xl font-bold text-gray-700">Rp <?= number_format(($totalPokok ?? 0) + ($totalWajib ?? 0) + ($totalSukarela ?? 0), 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-calculator text-3xl text-gray-300"></i>
            </div>
            <p class="text-xs text-gray-600">Akumulasi keseluruhan</p>
            <div class="mt-2 pt-2 border-t border-gray-200">
                <span class="text-xs font-semibold text-gray-700">3 jenis simpanan</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Transaksi Simpanan</h3>
                    <p class="text-sm text-gray-500 mt-1" id="tabTitle">Data simpanan pokok</p>
                </div>
                <button onclick="openModal('savingsModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 shadow-md">
                    <i class="fas fa-plus"></i>Input Simpanan
                </button>
            </div>
            
            <!-- Filter Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-filter mr-1"></i>Cari Anggota
                        </label>
                        <div class="relative">
                            <input 
                                id="filterSearchAnggota" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent" 
                                placeholder="Nama atau nomor KTP..."
                            >
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-list mr-1"></i>Pilih Anggota
                        </label>
                        <select id="filterAnggota" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="all">Semua Anggota</option>
                            <?php foreach($anggotaList ?? [] as $anggota): ?>
                                <option value="<?= $anggota['id_anggota'] ?>"><?= $anggota['nama_lengkap'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button onclick="loadSimpanan()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-sync"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i>Transaksi Terakhir
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nama Anggota
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-tag mr-2"></i>Jenis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-money-bill mr-2"></i>Total Simpanan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-check-circle mr-2"></i>Status / Catatan
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="simpananTableBody">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL INPUT SIMPANAN -->
    <div id="savingsModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Input Simpanan</h3>
                <button onclick="closeModal('savingsModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="mb-6 grid grid-cols-3 gap-2" id="jenisVisualGroup">
                <button type="button" class="jenis-select-btn group" data-jenis="pokok" onclick="selectJenis('pokok')">
                    <div class="p-3 rounded-lg border-2 border-gray-200 group-hover:border-emerald-500 transition-all cursor-pointer">
                        <i class="fas fa-piggy-bank text-2xl text-emerald-600 mb-2 block"></i>
                        <span class="text-xs font-semibold text-gray-700">Pokok</span>
                    </div>
                </button>
                <button type="button" class="jenis-select-btn group" data-jenis="wajib" onclick="selectJenis('wajib')">
                    <div class="p-3 rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all cursor-pointer">
                        <i class="fas fa-receipt text-2xl text-blue-600 mb-2 block"></i>
                        <span class="text-xs font-semibold text-gray-700">Wajib</span>
                    </div>
                </button>
                <button type="button" class="jenis-select-btn group" data-jenis="sukarela" onclick="selectJenis('sukarela')">
                    <div class="p-3 rounded-lg border-2 border-gray-200 group-hover:border-purple-500 transition-all cursor-pointer">
                        <i class="fas fa-heart text-2xl text-purple-600 mb-2 block"></i>
                        <span class="text-xs font-semibold text-gray-700">Sukarela</span>
                    </div>
                </button>
            </div>

            <form id="formSimpanan" class="space-y-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="jenisSelect" name="jenis" value="">

                <div id="tenorGroup" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-hourglass-half mr-1"></i>Tenor (bulan)
                    </label>
                    <select id="tenorSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="">Pilih tenor</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> Bulan</option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" id="tenorHidden" name="tenor" value="">
                    <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i>Pilih tenor simpanan pokok dari 1 sampai 12 bulan.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users mr-1"></i>Anggota
                    </label>
                    <div class="relative">
                        <input id="anggotaSearch" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Cari nama anggota..." autocomplete="off">
                        <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 max-h-60 overflow-y-auto hidden shadow-lg"></div>
                        <button type="button" id="semuaAnggotaBtn" class="absolute right-0 top-0 mt-2 mr-2 bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs border border-blue-300 hover:bg-blue-200 transition-colors hidden font-semibold">Semua Anggota</button>
                        <input type="hidden" id="anggotaSelect" name="id_anggota">
                    </div>
                    <div id="anggotaInfo" class="mt-2 text-xs text-gray-500 hidden flex items-center gap-1">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span id="anggotaInfoText"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill mr-1"></i>Jumlah (Rp)
                    </label>
                    <input id="jumlahInput" name="jumlah" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent" min="1" placeholder="0" required>
                </div>

                <div id="pokokInfo" class="hidden rounded-md p-3 text-sm">
                    <div id="pokokDetail"></div>
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('savingsModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors font-semibold">Batal</button>
                    <button type="submit" id="simpanBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL RIWAYAT TRANSAKSI -->
    <div id="historyModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4 border-b pb-3 border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-800" id="detailNamaAnggota">Detail Riwayat Simpanan</h3>
                    <p class="text-xs text-gray-500 mt-1" id="detailJenisSimpanan">Rincian seluruh transaksi simpanan</p>
                </div>
                <button onclick="closeModal('historyModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Ringkasan Singkat Modal -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4 flex justify-between items-center border border-gray-200">
                <div>
                    <span class="text-xs text-gray-500 font-semibold block uppercase">Total Terkumpul</span>
                    <span class="text-lg font-bold text-emerald-600" id="detailTotalNominal">Rp 0</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold block uppercase">Total Transaksi</span>
                    <span class="text-lg font-bold text-gray-800" id="detailTotalFrekuensi">0x</span>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi Modal -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Ke-</th>
                            <th class="px-4 py-3">Tanggal Transaksi</th>
                            <th class="px-4 py-3">Jumlah (Rp)</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-gray-200 bg-white">
                        <!-- Diisi via JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="closeModal('historyModal')" class="bg-gray-600 text-white px-5 py-2 rounded-md hover:bg-gray-700 transition-colors text-sm font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    <script>
    let currentTab = 'pokok';
    let isSubmitting = false;
    let rawSimpananData = []; // Menyimpan data mentah dari backend untuk modal detail

    function switchTab(tab) {
        currentTab = tab;
        
        document.querySelectorAll('.savings-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
        if (activeBtn) activeBtn.classList.add('active');

        const titles = {
            'pokok': 'Data simpanan pokok',
            'wajib': 'Data simpanan wajib',
            'sukarela': 'Data simpanan sukarela',
            'all': 'Semua data simpanan'
        };
        document.getElementById('tabTitle').textContent = titles[tab];

        addHiddenFilterJenis(tab);
        
        document.getElementById('filterAnggota').value = 'all';
        loadSimpanan();
    }

    function addHiddenFilterJenis(value = 'pokok') {
        let hidden = document.getElementById('filterJenis');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id = 'filterJenis';
            document.body.appendChild(hidden);
        }
        hidden.value = value;
    }

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
        if (id === 'savingsModal') resetForm();
    }

    function resetForm() {
        const form = document.getElementById('formSimpanan');
        if (form) form.reset();
        
        document.querySelectorAll('.jenis-select-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.querySelector('div').className = 'p-3 rounded-lg border-2 border-gray-200 group-hover:border-gray-500 transition-all cursor-pointer';
        });
        
        const tenorInput = document.getElementById('tenorSelect');
        const tenorHidden = document.getElementById('tenorHidden');
        if (tenorInput) {
            tenorInput.required = false;
            tenorInput.value = '';
            tenorInput.disabled = false;
        }
        if (tenorHidden) tenorHidden.value = '';
        
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
        
        const jenisSelect = document.getElementById('jenisSelect');
        if (jenisSelect) jenisSelect.value = '';
        
        resetPokokInfo();
        
        isSubmitting = false;
        const simpanBtn = document.getElementById('simpanBtn');
        if (simpanBtn) {
            simpanBtn.disabled = false;
            simpanBtn.innerHTML = 'Simpan';
        }
    }

    function selectJenis(jenis) {
        document.getElementById('jenisSelect').value = jenis;
        
        document.querySelectorAll('.jenis-select-btn').forEach(btn => {
            const div = btn.querySelector('div');
            if (btn.dataset.jenis === jenis) {
                btn.classList.add('active');
                if (jenis === 'pokok') {
                    div.style.borderColor = '#10b981';
                    div.style.backgroundColor = '#f0fdf4';
                } else if (jenis === 'wajib') {
                    div.style.borderColor = '#3b82f6';
                    div.style.backgroundColor = '#eff6ff';
                } else {
                    div.style.borderColor = '#a855f7';
                    div.style.backgroundColor = '#faf5ff';
                }
            } else {
                btn.classList.remove('active');
                div.className = 'p-3 rounded-lg border-2 border-gray-200 group-hover:border-gray-500 transition-all cursor-pointer';
                div.style.borderColor = '';
                div.style.backgroundColor = '';
            }
        });
        
        toggleTenorField();
        checkSimpananPokok();
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

    function formatRupiah(angka) {
        const number = parseInt(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function checkSimpananPokok() {
        const jenis = document.getElementById('jenisSelect').value;
        const idAnggota = document.getElementById('anggotaSelect').value;
        const jumlahInput = document.getElementById('jumlahInput');
        const simpanBtn = document.getElementById('simpanBtn');
        
        if (jenis === 'pokok' && idAnggota && idAnggota !== 'all') {
            fetch(`<?= base_url('admin/checkSimpananPokok/') ?>${idAnggota}`)
                .then(res => res.json())
                .then(data => {
                    const pokokInfo = document.getElementById('pokokInfo');
                    const pokokDetail = document.getElementById('pokokDetail');
                    
                    if (data.success) {
                        pokokInfo.classList.remove('hidden');
                        
                        if (data.isLunas) {
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
                            pokokInfo.className = 'bg-blue-50 border border-blue-200 rounded-md p-3';
                            const nextInstallment = data.count + 1;
                            const existingTenor = data.existingTenor ? parseInt(data.existingTenor) : null;
                            const tenorSelect = document.getElementById('tenorSelect');
                            const tenorHidden = document.getElementById('tenorHidden');

                            if (tenorSelect) {
                                tenorSelect.disabled = false;
                                tenorSelect.value = '';
                                if (existingTenor) {
                                    tenorSelect.value = existingTenor;
                                    tenorSelect.disabled = true;
                                }
                                if (tenorHidden) {
                                    tenorHidden.value = existingTenor ? String(existingTenor) : '';
                                }
                            }

                            const tenorText = existingTenor ? ` dari tenor ${existingTenor} bulan` : '';
                            const tenorHint = existingTenor ? '<br>• Tenor dikunci karena sudah dipilih sebelumnya.' : '';
                            pokokDetail.innerHTML = `
                                <div class="text-blue-700">
                                    <strong>Informasi Simpanan Pokok:</strong><br>
                                    • Total simpanan: ${formatRupiah(data.total)}<br>
                                    • Batas maksimal: ${formatRupiah(data.max_limit)}<br>
                                    • <strong>Sisa yang bisa diinput: ${formatRupiah(data.sisa)}</strong><br>
                                    • <strong>Simpanan berikutnya: ke-${nextInstallment}${tenorText}</strong>
                                    ${tenorHint}
                                </div>
                            `;
                            simpanBtn.disabled = false;
                            jumlahInput.disabled = false;
                            
                            if (data.sisa > 0) {
                                jumlahInput.max = data.sisa;
                            } else {
                                jumlahInput.disabled = true;
                                simpanBtn.disabled = true;
                            }
                        }
                    }
                })
                .catch(err => console.error('Error checking simpanan pokok:', err));
        } else if (jenis === 'pokok' && idAnggota === 'all') {
            const pokokInfo = document.getElementById('pokokInfo');
            const pokokDetail = document.getElementById('pokokDetail');
            pokokInfo.classList.remove('hidden');
            pokokInfo.className = 'bg-yellow-50 border border-yellow-200 rounded-md p-3';
            pokokDetail.innerHTML = `
                <div class="text-yellow-700">
                    <strong><i class="fas fa-info-circle mr-1"></i>Input untuk Semua Anggota:</strong><br>
                    • Simpanan akan diinput hanya untuk anggota yang belum lunas<br>
                    • Anggota yang sudah lunas dilewati secara otomatis
                </div>
            `;
            simpanBtn.disabled = false;
            jumlahInput.disabled = false;
            jumlahInput.removeAttribute('max');
        } else if ((jenis === 'wajib' || jenis === 'sukarela') && idAnggota && idAnggota !== 'all') {
            fetch(`<?= base_url('admin/checkSimpananPokok/') ?>${idAnggota}`)
                .then(res => res.json())
                .then(data => {
                    const pokokInfo = document.getElementById('pokokInfo');
                    const pokokDetail = document.getElementById('pokokDetail');
                    
                    if (data.success) {
                        pokokInfo.classList.remove('hidden');
                        
                        if (!data.isLunas) {
                            pokokInfo.className = 'bg-red-50 border border-red-200 rounded-md p-3';
                            pokokDetail.innerHTML = `
                                <div class="text-red-700">
                                    <strong><i class="fas fa-exclamation-triangle mr-1"></i>TIDAK DAPAT INPUT SIMPANAN ${jenis.toUpperCase()}!</strong><br>
                                    • Total simpanan pokok: ${formatRupiah(data.total)}<br>
                                    • <strong>Harus lunasi simpanan pokok terlebih dahulu!</strong>
                                </div>
                            `;
                            simpanBtn.disabled = true;
                            jumlahInput.disabled = true;
                        } else {
                            pokokInfo.className = 'bg-green-50 border border-green-200 rounded-md p-3';
                            pokokDetail.innerHTML = `
                                <div class="text-green-700">
                                    <strong><i class="fas fa-check-circle mr-1"></i>SIAP INPUT SIMPANAN ${jenis.toUpperCase()}</strong><br>
                                    • Simpanan pokok sudah lunas: ${formatRupiah(data.total)}
                                </div>
                            `;
                            simpanBtn.disabled = false;
                            jumlahInput.disabled = false;
                            jumlahInput.removeAttribute('max');
                        }
                    }
                })
                .catch(err => console.error('Error checking simpanan pokok:', err));
        } else {
            resetPokokInfo();
            simpanBtn.disabled = false;
            jumlahInput.disabled = false;
            jumlahInput.removeAttribute('max');
        }
    }

    function toggleTenorField() {
        const jenisSelect = document.getElementById('jenisSelect');
        const tenorGroup = document.getElementById('tenorGroup');
        const tenorInput = document.getElementById('tenorSelect');
        const tenorHidden = document.getElementById('tenorHidden');
        const semuaBtn = document.getElementById('semuaAnggotaBtn');
        const anggotaSelected = document.getElementById('anggotaSelect')?.value;

        if (!jenisSelect || !tenorGroup || !tenorInput) return;

        if (jenisSelect.value === 'pokok' && (anggotaSelected || anggotaSelected === 'all')) {
            tenorGroup.classList.remove('hidden');
            tenorInput.required = true;
            if (tenorHidden) tenorHidden.value = tenorInput.value || '';
            if (semuaBtn) semuaBtn.classList.remove('hidden');
        } else {
            tenorGroup.classList.add('hidden');
            tenorInput.required = false;
            tenorInput.value = '';
            tenorInput.disabled = false;
            if (tenorHidden) tenorHidden.value = '';
            if (semuaBtn) {
                if (jenisSelect.value === 'wajib') {
                    semuaBtn.classList.remove('hidden');
                } else {
                    semuaBtn.classList.add('hidden');
                }
            }
        }
    }

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
                    showNotification('warning', 'Peringatan', `Jumlah tidak boleh melebihi sisa simpanan pokok: ${formatRupiah(maxJumlah)}`);
                }
            }
        });
    }

    function setupAnggotaSearch() {
        const input = document.getElementById('anggotaSearch');
        const results = document.getElementById('anggotaResults');
        const hidden = document.getElementById('anggotaSelect');
        const semuaBtn = document.getElementById('semuaAnggotaBtn');
        const anggotaInfo = document.getElementById('anggotaInfo');

        if (!input || !results || !hidden || !semuaBtn) return;

        semuaBtn.addEventListener('click', function() {
            const jenis = document.getElementById('jenisSelect').value;
            
            if (!jenis) {
                showNotification('warning', 'Peringatan', 'Pilih jenis simpanan terlebih dahulu!');
                return;
            }
            
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
                    let infoText = 'Simpanan akan diinput untuk semua anggota';
                    if (jenis === 'pokok') {
                        infoText += ' yang belum lunas';
                    } else if (jenis === 'wajib' || jenis === 'sukarela') {
                        infoText += ' yang sudah lunas simpanan pokok';
                    }
                    anggotaInfo.innerHTML = infoText;
                    anggotaInfo.classList.remove('hidden');
                }
            }
            results.classList.add('hidden');
            checkSimpananPokok();
        });

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
                .then(res => res.json())
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
                            
                            if (anggotaInfo) {
                                anggotaInfo.innerHTML = `Anggota terpilih: ${a.nama_lengkap || a.nama}`;
                                anggotaInfo.classList.remove('hidden');
                            }
                            
                            toggleTenorField();
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

        document.addEventListener('click', function(e) {
            if (input && !input.contains(e.target) && results && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    }

    function showNotification(type, title, message, duration = 5000) {
        const existingNotification = document.getElementById('customNotification');
        if (existingNotification) existingNotification.remove();

        const colors = {
            success: { bg: '#10b981', icon: '✅', border: '#059669' },
            error: { bg: '#ef4444', icon: '❌', border: '#dc2626' },
            warning: { bg: '#f59e0b', icon: '⚠️', border: '#d97706' },
            info: { bg: '#3b82f6', icon: 'ℹ️', border: '#2563eb' }
        };

        const color = colors[type] || colors.info;

        const notification = document.createElement('div');
        notification.id = 'customNotification';
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; background: ${color.bg}; color: white;
            padding: 0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 10000; min-width: 400px; max-width: 500px; border-left: 4px solid ${color.border};
            animation: slideInRight 0.3s ease-out; font-family: 'Inter', sans-serif;
        `;

        notification.innerHTML = `
            <div style="display: flex; align-items: flex-start; padding: 20px; position: relative;">
                <div style="font-size: 24px; margin-right: 15px; margin-top: 2px;">${color.icon}</div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px; color: white;">${title}</div>
                    <div style="font-size: 14px; line-height: 1.5; color: rgba(255,255,255,0.9);">${message}</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; margin-left: 10px;">
                    <i class="fas fa-times"></i>
                </button>
                <div style="position: absolute; bottom: 0; left: 0; height: 3px; background: rgba(255,255,255,0.5); width: 100%; animation: progressBar ${duration}ms linear; border-radius: 0 0 12px 12px;"></div>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => { if (notification.parentElement) notification.remove(); }, 300);
            }
        }, duration);
    }

    // Load Data Simpanan dengan Pengelompokan
    function loadSimpanan() {
        const jenisFilter = document.getElementById('filterJenis')?.value || currentTab || 'all';
        const anggotaFilter = document.getElementById('filterAnggota')?.value || 'all';
        
        const tbody = document.getElementById('simpananTableBody');
        if (!tbody) return;

        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';

        let url = `<?= base_url('admin/getSimpananList') ?>?jenis=${jenisFilter}`;
        if (anggotaFilter && anggotaFilter !== 'all') {
            url += `&id_anggota=${anggotaFilter}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                rawSimpananData = data || []; // Simpan data mentah ke variabel global
                
                if (!data || data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p class="text-sm font-medium">Tidak ada data simpanan</p>
                                    <p class="text-xs">Mulai input simpanan dengan menekan tombol "Input Simpanan"</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Grouping berdasarkan Nama/ID dan Jenis Simpanan
                const groupedData = {};

                data.forEach(row => {
                    const idAnggota = row.id_anggota || row.nama_lengkap;
                    const jenis = row.jenis || 'pokok';
                    const key = `${idAnggota}_${jenis}`;

                    if (!groupedData[key]) {
                        let idField = row.id_sp || row.id_sw || row.id_ss || row.id;

                        groupedData[key] = {
                            id_anggota: idAnggota,
                            nama_lengkap: row.nama_lengkap || '-',
                            jenis: jenis,
                            total_jumlah: 0,
                            count: 0,
                            tanggal_terakhir: row.tanggal || '-',
                            status: row.status || 'aktif',
                            id_terakhir: idField
                        };
                    }

                    groupedData[key].total_jumlah += parseFloat(row.jumlah || 0);
                    groupedData[key].count += 1;

                    if (row.tanggal && row.tanggal > groupedData[key].tanggal_terakhir) {
                        groupedData[key].tanggal_terakhir = row.tanggal;
                        groupedData[key].id_terakhir = row.id_sp || row.id_sw || row.id_ss || row.id;
                    }
                });

                tbody.innerHTML = '';
                
                Object.values(groupedData).forEach(row => {
                    let statusClass = 'bg-gray-100 text-gray-800';
                    let statusIcon = 'fas fa-circle';
                    let statusText = row.status;

                    if (row.status === 'lunas') {
                        statusClass = 'bg-green-100 text-green-800';
                        statusIcon = 'fas fa-check-circle';
                        statusText = 'LUNAS';
                    } else if (row.status === 'aktif') {
                        statusClass = 'bg-blue-100 text-blue-800';
                        statusIcon = 'fas fa-play-circle';
                        statusText = 'Aktif';
                    }

                    let jenisBadgeClass = 'jenis-badge pokok';
                    if (row.jenis === 'wajib') jenisBadgeClass = 'jenis-badge wajib';
                    else if (row.jenis === 'sukarela') jenisBadgeClass = 'jenis-badge sukarela';

                    tbody.innerHTML += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">${row.tanggal_terakhir}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">${row.nama_lengkap}</div>
                                <div class="text-xs text-emerald-600 font-medium">
                                    <i class="fas fa-history mr-1"></i>Simpanan ke-${row.count} (${row.count}x Transaksi)
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="${jenisBadgeClass}">
                                    <i class="fas fa-tag"></i>
                                    ${row.jenis.charAt(0).toUpperCase() + row.jenis.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">${formatRupiah(row.total_jumlah)}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">
                                    <i class="${statusIcon}"></i>
                                    ${statusText}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="action-buttons">
                                    <button class="btn-detail" onclick="viewHistory('${row.id_anggota}', '${row.jenis}', '${row.nama_lengkap}')" title="Lihat Riwayat Pembayaran">
                                        <i class="fas fa-eye"></i>Detail
                                    </button>
                                    <button class="btn-delete" onclick="deleteSimpanan('${row.jenis}', '${row.id_terakhir}')" title="Hapus Transaksi Terakhir">
                                        <i class="fas fa-trash"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error('Error loading simpanan:', err);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-red-500">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat data
                        </td>
                    </tr>
                `;
            });
    }

    // Fungsi untuk menampilkan Riwayat Pembayaran pada Modal Detail
    function viewHistory(idAnggota, jenis, namaLengkap) {
        // Filter data transaksi milik anggota spesifik ini
        const userTransactions = rawSimpananData.filter(row => {
            const matchUser = (row.id_anggota == idAnggota || row.nama_lengkap === namaLengkap);
            const matchJenis = row.jenis === jenis;
            return matchUser && matchJenis;
        });

        // Urutkan transaksi dari yang paling lama ke terbaru
        userTransactions.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

        // Set UI Header Modal
        document.getElementById('detailNamaAnggota').textContent = namaLengkap;
        document.getElementById('detailJenisSimpanan').textContent = `Riwayat Simpanan ${jenis.toUpperCase()}`;

        let totalAmount = 0;
        const historyTbody = document.getElementById('historyTableBody');
        historyTbody.innerHTML = '';

        if (userTransactions.length === 0) {
            historyTbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada riwayat transaksi ditemukan.</td></tr>`;
        } else {
            userTransactions.forEach((tx, index) => {
                const amount = parseFloat(tx.jumlah || 0);
                totalAmount += amount;
                const idTx = tx.id_sp || tx.id_sw || tx.id_ss || tx.id;

                historyTbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-700">Ke-${index + 1}</td>
                        <td class="px-4 py-3 text-gray-900">${tx.tanggal || '-'}</td>
                        <td class="px-4 py-3 font-bold text-emerald-600">${formatRupiah(amount)}</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="deleteSimpananDetail('${jenis}', '${idTx}')" class="text-red-600 hover:text-red-800 text-xs font-semibold" title="Hapus transaksi ini">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('detailTotalNominal').textContent = formatRupiah(totalAmount);
        document.getElementById('detailTotalFrekuensi').textContent = `${userTransactions.length}x Transaksi`;

        openModal('historyModal');
    }

    function deleteSimpananDetail(jenis, id) {
        closeModal('historyModal');
        deleteSimpanan(jenis, id);
    }

    function deleteSimpanan(jenis, id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Data simpanan ${jenis} ini akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data simpanan',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch('<?= base_url('admin/deleteSimpanan') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                        'jenis': jenis,
                        'id': id
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.message,
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        });
                        loadSimpanan();
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: result.message,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        });
    }

    function setupFormSubmit() {
        const form = document.getElementById('formSimpanan');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (isSubmitting) {
                showNotification('warning', 'Peringatan', 'Sedang memproses data sebelumnya...');
                return;
            }
            
            const submitBtn = document.getElementById('simpanBtn');
            if (!submitBtn) return;

            const originalText = submitBtn.innerHTML;
            const formData = new FormData(this);
            
            const data = {
                id_anggota: document.getElementById('anggotaSelect')?.value,
                jenis: document.getElementById('jenisSelect')?.value,
                jumlah: document.getElementById('jumlahInput')?.value,
                tenor: document.getElementById('tenorSelect')?.value
            };
            
            if (!data.id_anggota || !data.jenis || !data.jumlah) {
                showNotification('error', 'Gagal!', 'Harap lengkapi semua field!');
                return;
            }

            if (parseInt(data.jumlah) <= 0) {
                showNotification('error', 'Gagal!', 'Jumlah simpanan harus lebih dari 0!');
                return;
            }

            if (data.jenis === 'pokok' && (!data.tenor || parseInt(data.tenor) <= 0)) {
                showNotification('error', 'Gagal!', 'Tenor simpanan pokok wajib diisi dengan angka bulan yang valid!');
                return;
            }

            isSubmitting = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            fetch('<?= base_url('admin/inputSimpanan') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    showNotification(result.type || 'success', 
                        result.type === 'success' ? 'Berhasil!' : 'Peringatan', 
                        result.message, 8000);
                    
                    setTimeout(() => {
                        closeModal('savingsModal');
                        loadSimpanan();
                    }, 1000);
                } else {
                    showNotification(result.type || 'error', 'Gagal!', result.message, 6000);
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                showNotification('error', 'Error!', 'Terjadi kesalahan: ' + err.message);
            })
            .finally(() => {
                isSubmitting = false;
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        try {
            addHiddenFilterJenis('pokok');
            switchTab('pokok');
            setupAnggotaSearch();
            setupFormSubmit();
            setupJumlahValidation();
            loadSimpanan();
            
            const filterSearchAnggota = document.getElementById('filterSearchAnggota');
            const filterAnggota = document.getElementById('filterAnggota');
            const filterJenis = document.getElementById('filterJenis');
            const tenorSelect = document.getElementById('tenorSelect');
            const anggotaSelect = document.getElementById('anggotaSelect');
            
            if (filterSearchAnggota) {
                filterSearchAnggota.addEventListener('input', function() {
                    const q = this.value.trim();
                    if (q.length < 2 && q.length > 0) return;
                    
                    if (!q) {
                        filterAnggota.value = 'all';
                    } else {
                        const options = filterAnggota.querySelectorAll('option');
                        let found = false;
                        options.forEach(option => {
                            if (option.textContent.toLowerCase().includes(q.toLowerCase())) {
                                filterAnggota.value = option.value;
                                found = true;
                            }
                        });
                        if (!found) filterAnggota.value = 'all';
                    }
                    loadSimpanan();
                });
            }
            
            if (filterJenis) filterJenis.addEventListener('change', loadSimpanan);
            if (filterAnggota) filterAnggota.addEventListener('change', function() {
                if (filterSearchAnggota) {
                    filterSearchAnggota.value = this.options[this.selectedIndex].text;
                }
                loadSimpanan();
            });
            
            if (tenorSelect) {
                tenorSelect.addEventListener('change', function() {
                    const tenorHidden = document.getElementById('tenorHidden');
                    if (tenorHidden) tenorHidden.value = this.value;
                });
            }

            if (anggotaSelect) {
                anggotaSelect.addEventListener('change', checkSimpananPokok);
            }
        } catch (err) {
            console.error('Error initializing savings page:', err);
        }
    });

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>