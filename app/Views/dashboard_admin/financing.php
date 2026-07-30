<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Peminjaman</title>
</head>
<body class="bg-gray-50/50 min-h-screen p-4 sm:p-6">

    <!-- Header Halaman -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-emerald-600"></i>
                Manajemen Pembiayaan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh pengajuan serta rincian pembiayaan anggota.</p>
        </div>
        <div>
            <button onclick="openModal('financingModal')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all flex items-center text-xs font-bold gap-2">
                <i class="fas fa-plus"></i> Peminjaman Baru
            </button>
        </div>
    </div>

    <!-- Metrics Cards (Selalu Sinkron) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pinjaman Aktif</h3>
                <p class="text-xl font-black text-gray-800 mt-0.5"><?= $total_aktif ?></p>
                <p class="text-[11px] font-semibold text-emerald-600 mt-0.5">Total Rp <?= number_format($total_jumlah, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu Persetujuan</h3>
                <p class="text-xl font-black text-gray-800 mt-0.5"><?= $total_menunggu ?></p>
                <p class="text-[11px] font-medium text-amber-600 mt-0.5">Pengajuan Baru</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mendekati Jatuh Tempo</h3>
                <p class="text-xl font-black text-gray-800 mt-0.5"><?= $total_jatuh_tempo ?></p>
                <p class="text-[11px] font-medium text-rose-600 mt-0.5">3 Hari ke Depan</p>
            </div>
        </div>
    </div>

    <!-- Container Tab Akad & Table -->
    <div class="bg-white rounded-2xl shadow-sm max-w-7xl mx-auto overflow-hidden border border-gray-100 mb-6">
        <div class="border-b border-gray-100 bg-gray-50/60 p-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <nav class="flex space-x-1">
                <button class="tab-button active py-2 px-3.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2 bg-white text-emerald-700 shadow-sm border border-gray-200/80" data-tab="all">
                    <i class="fas fa-th-list text-emerald-600"></i>Semua Akad
                    <span class="bg-emerald-100 text-emerald-800 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($pembiayaan ?? []) ?></span>
                </button>
                <button class="tab-button py-2 px-3.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2 text-gray-500 hover:text-gray-800 hover:bg-white/50" data-tab="qard">
                    <i class="fas fa-hand-holding-heart text-blue-500"></i>Qard
                    <span class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($qard ?? []) ?></span>
                </button>
                <button class="tab-button py-2 px-3.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2 text-gray-500 hover:text-gray-800 hover:bg-white/50" data-tab="murabahah">
                    <i class="fas fa-shopping-bag text-purple-500"></i>Murabahah
                    <span class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($murabahah ?? []) ?></span>
                </button>
                <button class="tab-button py-2 px-3.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2 text-gray-500 hover:text-gray-800 hover:bg-white/50" data-tab="mudharabah">
                    <i class="fas fa-briefcase text-amber-500"></i>Mudharabah
                    <span class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($mudharabah ?? []) ?></span>
                </button>
            </nav>

            <div class="relative px-2">
                <i class="fas fa-search absolute left-4 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" id="searchNama" placeholder="Cari nama anggota..." class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full sm:w-48 bg-white">
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <div class="tab-content active" id="all-content">
                <?= renderFinancingTable($pembiayaan ?? []) ?>
            </div>
            <div class="tab-content hidden" id="qard-content">
                <?= renderFinancingTable($qard ?? []) ?>
            </div>
            <div class="tab-content hidden" id="murabahah-content">
                <?= renderFinancingTable($murabahah ?? []) ?>
            </div>
            <div class="tab-content hidden" id="mudharabah-content">
                <?= renderFinancingTable($mudharabah ?? []) ?>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL PEMBIAYAAN -->
    <div id="detailModal" class="modal fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-auto border border-emerald-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-base">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base leading-tight" id="det_nama">Detail Pembiayaan</h3>
                        <p class="text-[11px] text-emerald-100" id="det_no_anggota">-</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('detailModal')" class="text-white/80 hover:text-white w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                <div class="bg-gray-50 p-3.5 rounded-2xl border border-gray-100 space-y-2">
                    <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                        <span class="text-gray-500">Skema Akad:</span>
                        <span id="det_akad" class="font-bold uppercase text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded text-[10px]">-</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                        <span class="text-gray-500">Tanggal Akad:</span>
                        <span id="det_tanggal" class="font-bold text-gray-800">-</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                        <span class="text-gray-500">Status Pembiayaan:</span>
                        <span id="det_status" class="font-bold capitalize">-</span>
                    </div>
                </div>

                <div class="bg-emerald-50/60 border border-emerald-100 p-3.5 rounded-2xl space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Pinjaman Pokok:</span>
                        <span id="det_pokok" class="font-bold text-gray-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span id="det_label_margin">Margin Keuntungan (10%):</span>
                        <span id="det_margin" class="font-bold text-emerald-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-emerald-200/80">
                        <span class="font-bold text-gray-700">Total Pengembalian:</span>
                        <span id="det_pinjam" class="font-black text-emerald-800 text-sm">Rp 0</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div>
                        <span class="text-gray-400 block text-[10px] font-bold uppercase">Tenor</span>
                        <span id="det_tenor" class="font-bold text-gray-800 text-xs">0 Bulan</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] font-bold uppercase">Angsuran / Bulan</span>
                        <span id="det_angsuran" class="font-black text-emerald-700 text-xs">Rp 0 /bln</span>
                    </div>
                </div>

                <div>
                    <label class="font-bold text-gray-700 block mb-1">Keperluan Pembiayaan:</label>
                    <div id="det_keperluan" class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-600 italic text-[11px]">
                        -
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 text-right">
                <button type="button" onclick="closeModal('detailModal')" class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL PENGAJUAN PINJAMAN -->
    <div id="financingModal" class="modal fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center z-50 hidden p-4">
        <div class="bg-white p-6 rounded-3xl shadow-2xl max-w-lg w-full mx-auto border border-emerald-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-600"></i> Pengajuan Peminjaman Baru
                </h3>
                <button type="button" onclick="closeModal('financingModal')" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formPembiayaan" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Anggota Koperasi *</label>
                    <div class="relative">
                        <input type="text" id="anggotaSearch" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" placeholder="Cari nama atau NIK anggota..." autocomplete="off">
                        <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-200 rounded-xl w-full mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
                        <input type="hidden" id="id_anggota" name="id_anggota" required>
                    </div>
                    <div id="selectedAnggota" class="mt-2 p-2.5 bg-emerald-50/60 border border-emerald-100 rounded-xl hidden text-xs">
                        <span class="font-bold text-emerald-800" id="anggotaNama"></span>
                        <span class="text-[10px] text-emerald-600 block" id="anggotaKtp"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Akad Syariah *</label>
                        <select id="selectAkad" name="akad" onchange="hitungSimulasi()" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" required>
                            <option value="">Pilih Akad</option>
                            <option value="qard">Qard (Bebas Margin 0%)</option>
                            <option value="murabahah">Murabahah (Margin 10%)</option>
                            <option value="mudharabah">Mudharabah (Bagi Hasil 10%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tenor (Bulan) *</label>
                        <select id="selectTenor" name="tenor" onchange="hitungSimulasi()" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" required>
                            <option value="">Pilih Tenor</option>
                            <?php for($i = 1; $i <= 24; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> Bulan</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Pinjam *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-400 font-bold text-xs">Rp</span>
                        <input type="number" id="inputPinjam" name="jml_pinjam" oninput="hitungSimulasi()" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-bold" placeholder="0" min="100000" required>
                    </div>
                </div>

                <div id="boxSimulasi" class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl hidden">
                    <h4 class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-2">Simulasi Skema Pembayaran</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-500 block text-[10px]">Margin/Rate:</span>
                            <span id="textRateMargin" class="font-bold text-emerald-700">0%</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px]">Nominal Margin:</span>
                            <span id="textNominalMargin" class="font-bold text-emerald-700">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px]">Total Kewajiban:</span>
                            <span id="textTotalPinjaman" class="font-bold text-emerald-700">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[10px]">Angsuran/Bulan:</span>
                            <span id="textAngsuranBulanan" class="font-bold text-emerald-700">Rp 0 /bln</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Keperluan Pinjaman *</label>
                    <textarea id="inputKeperluan" name="keperluan" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" rows="2" placeholder="Catat keperluan pengajuan..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Akad *</label>
                    <input type="date" id="inputTanggal" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="flex space-x-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('financingModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-xl text-xs font-semibold hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" id="submitBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-emerald-700 transition"><i class="fas fa-paper-plane mr-1"></i> Ajukan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRM DELETE -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center z-50 hidden p-4">
        <div class="bg-white p-6 rounded-3xl shadow-2xl max-w-xs w-full mx-auto border border-rose-100 text-center">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3 text-lg">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-1">Hapus Pembiayaan?</h3>
            <p class="text-xs text-gray-500 mb-4">Pembiayaan milik <b id="del_nama" class="text-gray-800"></b> beserta seluruh riwayat angsurannya akan dihapus permanen.</p>

            <input type="hidden" id="del_id">
            <input type="hidden" id="del_akad">

            <div class="flex gap-2">
                <button type="button" onclick="closeModal('deleteModal')" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl text-xs font-bold hover:bg-gray-300 transition">Batal</button>
                <button type="button" onclick="executeDelete()" id="btnExecDelete" class="flex-1 bg-rose-600 text-white py-2 rounded-xl text-xs font-bold hover:bg-rose-700 transition">Hapus Data</button>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'bg-white', 'text-emerald-700', 'shadow-sm', 'border', 'border-gray-200/80');
                    btn.classList.add('text-gray-500');
                });

                button.classList.add('active', 'bg-white', 'text-emerald-700', 'shadow-sm', 'border', 'border-gray-200/80');
                button.classList.remove('text-gray-500');

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });

                const tabId = button.getAttribute('data-tab');
                const tabContent = document.getElementById(`${tabId}-content`);
                if (tabContent) {
                    tabContent.classList.remove('hidden');
                    tabContent.classList.add('active');
                }
            });
        });

        // Search
        document.getElementById('searchNama').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.financing-row').forEach(row => {
                const nama = (row.getAttribute('data-nama') || '').toLowerCase();
                row.style.display = nama.includes(query) ? '' : 'none';
            });
        });

        function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById(id).classList.remove("flex");
        }

        // Populate Detail Modal Interaktif
        function showDetailModal(item) {
            document.getElementById('det_nama').innerText = item.nama_lengkap || 'Anggota';
            document.getElementById('det_no_anggota').innerText = 'No. Anggota: ' + (item.nomor_anggota || '-');
            document.getElementById('det_akad').innerText = item.akad || '-';
            document.getElementById('det_tanggal').innerText = item.tanggal ? new Date(item.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
            document.getElementById('det_status').innerText = item.status || '-';

            const totalPinjam = parseFloat(item.jml_pinjam) || 0;
            const pokok = parseFloat(item.pokok) || totalPinjam;
            const margin = parseFloat(item.margin) || 0;
            const tenor = parseInt(item.tenor) || 1;
            const angsuran = totalPinjam / tenor;

            document.getElementById('det_pokok').innerText = 'Rp ' + Math.round(pokok).toLocaleString('id-ID');
            document.getElementById('det_margin').innerText = 'Rp ' + Math.round(margin).toLocaleString('id-ID');
            document.getElementById('det_pinjam').innerText = 'Rp ' + Math.round(totalPinjam).toLocaleString('id-ID');

            document.getElementById('det_label_margin').innerText = (item.akad === 'qard') 
                ? 'Kebajikan/Biaya Admin (0%):' 
                : (item.akad === 'murabahah' ? 'Margin Keuntungan Jual Beli (10%):' : 'Nisbah Bagi Hasil (10%):');

            document.getElementById('det_tenor').innerText = tenor + ' Bulan';
            document.getElementById('det_angsuran').innerText = 'Rp ' + Math.round(angsuran).toLocaleString('id-ID') + ' /bln';
            document.getElementById('det_keperluan').innerText = item.keperluan || 'Tidak ada catatan keperluan.';

            openModal('detailModal');
        }

        function confirmDelete(id, akad, nama) {
            document.getElementById('del_id').value = id;
            document.getElementById('del_akad').value = akad;
            document.getElementById('del_nama').innerText = nama;
            openModal('deleteModal');
        }

        function executeDelete() {
            const id = document.getElementById('del_id').value;
            const akad = document.getElementById('del_akad').value;
            const btn = document.getElementById('btnExecDelete');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData();
            formData.append('id', id);
            formData.append('akad', akad);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('admin/deletePembiayaan') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + res.message);
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'Hapus Data';
                closeModal('deleteModal');
            });
        }

        // Search Anggota Autocomplete
        function setupAnggotaSearch() {
            const input = document.getElementById('anggotaSearch');
            const results = document.getElementById('anggotaResults');
            const hidden = document.getElementById('id_anggota');
            const selectedDiv = document.getElementById('selectedAnggota');

            if (!input) return;

            input.addEventListener('input', function() {
                const q = this.value.trim();
                if (!q) {
                    results.classList.add('hidden');
                    return;
                }

                fetch('<?= base_url('admin/search-anggota') ?>?q=' + encodeURIComponent(q))
                    .then(res => res.json())
                    .then(data => {
                        results.innerHTML = '';
                        if (!data || data.length === 0) {
                            results.innerHTML = '<div class="p-3 text-xs text-gray-400">Anggota tidak ditemukan</div>';
                        } else {
                            data.forEach(anggota => {
                                const div = document.createElement('div');
                                div.className = 'p-2.5 hover:bg-emerald-50 cursor-pointer border-b border-gray-100 text-xs';
                                div.innerHTML = `<div class="font-bold text-gray-800">${anggota.nama_lengkap}</div><div class="text-[10px] text-gray-500">${anggota.no_ktp || '-'}</div>`;
                                div.onclick = () => {
                                    input.value = anggota.nama_lengkap;
                                    hidden.value = anggota.id_anggota || anggota.id;
                                    document.getElementById('anggotaNama').textContent = anggota.nama_lengkap;
                                    document.getElementById('anggotaKtp').textContent = 'NIK: ' + (anggota.no_ktp || '-');
                                    selectedDiv.classList.remove('hidden');
                                    results.classList.add('hidden');
                                };
                                results.appendChild(div);
                            });
                        }
                        results.classList.remove('hidden');
                    });
            });
        }

        function hitungSimulasi() {
            const akad = document.getElementById('selectAkad').value;
            const pinjam = parseFloat(document.getElementById('inputPinjam').value) || 0;
            const tenor = parseInt(document.getElementById('selectTenor').value) || 0;
            const box = document.getElementById('boxSimulasi');

            if (!akad || pinjam <= 0 || tenor <= 0) {
                box.classList.add('hidden');
                return;
            }

            let rate = (akad === 'murabahah' || akad === 'mudharabah') ? 0.10 : 0;
            const nominalMargin = pinjam * rate;
            const totalPengembalian = pinjam + nominalMargin;
            const angsuranPerBulan = totalPengembalian / tenor;

            document.getElementById('textRateMargin').innerText = (rate * 100) + '%';
            document.getElementById('textNominalMargin').innerText = 'Rp ' + Math.round(nominalMargin).toLocaleString('id-ID');
            document.getElementById('textTotalPinjaman').innerText = 'Rp ' + Math.round(totalPengembalian).toLocaleString('id-ID');
            document.getElementById('textAngsuranBulanan').innerText = 'Rp ' + Math.round(angsuranPerBulan).toLocaleString('id-ID') + ' /bln';

            box.classList.remove('hidden');
        }

        // ==========================================
        // FIXED AJAX SAVE PEMBIAYAAN (EVENT SUBMIT)
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            setupAnggotaSearch();

            const formPembiayaan = document.getElementById('formPembiayaan');
            if (formPembiayaan) {
                formPembiayaan.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const id_anggota = document.getElementById('id_anggota').value;
                    const akad       = document.getElementById('selectAkad').value;
                    const jml_pinjam = document.getElementById('inputPinjam').value;
                    const tenor      = document.getElementById('selectTenor').value;
                    const keperluan  = document.getElementById('inputKeperluan').value;
                    const tanggal    = document.getElementById('inputTanggal').value;

                    if (!id_anggota || id_anggota === '0' || !akad || !jml_pinjam || !tenor || !tanggal) {
                        alert('Pilih anggota dari hasil pencarian dan isi seluruh field!');
                        return;
                    }

                    const submitBtn = document.getElementById('submitBtn');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

                    const formData = new FormData();
                    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    formData.append('id_anggota', id_anggota);
                    formData.append('akad', akad);
                    formData.append('jml_pinjam', jml_pinjam);
                    formData.append('tenor', tenor);
                    formData.append('keperluan', keperluan);
                    formData.append('tanggal', tanggal);

                    fetch('<?= base_url('admin/savePembiayaan') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.status === 'success') {
                            alert(response.message);
                            closeModal('financingModal');
                            location.reload();
                        } else {
                            alert('Gagal: ' + (response.message || 'Terjadi kesalahan saat menyimpan'));
                        }
                    })
                    .catch(err => {
                        console.error('Error save:', err);
                        alert('Terjadi kesalahan jaringan/sistem.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
        });
    </script>
</body>
</html>

<?php
// Helper Render Tabel dengan Kolom Pokok, Margin & Total
function renderFinancingTable($data) {
    if (empty($data)) {
        return '
            <div class="text-center py-12">
                <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-file-invoice text-xl"></i>
                </div>
                <p class="text-gray-500 font-medium text-xs">Belum ada data pembiayaan pada kategori ini</p>
            </div>
        ';
    }

    $html = '
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">No</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Nama Anggota</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Akad</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Pokok Pinjaman</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Margin (10%)</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Total Kewajiban</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Tenor</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-3.5 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-xs">
    ';

    $no = 1;
    foreach ($data as $item) {
        $jsonItem = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
        $akad   = strtolower($item['akad'] ?? 'qard');
        $status = strtolower($item['status'] ?? 'aktif');

        $totalPinjam = (float)($item['jml_pinjam'] ?? 0);
        $pokok       = (float)($item['pokok'] ?? $totalPinjam);
        $margin      = (float)($item['margin'] ?? 0);

        $akadClass = $akad === 'qard' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                     ($akad === 'murabahah' ? 'bg-purple-50 text-purple-700 border-purple-200' : 
                     'bg-amber-50 text-amber-700 border-amber-200');

        $statusClass = $status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 
                      ($status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                      ($status === 'lunas' ? 'bg-blue-100 text-blue-800' : 
                      'bg-rose-100 text-rose-800'));

        $html .= '
            <tr class="financing-row hover:bg-emerald-50/20 transition" data-nama="' . esc(strtolower($item['nama_lengkap'])) . '">
                <td class="px-3.5 py-3 font-semibold text-gray-500">' . $no++ . '</td>
                <td class="px-3.5 py-3 font-bold text-gray-800">' . esc($item['nama_lengkap']) . '</td>
                <td class="px-3.5 py-3">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border ' . $akadClass . '">
                        ' . ucfirst($akad) . '
                    </span>
                </td>
                <td class="px-3.5 py-3 text-gray-700 font-semibold">Rp ' . number_format($pokok, 0, ',', '.') . '</td>
                <td class="px-3.5 py-3 font-bold ' . ($margin > 0 ? 'text-emerald-700' : 'text-gray-400') . '">
                    ' . ($margin > 0 ? 'Rp ' . number_format($margin, 0, ',', '.') : 'Rp 0 (0%)') . '
                </td>
                <td class="px-3.5 py-3 font-extrabold text-gray-900">Rp ' . number_format($totalPinjam, 0, ',', '.') . '</td>
                <td class="px-3.5 py-3 text-gray-600 font-medium">' . esc($item['tenor']) . ' Bln</td>
                <td class="px-3.5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ' . $statusClass . '">
                        ' . ucfirst($status) . '
                    </span>
                </td>
                <td class="px-3.5 py-3 text-center space-x-1 whitespace-nowrap">
                    <button type="button" onclick=\'showDetailModal(' . $jsonItem . ')\' 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg text-xs font-bold transition inline-flex items-center gap-1"
                            title="Lihat Detail Rincian">
                        <i class="fas fa-eye text-[10px]"></i> Detail
                    </button>
                    <button type="button" onclick="confirmDelete(\'' . $item['id'] . '\', \'' . $akad . '\', \'' . esc($item['nama_lengkap']) . '\')" 
                            class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-2 py-1 rounded-lg text-xs font-bold transition inline-flex items-center gap-1"
                            title="Hapus Pembiayaan">
                        <i class="fas fa-trash-alt text-[10px]"></i> Hapus
                    </button>
                </td>
            </tr>
        ';
    }

    $html .= '
                </tbody>
            </table>
        </div>
    ';

    return $html;
}
?>