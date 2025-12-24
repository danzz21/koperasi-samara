<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pinjaman Pending</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Modal styling - gunakan nama yang unik */
        .pinjaman-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .pinjaman-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .pinjaman-modal-box {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            margin: 1rem;
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .pinjaman-modal-overlay.active .pinjaman-modal-box {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar Pinjaman Pending</h2>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php if (!empty($pending)): ?>
                <?php foreach($pending as $p): ?>
                    <div class="bg-white rounded-lg shadow-lg border-l-4 border-yellow-500 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800"><?= esc($p['nama_lengkap'] ?? 'N/A') ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-id-card mr-1"></i>
                                        ID: <?= esc($p['nomor_anggota'] ?? 'N/A') ?>
                                    </p>
                                </div>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= strtoupper($p['status'] ?? 'PENDING') ?>
                                </span>
                            </div>
                            
                            <!-- Informasi Utama -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Jumlah Pinjaman:</span>
                                    <span class="font-bold text-lg text-emerald-600">
                                        Rp <?= number_format($p['jml_pinjam'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Angsuran per Bulan:</span>
                                    <span class="font-semibold">
                                        Rp <?= number_format($p['jml_angsuran'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Jenis Akad:</span>
                                    <span class="font-semibold text-blue-600">
                                        <?= strtoupper($p['jenis'] ?? '-') ?>
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tanggal Pengajuan:</span>
                                    <span class="font-medium">
                                        <?= date('d/m/Y', strtotime($p['tanggal'] ?? '')) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="flex space-x-2">
                                <button type="button" onclick="showPinjamanDetail('<?= $p['jenis'] ?>', <?= $p['id'] ?>)" 
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm flex items-center justify-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                                
                                <a href="<?= base_url("admin/pinjaman/verifikasi/{$p['jenis']}/{$p['id']}") ?>"
                                   onclick="return confirmPinjamanVerifikasi('<?= esc($p['nama_lengkap']) ?>', '<?= esc($p['jenis_bank'] ?? '') ?>', '<?= esc($p['no_rek'] ?? '') ?>')"
                                   class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm flex items-center justify-center">
                                    <i class="fas fa-check mr-1"></i>Setujui
                                </a>
                                
                                <a href="<?= base_url("admin/pinjaman/tolak/{$p['jenis']}/{$p['id']}") ?>"
                                   onclick="return confirm('Tolak pengajuan <?= esc(addslashes($p['nama_lengkap'])) ?>?')"
                                   class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm flex items-center justify-center">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3">
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak ada pengajuan pending</h3>
                        <p class="text-gray-500">Semua pengajuan pinjaman telah diproses.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal untuk Detail -->
    <div id="pinjamanDetailModal" class="pinjaman-modal-overlay">
        <div class="pinjaman-modal-box">
            <div class="p-6">
                <!-- Header Modal -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-file-invoice mr-2 text-blue-500"></i>
                        Detail Pengajuan Pinjaman
                    </h3>
                    <button type="button" id="closePinjamanModalBtn" 
                            class="text-gray-500 hover:text-gray-700 focus:outline-none transition duration-300 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Content Modal -->
                <div id="pinjamanModalContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
    // ========== GLOBAL VARIABLES ==========
    let isPinjamanModalOpen = false;

    // ========== MODAL FUNCTIONS ==========
    function showPinjamanDetail(jenis, id) {
        const modal = document.getElementById('pinjamanDetailModal');
        const modalContent = document.getElementById('pinjamanModalContent');
        
        if (!modal || !modalContent) {
            console.error('Modal elements not found');
            return;
        }
        
        // Tampilkan loading state
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                <p class="text-gray-600">Memuat detail pinjaman...</p>
            </div>
        `;
        
        // Tampilkan modal
        modal.classList.add('active');
        isPinjamanModalOpen = true;
        document.body.style.overflow = 'hidden';
        
        // AJAX untuk mengambil detail
        fetch(`<?= base_url('admin/pinjaman/detail/') ?>${jenis}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const d = data.data;
                const formattedAmount = new Intl.NumberFormat('id-ID').format(d.jml_pinjam || 0);
                const formattedAngsuran = new Intl.NumberFormat('id-ID').format(d.jml_angsuran || 0);
                
                // Format tanggal
                let tanggalFormatted = '-';
                if (d.tanggal) {
                    const date = new Date(d.tanggal);
                    tanggalFormatted = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                }
                
                modalContent.innerHTML = `
                    <div class="space-y-4">
                        <!-- Status Badge -->
                        <div class="text-center mb-4">
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                STATUS: ${d.status ? d.status.toUpperCase() : 'PENDING'}
                            </span>
                        </div>
                        
                        <!-- Informasi Anggota -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Informasi Anggota
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-800">${d.nama_lengkap || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nomor Anggota</p>
                                    <p class="font-semibold text-gray-800">${d.nomor_anggota || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">No. HP</p>
                                    <p class="font-semibold text-gray-800">${d.no_hp || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Email</p>
                                    <p class="font-semibold text-gray-800">${d.email || '-'}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600 mb-1">Alamat</p>
                                    <p class="font-medium text-gray-800">${d.alamat || '-'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Rekening -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="font-bold text-blue-700 mb-3 flex items-center">
                                <i class="fas fa-university mr-2"></i>
                                Informasi Rekening Transfer
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Bank</p>
                                    <p class="font-bold text-lg text-blue-700">${d.jenis_bank || 'Belum diisi'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">No. Rekening</p>
                                    <p class="font-bold text-lg text-gray-800">${d.no_rek || '-'}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600 mb-1">Atas Nama Rekening</p>
                                    <p class="font-medium text-gray-800">${d.atasnama_rekening || d.nama_lengkap || '-'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Pinjaman -->
                        <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200">
                            <h4 class="font-bold text-emerald-700 mb-3">Informasi Pinjaman</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Jumlah Pinjaman</p>
                                    <p class="font-bold text-lg text-emerald-600">Rp ${formattedAmount}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Angsuran per Bulan</p>
                                    <p class="font-semibold text-lg text-gray-800">Rp ${formattedAngsuran}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Jenis Akad</p>
                                    <p class="font-semibold text-blue-600">${jenis.toUpperCase()}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Tanggal Pengajuan</p>
                                    <p class="font-medium text-gray-800">${tanggalFormatted}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <h4 class="font-bold text-yellow-700 mb-3">Aksi</h4>
                            <div class="flex space-x-3">
                                <a href="<?= base_url('admin/pinjaman/verifikasi/') ?>${jenis}/${id}"
                                   onclick="return confirmPinjamanVerifikasi('${d.nama_lengkap || ''}', '${d.jenis_bank || ''}', '${d.no_rek || ''}')"
                                   class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-4 rounded-lg font-medium transition duration-300 text-center">
                                    <i class="fas fa-check mr-1"></i>Setujui
                                </a>
                                <a href="<?= base_url('admin/pinjaman/tolak/') ?>${jenis}/${id}"
                                   onclick="return confirm('Tolak pengajuan ${d.nama_lengkap || ''}?')"
                                   class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg font-medium transition duration-300 text-center">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                throw new Error(data.message || 'Data tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error fetching detail:', error);
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Gagal Memuat Data</h4>
                    <p class="text-gray-600">${error.message}</p>
                    <button onclick="closePinjamanModal()" 
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            `;
        });
    }

    function closePinjamanModal() {
        const modal = document.getElementById('pinjamanDetailModal');
        if (modal) {
            modal.classList.remove('active');
            isPinjamanModalOpen = false;
            document.body.style.overflow = 'auto';
        } else {
            console.error('Modal element not found');
        }
    }

    // ========== CONFIRMATION FUNCTION ==========
    function confirmPinjamanVerifikasi(nama, bank, noRek) {
        let message = `Setujui pengajuan pinjaman ${nama}?\n\n`;
        
        if (bank && bank !== 'Belum diisi' && noRek) {
            message += `✅ Data rekening lengkap:\nBank: ${bank}\nNo. Rekening: ${noRek}\n\nDana akan ditransfer ke rekening tersebut.`;
        } else {
            message += `⚠ PERHATIAN: Data rekening belum lengkap!\nBank: ${bank || 'Belum diisi'}\nNo. Rekening: ${noRek || 'Belum diisi'}\n\nLanjutkan verifikasi?`;
        }
        
        return confirm(message);
    }

    // ========== EVENT LISTENERS ==========
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('pinjamanDetailModal');
        const closeModalBtn = document.getElementById('closePinjamanModalBtn');
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isPinjamanModalOpen) {
                closePinjamanModal();
            }
        });
        
        // Close when clicking outside modal content
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePinjamanModal();
                }
            });
        }
        
        // Close button event listener
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closePinjamanModal);
        }
    });

    // ========== EXPORT FUNCTIONS TO GLOBAL SCOPE ==========
    window.showPinjamanDetail = showPinjamanDetail;
    window.closePinjamanModal = closePinjamanModal;
    window.confirmPinjamanVerifikasi = confirmPinjamanVerifikasi;
    </script>
</body>
</html>