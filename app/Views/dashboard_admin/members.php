<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Anggota</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Modal Styles - FIXED */
          .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex !important;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            margin: 0 20px;
        }

        .btn-reset {
            color: #f59e0b;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            color: #d97706;
            background-color: #fef3c7;
        }

        /* Loading Spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Simple Utility Classes */
        .hidden { display: none !important; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-3 > * + * { margin-left: 0.75rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        
        /* Colors */
        .bg-emerald-600 { background-color: #059669; }
        .bg-emerald-700 { background-color: #047857; }
        .bg-gray-500 { background-color: #6b7280; }
        .bg-gray-600 { background-color: #4b5563; }
        .bg-orange-600 { background-color: #ea580c; }
        .bg-orange-700 { background-color: #c2410c; }
        
        .text-white { color: white; }
        .text-gray-600 { color: #4b5563; }
        .text-gray-800 { color: #1f2937; }
        .text-blue-600 { color: #2563eb; }
        .text-green-600 { color: #16a34a; }
        .text-orange-600 { color: #ea580c; }
        
        /* Hover States */
        .hover\:bg-emerald-700:hover { background-color: #047857; }
        .hover\:bg-gray-600:hover { background-color: #4b5563; }
        .hover\:bg-orange-700:hover { background-color: #c2410c; }
        .hover\:text-blue-900:hover { color: #1e40af; }
        .hover\:text-green-900:hover { color: #14532d; }
        .hover\:text-orange-900:hover { color: #7c2d12; }
        
        /* Layout */
        .rounded-lg { border-radius: 8px; }
        .rounded-md { border-radius: 6px; }
        .rounded-xl { border-radius: 12px; }
        
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mr-2 { margin-right: 0.5rem; }
        
        /* Typography */
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .text-xl { font-size: 1.25rem; }
        .text-3xl { font-size: 1.875rem; }
        
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        
        .uppercase { text-transform: uppercase; }
        
        /* Table Styles */
        .table-row {
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
        
        .bg-white { background-color: white; }
        .bg-gray-50 { background-color: #f9fafb; }
        .bg-green-100 { background-color: #dcfce7; }
        .bg-yellow-100 { background-color: #fef3c7; }
        
        .text-green-800 { color: #166534; }
        .text-yellow-800 { color: #92400e; }
        
        .whitespace-nowrap { white-space: nowrap; }
        
        /* Form Styles */
        .border { border: 1px solid #d1d5db; }
        .border-b { border-bottom: 1px solid #e5e7eb; }
        .border-gray-200 { border-color: #e5e7eb; }
        .border-gray-300 { border-color: #d1d5db; }
        
        .focus\:outline-none:focus { outline: none; }
        .focus\:ring-2:focus { 
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.5); 
            border-color: transparent;
        }
        
        .w-full { width: 100%; }
        .max-w-md { max-width: 500px; }
    </style>
</head>
<body>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Anggota</h2>
        <p class="text-gray-600">Kelola data anggota koperasi</p>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Daftar Anggota</h3>
                <button onclick="openModal('memberModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Anggota
                </button>
            </div>

            <form method="GET" action="" onsubmit="return false;">
                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="Cari anggota..."
                    autocomplete="off"
                    class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. KTP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="anggotaTableBody">
                    <?php if (!isset($search) || $search === ''): ?>
                        <?php foreach ($anggota as $data): 
                            $id_anggota = $data['id_anggota'];
                            $id_user = $data['id_user'] ?? null;
                            $nama = ucwords($data['nama_lengkap']);
                            $ktp = $data['no_ktp'];
                            $status = $data['status'] ?? 'Menunggu Verifikasi';
                            $tanggal = isset($data['tanggal_daftar']) ? date("d M Y", strtotime($data['tanggal_daftar'])) : '-';
                            $urlDetail = base_url('admin/detail-anggota/' . $id_anggota);
                            $badge = (strtolower($status) == 'aktif') ?
                                "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>$status</span>" :
                                "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>$status</span>";
                            
                            // Cek apakah user_id tersedia
                            $canReset = !empty($id_user);
                            $resetClass = $canReset ? 'btn-reset' : 'btn-reset disabled';
                            $resetTitle = $canReset ? 'Reset Password' : 'User tidak ditemukan';
                        ?>
                        <tr class='table-row' onclick="window.location.href='<?= $urlDetail ?>'">
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $id_anggota ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'><?= $nama ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $ktp ?></td>
                            <td class='px-6 py-4 whitespace-nowrap'><?= $badge ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $tanggal ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2'>
                                <button onclick="event.stopPropagation(); window.location.href='<?= base_url('admin/edit-anggota/' . $id_anggota) ?>'" class='text-blue-600 hover:text-blue-900' title="Edit">
                                    <i class='fas fa-edit'></i>
                                </button>
                                 <button onclick="event.stopPropagation(); showDeleteModal(<?= $id_anggota ?>)" 
            class='text-red-600 hover:text-red-900' 
            title="Hapus Anggota">
        <i class='fas fa-trash'></i>
    </button>
                                <button onclick="event.stopPropagation(); window.location.href='<?= base_url('admin/detail-anggota/' . $id_anggota) ?>'" class='text-green-600 hover:text-green-900' title="Detail">
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
                                <!-- Ganti tombol print dengan tombol nonaktifkan -->
<button onclick="event.stopPropagation(); toggleMemberStatus(<?= $id_anggota ?>, '<?= htmlspecialchars($nama) ?>', '<?= $status ?>')" 
        class='<?= strtolower($status) == 'nonaktif' ? 'text-green-600 hover:text-green-900' : 'text-orange-600 hover:text-orange-900' ?>' 
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
            <!-- Tambahkan CSRF Token -->
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP *</label>
                <input type="text" name="no_ktp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                <input type="text" name="no_telp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                <textarea name="alamat" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Diri</label>
                <input type="file" name="foto_diri" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto KTP</label>
                <input type="file" name="foto_ktp" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal('memberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- MODAL KONFIRMASI RESET PASSWORD -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Reset Password</h3>
            <p class="text-gray-600 mb-6" id="resetPasswordText">
                Apakah Anda yakin ingin mereset password anggota?
            </p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('resetPasswordModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmResetPassword()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors" id="confirmResetBtn">
                    Reset Password
                </button>
            </div>
        </div>
    </div>
<!-- MODAL KONFIRMASI STATUS ANGGOTA -->
<div id="statusMemberModal" class="modal">
    <div class="modal-content">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="statusMemberTitle">Ubah Status Anggota</h3>
        <p class="text-gray-600 mb-6" id="statusMemberText">
            Apakah Anda yakin ingin mengubah status anggota?
        </p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeModal('statusMemberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                Batal
            </button>
            <button type="button" onclick="confirmToggleStatus()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors" id="confirmStatusBtn">
                Ubah Status
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS ANGGOTA -->
<div id="deleteMemberModal" class="modal">
    <div class="modal-content max-w-lg">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="deleteMemberTitle">Hapus Anggota</h3>
        
        <div id="deleteMemberContent">
            <!-- Konten akan diisi oleh JavaScript -->
        </div>

        <div id="deleteLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-4"></i>
            <p class="text-gray-600">Memuat data anggota...</p>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('deleteMemberModal')" 
                        class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <div class="flex space-x-2">
                    <button type="button" onclick="confirmDeleteMember(false)" 
                            class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors">
                        Nonaktifkan
                    </button>
                    <button type="button" onclick="confirmDeleteMember(true)" 
                            class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors">
                        Hapus Permanen
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Nonaktifkan:</strong> Data tetap tersimpan dan bisa dipulihkan.<br>
                <strong>Hapus Permanen:</strong> Semua data akan dihapus selamanya.
            </p>
        </div>
    </div>
</div>
    <script>
        let currentUserId = null;
let currentUserName = null;

// Fungsi Reset Password dengan debug detail
function resetPassword(userId, userName) {
    console.log('=== RESET PASSWORD CALLED ===');
    console.log('Parameters received:');
    console.log('userId:', userId);
    console.log('userName:', userName);
    console.log('Type of userId:', typeof userId);
    console.log('Is null?:', userId === null);
    console.log('Is undefined?:', userId === undefined);
    console.log('======================');

    // Validasi di frontend dulu
    if (userId === null || userId === undefined || userId === 'null' || userId === 'undefined') {
        console.error('❌ ERROR: User ID is invalid in frontend:', userId);
        alert('Error: User ID tidak valid untuk ' + userName);
        return;
    }

    currentUserId = userId;
    currentUserName = userName;
    
    console.log('Stored in variables - currentUserId:', currentUserId, 'currentUserName:', currentUserName);
    
    // Update teks modal
    const resetText = document.getElementById('resetPasswordText');
    if (resetText) {
        resetText.innerHTML = 
            `Apakah Anda yakin ingin mereset password untuk:<br>
             <strong>${userName}</strong><br><br>
             User ID: <strong>${userId}</strong><br>
             Password akan dikembalikan ke: <strong>123</strong>`;
    }
    
    // Buka modal reset password
    openModal('resetPasswordModal');
}

// Fungsi konfirmasi reset password
function confirmResetPassword() {
    console.log('=== CONFIRM RESET ===');
    console.log('currentUserId sebelum kirim:', currentUserId);
    console.log('currentUserName:', currentUserName);
    console.log('======================');

    if (!currentUserId) {
        alert('Error: User ID tidak ditemukan di frontend');
        return;
    }

    const button = document.getElementById('confirmResetBtn');
    const originalText = button.innerHTML;
    
    // Loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    button.disabled = true;

    // GUNAKAN FORMDATA - lebih reliable untuk CI4
    const formData = new FormData();
    formData.append('user_id', currentUserId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }

    // Kirim request
    fetch('<?= base_url('admin/reset-password') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data from server:', data);
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

// Variabel global untuk delete member
let currentDeleteMemberId = null;

// Fungsi tampilkan modal hapus
// Fungsi showDeleteModal - PERBAIKI LOGIKA SUMMARY
function showDeleteModal(memberId) {
    console.log('Show delete modal for member ID:', memberId);
    currentDeleteMemberId = memberId;
    
    const contentDiv = document.getElementById('deleteMemberContent');
    const loadingDiv = document.getElementById('deleteLoading');
    
    contentDiv.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    
    // URL endpoint
    const detailsUrl = '<?= base_url("admin/get-member-details/") ?>' + memberId;
    
    console.log('Fetching URL:', detailsUrl);
    
    fetch(detailsUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // ✅ TAMBAHKAN HEADER INI
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        
        loadingDiv.classList.add('hidden');
        contentDiv.classList.remove('hidden');
        
        if (data.status === 'success' && data.data) {
            const anggota = data.data.anggota;
            const summary = data.data.summary;
            const totalData = data.data.total_data_terkait || 0;
            
            // Tampilkan data
            let summaryHtml = '';
            
            if (totalData > 0) {
                let detailItems = [];
                
                if (summary.simpanan_pokok > 0) detailItems.push(`${summary.simpanan_pokok} simpanan pokok`);
                if (summary.simpanan_wajib > 0) detailItems.push(`${summary.simpanan_wajib} simpanan wajib`);
                if (summary.simpanan_sukarela > 0) detailItems.push(`${summary.simpanan_sukarela} simpanan sukarela`);
                if (summary.pembiayaan_aktif > 0) detailItems.push(`${summary.pembiayaan_aktif} pembiayaan aktif`);
                if (summary.pembayaran_pending > 0) detailItems.push(`${summary.pembayaran_pending} pembayaran pending`);
                
                summaryHtml = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                        <p class="text-sm text-yellow-800 font-semibold mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Anggota ini memiliki ${totalData} data terkait
                        </p>
                        <ul class="text-xs text-yellow-700 ml-5 list-disc">
                            ${detailItems.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }
            
            contentDiv.innerHTML = `
                ${summaryHtml}
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">Detail Anggota:</p>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <table class="text-sm w-full">
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
                                    <span class="px-2 py-1 text-xs rounded-full ${anggota.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${anggota.status}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">Tanggal Daftar</td>
                                <td class="py-1">${anggota.tanggal_daftar}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
        } else {
            contentDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <p class="text-red-700">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${data.message || 'Gagal memuat data'}
                    </p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loadingDiv.classList.add('hidden');
        contentDiv.classList.remove('hidden');
        contentDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-red-700 font-semibold">Error</p>
                <p class="text-sm text-red-600">${error.message}</p>
            </div>
        `;
    });
    
    openModal('deleteMemberModal');
}

// Fungsi konfirmasi hapus
// Di view members.php
function confirmDeleteMember(hardDelete = false) {
    if (!currentDeleteMemberId) return;
    
    const confirmText = hardDelete ? 
        'Apakah Anda YAKIN ingin menghapus PERMANEN?' :
        'Apakah Anda yakin ingin menonaktifkan?';
    
    if (!confirm(confirmText)) return;
    
    // Dapatkan CSRF token dari form yang sudah ada
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '<?= csrf_hash() ?>';
    
    const formData = new FormData();
    formData.append('member_id', currentDeleteMemberId);
    formData.append('hard_delete', hardDelete);
    formData.append('csrf_token', csrfToken); // Gunakan nama 'csrf_token'
    // Atau gunakan nama default CI4:
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url("admin/delete-anggota") ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken // Header alternatif
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ ' + data.message);
            location.reload(); // Reload halaman
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

// Fungsi untuk toggle status anggota
function toggleMemberStatus(memberId, memberName, currentStatus) {
    console.log('=== TOGGLE MEMBER STATUS ===');
    console.log('memberId:', memberId);
    console.log('memberName:', memberName);
    console.log('currentStatus:', currentStatus);
    
    currentMemberId = memberId;
    currentMemberName = memberName;
    currentMemberStatus = currentStatus.toLowerCase();
    
    const isCurrentlyActive = currentMemberStatus === 'aktif';
    const newStatus = isCurrentlyActive ? 'nonaktif' : 'aktif';
    
    // Update modal content
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
            'flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors' :
            'flex-1 bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition-colors';
    }
    
    openModal('statusMemberModal');
}

// Fungsi konfirmasi ubah status
function confirmToggleStatus() {
    console.log('=== CONFIRM STATUS CHANGE ===');
    console.log('currentMemberId:', currentMemberId);
    console.log('currentMemberName:', currentMemberName);
    console.log('currentMemberStatus:', currentMemberStatus);
    
    if (!currentMemberId) {
        alert('Error: Member ID tidak ditemukan');
        return;
    }

    const button = document.getElementById('confirmStatusBtn');
    const originalText = button.innerHTML;
    
    // Loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    button.disabled = true;

    const formData = new FormData();
    formData.append('member_id', currentMemberId);
    formData.append('current_status', currentMemberStatus);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    // Kirim request ke controller
    fetch('<?= base_url('admin/toggle-member-status') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            alert('✅ Status anggota berhasil diubah!');
            closeModal('statusMemberModal');
            location.reload(); // Reload untuk update tampilan
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
 function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById(id).classList.remove("flex");
        }

        document.getElementById('formMember').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;

    // PERBAIKI URL INI - sesuaikan dengan route yang ada
    fetch('<?= base_url('admin/dashboard_admin/members/save') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
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
        alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

        // Live search anggota
        const searchInput = document.getElementById('searchInput');
        const anggotaTableBody = document.getElementById('anggotaTableBody');
        let searchTimeout;

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
                                ? `<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>${item.status}</span>`
                                : `<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>${item.status}</span>`;
                            
                            const userId = item.id_user;
                            const anggotaId = item.id_anggota;
                            const canReset = userId !== null && userId !== undefined;
                            const resetClass = canReset ? 'btn-reset' : 'btn-reset disabled';
                            const resetTitle = canReset ? 'Reset Password' : 'User tidak ditemukan';
                            
                            anggotaTableBody.innerHTML += `
                                <tr class='table-row' onclick="window.location.href='${item.urlDetail}'">
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${anggotaId}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>${item.nama_lengkap}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${item.no_ktp}</td>
                                    <td class='px-6 py-4 whitespace-nowrap'>${badge}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${item.tanggal_daftar}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2'>
                                        <button onclick="event.stopPropagation(); window.location.href='/admin/edit-anggota/${anggotaId}'" class='text-blue-600 hover:text-blue-900' title="Edit">
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button onclick="event.stopPropagation(); window.location.href='${item.urlDetail}'" class='text-green-600 hover:text-green-900' title="Detail">
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
                                        <button onclick="event.stopPropagation(); window.print();" class='text-orange-600 hover:text-orange-900' title="Print">
                                            <i class='fas fa-print'></i>
                                        </button>
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
    </script>
</body>
</html>