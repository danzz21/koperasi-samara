<h2 class="text-2xl font-bold mb-4">Daftar Pinjaman Pending</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- DEBUG: Untuk melihat data yang diterima -->
<!-- <pre><?php // print_r($pending[0] ?? 'No data') ?></pre> -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <?php if (!empty($pending)): ?>
        <?php foreach($pending as $p): ?>
            <div class="bg-white rounded-lg shadow-lg border-l-4 border-yellow-500 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <!-- Header dengan Nama dan Status -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800"><?= esc($p['nama_lengkap'] ?? 'N/A') ?></h3>
                            <p class="text-sm text-gray-600">ID: <?= esc($p['nomor_anggota'] ?? 'N/A') ?></p>
                        </div>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
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
                                <?= ucfirst($p['jenis'] ?? '-') ?>
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium">
                                <?= date('d/m/Y', strtotime($p['tanggal'] ?? '')) ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Separator -->
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <!-- INFORMASI REKENING -->
                    <div class="mb-4">
                        <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-university mr-2 text-blue-500"></i>
                            Informasi Rekening Transfer
                        </h4>
                        
                        <div class="bg-blue-50 rounded-lg p-3 space-y-2">
                            <?php 
                            // Debug: cek apakah data ada
                            // echo "Bank: " . ($p['jenis_bank'] ?? 'NULL') . "<br>";
                            // echo "No Rek: " . ($p['no_rek'] ?? 'NULL') . "<br>";
                            // echo "Atas Nama: " . ($p['atasnama_rekening'] ?? 'NULL') . "<br>";
                            
                            // Periksa apakah data tidak kosong
                            $bank = isset($p['jenis_bank']) && trim($p['jenis_bank']) !== '' ? $p['jenis_bank'] : null;
                            $noRek = isset($p['no_rek']) && trim($p['no_rek']) !== '' ? $p['no_rek'] : null;
                            $atasNama = isset($p['atasnama_rekening']) && trim($p['atasnama_rekening']) !== '' ? $p['atasnama_rekening'] : null;
                            
                            if ($bank && $noRek): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Bank:</span>
                                    <span class="font-bold text-blue-700"><?= esc($bank) ?></span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-700">No. Rekening:</span>
                                    <span class="font-bold text-gray-800"><?= esc($noRek) ?></span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Atas Nama:</span>
                                    <span class="font-medium"><?= esc($atasNama ?? $p['nama_lengkap']) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                    <span class="text-yellow-700 font-medium">
                                        Data rekening belum diisi<br>
                                        <small class="text-xs">
                                            Bank: <?= $bank ? esc($bank) : 'kosong' ?> | 
                                            No Rek: <?= $noRek ? esc($noRek) : 'kosong' ?>
                                        </small>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Kontak Anggota -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <h4 class="font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-green-500"></i>
                            Kontak Anggota
                        </h4>
                        <p class="text-gray-700 flex items-center">
                            <i class="fas fa-mobile-alt mr-2"></i>
                            <?= esc($p['no_hp'] ?? '-') ?>
                        </p>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex space-x-2">
                        <!-- Tombol Detail Modal -->
                        <button onclick="showDetail('<?= $p['jenis'] ?>', <?= $p['id'] ?>)" 
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                        
                        <!-- Tombol Verifikasi - PERBAIKI: pakai no_rek bukan no_rekening -->
                        <a href="<?= base_url("admin/pinjaman/verifikasi/{$p['jenis']}/{$p['id']}") ?>" 
                           onclick="return confirmVerifikasi('<?= esc($p['nama_lengkap']) ?>', '<?= esc($p['jenis_bank'] ?? '') ?>', '<?= esc($p['no_rek'] ?? '') ?>')"
                           class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm text-center">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </a>
                        
                        <!-- Tombol Tolak -->
                        <a href="<?= base_url("admin/pinjaman/tolak/{$p['jenis']}/{$p['id']}") ?>" 
                           onclick="return confirm('Tolak pengajuan pinjaman <?= esc($p['nama_lengkap']) ?>?')"
                           class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg font-medium transition duration-300 text-sm text-center">
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


<!-- Modal untuk Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Detail Pengajuan Pinjaman</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi konfirmasi dengan informasi rekening
function confirmVerifikasi(nama, bank, noRek) {
    let message = `Setujui pengajuan pinjaman ${nama}?`;
    
    if (bank && noRek) {
        message += `\n\nDana akan ditransfer ke:\nBank: ${bank}\nNo. Rekening: ${noRek}`;
    } else if (!bank || !noRek) {
        message += `\n\n⚠ PERHATIAN: Data rekening belum lengkap!\nBank: ${bank || 'Belum diisi'}\nNo. Rekening: ${noRek || 'Belum diisi'}`;
    }
    
    return confirm(message);
}

// Fungsi untuk menampilkan detail pinjaman
function showDetail(jenis, id) {
    // Tampilkan loading
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-600">Memuat detail pinjaman...</p>
        </div>
    `;
    
    // Tampilkan modal
    document.getElementById('detailModal').classList.remove('hidden');
    
    // AJAX untuk mengambil detail
    fetch(`<?= base_url('admin/pinjaman/detail/') ?>${jenis}/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const d = data.data;
                
                // Format jumlah pinjaman
                const jumlahPinjaman = d.jml_pinjam || 0;
                const formattedAmount = new Intl.NumberFormat('id-ID').format(jumlahPinjaman);
                
                // PERBAIKAN: Pakai d.no_rek bukan d.no_rekening
                const bank = d.jenis_bank || 'Belum diisi';
                const noRek = d.no_rek || '-'; // PERHATIAN: pakai no_rek
                const atasNama = d.atasnama_rekening || d.nama_lengkap || '-';
                
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <!-- Informasi Anggota -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-gray-700 mb-3">Informasi Anggota</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                                    <p class="font-semibold">${d.nama_lengkap || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nomor Anggota</p>
                                    <p class="font-semibold">${d.nomor_anggota || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. HP</p>
                                    <p class="font-semibold">${d.no_hp || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-semibold">${d.email || '-'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Rekening -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-bold text-blue-700 mb-3 flex items-center">
                                <i class="fas fa-university mr-2"></i>
                                Informasi Rekening Transfer
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Bank</p>
                                    <p class="font-bold text-lg text-blue-700">${bank}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Rekening</p>
                                    <p class="font-bold text-lg text-gray-800">${noRek}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600">Atas Nama Rekening</p>
                                    <p class="font-medium">${atasNama}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Pinjaman -->
                        <div class="bg-emerald-50 p-4 rounded-lg">
                            <h4 class="font-bold text-emerald-700 mb-3">Informasi Pinjaman</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-600">Jumlah Pinjaman</p>
                                    <p class="font-bold text-lg text-emerald-600">Rp ${formattedAmount}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Angsuran per Bulan</p>
                                    <p class="font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(d.jml_angsuran || 0)}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Jenis Akad</p>
                                    <p class="font-semibold">${jenis}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                                    <p class="font-medium">${new Date(d.tanggal).toLocaleDateString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Gagal Memuat Data</h4>
                        <p class="text-gray-600">${data.message || 'Terjadi kesalahan saat mengambil data'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Koneksi Error</h4>
                    <p class="text-gray-600">Gagal terhubung ke server. Silakan coba lagi.</p>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Tutup modal jika klik di luar
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>