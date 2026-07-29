<!-- Header Halaman Angsuran -->
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?? 'Manajemen Angsuran' ?></h2>
    <p class="text-gray-600">Kelola dan pantau progres pembayaran angsuran anggota untuk semua jenis pembiayaan</p>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 fade-in flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-2 text-green-600 text-lg"></i>
        <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 fade-in flex items-center shadow-sm">
        <i class="fas fa-exclamation-circle mr-2 text-red-600 text-lg"></i>
        <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl shadow-xl flex items-center space-x-3">
        <i class="fas fa-spinner fa-spin text-emerald-600 text-2xl"></i>
        <span class="text-gray-700 font-semibold text-sm">Memproses pembayaran...</span>
    </div>
</div>

<!-- Container Tab & Table -->
<div class="bg-white rounded-xl shadow-md max-w-7xl mx-auto overflow-hidden mb-6 border border-gray-100">
    <div class="border-b border-gray-200 bg-gray-50/50">
        <nav class="flex space-x-2 px-6 pt-3">
            <button class="tab-button active py-3 px-5 text-sm font-bold border-b-2 border-emerald-600 text-emerald-600 transition-colors flex items-center rounded-t-lg" data-tab="qard">
                <i class="fas fa-hand-holding-heart mr-2 text-xs"></i>Qard
                <span class="ml-2 bg-emerald-100 text-emerald-800 py-0.5 px-2.5 rounded-full text-xs font-bold"><?= count($qard ?? []) ?></span>
            </button>
            <button class="tab-button py-3 px-5 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-colors flex items-center rounded-t-lg" data-tab="murabahah">
                <i class="fas fa-shopping-bag mr-2 text-xs"></i>Murabahah
                <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2.5 rounded-full text-xs font-bold"><?= count($murabahah ?? []) ?></span>
            </button>
            <button class="tab-button py-3 px-5 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-colors flex items-center rounded-t-lg" data-tab="mudharabah">
                <i class="fas fa-briefcase mr-2 text-xs"></i>Mudharabah
                <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2.5 rounded-full text-xs font-bold"><?= count($mudharabah ?? []) ?></span>
            </button>
        </nav>
    </div>

    <!-- Tab Content Area -->
    <div class="p-6">
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

<!-- Modal Bayar Angsuran Super Fleksibel -->
<div id="bayarModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-md w-full mx-4 border border-emerald-100">
        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mr-2 text-sm">
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
            <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-3.5 text-xs space-y-2">
                <div class="flex justify-between items-center text-gray-600">
                    <span>Posisi Angsuran:</span>
                    <span id="text_posisi_saat_ini" class="font-extrabold text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded">Angsuran Ke-1</span>
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
                    <button type="button" onclick="setMultiBulan(1)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">1 Bulan</button>
                    <button type="button" onclick="setMultiBulan(2)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">2 Bulan</button>
                    <button type="button" onclick="setMultiBulan(3)" class="py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all">3 Bulan</button>
                    <button type="button" onclick="setMultiBulan('lunas')" class="py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition-all">Pelunasan</button>
                </div>
            </div>

            <!-- Input Custom Nominal -->
            <div>
                <label for="jumlah_bayar" class="block text-xs font-bold text-gray-700 mb-1">Nominal Pembayaran *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 font-bold text-sm">Rp</span>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" oninput="hitungEfekBayar()" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-gray-800 text-sm" required min="1">
                </div>
            </div>

            <!-- Preview Efek Pembayaran Dinamis -->
            <div id="boxPreviewDinamis" class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs space-y-1.5">
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
                <button type="button" id="cancelBtn" class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition-colors text-xs font-bold shadow-sm" id="submitBtn">
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
                btn.classList.remove('active', 'border-emerald-600', 'text-emerald-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            button.classList.add('active', 'border-emerald-600', 'text-emerald-600');
            button.classList.remove('border-transparent', 'text-gray-500');

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

    // Modal functionality
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

    document.querySelectorAll('.bayar-btn').forEach(button => {
        button.addEventListener('click', function() {
            const jenis = this.getAttribute('data-jenis');
            const id = this.getAttribute('data-id');

            document.getElementById('jenis_angsuran').value = jenis;
            document.getElementById('id_angsuran').value = id;

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            this.disabled = true;

            fetch(`<?= base_url('admin/dashboard_admin/angsuran/detail') ?>?jenis=${jenis}&id=${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
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
        if (e.target === modal) {
            hideModal();
        }
    });

    window.addEventListener('load', () => {
        loadingOverlay.classList.add('hidden');
    });
</script>

<?php
// Helper Function renderTable yang Rapi (1 Frame & Logika Tenor Akurat)
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
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Anggota</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Akad</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Pinjaman / Bln</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase w-48">Progres Tenor</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Terbayar</th>
                        <th class="px-3.5 py-3 text-xs font-bold text-gray-500 uppercase">Sisa</th>
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

        // Hitung Tenor Terbayar dengan Aman
        if ($type === 'qard') {
            $tenor_dibayar = (int)($item['tenor_dibayar'] ?? 0);
        } else {
            // Jika ada sisa_tenor dari DB, tenor_dibayar = total - sisa
            if (isset($item['sisa_tenor'])) {
                $tenor_dibayar = max(0, $jml_angsuran - (int)$item['sisa_tenor']);
            } else {
                // Fallback kalkulasi dari nominal terbayar vs angsuran per bulan
                $tenor_dibayar = $angsuran_per_bulan > 0 ? (int)floor($jml_terbayar / $angsuran_per_bulan) : 0;
            }
        }

        // Pastikan tenor terbayar tidak melebihi total tenor
        $tenor_dibayar = min($tenor_dibayar, $jml_angsuran);
        $sisa_pinjaman = max(0, $jml_pinjam - $jml_terbayar);

        // Hitung Persentase & Posisi Angsuran Ke-
        $persenProgres = min(100, round(($jml_terbayar / ($jml_pinjam > 0 ? $jml_pinjam : 1)) * 100));

        if ($sisa_pinjaman <= 0 && $jml_terbayar > 0) {
            $posisiText = 'Lunas';
            $status = 'lunas';
            $status_class = 'bg-green-100 text-green-800 border border-green-200';
        } else {
            $angsuranKe = min($tenor_dibayar + 1, $jml_angsuran);
            $posisiText = 'Ke-' . $angsuranKe;
            $status = $item['status'] ?? 'aktif';
            $status_class = strtolower($status) == 'aktif' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200';
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

                <!-- 4. Progres Tenor (Fit 1 Frame) -->
                <td class="px-3.5 py-3 whitespace-nowrap w-48">
                    <div class="flex items-center justify-between text-[10px] mb-1 gap-1">
                        <span class="font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">' . $posisiText . '</span>
                        <span class="font-bold text-gray-600 text-[10px]">' . $tenor_dibayar . '/' . $jml_angsuran . ' bln (' . $persenProgres . '%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: ' . $persenProgres . '%"></div>
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

                <!-- 8. Aksi -->
                <td class="px-3.5 py-3 whitespace-nowrap text-center text-xs font-medium">
        ';

        if ($sisa_pinjaman > 0) {
            $html .= '
                <button class="bayar-btn bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-md text-xs font-bold transition duration-150 inline-flex items-center space-x-1 shadow-sm"
                        data-jenis="' . $type . '" 
                        data-id="' . $itemId . '">
                    <i class="fas fa-hand-holding-usd text-[10px]"></i>
                    <span>Bayar</span>
                </button>
            ';
        } else {
            $html .= '
                <span class="text-emerald-600 inline-flex items-center space-x-1 text-[11px] font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
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