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
        #detailModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        #detailModal.show {
            display: flex;
        }
        
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Animation for modal */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        #detailModal {
            animation: fadeIn 0.3s ease-out;
        }
        
        #detailModal > div {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar Pinjaman Pending</h2>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-sm">Total Pending</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $pendingPinjamanCount ?></p>
                    </div>
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-sm">Menunggu Verifikasi</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $pendingPinjamanCount ?></p>
                    </div>
                    <i class="fas fa-user-check text-blue-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-sm">Siap Diproses</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $pendingPinjamanCount ?></p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>
        </div>

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
                            <div class="space-y-3 mb-4">
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
                            
                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <!-- INFORMASI REKENING TRANSFER -->
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-university mr-2 text-blue-500"></i>
                                    Informasi Rekening Transfer
                                </h4>
                                
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                    <?php if (!empty($p['jenis_bank']) && !empty($p['no_rek'])): ?>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">Bank:</span>
                                                <span class="font-bold text-blue-700"><?= esc($p['jenis_bank']) ?></span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">No. Rekening:</span>
                                                <span class="font-bold text-gray-800"><?= esc($p['no_rek']) ?></span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">Atas Nama:</span>
                                                <span class="font-medium"><?= esc($p['atasnama_rekening'] ?? $p['nama_lengkap']) ?></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-2">
                                            <i class="fas fa-exclamation-triangle text-red-500 text-lg mb-1"></i>
                                            <p class="text-red-700 font-medium text-sm">
                                                Data rekening tidak tersedia
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- KONTAK ANGGOTA -->
                            <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-100">
                                <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-address-card mr-2 text-green-500"></i>
                                    Kontak Anggota
                                </h4>
                                <div class="space-y-2">
                                    <?php if (!empty($p['no_hp'])): ?>
                                        <p class="text-gray-700 flex items-center">
                                            <i class="fas fa-phone mr-2"></i>
                                            <?= esc($p['no_hp']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($p['email'])): ?>
                                        <p class="text-gray-700 flex items-center text-sm">
                                            <i class="fas fa-envelope mr-2"></i>
                                            <?= esc($p['email']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (empty($p['no_hp']) && empty($p['email'])): ?>
                                        <p class="text-gray-500 italic text-sm">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Tidak ada kontak tersedia
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="flex space-x-2">
                                <button onclick="showDetail('<?= $p['jenis'] ?>', <?= $p['id'] ?>)"
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm flex items-center justify-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                                
                                <a href="<?= base_url("admin/pinjaman/verifikasi/{$p['jenis']}/{$p['id']}") ?>"
                                   onclick="return confirmVerifikasi('<?= esc($p['nama_lengkap']) ?>', '<?= esc($p['jenis_bank'] ?? '') ?>', '<?= esc($p['no_rek'] ?? '') ?>')"
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
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="modal-content bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6">
                <!-- Header Modal -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-file-invoice mr-2 text-blue-500"></i>
                        Detail Pengajuan Pinjaman
                    </h3>
                    <button type="button" onclick="closeModal()" 
                            class="text-gray-500 hover:text-gray-700 focus:outline-none transition duration-300">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <!-- Content Modal -->
                <div id="modalContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
                
                <!-- Footer Modal -->
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-300">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ========== GLOBAL VARIABLES ==========
    let isModalOpen = false;
    let currentModal = null;

    // ========== MODAL FUNCTIONS ==========
    function showDetail(jenis, id) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        
        if (!modal || !modalContent) return;
        
        // Tampilkan loading state
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                <p class="text-gray-600">Memuat detail pinjaman...</p>
            </div>
        `;
        
        // Tampilkan modal
        modal.classList.remove('hidden');
        modal.classList.add('show');
        isModalOpen = true;
        currentModal = modal;
        document.body.style.overflow = 'hidden';
        
        // AJAX untuk mengambil detail
        fetch(`<?= base_url('admin/pinjaman/detail/') ?>${jenis}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
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
                                   onclick="return confirmVerifikasi('${d.nama_lengkap || ''}', '${d.jenis_bank || ''}', '${d.no_rek || ''}')"
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
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Gagal Memuat Data</h4>
                    <p class="text-gray-600">${error.message}</p>
                    <button onclick="closeModal()" 
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            `;
        });
    }

    function closeModal() {
        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.classList.remove('show');
            modal.classList.add('hidden');
            isModalOpen = false;
            currentModal = null;
            document.body.style.overflow = 'auto';
        }
    }

    // ========== CONFIRMATION FUNCTION ==========
    function confirmVerifikasi(nama, bank, noRek) {
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
        const modal = document.getElementById('detailModal');
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isModalOpen && currentModal) {
                closeModal();
            }
        });
        
        // Close when clicking outside modal content
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
        
        // Add fade-in animation for flash messages
        const flashMessages = document.querySelectorAll('.animate-fade-in');
        flashMessages.forEach((msg, index) => {
            setTimeout(() => {
                msg.style.opacity = '1';
                msg.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Initialize flash messages with initial styles
        flashMessages.forEach(msg => {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            msg.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        });
    });

    // ========== EXPORT FUNCTIONS TO GLOBAL SCOPE ==========
    window.showDetail = showDetail;
    window.closeModal = closeModal;
    window.confirmVerifikasi = confirmVerifikasi;
    </script>
</body>
</html>