<!-- Header Halaman Angsuran -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
            <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
            <?= $title ?? 'Manajemen Angsuran' ?>
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kelola, bayar, dan pantau rincian status tenor serta riwayat angsuran anggota.</p>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-2 text-emerald-600 text-lg"></i>
        <span class="text-sm font-semibold"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-exclamation-circle mr-2 text-rose-600 text-lg"></i>
        <span class="text-sm font-semibold"><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-2xl shadow-2xl flex items-center space-x-3 border border-gray-100">
        <i class="fas fa-spinner fa-spin text-emerald-600 text-2xl"></i>
        <span class="text-gray-700 font-bold text-sm">Memproses...</span>
    </div>
</div>

<!-- Container Tab & Table -->
<div class="bg-white rounded-2xl shadow-sm max-w-7xl mx-auto overflow-hidden mb-6 border border-gray-100">
    <div class="border-b border-gray-100 bg-gray-50/60 p-2">
        <nav class="flex space-x-2">
            <button class="tab-button active py-2.5 px-4 text-xs font-bold rounded-xl transition-all flex items-center gap-2 bg-white text-emerald-700 shadow-sm border border-gray-200/80" data-tab="qard">
                <i class="fas fa-hand-holding-heart text-emerald-600"></i>Qard
                <span class="bg-emerald-100 text-emerald-800 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($qard ?? []) ?></span>
            </button>
            <button class="tab-button py-2.5 px-4 text-xs font-bold rounded-xl transition-all flex items-center gap-2 text-gray-500 hover:text-gray-800 hover:bg-white/50" data-tab="murabahah">
                <i class="fas fa-shopping-bag text-gray-400"></i>Murabahah
                <span class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($murabahah ?? []) ?></span>
            </button>
            <button class="tab-button py-2.5 px-4 text-xs font-bold rounded-xl transition-all flex items-center gap-2 text-gray-500 hover:text-gray-800 hover:bg-white/50" data-tab="mudharabah">
                <i class="fas fa-briefcase text-gray-400"></i>Mudharabah
                <span class="bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-[10px] font-extrabold"><?= count($mudharabah ?? []) ?></span>
            </button>
        </nav>
    </div>

    <!-- Tab Content Area -->
    <div class="p-4 sm:p-6">
        <div class="tab-content active fade-in" id="qard-content">
            <?= renderTable($qard ?? [], 'qard', 'id_qard') ?>
        </div>
        <div class="tab-content hidden fade-in" id="murabahah-content">
            <?= renderTable($murabahah ?? [], 'murabahah', 'id_mr') ?>
        </div>
        <div class="tab-content hidden fade-in" id="mudharabah-content">
            <?= renderTable($mudharabah ?? [], 'mudharabah', 'id_md') ?>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL HISTORY & DETAIL TENOR (FITUR BARU) -->
<!-- ========================================== -->
<div id="historyModal" class="modal fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center z-50 hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-auto border border-emerald-100 overflow-hidden my-8">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-base">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight" id="hist_nama_anggota">Detail & History Angsuran</h3>
                    <p class="text-[11px] text-emerald-100" id="hist_no_anggota">-</p>
                </div>
            </div>
            <button type="button" onclick="closeHistoryModal()" class="text-white/80 hover:text-white w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
            <!-- Ringkasan Status Tenor Card -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 p-3.5 rounded-2xl border border-gray-100 text-xs">
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Pinjaman</span>
                    <span class="font-black text-gray-800 text-sm" id="hist_total_pinjam">Rp 0</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Sudah Dibayar</span>
                    <span class="font-black text-emerald-600 text-sm" id="hist_terbayar">Rp 0</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Sisa Tagihan</span>
                    <span class="font-black text-rose-600 text-sm" id="hist_sisa">Rp 0</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Progress Tenor</span>
                    <span class="font-black text-emerald-800 text-sm" id="hist_tenor_text">0 / 0 Bln</span>
                </div>
            </div>

            <!-- Visual Matriks Tenor per Bulan -->
            <div>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span><i class="fas fa-calendar-alt text-emerald-600 mr-1.5"></i> Status Matriks Tenor Bulan</span>
                    <span class="text-[10px] text-gray-400 normal-case font-normal">*Tanggal jatuh tempo dihitung per bulan dari akad</span>
                </h4>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2" id="gridTenorBulan">
                    <!-- Dynamic Tenor Badges -->
                </div>
            </div>

            <!-- Tabel History Pembayaran Real -->
            <div>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">
                    <i class="fas fa-list-ul text-emerald-600 mr-1.5"></i> Riwayat Pembayaran Terdaftar
                </h4>
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                            <tr>
                                <th class="p-2.5">Ke-</th>
                                <th class="p-2.5">Tgl Bayar</th>
                                <th class="p-2.5">Nominal</th>
                                <th class="p-2.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableHistoryBody" class="divide-y divide-gray-100">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 text-right">
            <button type="button" onclick="closeHistoryModal()" class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL BAYAR ANGSURAN (PEMBAYARAN LOGIC)    -->
<!-- ========================================== -->
<div id="bayarModal" class="modal fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center z-50 hidden p-4">
    <div class="bg-white p-6 rounded-3xl shadow-2xl max-w-md w-full mx-auto border border-emerald-100">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800 flex items-center">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mr-2 text-sm">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                Bayar Angsuran
            </h3>
            <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <form action="<?= base_url('admin/dashboard_admin/angsuran/bayar') ?>" method="POST" class="mt-4 space-y-4" id="bayarForm">
            <?= csrf_field() ?>
            <input type="hidden" name="jenis" id="jenis_angsuran">
            <input type="hidden" name="id" id="id_angsuran">

            <!-- Ringkasan Pinjaman Rapi -->
            <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-3.5 text-xs space-y-2">
                <div class="flex justify-between items-center text-gray-600">
                    <span>Posisi Angsuran:</span>
                    <span id="text_posisi_saat_ini" class="font-extrabold text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded-md">Angsuran Ke-1</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Angsuran Standard:</span>
                    <span id="text_angsuran_per_bulan" class="font-bold text-gray-800">Rp 0 / bulan</span>
                </div>
                <div class="flex justify-between text-gray-600 pt-1 border-t border-emerald-200/50">
                    <span>Sisa Pokok Tagihan:</span>
                    <span id="text_sisa_pinjaman" class="font-extrabold text-rose-600">Rp 0</span>
                </div>
            </div>

            <!-- Quick Select Bulan -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Berapa Bulan Sekaligus?</label>
                <div class="grid grid-cols-4 gap-2" id="boxQuickBulan">
                    <button type="button" onclick="setMultiBulan(1)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">1 Bulan</button>
                    <button type="button" onclick="setMultiBulan(2)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">2 Bulan</button>
                    <button type="button" onclick="setMultiBulan(3)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">3 Bulan</button>
                    <button type="button" onclick="setMultiBulan('lunas')" class="py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-all">Pelunasan</button>
                </div>
            </div>

            <!-- Input Custom Nominal -->
            <div>
                <label for="jumlah_bayar" class="block text-xs font-bold text-gray-700 mb-1">Nominal Pembayaran *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 font-bold text-sm">Rp</span>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" oninput="hitungEfekBayar()" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-gray-800 text-sm" required min="1">
                </div>
            </div>

            <!-- Preview Efek Pembayaran Dinamis -->
            <div id="boxPreviewDinamis" class="p-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-gray-500">Setara dengan:</span>
                    <span id="text_setara_bulan" class="font-bold text-emerald-700">0 Bulan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Sisa Tagihan Setelah Bayar:</span>
                    <span id="text_sisa_setelah_bayar" class="font-bold text-gray-800">Rp 0</span>
                </div>
                <div class="flex justify-between items-center pt-1 border-t border-gray-200/60">
                    <span class="text-gray-500">Status Setelah Bayar:</span>
                    <span id="text_status_baru" class="font-bold text-emerald-700">Aktif</span>
                </div>
            </div>

            <div class="flex space-x-3 pt-3 border-t border-gray-100">
                <button type="button" id="cancelBtn" class="flex-1 bg-gray-500 text-white py-2.5 rounded-xl hover:bg-gray-600 transition-colors text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2.5 rounded-xl hover:bg-emerald-700 transition-colors text-xs font-bold shadow-sm" id="submitBtn">
                    <i class="fas fa-check-circle mr-1"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab functionality
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

    // Modal Bayar Modifiers
    const modal = document.getElementById('bayarModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeModal = document.getElementById('closeModal');
    const bayarForm = document.getElementById('bayarForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    let globalNominalPerBulan = 0;
    let globalSisaTagihan = 0;
    let globalTotalTenor = 1;
    let globalTenorTerbayar = 0;

    // Trigger Open Modal Bayar
    document.querySelectorAll('.bayar-btn').forEach(button => {
        button.addEventListener('click', function() {
            const jenis = this.getAttribute('data-jenis');
            const id = this.getAttribute('data-id');

            document.getElementById('jenis_angsuran').value = jenis;
            document.getElementById('id_angsuran').value = id;

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`<?= base_url('admin/dashboard_admin/angsuran/detail') ?>?jenis=${jenis}&id=${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = data.data;
                        globalSisaTagihan = parseFloat(data.sisa_pembayaran) || 0;
                        globalTotalTenor = parseInt(data.jml_angsuran) || 1;
                        const jmlPinjam = parseFloat(item.jml_pinjam) || 0;

                        globalNominalPerBulan = Math.round(jmlPinjam / globalTotalTenor);
                        globalTenorTerbayar = Math.floor((jmlPinjam - globalSisaTagihan) / globalNominalPerBulan);
                        const angsuranKe = Math.min(globalTenorTerbayar + 1, globalTotalTenor);

                        document.getElementById('text_posisi_saat_ini').innerText = `Angsuran Ke-${angsuranKe} dari ${globalTotalTenor} Bulan`;
                        document.getElementById('text_angsuran_per_bulan').innerText = 'Rp ' + globalNominalPerBulan.toLocaleString('id-ID') + ' / bulan';
                        document.getElementById('text_sisa_pinjaman').innerText = 'Rp ' + globalSisaTagihan.toLocaleString('id-ID');

                        setMultiBulan(1);

                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        alert('Gagal memuat detail angsuran.');
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
        });
    });

    // Helper Modal Detail & History Tenor
    function showHistoryModal(jenis, id) {
        const historyModal = document.getElementById('historyModal');
        loadingOverlay.classList.remove('hidden');

        fetch(`<?= base_url('admin/dashboard_admin/angsuran/detail') ?>?jenis=${jenis}&id=${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const item = res.data;
                const history = res.history || [];
                const totalTenor = res.jml_angsuran || 1;
                const totalPinjam = parseFloat(item.jml_pinjam) || 0;
                const terbayar = parseFloat(item.jml_terbayar) || 0;
                const sisa = res.sisa_pembayaran || 0;
                const nominalPerBulan = Math.round(totalPinjam / totalTenor);
                const tenorTerbayar = Math.min(totalTenor, Math.floor(terbayar / nominalPerBulan));

                // Populate Header Card
                document.getElementById('hist_nama_anggota').innerText = item.nama_lengkap || 'Anggota';
                document.getElementById('hist_no_anggota').innerText = `No. Anggota: ${item.nomor_anggota || '-'}`;
                document.getElementById('hist_total_pinjam').innerText = 'Rp ' + totalPinjam.toLocaleString('id-ID');
                document.getElementById('hist_terbayar').innerText = 'Rp ' + terbayar.toLocaleString('id-ID');
                document.getElementById('hist_sisa').innerText = 'Rp ' + sisa.toLocaleString('id-ID');
                document.getElementById('hist_tenor_text').innerText = `${tenorTerbayar} / ${totalTenor} Bln`;

                // Render Visual Grid Matriks Tenor Bulan
                const grid = document.getElementById('gridTenorBulan');
                grid.innerHTML = '';

                const tglAkad = item.tanggal ? new Date(item.tanggal) : new Date();

                for (let i = 1; i <= totalTenor; i++) {
                    // Hitung estimasi tgl jatuh tempo per bulan
                    let estTgl = new Date(tglAkad);
                    estTgl.setMonth(estTgl.getMonth() + i);
                    let formatTgl = estTgl.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });

                    let isLunas = i <= tenorTerbayar;
                    let bgClass = isLunas 
                        ? 'bg-emerald-100 text-emerald-800 border-emerald-300' 
                        : 'bg-gray-100 text-gray-500 border-gray-200';
                    let icon = isLunas ? '<i class="fas fa-check-circle text-emerald-600 mr-1"></i>' : '<i class="far fa-clock text-gray-400 mr-1"></i>';

                    grid.innerHTML += `
                        <div class="p-2 border rounded-xl text-center ${bgClass} flex flex-col justify-between">
                            <span class="text-[10px] font-bold block">Bulan Ke-${i}</span>
                            <span class="text-[9px] text-gray-500 block mb-1">${formatTgl}</span>
                            <span class="text-[10px] font-extrabold flex items-center justify-center">${icon}${isLunas ? 'Lunas' : 'Belum'}</span>
                        </div>
                    `;
                }

                // Render Table History
                const tbody = document.getElementById('tableHistoryBody');
                tbody.innerHTML = '';

                if (history.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-gray-400 text-xs">Belum ada riwayat transaksi pembayaran</td></tr>`;
                } else {
                    history.forEach(h => {
                        const tgl = h.tanggal_bayar ? new Date(h.tanggal_bayar).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-2.5 font-bold text-gray-800">Ke-${h.angsuran_ke}</td>
                                <td class="p-2.5 text-gray-600">${tgl}</td>
                                <td class="p-2.5 font-bold text-emerald-700">Rp ${parseFloat(h.jumlah_bayar).toLocaleString('id-ID')}</td>
                                <td class="p-2.5 text-right"><span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full font-bold">Berhasil</span></td>
                            </tr>
                        `;
                    });
                }

                historyModal.classList.remove('hidden');
                historyModal.classList.add('flex');
            } else {
                alert('Gagal mengambil data history.');
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            loadingOverlay.classList.add('hidden');
        });
    }

    function closeHistoryModal() {
        const historyModal = document.getElementById('historyModal');
        historyModal.classList.add('hidden');
        historyModal.classList.remove('flex');
    }

    function setMultiBulan(bulan) {
        let nominal = 0;
        if (bulan === 'lunas') {
            nominal = globalSisaTagihan;
        } else {
            nominal = Math.min(globalNominalPerBulan * bulan, globalSisaTagihan);
        }
        document.getElementById('jumlah_bayar').value = nominal;
        hitungEfekBayar();
    }

    function hitungEfekBayar() {
        const inputVal = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
        let bayar = inputVal;
        if (bayar > globalSisaTagihan) {
            bayar = globalSisaTagihan;
            document.getElementById('jumlah_bayar').value = bayar;
        }

        const hitungSetaraBulan = (bayar / globalNominalPerBulan).toFixed(1);
        const sisaAkhir = Math.max(0, globalSisaTagihan - bayar);

        document.getElementById('text_setara_bulan').innerText = `${hitungSetaraBulan} Bulan Angsuran`;
        document.getElementById('text_sisa_setelah_bayar').innerText = 'Rp ' + Math.round(sisaAkhir).toLocaleString('id-ID');

        if (sisaAkhir <= 0 && bayar > 0) {
            document.getElementById('text_status_baru').innerText = 'LUNAS 🎉';
            document.getElementById('text_status_baru').className = 'font-bold text-blue-700';
        } else {
            document.getElementById('text_status_baru').innerText = 'Aktif';
            document.getElementById('text_status_baru').className = 'font-bold text-emerald-800';
        }
    }

    bayarForm.addEventListener('submit', function(e) {
        const jumlahBayar = document.getElementById('jumlah_bayar').value;

        if (!jumlahBayar || jumlahBayar <= 0) {
            e.preventDefault();
            alert('Masukkan jumlah pembayaran yang valid');
            return;
        }

        loadingOverlay.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    });

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    cancelBtn.addEventListener('click', hideModal);
    closeModal.addEventListener('click', hideModal);

    window.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
        if (e.target === document.getElementById('historyModal')) closeHistoryModal();
    });

    window.addEventListener('load', () => {
        loadingOverlay.classList.add('hidden');
    });
</script>

<?php
// Helper Function renderTable yang Lebih Lengkap & Interaktif
function renderTable($data, $type, $idField)
{
    if (empty($data)) {
        return '
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-file-invoice text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium text-sm">Belum ada data pembiayaan ' . ucfirst($type) . '</p>
            </div>
        ';
    }

    $html = '
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Anggota</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Tgl Akad</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Pinjaman / Bln</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase w-48">Progres Tenor</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Terbayar</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Sisa Tagihan</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-3.5 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
    ';

    foreach ($data as $item) {
        $jml_angsuran = isset($item['jml_angsuran']) && (int)$item['jml_angsuran'] > 0 ? (int)$item['jml_angsuran'] : 1;
        $jml_pinjam = (float)($item['jml_pinjam'] ?? 0);
        $jml_terbayar = (float)($item['jml_terbayar'] ?? 0);
        $angsuran_per_bulan = $jml_pinjam / $jml_angsuran;

        // Hitung Tenor Terbayar
        if ($type === 'qard') {
            $tenor_dibayar = (int)($item['tenor_dibayar'] ?? 0);
        } else {
            if (isset($item['sisa_tenor'])) {
                $tenor_dibayar = max(0, $jml_angsuran - (int)$item['sisa_tenor']);
            } else {
                $tenor_dibayar = $angsuran_per_bulan > 0 ? (int)floor($jml_terbayar / $angsuran_per_bulan) : 0;
            }
        }

        $tenor_dibayar = min($tenor_dibayar, $jml_angsuran);
        $sisa_pinjaman = max(0, $jml_pinjam - $jml_terbayar);
        $persenProgres = min(100, round(($jml_terbayar / ($jml_pinjam > 0 ? $jml_pinjam : 1)) * 100));

        if ($sisa_pinjaman <= 0 && $jml_terbayar > 0) {
            $posisiText = 'Lunas';
            $status = 'lunas';
            $status_class = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
        } else {
            $angsuranKe = min($tenor_dibayar + 1, $jml_angsuran);
            $posisiText = 'Ke-' . $angsuranKe;
            $status = $item['status'] ?? 'aktif';
            $status_class = strtolower($status) == 'aktif' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-200';
        }

        $itemId = $item[$idField] ?? null;
        if (!$itemId) continue;

        $html .= '
            <tr class="hover:bg-emerald-50/30 transition duration-150">
                <!-- 1. Anggota -->
                <td class="px-3.5 py-3 whitespace-nowrap">
                    <div class="text-xs font-bold text-gray-800">' . ($item['nama_lengkap'] ?? 'N/A') . '</div>
                    <div class="text-[10px] text-gray-500 font-medium"><i class="fas fa-id-badge text-gray-400 mr-1"></i>' . ($item['nomor_anggota'] ?? 'N/A') . '</div>
                </td>

                <!-- 2. Akad / Tanggal -->
                <td class="px-3.5 py-3 whitespace-nowrap text-xs text-gray-600 font-medium">
                    ' . (isset($item['tanggal']) ? date('d M Y', strtotime($item['tanggal'])) : 'N/A') . '
                </td>

                <!-- 3. Pinjaman & Angsuran -->
                <td class="px-3.5 py-3 whitespace-nowrap">
                    <div class="text-xs font-bold text-gray-800">Rp ' . number_format($jml_pinjam, 0, ',', '.') . '</div>
                    <div class="text-[10px] text-gray-500">Rp ' . number_format($angsuran_per_bulan, 0, ',', '.') . '/bln</div>
                </td>

                <!-- 4. Progres Tenor -->
                <td class="px-3.5 py-3 whitespace-nowrap w-48">
                    <div class="flex items-center justify-between text-[10px] mb-1 gap-1">
                        <span class="font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">' . $posisiText . '</span>
                        <span class="font-bold text-gray-600 text-[10px]">' . $tenor_dibayar . '/' . $jml_angsuran . ' bln (' . $persenProgres . '%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-300" style="width: ' . $persenProgres . '%"></div>
                    </div>
                </td>

                <!-- 5. Total Terbayar -->
                <td class="px-3.5 py-3 whitespace-nowrap text-xs font-bold text-emerald-700">
                    Rp ' . number_format($jml_terbayar, 0, ',', '.') . '
                </td>

                <!-- 6. Sisa Tagihan -->
                <td class="px-3.5 py-3 whitespace-nowrap text-xs font-extrabold text-rose-600">
                    Rp ' . number_format($sisa_pinjaman, 0, ',', '.') . '
                </td>

                <!-- 7. Status -->
                <td class="px-3.5 py-3 whitespace-nowrap">
                    <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded-full ' . $status_class . '">
                        ' . ucfirst($status) . '
                    </span>
                </td>

                <!-- 8. Aksi (Tombol Detail/History + Bayar) -->
                <td class="px-3.5 py-3 whitespace-nowrap text-center text-xs font-medium space-x-1">
                    <button type="button" onclick="showHistoryModal(\'' . $type . '\', \'' . $itemId . '\')" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1"
                            title="Lihat Detail & History">
                        <i class="fas fa-eye text-[11px]"></i>
                    </button>
        ';

        if ($sisa_pinjaman > 0) {
            $html .= '
                <button class="bayar-btn bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg text-xs font-bold transition inline-flex items-center space-x-1 shadow-sm"
                        data-jenis="' . $type . '" 
                        data-id="' . $itemId . '">
                    <i class="fas fa-hand-holding-usd text-[10px]"></i>
                    <span>Bayar</span>
                </button>
            ';
        } else {
            $html .= '
                <span class="text-emerald-600 inline-flex items-center space-x-1 text-[11px] font-bold bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                    <i class="fas fa-check-circle"></i>
                    <span>Lunas</span>
                </span>
            ';
        }

        $html .= '
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