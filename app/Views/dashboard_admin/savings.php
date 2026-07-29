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
            <i class="fas fa-money-bill-wave mr-2"></i>Pokok
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

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Card Simpanan Pokok -->
        <div class="savings-stat-card bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-xl shadow-md border-l-4 border-emerald-600" onclick="switchTab('pokok')">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm text-emerald-600 font-semibold">Simpanan Pokok</p>
                    <!-- TAMBAHKAN ID: statTotalPokok -->
                    <p id="statTotalPokok" class="text-2xl font-bold text-emerald-700">Rp <?= number_format($totalPokok ?? 0, 0, ',', '.') ?></p>
                </div>
                <i class="fas fa-money-bill-wave text-3xl text-green-500 opacity-80"></i>
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
                    <!-- TAMBAHKAN ID: statTotalWajib -->
                    <p id="statTotalWajib" class="text-2xl font-bold text-blue-700">Rp <?= number_format($totalWajib ?? 0, 0, ',', '.') ?></p>
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
                    <!-- TAMBAHKAN ID: statTotalSukarela -->
                    <p id="statTotalSukarela" class="text-2xl font-bold text-purple-700">Rp <?= number_format($totalSukarela ?? 0, 0, ',', '.') ?></p>
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
                    <!-- TAMBAHKAN ID: statTotalSemua -->
                    <p id="statTotalSemua" class="text-2xl font-bold text-gray-700">Rp <?= number_format(($totalPokok ?? 0) + ($totalWajib ?? 0) + ($totalSukarela ?? 0), 0, ',', '.') ?></p>
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
                            <input id="filterSearchAnggota" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Nama atau nomor KTP...">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-list mr-1"></i>Pilih Anggota
                        </label>
                        <select id="filterAnggota" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="all">Semua Anggota</option>
                            <?php foreach ($anggotaList ?? [] as $anggota): ?>
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
                        <i class="fas fa-money-bill-wave text-2xl text-emerald-600 mb-2 block"></i>
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

            <!-- SESUDAH: -->
            <form id="formSimpanan" class="space-y-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="jenisSelect" name="jenis" value="">

                <!-- Field Tenor Simpanan Pokok -->
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

                <!-- Field Pilih / Cari Anggota -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users mr-1"></i>Anggota *
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

                <!-- CARD INFO DINAMIS KHUSUS SIMPANAN WAJIB -->
                <div id="infoWajibCard" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs space-y-1.5 transition-all">
                    <div class="flex justify-between items-center text-blue-900 border-b border-blue-200 pb-1.5">
                        <span class="font-semibold"><i class="fas fa-info-circle mr-1"></i>Posisi Simpanan Wajib:</span>
                        <span id="textWajibKe" class="font-bold text-sm bg-blue-200 px-2 py-0.5 rounded text-blue-900">Ke-1</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Total Terkumpul Saat Ini:</span>
                        <span id="textTotalWajibLama" class="font-bold text-gray-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Standar Wajib Bulanan:</span>
                        <span class="font-semibold text-gray-800">Rp 50.000 / bulan</span>
                    </div>
                </div>

                <!-- Field Input Jumlah Rp -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill mr-1"></i>Jumlah Simpanan (Rp) *
                    </label>
                    <input id="jumlahInput" name="jumlah" type="number" oninput="hitungSimulasiWajib()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base font-bold text-gray-800" min="1" placeholder="0" required>

                    <!-- Pilihan Cepat Nominal Wajib -->
                    <div id="quickSelectWajib" class="hidden grid-cols-4 gap-2 mt-2">
                        <button type="button" onclick="setQuickWajib(50000)" class="py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs font-semibold hover:bg-blue-100">1 Bln (50rb)</button>
                        <button type="button" onclick="setQuickWajib(100000)" class="py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs font-semibold hover:bg-blue-100">2 Bln (100rb)</button>
                        <button type="button" onclick="setQuickWajib(150000)" class="py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs font-semibold hover:bg-blue-100">3 Bln (150rb)</button>
                        <button type="button" onclick="setQuickWajib(300000)" class="py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs font-semibold hover:bg-blue-100">6 Bln (300rb)</button>
                    </div>
                </div>

                <!-- PREVIEW HASIL SETELAH INPUT -->
                <div id="previewWajibResult" class="hidden bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-xs space-y-1">
                    <div class="flex justify-between text-gray-700">
                        <span>Setara Tambahan:</span>
                        <span id="textSetaraBulan" class="font-bold text-emerald-800">0 Bulan</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Total Baru Setelah Simpan:</span>
                        <span id="textTotalWajibBaru" class="font-bold text-emerald-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Menjadi Simpanan Wajib Ke:</span>
                        <span id="textWajibKeBaru" class="font-bold text-emerald-900">Ke-1</span>
                    </div>
                </div>

                <div id="pokokInfo" class="hidden rounded-md p-3 text-sm">
                    <div id="pokokDetail"></div>
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('savingsModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors font-semibold">Batal</button>
                    <button type="button" onclick="submitSimpananForm()" id="simpanBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL RIWAYAT TRANSAKSI -->
    <div id="historyModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 overflow-y-auto max-h-[90vh] border border-gray-100">
            <div class="flex justify-between items-start mb-5 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800" id="detailNamaAnggota">Detail Riwayat Simpanan</h3>
                        <p class="text-xs text-gray-500 font-medium mt-0.5" id="detailJenisSimpanan">Rincian seluruh transaksi simpanan</p>
                    </div>
                </div>
                <button onclick="closeModal('historyModal')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3.5">
                    <span class="text-[11px] text-emerald-700 font-bold uppercase tracking-wider block mb-1">Total Terkumpul</span>
                    <span class="text-lg font-extrabold text-emerald-800 block" id="detailTotalNominal">Rp 0</span>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5">
                    <span class="text-[11px] text-blue-700 font-bold uppercase tracking-wider block mb-1" id="detailLabelKeBerapa">Simpanan Ke-</span>
                    <span class="text-lg font-extrabold text-blue-800 block" id="detailStatusKeBerapa">Ke-1</span>
                </div>
                <div class="bg-purple-50 border border-purple-100 rounded-xl p-3.5">
                    <span class="text-[11px] text-purple-700 font-bold uppercase tracking-wider block mb-1">Total Transaksi</span>
                    <span class="text-lg font-extrabold text-purple-800 block" id="detailTotalFrekuensi">0x</span>
                </div>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-xl shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-[11px] font-bold tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3">Urutan</th>
                            <th class="px-5 py-3">Tanggal Transaksi</th>
                            <th class="px-5 py-3 text-right">Nominal (Rp)</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-gray-100 bg-white">
                    </tbody>
                </table>
            </div>

            <div class="pt-4 mt-5 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-400"><i class="fas fa-shield-alt mr-1"></i>Data tercatat secara otomatis</span>
                <button type="button" onclick="closeModal('historyModal')" class="bg-gray-800 text-white px-5 py-2 rounded-xl hover:bg-gray-900 transition-colors text-sm font-semibold shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentTab = 'pokok';
        let isSubmitting = false;
        let rawSimpananData = [];
        let currentWajibAnggotaTotal = 0;
        const STANDAR_WAJIB = 50000;

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
                const div = btn.querySelector('div');
                if (div) {
                    div.className = 'p-3 rounded-lg border-2 border-gray-200 group-hover:border-gray-500 transition-all cursor-pointer';
                    div.style.borderColor = '';
                    div.style.backgroundColor = '';
                }
            });

            const tenorInput = document.getElementById('tenorSelect');
            const tenorHidden = document.getElementById('tenorHidden');
            if (tenorInput) {
                tenorInput.required = false;
                tenorInput.value = '';
                tenorInput.disabled = false;
                tenorInput.removeAttribute('name'); // Mencegah nama 'tenor' bentrok dari select kosong
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

            currentWajibAnggotaTotal = 0;
            resetPokokInfo();
            checkAndSetupWajibUI();

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
                    if (div) {
                        div.className = 'p-3 rounded-lg border-2 border-gray-200 group-hover:border-gray-500 transition-all cursor-pointer';
                        div.style.borderColor = '';
                        div.style.backgroundColor = '';
                    }
                }
            });


            runDynamicCalculations();
        }

        function runDynamicCalculations() {
            toggleTenorField();
            checkSimpananPokok();
            checkAndSetupWajibUI();
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
            const tenorGroup = document.getElementById('tenorGroup');
            const tenorSelect = document.getElementById('tenorSelect');
            const tenorHidden = document.getElementById('tenorHidden');

            if (jenis === 'pokok' && idAnggota && idAnggota !== 'all') {
                fetch(`<?= base_url('admin/checkSimpananPokok/') ?>${idAnggota}`)
                    .then(res => res.json())
                    .then(data => {
                        const pokokInfo = document.getElementById('pokokInfo');
                        const pokokDetail = document.getElementById('pokokDetail');

                        if (data.success) {
                            pokokInfo.classList.remove('hidden');

                            if (data.isLunas) {
                                pokokInfo.className = 'bg-red-50 border border-red-200 rounded-md p-3 text-xs';
                                pokokDetail.innerHTML = `
                            <div class="text-red-700">
                                <strong><i class="fas fa-exclamation-triangle mr-1"></i>SIMPANAN POKOK SUDAH LUNAS!</strong><br>
                                • Total Tersimpan: <strong>${formatRupiah(data.total)}</strong><br>
                                • Batas Maksimal: ${formatRupiah(data.max_limit)}<br>
                                <span class="text-red-600 font-semibold mt-1 block">Tidak dapat menginput simpanan pokok lagi.</span>
                            </div>
                        `;
                                simpanBtn.disabled = true;
                                jumlahInput.disabled = true;
                                if (tenorGroup) tenorGroup.classList.add('hidden');
                            } else {
                                pokokInfo.className = 'bg-blue-50 border border-blue-200 rounded-md p-3 text-xs';

                                const existingCount = parseInt(data.count) || 0;
                                const existingTenorVal = parseInt(data.existingTenor) || 0;

                                // Angsuran Ke-1 jika belum ada transaksi (total tersimpan = 0)
                                const isFirstInstallment = (existingCount === 0 || parseFloat(data.total) === 0);
                                const currentInstallment = isFirstInstallment ? 1 : (existingCount + 1);

                                const infoTenorText = tenorGroup ? tenorGroup.querySelector('p') : null;

                                let tenorText = '';

                                if (isFirstInstallment) {
                                    // ANGSURAN KE-1: Bebas Pilih Tenor, Keterangan Terkunci MATI
                                    if (tenorGroup) tenorGroup.classList.remove('hidden');
                                    if (tenorSelect) {
                                        tenorSelect.disabled = false;
                                        tenorSelect.required = true;
                                        tenorSelect.setAttribute('name', 'tenor');
                                    }
                                    if (tenorHidden) {
                                        tenorHidden.removeAttribute('name');
                                        tenorHidden.value = '';
                                    }
                                    if (infoTenorText) {
                                        infoTenorText.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Pilih tenor simpanan pokok dari 1 sampai 12 bulan.';
                                        infoTenorText.className = 'text-xs text-gray-500 mt-2';
                                    }
                                    // Teks kunci di card biru disosongkan
                                    tenorText = '';
                                } else {
                                    // ANGSURAN KE-2 DST: Tenor Dikunci Sesuai Transaksi Awal
                                    if (tenorGroup) tenorGroup.classList.remove('hidden');
                                    if (tenorSelect) {
                                        tenorSelect.value = existingTenorVal;
                                        tenorSelect.disabled = true; // Dikunci
                                        tenorSelect.required = false;
                                        tenorSelect.removeAttribute('name');
                                    }
                                    if (tenorHidden) {
                                        tenorHidden.setAttribute('name', 'tenor');
                                        tenorHidden.value = existingTenorVal;
                                    }
                                    if (infoTenorText) {
                                        infoTenorText.innerHTML = `<i class="fas fa-lock mr-1 text-amber-600"></i>Tenor otomatis terkunci dari transaksi awal (<strong>${existingTenorVal} Bulan</strong>).`;
                                        infoTenorText.className = 'text-xs text-amber-700 font-semibold mt-2';
                                    }
                                    // Teks kunci baru tampil jika benar-benar angsuran ke-2+
                                    tenorText = existingTenorVal > 0 ? ` (Tenor Terkunci: ${existingTenorVal} Bulan)` : '';
                                }

                                pokokDetail.innerHTML = `
                            <div class="text-blue-900 space-y-1">
                                <div class="flex justify-between border-b border-blue-200 pb-1 font-bold">
                                    <span><i class="fas fa-piggy-bank mr-1"></i>Posisi Simpanan Pokok:</span>
                                    <span class="bg-blue-200 px-2 py-0.5 rounded text-blue-900">Angsuran Ke-${currentInstallment}${tenorText}</span>
                                </div>
                                <div class="flex justify-between pt-1 text-gray-700">
                                    <span>Total Tersimpan Saat Ini:</span>
                                    <span class="font-bold text-gray-900">${formatRupiah(data.total)}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Sisa Yang Harus Dibayar:</span>
                                    <span class="font-bold text-blue-700">${formatRupiah(data.sisa)}</span>
                                </div>
                            </div>
                        `;

                                simpanBtn.disabled = false;
                                jumlahInput.disabled = false;
                                if (data.sisa > 0) {
                                    jumlahInput.max = data.sisa;
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Error checking simpanan pokok:', err));
            } else if ((jenis === 'wajib' || jenis === 'sukarela') && idAnggota && idAnggota !== 'all') {
                fetch(`<?= base_url('admin/checkSimpananPokok/') ?>${idAnggota}`)
                    .then(res => res.json())
                    .then(data => {
                        const pokokInfo = document.getElementById('pokokInfo');
                        const pokokDetail = document.getElementById('pokokDetail');

                        if (data.success) {
                            pokokInfo.classList.remove('hidden');

                            if (!data.isLunas) {
                                pokokInfo.className = 'bg-red-50 border border-red-200 rounded-md p-3 text-xs';
                                pokokDetail.innerHTML = `
                            <div class="text-red-700">
                                <strong><i class="fas fa-exclamation-triangle mr-1"></i>BELUM BISA INPUT SIMPANAN ${jenis.toUpperCase()}!</strong><br>
                                • Total Simpanan Pokok Saat Ini: <strong>${formatRupiah(data.total)}</strong><br>
                                • <strong>Anggota harus melunasi Simpanan Pokok (${formatRupiah(data.max_limit)}) terlebih dahulu.</strong>
                            </div>
                        `;
                                simpanBtn.disabled = true;
                                jumlahInput.disabled = true;
                            } else {
                                pokokInfo.className = 'bg-green-50 border border-green-200 rounded-md p-3 text-xs';
                                pokokDetail.innerHTML = `
                            <div class="text-green-800 flex justify-between items-center">
                                <span><i class="fas fa-check-circle mr-1 text-green-600"></i>Syarat Lunas Simpanan Pokok Terpenuhi</span>
                                <span class="font-bold">${formatRupiah(data.total)}</span>
                            </div>
                        `;
                                simpanBtn.disabled = false;
                                jumlahInput.disabled = false;
                                jumlahInput.removeAttribute('max');
                            }
                        }
                    });
            } else {
                resetPokokInfo();
                simpanBtn.disabled = false;
                jumlahInput.disabled = false;
                jumlahInput.removeAttribute('max');
            }
        }

        function checkAndSetupWajibUI() {
            const jenis = document.getElementById('jenisSelect')?.value;
            const idAnggota = document.getElementById('anggotaSelect')?.value;
            const infoCard = document.getElementById('infoWajibCard');
            const quickSelect = document.getElementById('quickSelectWajib');
            const previewResult = document.getElementById('previewWajibResult');

            if (jenis === 'wajib') {
                if (quickSelect) {
                    quickSelect.classList.remove('hidden');
                    quickSelect.classList.add('grid');
                }

                if (idAnggota && idAnggota !== 'all') {
                    // Ambil data simpanan wajib spesifik anggota
                    fetch(`<?= base_url('admin/getSimpananList') ?>?jenis=wajib&id_anggota=${idAnggota}`)
                        .then(res => res.json())
                        .then(data => {
                            // Penanganan jika respon dibungkus array/object
                            const wajibList = Array.isArray(data) ? data : (data.data || []);

                            // Hitung akumulasi nominal wajib yang sudah masuk
                            currentWajibAnggotaTotal = wajibList.reduce((acc, curr) => {
                                // Cek berbagai kemungkinan nama kolom nominal dari database
                                const rawVal = curr.jumlah ?? curr.nominal ?? curr.total_jumlah ?? curr.total ?? 0;
                                const val = parseFloat(rawVal) || 0;
                                return acc + val;
                            }, 0);

                            // Hitung jumlah bulan yang ter-cover berdasarkan standar Rp 50.000 / bulan
                            const bulanTercover = Math.floor(currentWajibAnggotaTotal / STANDAR_WAJIB);
                            const wajibKeBerikutnya = bulanTercover + 1;

                            const textWajibKe = document.getElementById('textWajibKe');
                            const textTotalWajibLama = document.getElementById('textTotalWajibLama');

                            if (textWajibKe) {
                                textWajibKe.innerHTML = `Bulan Ke-<b>${wajibKeBerikutnya}</b> <span class="text-xs font-normal">(${wajibList.length}x Input)</span>`;
                            }
                            if (textTotalWajibLama) {
                                textTotalWajibLama.innerText = formatRupiah(currentWajibAnggotaTotal);
                            }

                            if (infoCard) infoCard.classList.remove('hidden');
                            hitungSimulasiWajib();
                        })
                        .catch(err => {
                            console.error('Error fetching data wajib:', err);
                            if (infoCard) infoCard.classList.add('hidden');
                        });
                    return;
                }
            }

            if (infoCard) infoCard.classList.add('hidden');
            if (quickSelect) {
                quickSelect.classList.add('hidden');
                quickSelect.classList.remove('grid');
            }
            if (previewResult) previewResult.classList.add('hidden');
        }

        function setQuickWajib(nominal) {
            document.getElementById('jumlahInput').value = nominal;
            hitungSimulasiWajib();
        }

        function hitungSimulasiWajib() {
            const jenis = document.getElementById('jenisSelect').value;
            const inputVal = parseFloat(document.getElementById('jumlahInput').value) || 0;
            const previewResult = document.getElementById('previewWajibResult');

            if (jenis !== 'wajib' || inputVal <= 0) {
                if (previewResult) previewResult.classList.add('hidden');
                return;
            }

            const setaraBulan = (inputVal / STANDAR_WAJIB).toFixed(1);
            const totalBaru = currentWajibAnggotaTotal + inputVal;
            const wajibKeBaru = Math.floor(totalBaru / STANDAR_WAJIB);

            document.getElementById('textSetaraBulan').innerText = `${setaraBulan} Bulan Simpanan`;
            document.getElementById('textTotalWajibBaru').innerText = formatRupiah(totalBaru);
            document.getElementById('textWajibKeBaru').innerText = `Ke-${wajibKeBaru > 0 ? wajibKeBaru : 1}`;

            if (previewResult) previewResult.classList.remove('hidden');
        }

        function toggleTenorField() {
            const jenisSelect = document.getElementById('jenisSelect');
            const tenorGroup = document.getElementById('tenorGroup');
            const tenorInput = document.getElementById('tenorSelect');
            const semuaBtn = document.getElementById('semuaAnggotaBtn');
            const idAnggota = document.getElementById('anggotaSelect')?.value;

            if (!jenisSelect || !tenorGroup || !tenorInput) return;

            if (jenisSelect.value === 'pokok') {
                // Hanya buka grup tenor secara manual jika Belum Memilih Anggota
                if (!idAnggota || idAnggota === 'all') {
                    tenorGroup.classList.remove('hidden');
                    tenorInput.required = true;
                }
                if (semuaBtn) semuaBtn.classList.remove('hidden');
            } else {
                tenorGroup.classList.add('hidden');
                tenorInput.required = false;
                tenorInput.value = '';
                tenorInput.disabled = false;
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
                runDynamicCalculations();
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
                    runDynamicCalculations();
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

                                // KUNCI UTAMA: Memanggil kalkulasi otomatis setelah anggota dipilih
                                runDynamicCalculations();
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

        function showNotification(type, title, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type, // 'success', 'error', 'warning', atau 'info'
                title: message || title
            });
        }

        function loadSimpanan() {
            const jenisFilter = document.getElementById('filterJenis')?.value || currentTab || 'all';
            const anggotaFilter = document.getElementById('filterAnggota')?.value || 'all';

            const tbody = document.getElementById('simpananTableBody');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';

            // TAMBAHKAN PANGGILAN INI AGAR CARD STAT DI ATAS SELALU REFRESH OTOMATIS
            updateStatCardsFromData();

            let url = `<?= base_url('admin/getSimpananList') ?>?jenis=${jenisFilter}`;
            if (anggotaFilter && anggotaFilter !== 'all') {
                url += `&id_anggota=${anggotaFilter}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    rawSimpananData = data || [];

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

                        groupedData[key].total_jumlah += parseFloat(row.jumlah || row.nominal || 0);
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

                        let subInfoText = `<i class="fas fa-history mr-1"></i>${row.count}x Transaksi`;

                        if (row.jenis === 'wajib') {
                            const nominalAcuanStandard = 50000;
                            const totalUang = parseFloat(row.total_jumlah || 0);
                            const bulanTercover = Math.floor(totalUang / nominalAcuanStandard);
                            const sisaUangPecahan = totalUang % nominalAcuanStandard;

                            if (bulanTercover > 0) {
                                subInfoText = `<i class="fas fa-calendar-check mr-1 text-blue-600"></i>Simpanan Wajib Ke-<b>${bulanTercover}</b> (${row.count}x Input)`;
                                if (sisaUangPecahan > 0) {
                                    subInfoText += ` <span class="text-xs text-amber-600 font-normal">(+${formatRupiah(sisaUangPecahan)})</span>`;
                                }
                            } else {
                                subInfoText = `<i class="fas fa-history mr-1"></i>Simpanan Wajib Ke-1 (${row.count}x Input)`;
                            }
                        } else if (row.jenis === 'pokok') {
                            subInfoText = `<i class="fas fa-money-bill-wave mr-1"></i>Simpanan Pokok Ke-${row.count}`;
                        }

                        tbody.innerHTML += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">${row.tanggal_terakhir}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">${row.nama_lengkap}</div>
                                <div class="text-xs text-blue-600 font-medium">${subInfoText}</div>
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
                });
        }

        function viewHistory(idAnggota, jenis, namaLengkap) {
            const userTransactions = rawSimpananData.filter(row => {
                const matchUser = (row.id_anggota == idAnggota || row.nama_lengkap === namaLengkap);
                const matchJenis = row.jenis === jenis;
                return matchUser && matchJenis;
            });

            userTransactions.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

            document.getElementById('detailNamaAnggota').textContent = namaLengkap;
            document.getElementById('detailJenisSimpanan').textContent = `Rincian Seluruh Riwayat Simpanan ${jenis.toUpperCase()}`;

            let totalAmount = 0;
            const historyTbody = document.getElementById('historyTableBody');
            historyTbody.innerHTML = '';

            if (userTransactions.length === 0) {
                historyTbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                        <i class="fas fa-folder-open text-3xl mb-2 block"></i>
                        <span class="text-sm">Belum ada riwayat transaksi ditemukan.</span>
                    </td>
                </tr>
            `;
            } else {
                userTransactions.forEach((tx, index) => {
                    const amount = parseFloat(tx.jumlah || tx.nominal || 0);
                    totalAmount += amount;
                    const idTx = tx.id_sp || tx.id_sw || tx.id_ss || tx.id;

                    historyTbody.innerHTML += `
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                Ke-${index + 1}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 font-medium whitespace-nowrap">
                            <i class="far fa-calendar-alt mr-2 text-gray-400"></i>${tx.tanggal || '-'}
                        </td>
                        <td class="px-5 py-3.5 font-bold text-gray-900 text-right whitespace-nowrap">
                            ${formatRupiah(amount)}
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            <button onclick="deleteSimpananDetail('${jenis}', '${idTx}')" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-2.5 py-1 rounded-lg text-xs font-semibold transition-all" title="Hapus transaksi ini">
                                <i class="fas fa-trash-alt mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                `;
                });
            }

            document.getElementById('detailTotalNominal').textContent = formatRupiah(totalAmount);
            document.getElementById('detailTotalFrekuensi').textContent = `${userTransactions.length}x Transaksi`;

            const labelEl = document.getElementById('detailLabelKeBerapa');
            const valueEl = document.getElementById('detailStatusKeBerapa');

            if (jenis === 'wajib') {
                const standarWajib = 50000;
                const keBerapa = Math.floor(totalAmount / standarWajib);
                labelEl.textContent = "Simpanan Wajib Ke-";
                valueEl.textContent = `Ke-${keBerapa > 0 ? keBerapa : 1}`;
            } else if (jenis === 'pokok') {
                labelEl.textContent = "Simpanan Pokok Ke-";
                valueEl.textContent = `Ke-${userTransactions.length}`;
            } else if (jenis === 'sukarela') {
                labelEl.textContent = "Status Sukarela";
                valueEl.textContent = userTransactions.length > 0 ? "Fleksibel / Aktif" : "Belum Ada";
            }

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
                        didOpen: () => {
                            Swal.showLoading();
                        }
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
                    anggotaSelect.addEventListener('change', runDynamicCalculations);
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

        //refresh halaman

        function updateStatCardsFromData() {
            // Selalu minta seluruh data simpanan ('all') tanpa filter agar akumulasi semua card akurat
            const filterAnggota = document.getElementById('filterAnggota')?.value || 'all';
            let url = `<?= base_url('admin/getSimpananList') ?>?jenis=all`;
            if (filterAnggota && filterAnggota !== 'all') {
                url += `&id_anggota=${filterAnggota}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!data) return;

                    let totalPokok = 0;
                    let totalWajib = 0;
                    let totalSukarela = 0;

                    data.forEach(row => {
                        const amount = parseFloat(row.jumlah || row.nominal || 0) || 0;
                        const jenis = row.jenis || 'pokok';

                        if (jenis === 'pokok') {
                            totalPokok += amount;
                        } else if (jenis === 'wajib') {
                            totalWajib += amount;
                        } else if (jenis === 'sukarela') {
                            totalSukarela += amount;
                        }
                    });

                    const totalSemua = totalPokok + totalWajib + totalSukarela;

                    // Update DOM Card secara otomatis tanpa reload page!
                    const elPokok = document.getElementById('statTotalPokok');
                    const elWajib = document.getElementById('statTotalWajib');
                    const elSukarela = document.getElementById('statTotalSukarela');
                    const elSemua = document.getElementById('statTotalSemua');

                    if (elPokok) elPokok.innerText = formatRupiah(totalPokok);
                    if (elWajib) elWajib.innerText = formatRupiah(totalWajib);
                    if (elSukarela) elSukarela.innerText = formatRupiah(totalSukarela);
                    if (elSemua) elSemua.innerText = formatRupiah(totalSemua);
                })
                .catch(err => console.error('Error updating stat cards:', err));
        }

        function submitSimpananForm() {
    if (isSubmitting) return;

    const form = document.getElementById('formSimpanan');
    const submitBtn = document.getElementById('simpanBtn');
    const formData = new FormData(form);

    const jenisVal = document.getElementById('jenisSelect')?.value;
    const idAnggotaVal = document.getElementById('anggotaSelect')?.value;
    const jumlahVal = document.getElementById('jumlahInput')?.value;

    const tenorSelectVal = document.getElementById('tenorSelect')?.value;
    const tenorHiddenVal = document.getElementById('tenorHidden')?.value;
    const activeTenor = tenorSelectVal || tenorHiddenVal;

    // Validasi Field
    if (!idAnggotaVal || !jenisVal || !jumlahVal || parseFloat(jumlahVal) <= 0) {
        showNotification('error', 'Gagal!', 'Harap lengkapi semua field!');
        return;
    }

    if (jenisVal === 'pokok') {
        if (!activeTenor || parseInt(activeTenor) <= 0) {
            showNotification('error', 'Gagal!', 'Tenor simpanan pokok wajib diisi!');
            return;
        }
        formData.set('tenor', activeTenor);
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
            showNotification('success', 'Berhasil!', result.message);
            closeModal('savingsModal');
            loadSimpanan();
        } else {
            showNotification('error', 'Gagal!', result.message);
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        showNotification('error', 'Error!', 'Terjadi kesalahan sistem.');
    })
    .finally(() => {
        isSubmitting = false;
        submitBtn.innerHTML = 'Simpan';
        submitBtn.disabled = false;
    });
}
    </script>

</body>

</html>