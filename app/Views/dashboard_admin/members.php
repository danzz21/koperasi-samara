<!-- Header Halaman Anggota -->
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?? 'Manajemen Anggota' ?></h2>
    <p class="text-gray-600">Kelola data anggota aktif dan pendaftaran anggota baru</p>
</div>

<!-- Table Container -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
            <h3 class="text-xl font-bold text-gray-800">Daftar Anggota</h3>
            <button onclick="openModal('memberModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center text-sm font-semibold shadow-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Anggota
            </button>
        </div>

        <form method="GET" action="" onsubmit="return false;">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="Cari nama, KTP..."
                    autocomplete="off"
                    class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
                />
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. KTP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="anggotaTableBody">
                <?php if (!isset($search) || $search === ''): ?>
                    <?php $nomor = 1; foreach ($anggota as $data): 
                        $id_anggota = $data['id_anggota'];
                        $id_user = $data['id_user'] ?? null;
                        $nama = ucwords($data['nama_lengkap']);
                        $ktp = $data['no_ktp'];
                        $status = $data['status'] ?? 'Menunggu Verifikasi';
                        $tanggal = isset($data['tanggal_daftar']) ? date("d M Y", strtotime($data['tanggal_daftar'])) : '-';
                        $urlDetail = base_url('admin/detail-anggota/' . $id_anggota);
                        
                        $isAktif = strtolower($status) == 'aktif';
                        $badge = $isAktif ?
                            "<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>$status</span>" :
                            "<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>$status</span>";
                        
                        $canReset = !empty($id_user);
                        $resetClass = $canReset ? 'text-amber-600 hover:text-amber-800 hover:bg-amber-50 p-1.5 rounded transition-colors' : 'text-gray-300 cursor-not-allowed p-1.5';
                        $resetTitle = $canReset ? 'Reset Password' : 'User tidak ditemukan';
                    ?>
                    <tr class='hover:bg-gray-50 transition duration-150 cursor-pointer' onclick="window.location.href='<?= $urlDetail ?>'">
                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $nomor++ ?></td>
                        <td class='px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900'><?= $nama ?></td>
                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'><?= $ktp ?></td>
                        <td class='px-6 py-4 whitespace-nowrap'><?= $badge ?></td>
                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'><?= $tanggal ?></td>
                        <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-1'>
                            <button type="button"
                                onclick="event.stopPropagation(); openEditMemberModal(this)"
                                class='text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded transition-colors'
                                data-id="<?= $id_anggota ?>"
                                data-name="<?= htmlspecialchars($data['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?>"
                                data-ktp="<?= htmlspecialchars($data['no_ktp'], ENT_QUOTES, 'UTF-8') ?>"
                                data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"
                                data-tanggal="<?= htmlspecialchars($data['tanggal_daftar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                title="Edit">
                                <i class='fas fa-edit'></i>
                            </button>
                            
                            <button onclick="event.stopPropagation(); showDeleteModal(<?= $id_anggota ?>)" 
                                class='text-red-600 hover:text-red-900 p-1.5 hover:bg-red-50 rounded transition-colors' 
                                title="Hapus Anggota">
                                <i class='fas fa-trash'></i>
                            </button>
                            
                            <button onclick="event.stopPropagation(); window.location.href='<?= base_url('admin/detail-anggota/' . $id_anggota) ?>'" 
                                class='text-emerald-600 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded transition-colors' 
                                title="Detail">
                                <i class='fas fa-eye'></i>
                            </button>
                            
                            <?php if ($canReset): ?>
                            <button onclick="event.stopPropagation(); resetPassword(<?= $id_user ?>, '<?= htmlspecialchars($nama) ?>')" class='<?= $resetClass ?>' title="<?= $resetTitle ?>">
                                <i class='fas fa-key'></i>
                            </button>
                            <?php else: ?>
                            <button class='<?= $resetClass ?>' title="<?= $resetTitle ?>" disabled>
                                <i class='fas fa-key'></i>
                            </button>
                            <?php endif; ?>
                            
                            <button onclick="event.stopPropagation(); toggleMemberStatus(<?= $id_anggota ?>, '<?= htmlspecialchars($nama) ?>', '<?= $status ?>')" 
                                class='p-1.5 rounded transition-colors <?= strtolower($status) == 'nonaktif' ? 'text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50' : 'text-orange-600 hover:text-orange-900 hover:bg-orange-50' ?>' 
                                title="<?= strtolower($status) == 'nonaktif' ? 'Aktifkan Anggota' : 'Nonaktifkan Anggota' ?>">
                                <i class='fas <?= strtolower($status) == 'nonaktif' ? 'fa-check-circle' : 'fa-times-circle' ?>'></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH ANGGOTA -->
<div id="memberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tambah Anggota Baru</h3>
        <form id="formMember" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP *</label>
                <input type="text" name="no_ktp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                <input type="text" name="no_telp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                <textarea name="alamat" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" rows="3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Diri</label>
                <input type="file" name="foto_diri" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto KTP</label>
                <input type="file" name="foto_ktp" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal('memberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors text-sm font-semibold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT ANGGOTA -->
<div id="editMemberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="editMemberTitle">Edit Data Anggota</h3>
        <form id="editMemberForm" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id_anggota" id="editMemberId" />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" id="editNamaLengkap" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP *</label>
                <input type="text" name="no_ktp" id="editNoKtp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="Aktif">Aktif</option>
                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Daftar</label>
                <input type="date" name="tanggal_daftar" id="editTanggalDaftar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm" />
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal('editMemberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors text-sm font-semibold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI RESET PASSWORD -->
<div id="resetPasswordModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Reset Password</h3>
        <p class="text-gray-600 text-sm mb-6" id="resetPasswordText">
            Apakah Anda yakin ingin mereset password anggota?
        </p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeModal('resetPasswordModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="confirmResetPassword()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold" id="confirmResetBtn">
                Reset Password
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI STATUS ANGGOTA -->
<div id="statusMemberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="statusMemberTitle">Ubah Status Anggota</h3>
        <p class="text-gray-600 text-sm mb-6" id="statusMemberText">
            Apakah Anda yakin ingin mengubah status anggota?
        </p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeModal('statusMemberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="confirmToggleStatus()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold" id="confirmStatusBtn">
                Ubah Status
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS ANGGOTA -->
<div id="deleteMemberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="deleteMemberTitle">Hapus Anggota</h3>
        
        <div id="deleteMemberContent"></div>

        <div id="deleteLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-4"></i>
            <p class="text-gray-600 text-sm">Memuat data anggota...</p>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('deleteMemberModal')" 
                        class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                    Batal
                </button>
                <div class="flex space-x-2 flex-1">
                    <button type="button" onclick="confirmDeleteMember(false)" 
                            class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold">
                        Nonaktifkan
                    </button>
                    <button type="button" onclick="confirmDeleteMember(true)" 
                            class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors text-sm font-semibold">
                        Hapus Permanen
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Nonaktifkan:</strong> Data tetap tersimpan.<br>
                <strong>Hapus Permanen:</strong> Data dihapus selamanya.
            </p>
        </div>
    </div>
</div>

<script>
let currentUserId = null;
let currentUserName = null;

function resetPassword(userId, userName) {
    if (userId === null || userId === undefined || userId === 'null' || userId === 'undefined') {
        alert('Error: User ID tidak valid untuk ' + userName);
        return;
    }

    currentUserId = userId;
    currentUserName = userName;
    
    const resetText = document.getElementById('resetPasswordText');
    if (resetText) {
        resetText.innerHTML = 
            `Apakah Anda yakin ingin mereset password untuk:<br>
             <strong>${userName}</strong><br><br>
             User ID: <strong>${userId}</strong><br>
             Password akan dikembalikan ke: <strong>123</strong>`;
    }
    
    openModal('resetPasswordModal');
}

function confirmResetPassword() {
    if (!currentUserId) {
        alert('Error: User ID tidak ditemukan di frontend');
        return;
    }

    const button = document.getElementById('confirmResetBtn');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    button.disabled = true;

    const formData = new FormData();
    formData.append('user_id', currentUserId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('admin/reset-password') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ Password berhasil direset ke "123"');
            closeModal('resetPasswordModal');
        } else {
            alert('❌ ' + (data.message || 'Gagal mereset password'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('❌ Terjadi kesalahan jaringan');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

let currentDeleteMemberId = null;

function showDeleteModal(memberId) {
    currentDeleteMemberId = memberId;
    
    const contentDiv = document.getElementById('deleteMemberContent');
    const loadingDiv = document.getElementById('deleteLoading');
    
    contentDiv.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    
    const detailsUrl = '<?= base_url("admin/get-member-details/") ?>' + memberId;
    
    fetch(detailsUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.classList.add('hidden');
        contentDiv.classList.remove('hidden');
        
        if (data.status === 'success' && data.data) {
            const anggota = data.data.anggota;
            const summary = data.data.summary;
            const totalData = data.data.total_data_terkait || 0;
            
            let summaryHtml = '';
            if (totalData > 0) {
                let detailItems = [];
                if (summary.simpanan_pokok > 0) detailItems.push(`${summary.simpanan_pokok} simpanan pokok`);
                if (summary.simpanan_wajib > 0) detailItems.push(`${summary.simpanan_wajib} simpanan wajib`);
                if (summary.simpanan_sukarela > 0) detailItems.push(`${summary.simpanan_sukarela} simpanan sukarela`);
                if (summary.pembiayaan_aktif > 0) detailItems.push(`${summary.pembiayaan_aktif} pembiayaan aktif`);
                if (summary.pembayaran_pending > 0) detailItems.push(`${summary.pembayaran_pending} pembayaran pending`);
                
                summaryHtml = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                        <p class="text-xs text-yellow-800 font-semibold mb-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Anggota ini memiliki ${totalData} data terkait
                        </p>
                        <ul class="text-xs text-yellow-700 ml-4 list-disc">
                            ${detailItems.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }
            
            contentDiv.innerHTML = `
                ${summaryHtml}
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Detail Anggota:</p>
                    <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                        <table class="text-xs w-full">
                            <tr>
                                <td class="py-1 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-1"><strong>${anggota.nama}</strong></td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">No. Anggota</td>
                                <td class="py-1">${anggota.nomor_anggota}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">Email</td>
                                <td class="py-1">${anggota.email}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">Status</td>
                                <td class="py-1">
                                    <span class="px-2 py-0.5 text-[10px] rounded-full ${anggota.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${anggota.status}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
        } else {
            contentDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-xs text-red-700">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        ${data.message || 'Gagal memuat data'}
                    </p>
                </div>
            `;
        }
    })
    .catch(error => {
        loadingDiv.classList.add('hidden');
        contentDiv.classList.remove('hidden');
        contentDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-3">
                <p class="text-xs text-red-700 font-semibold">Error: ${error.message}</p>
            </div>
        `;
    });
    
    openModal('deleteMemberModal');
}

function confirmDeleteMember(hardDelete = false) {
    if (!currentDeleteMemberId) return;
    
    const confirmText = hardDelete ? 
        'Apakah Anda YAKIN ingin menghapus PERMANEN?' :
        'Apakah Anda yakin ingin menonaktifkan?';
    
    if (!confirm(confirmText)) return;
    
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '<?= csrf_hash() ?>';
    
    const formData = new FormData();
    formData.append('member_id', currentDeleteMemberId);
    formData.append('hard_delete', hardDelete);
    formData.append('<?= csrf_token() ?>', csrfToken);
    
    fetch('<?= base_url("admin/delete-anggota") ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan jaringan');
    });
}

let currentMemberId = null;
let currentMemberName = null;
let currentMemberStatus = null;

function toggleMemberStatus(memberId, memberName, currentStatus) {
    currentMemberId = memberId;
    currentMemberName = memberName;
    currentMemberStatus = currentStatus.toLowerCase();
    
    const isCurrentlyActive = currentMemberStatus === 'aktif';
    const newStatus = isCurrentlyActive ? 'nonaktif' : 'aktif';
    
    const statusTitle = document.getElementById('statusMemberTitle');
    const statusText = document.getElementById('statusMemberText');
    const confirmBtn = document.getElementById('confirmStatusBtn');
    
    if (statusTitle && statusText && confirmBtn) {
        statusTitle.textContent = isCurrentlyActive ? 'Nonaktifkan Anggota' : 'Aktifkan Anggota';
        statusText.innerHTML = 
            `Apakah Anda yakin ingin <strong>${isCurrentlyActive ? 'menonaktifkan' : 'mengaktifkan'}</strong> anggota:<br>
             <strong>${memberName}</strong><br><br>
             ID Anggota: <strong>${memberId}</strong><br>
             Status saat ini: <strong>${currentStatus}</strong><br>
             Status akan diubah menjadi: <strong>${newStatus}</strong>`;
        
        confirmBtn.textContent = isCurrentlyActive ? 'Nonaktifkan' : 'Aktifkan';
        confirmBtn.className = isCurrentlyActive ? 
            'flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold' :
            'flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors text-sm font-semibold';
    }
    
    openModal('statusMemberModal');
}

function confirmToggleStatus() {
    if (!currentMemberId) {
        alert('Error: Member ID tidak ditemukan');
        return;
    }

    const button = document.getElementById('confirmStatusBtn');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    button.disabled = true;

    const formData = new FormData();
    formData.append('member_id', currentMemberId);
    formData.append('current_status', currentMemberStatus);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('admin/toggle-member-status') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ Status anggota berhasil diubah!');
            closeModal('statusMemberModal');
            location.reload();
        } else {
            alert('❌ ' + (data.message || 'Gagal mengubah status anggota'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('❌ Terjadi kesalahan jaringan');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function openEditMemberModal(button) {
    const form = document.getElementById('editMemberForm');
    if (!form) return;

    const id = button.getAttribute('data-id') || '';
    const name = button.getAttribute('data-name') || '';
    const ktp = button.getAttribute('data-ktp') || '';
    const status = button.getAttribute('data-status') || '';
    const tanggal = button.getAttribute('data-tanggal') || '';

    document.getElementById('editMemberId').value = id;
    document.getElementById('editNamaLengkap').value = name;
    document.getElementById('editNoKtp').value = ktp;
    document.getElementById('editStatus').value = status;
    document.getElementById('editTanggalDaftar').value = tanggal;
    document.getElementById('editMemberTitle').textContent = 'Edit Data Anggota #' + id;
    form.action = '<?= base_url('admin/update-anggota') ?>/' + id;

    openModal('editMemberModal');
}

function openEditMemberModalFromData(data) {
    const form = document.getElementById('editMemberForm');
    if (!form) return;

    document.getElementById('editMemberId').value = data.id || '';
    document.getElementById('editNamaLengkap').value = data.name || '';
    document.getElementById('editNoKtp').value = data.ktp || '';
    document.getElementById('editStatus').value = data.status || '';
    document.getElementById('editTanggalDaftar').value = data.tanggal || '';
    document.getElementById('editMemberTitle').textContent = 'Edit Data Anggota #' + (data.id || '');
    form.action = '<?= base_url('admin/update-anggota') ?>/' + (data.id || '');

    openModal('editMemberModal');
}

function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove("hidden");
        el.classList.add("flex");
    }
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add("hidden");
        el.classList.remove("flex");
    }
}

document.getElementById('editMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async res => {
        const text = await res.text();
        if (res.redirected) {
            window.location.href = res.url;
            return null;
        }
        return text;
    })
    .then(data => {
        if (data === null) return;
        if (data.includes('success') || data.includes('Data anggota berhasil diperbarui')) {
            alert('Data anggota berhasil diperbarui!');
            closeModal('editMemberModal');
            location.reload();
        } else {
            alert('Gagal menyimpan perubahan.');
        }
    })
    .catch(err => {
        console.error('Edit error:', err);
        alert('Terjadi kesalahan jaringan.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('formMember').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;

    fetch('<?= base_url('admin/dashboard_admin/members/save') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Anggota berhasil ditambahkan!');
            closeModal('memberModal');
            location.reload();
        } else {
            alert(data.message || 'Gagal menyimpan data.');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan jaringan.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Live search anggota
const searchInput = document.getElementById('searchInput');
const anggotaTableBody = document.getElementById('anggotaTableBody');
let searchTimeout;

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        searchTimeout = setTimeout(() => {
            if (query.length === 0) {
                location.reload();
                return;
            }
            
            fetch('/admin/search-anggota?q=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    anggotaTableBody.innerHTML = '';
                    if (data.length === 0) {
                        anggotaTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>`;
                        return;
                    }
                    data.forEach(item => {
                        let badge = (item.status.toLowerCase() === 'aktif')
                            ? `<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>${item.status}</span>`
                            : `<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>${item.status}</span>`;
                        
                        const userId = item.id_user;
                        const anggotaId = item.id_anggota;
                        const canReset = userId !== null && userId !== undefined;
                        const resetClass = canReset ? 'text-amber-600 hover:text-amber-800 hover:bg-amber-50 p-1.5 rounded transition-colors' : 'text-gray-300 cursor-not-allowed p-1.5';
                        const resetTitle = canReset ? 'Reset Password' : 'User tidak ditemukan';
                        
                        anggotaTableBody.innerHTML += `
                            <tr class='hover:bg-gray-50 transition duration-150 cursor-pointer' onclick="window.location.href='${item.urlDetail}'">
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${anggotaId}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900'>${item.nama_lengkap}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>${item.no_ktp}</td>
                                <td class='px-6 py-4 whitespace-nowrap'>${badge}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>${item.tanggal_daftar}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-1'>
                                    <button type="button" onclick="event.stopPropagation(); openEditMemberModalFromData({id:${anggotaId}, name:'${item.nama_lengkap.replace(/'/g, "\\'")}', ktp:'${(item.no_ktp || '').replace(/'/g, "\\'")}', status:'${(item.status || '').replace(/'/g, "\\'")}', tanggal:'${(item.tanggal_daftar || '').replace(/'/g, "\\'")}'});" class='text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded transition-colors' title="Edit">
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); window.location.href='${item.urlDetail}'" class='text-emerald-600 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded transition-colors' title="Detail">
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    ${canReset ? 
                                        `<button onclick="event.stopPropagation(); resetPassword(${userId}, '${item.nama_lengkap.replace(/'/g, "\\'")}')" class='${resetClass}' title="${resetTitle}">
                                            <i class='fas fa-key'></i>
                                        </button>` :
                                        `<button class='${resetClass}' title="${resetTitle}" disabled>
                                            <i class='fas fa-key'></i>
                                        </button>`
                                    }
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(err => {
                    console.error('Search error:', err);
                    anggotaTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Error saat mencari data</td></tr>`;
                });
        }, 500);
    });
}
</script>