<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Peminjaman</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Pinjaman</h2>
        <p class="text-gray-600">Kelola pinjaman anggota koperasi</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card Aktif -->
        <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
            <div class="text-emerald-600 text-3xl mb-2"><i class="fas fa-check-circle"></i></div>
            <h3 class="text-md font-bold text-gray-800">Pinjaman Aktif</h3>
            <p class="text-2xl font-bold text-emerald-600 mt-2"><?= $total_aktif ?></p>
            <p class="text-sm text-gray-600">Total Rp <?= number_format($total_jumlah, 0, ',', '.') ?></p>
        </div>

        <!-- Card Menunggu -->
        <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
            <div class="text-yellow-500 text-3xl mb-2"><i class="fas fa-clock"></i></div>
            <h3 class="text-md font-bold text-gray-800">Menunggu Persetujuan</h3>
            <p class="text-2xl font-bold text-yellow-500 mt-2"><?= $total_menunggu ?></p>
            <p class="text-sm text-gray-600">Pinjaman baru</p>
        </div>

        <!-- Card Jatuh Tempo -->
        <div class="bg-white p-6 rounded-xl shadow-md flex flex-col items-center">
            <div class="text-red-600 text-3xl mb-2"><i class="fas fa-exclamation-triangle"></i></div>
            <h3 class="text-md font-bold text-gray-800">Jatuh Tempo</h3>
            <p class="text-2xl font-bold text-red-600 mt-2"><?= $total_jatuh_tempo ?></p>
            <p class="text-sm text-gray-600">3 hari ke depan</p>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-md max-w-7xl mx-auto overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex flex-col gap-4">
        <!-- Baris 1: Judul -->
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Pinjaman</h3>
        </div>

        <!-- Baris 2: Filter + Tombol -->
        <div class="flex flex-wrap gap-3 items-center justify-between">
            <!-- Bagian Kiri: Filter -->
            <div class="flex flex-wrap gap-3 items-center">
                <!-- Filter Status -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Status:</label>
                    <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <option value="all">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>

                <!-- Filter Akad -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Akad:</label>
                    <select id="filterAkad" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <option value="all">Semua Akad</option>
                        <option value="qard">Qard</option>
                        <option value="murabahah">Murabahah</option>
                        <option value="mudharabah">Mudharabah</option>
                    </select>
                </div>

                <!-- Search Nama -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Cari:</label>
                    <input type="text" id="searchNama" placeholder="Nama anggota..."
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm w-40">
                </div>

                <!-- Tombol Reset -->
                <button onclick="resetFilter()" class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm">
                    <i class="fas fa-refresh mr-1"></i>Reset
                </button>
            </div>

            <!-- Bagian Kanan: Tombol Tambah -->
            <div>
                <button onclick="openModal('financingModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i>Peminjaman Baru
                </button>
            </div>
        </div>
    </div>
</div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
               <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
    <?php foreach ($pembiayaan as $item): ?>
    <tr class="table-row" 
        data-status="<?= esc($item['status']) ?>" 
        data-akad="<?= esc($item['akad']) ?>" 
        data-nama="<?= esc(strtolower($item['nama_lengkap'])) ?>">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['id']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['nama_lengkap']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                <?= $item['akad'] === 'qard' ? 'bg-blue-100 text-blue-800' : 
                   ($item['akad'] === 'murabahah' ? 'bg-purple-100 text-purple-800' : 
                   'bg-orange-100 text-orange-800') ?>">
                <?= ucfirst($item['akad']) ?>
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($item['jml_pinjam'] ?? $item['jumlah'], 0, ',', '.') ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <?= isset($item['tenor']) ? esc($item['tenor']) . ' Bulan' : '-' ?>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <?php 
            if (!empty($item['tanggal']) && $item['tanggal'] != '0000-00-00') {
                echo date('d M Y', strtotime($item['tanggal']));
            } else {
                echo '-';
            }
            ?>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                <?= $item['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 
                   ($item['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                   ($item['status'] === 'lunas' ? 'bg-blue-100 text-blue-800' : 
                   'bg-red-100 text-red-800')) ?>">
                <?= ucfirst($item['status']) ?>
            </span>
        </td>
    </tr>
    <?php endforeach; ?>
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

    <!-- Modal Pengajuan Pembiayaan -->
    <div id="financingModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Pengajuan Pinjaman</h3>
            <form id="formPembiayaan" class="space-y-4">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Anggota *</label>
                        <div class="relative">
                            <input type="text" id="anggotaSearch" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Cari anggota..." autocomplete="off">
                            <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 max-h-60 overflow-y-auto hidden"></div>
                            <input type="hidden" id="id_anggota" name="id_anggota" required>
                        </div>
                        <div id="selectedAnggota" class="mt-2 p-2 bg-gray-50 rounded hidden">
                            <span class="text-sm font-medium" id="anggotaNama"></span>
                            <span class="text-xs text-gray-500 block" id="anggotaKtp"></span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Akad Syariah *</label>
                        <select name="akad" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            <option value="">Pilih Akad</option>
                            <option value="qard">Qard</option>
                            <option value="murabahah">Murabahah</option>
                            <option value="mudharabah">Mudharabah</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pinjam *</label>
                        <input type="number" name="jml_pinjam" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="0" min="100000" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tenor (Bulan) *</label>
                        <select name="tenor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            <option value="">Pilih Tenor</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                            <option value="24">24 Bulan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan Pinjaman *</label>
                    <textarea name="keperluan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="3" placeholder="Jelaskan keperluan pinjam..." required></textarea>
                </div>

                <!-- Tambahkan field tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                    <input type="date" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('financingModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ========== FILTER FUNCTIONS ==========
        function applyFilter() {
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase().trim();
            const akadFilter = document.getElementById('filterAkad').value.toLowerCase().trim();
            const searchFilter = document.getElementById('searchNama').value.toLowerCase().trim();
            
            const rows = document.querySelectorAll('.table-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const status = (row.getAttribute('data-status') || '').toLowerCase().trim();
                const akad = (row.getAttribute('data-akad') || '').toLowerCase().trim();
                const nama = (row.getAttribute('data-nama') || '').toLowerCase().trim();

                const statusMatch = statusFilter === 'all' || status === statusFilter;
                const akadMatch = akadFilter === 'all' || akad === akadFilter;
                const searchMatch = searchFilter === '' || nama.includes(searchFilter);
                
                if (statusMatch && akadMatch && searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
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

        
        // ========== MODAL FUNCTIONS ==========
        function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById(id).classList.remove("flex");
            resetForm();
        }

        function resetFilter() {
            document.getElementById('filterStatus').value = 'all';
            document.getElementById('filterAkad').value = 'all';
            document.getElementById('searchNama').value = '';
            applyFilter();
        }


        // ========== AUTOSEARCH FUNCTIONS ==========
        function setupAnggotaSearch() {
            const input = document.getElementById('anggotaSearch');
            const results = document.getElementById('anggotaResults');
            const hidden = document.getElementById('id_anggota');
            const selectedDiv = document.getElementById('selectedAnggota');
            const anggotaNama = document.getElementById('anggotaNama');
            const anggotaKtp = document.getElementById('anggotaKtp');

            if (!input || !results || !hidden) {
                console.error('Elemen autocomplete tidak ditemukan!');
                return;
            }

            input.addEventListener('input', function() {
                const q = this.value.trim();
                
                if (!q) {
                    results.innerHTML = '';
                    results.classList.add('hidden');
                    hidden.value = '';
                    selectedDiv.classList.add('hidden');
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

                        data.forEach(anggota => {
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2 hover:bg-emerald-100 cursor-pointer border-b border-gray-100';
                            div.innerHTML = `
                                <div class="font-medium">${anggota.nama_lengkap}</div>
                                <div class="text-xs text-gray-500">${anggota.no_ktp} - ${anggota.nomor_anggota || 'AGT-' + anggota.id_anggota}</div>
                            `;
                            
                            div.addEventListener('click', function() {
                                const selectedId = anggota.id_anggota || anggota.id;
                                input.value = anggota.nama_lengkap;
                                hidden.value = selectedId;
                                
                                anggotaNama.textContent = anggota.nama_lengkap;
                                anggotaKtp.textContent = anggota.no_ktp + ' - ' + (anggota.nomor_anggota || 'AGT-' + selectedId);
                                selectedDiv.classList.remove('hidden');
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

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.classList.add('hidden');
                }
            });
        }

        // ========== FORM SUBMISSION ==========
        document.getElementById('formPembiayaan').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateFormBeforeSubmit()) {
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            const hiddenInput = document.getElementById('id_anggota');
            const id_anggota = hiddenInput.value;
            const akad = document.querySelector('select[name="akad"]').value;
            const jml_pinjam = document.querySelector('input[name="jml_pinjam"]').value;
            const tenor = document.querySelector('select[name="tenor"]').value;
            const keperluan = document.querySelector('textarea[name="keperluan"]').value;
            const tanggal = document.querySelector('input[name="tanggal"]').value;
            
            // Validasi
            if (!id_anggota || !akad || !jml_pinjam || jml_pinjam < 100000 || !tanggal) {
                alert('Harap lengkapi semua field dengan benar!');
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengajukan...';
            submitBtn.disabled = true;

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
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    alert('Pembiayaan berhasil diajukan dan langsung aktif!');
                    closeModal('financingModal');
                    location.reload();
                } else {
                    alert('Gagal mengajukan pembiayaan: ' + (response.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error('Error fetch:', err);
                alert('Terjadi kesalahan jaringan: ' + err.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function validateFormBeforeSubmit() {
            const hiddenInput = document.getElementById('id_anggota');
            const id_anggota = hiddenInput ? hiddenInput.value : null;
            
            if (!id_anggota || id_anggota === 'undefined' || id_anggota === 'null' || id_anggota === '' || id_anggota === '0') {
                alert('ERROR: ID Anggota tidak valid!\n\nSilakan pilih anggota dari daftar yang muncul, jangan ketik manual.');
                return false;
            }
            
            if (isNaN(id_anggota)) {
                alert('ERROR: Format ID Anggota tidak valid!');
                return false;
            }
            
            return true;
        }

        // ========== INITIALIZATION ==========
        document.addEventListener('DOMContentLoaded', function() {
            setupAnggotaSearch();
            
            // Event listeners untuk filter
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            document.getElementById('filterAkad').addEventListener('change', applyFilter);
            document.getElementById('searchNama').addEventListener('input', applyFilter);
        });
    </script>   
</body>
</html>