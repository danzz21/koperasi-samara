<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .avatar-placeholder { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; }
        .modal-content { position: relative; background: white; margin: 5% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); }
        .loading { display: none; }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Pengaturan</h2>
            <p class="text-gray-600">Kelola pengaturan sistem dan hak akses</p>
        </div>

        <div id="notification" class="fixed top-4 right-4 z-50 hidden">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <i class="fas fa-check-circle mr-2"></i>
                <span id="notificationMessage"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Manajemen Admin -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Manajemen Admin</h3>
                <div class="space-y-3" id="userList">
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full avatar-placeholder">
                                    <?= strtoupper(substr($admin['nama_lengkap'], 0, 2)) ?>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= esc($admin['nama_lengkap']) ?></p>
                                    <p class="text-sm text-gray-600"><?= esc($admin['role']) ?> • <?= esc($admin['email']) ?></p>
                                    <p class="text-xs text-gray-500">Status: <?= esc($admin['status']) ?></p>
                                </div>
                            </div>
                            <button class="edit-user text-blue-600 hover:text-blue-900" data-id="<?= $admin['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users text-3xl mb-2 text-gray-300"></i>
                            <p>Belum ada data admin</p>
                        </div>
                    <?php endif; ?>
                </div>
                <button id="addUserBtn" class="w-full mt-4 bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Admin
                </button>
            </div>

            <!-- Pengaturan Akad Syariah -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Pengaturan Akad Syariah</h3>
                <div class="space-y-3" id="akadList">
                    <div class="flex items-center justify-between p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <div>
                            <p class="font-medium text-emerald-800">Murabahah</p>
                            <p class="text-sm text-emerald-600">Margin: 10%</p>
                        </div>
                        <button class="edit-akad text-emerald-600 hover:text-emerald-900" data-id="1" data-type="murabahah">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div>
                            <p class="font-medium text-blue-800">Mudharabah</p>
                            <p class="text-sm text-blue-600">Bagi Hasil: 60:40</p>
                        </div>
                        <button class="edit-akad text-blue-600 hover:text-blue-900" data-id="2" data-type="mudharabah">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-purple-50 border border-purple-200 rounded-lg">
                        <div>
                            <p class="font-medium text-purple-800">Ijarah</p>
                            <p class="text-sm text-purple-600">Sewa: 8%</p>
                        </div>
                        <button class="edit-akad text-purple-600 hover:text-purple-900" data-id="3" data-type="ijarah">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit/Tambah Admin -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800" id="userModalTitle">Tambah Admin</h3>
                    <button id="closeUserModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="userForm" class="space-y-4">
                    <!-- Form user (tetap sama seperti sebelumnya) -->
                    <input type="hidden" id="userId" name="id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <div class="text-red-500 text-sm hidden" id="error_nama_lengkap"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <div class="text-red-500 text-sm hidden" id="error_email"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" id="username" name="username" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <div class="text-red-500 text-sm hidden" id="error_username"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span id="passwordRequired">*</span></label>
                            <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <div class="text-red-500 text-sm hidden" id="error_password"></div>
                            <p class="text-xs text-gray-500 mt-1" id="passwordHint">Kosongkan jika tidak ingin mengubah password</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTP *</label>
                            <input type="text" id="nomor_ktp" name="nomor_ktp" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <div class="text-red-500 text-sm hidden" id="error_nomor_ktp"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                            <input type="text" id="nomor_hp" name="nomor_hp" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <div class="text-red-500 text-sm hidden" id="error_nomor_hp"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP Keluarga</label>
                        <input type="text" id="nomor_hp_keluarga" name="nomor_hp_keluarga" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select id="role" name="role" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="operator">Operator</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select id="status" name="status" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="verified">Verified</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="button" id="deleteUserBtn" class="flex-1 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition-colors hidden">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                        <button type="submit" class="flex-1 bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Akad Syariah -->
    <div id="akadModal" class="modal">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800" id="akadModalTitle">Edit Akad Syariah</h3>
                    <button id="closeAkadModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="akadForm" class="space-y-4">
                    <input type="hidden" id="akadId" name="id">
                    <input type="hidden" id="akadType" name="type">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Akad</label>
                        <input type="text" id="akadName" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="akadDescription" name="description" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" rows="3" readonly></textarea>
                    </div>

                    <!-- Form fields untuk Murabahah -->
                    <div id="murabahahFields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Margin Rate (%)</label>
                            <input type="number" id="marginRate" name="margin_rate" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" step="0.1" min="0" max="100">
                        </div>
                    </div>

                    <!-- Form fields untuk Mudharabah -->
                    <div id="mudharabahFields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bagi Hasil</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Pemilik Modal (%)</label>
                                    <input type="number" id="profitOwner" name="profit_owner" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" min="0" max="100" value="60">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Pengelola (%)</label>
                                    <input type="number" id="profitManager" name="profit_manager" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" min="0" max="100" value="40">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form fields untuk Ijarah -->
                    <div id="ijarahFields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rate Sewa (%)</label>
                            <input type="number" id="rentRate" name="rent_rate" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" step="0.1" min="0" max="100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="akadStatus" name="status" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="loading" class="loading fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>

    <script>
        const baseUrl = window.location.origin;

        // DOM Elements
        const userModal = document.getElementById('userModal');
        const akadModal = document.getElementById('akadModal');
        const userForm = document.getElementById('userForm');
        const akadForm = document.getElementById('akadForm');
        const addUserBtn = document.getElementById('addUserBtn');
        const loading = document.getElementById('loading');
        const notification = document.getElementById('notification');

        // Data akad
        const akadData = {
            1: {
                name: 'Murabahah',
                description: 'Jual beli dengan harga pokok plus margin keuntungan',
                margin_rate: 10,
                status: 'active',
                type: 'murabahah'
            },
            2: {
                name: 'Mudharabah',
                description: 'Kerjasama bagi hasil antara pemilik modal dan pengelola',
                profit_sharing: '60:40',
                status: 'active',
                type: 'mudharabah'
            },
            3: {
                name: 'Ijarah',
                description: 'Sewa menyewa asset dengan imbalan sewa',
                rent_rate: 8,
                status: 'active',
                type: 'ijarah'
            }
        };

        // Show notification
        function showNotification(message, type = 'success') {
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = message;
            
            if (type === 'error') {
                notification.classList.remove('bg-green-500');
                notification.classList.add('bg-red-500');
            } else {
                notification.classList.remove('bg-red-500');
                notification.classList.add('bg-green-500');
            }
            
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        // Clear error messages
        function clearErrors() {
            document.querySelectorAll('[id^="error_"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        // Modal functions untuk User
        function openUserModal(userId = null) {
            // ... kode openUserModal tetap sama
            const modalTitle = document.getElementById('userModalTitle');
            const deleteBtn = document.getElementById('deleteUserBtn');
            const passwordRequired = document.getElementById('passwordRequired');
            const passwordHint = document.getElementById('passwordHint');
            
            clearErrors();
            
            if (userId) {
                modalTitle.textContent = 'Edit Admin';
                passwordRequired.classList.add('hidden');
                passwordHint.classList.remove('hidden');
                
                loading.style.display = 'flex';
                fetch(`${baseUrl}/admindashboard/getAdmin/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        loading.style.display = 'none';
                        if (data.status === 'success') {
                            const user = data.data;
                            document.getElementById('userId').value = user.id;
                            document.getElementById('nama_lengkap').value = user.nama_lengkap;
                            document.getElementById('email').value = user.email;
                            document.getElementById('username').value = user.username;
                            document.getElementById('nomor_ktp').value = user.nomor_ktp;
                            document.getElementById('nomor_hp').value = user.nomor_hp;
                            document.getElementById('nomor_hp_keluarga').value = user.nomor_hp_keluarga || '';
                            document.getElementById('role').value = user.role;
                            document.getElementById('status').value = user.status;
                            deleteBtn.classList.remove('hidden');
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        loading.style.display = 'none';
                        showNotification('Terjadi kesalahan saat memuat data', 'error');
                    });
                
            } else {
                modalTitle.textContent = 'Tambah Admin';
                userForm.reset();
                document.getElementById('userId').value = '';
                document.getElementById('role').value = 'admin';
                document.getElementById('status').value = 'verified';
                passwordRequired.classList.remove('hidden');
                passwordHint.classList.add('hidden');
                deleteBtn.classList.add('hidden');
            }
            userModal.style.display = 'block';
        }

        // Modal functions untuk Akad
        function openAkadModal(akadId, akadType) {
            const akad = akadData[akadId];
            
            if (!akad) {
                showNotification('Data akad tidak ditemukan', 'error');
                return;
            }

            // Set judul modal
            document.getElementById('akadModalTitle').textContent = `Edit ${akad.name}`;
            
            // Set nilai form
            document.getElementById('akadId').value = akadId;
            document.getElementById('akadType').value = akadType;
            document.getElementById('akadName').value = akad.name;
            document.getElementById('akadDescription').value = akad.description;
            document.getElementById('akadStatus').value = akad.status;

            // Sembunyikan semua field khusus
            document.getElementById('murabahahFields').classList.add('hidden');
            document.getElementById('mudharabahFields').classList.add('hidden');
            document.getElementById('ijarahFields').classList.add('hidden');

            // Tampilkan field sesuai jenis akad
            if (akadType === 'murabahah') {
                document.getElementById('murabahahFields').classList.remove('hidden');
                document.getElementById('marginRate').value = akad.margin_rate || '';
            } else if (akadType === 'mudharabah') {
                document.getElementById('mudharabahFields').classList.remove('hidden');
                if (akad.profit_sharing) {
                    const [owner, manager] = akad.profit_sharing.split(':');
                    document.getElementById('profitOwner').value = owner;
                    document.getElementById('profitManager').value = manager;
                }
            } else if (akadType === 'ijarah') {
                document.getElementById('ijarahFields').classList.remove('hidden');
                document.getElementById('rentRate').value = akad.rent_rate || '';
            }

            akadModal.style.display = 'block';
        }

        // Event Listeners untuk modal user
        addUserBtn.addEventListener('click', () => openUserModal());

        document.getElementById('closeUserModal').addEventListener('click', () => {
            userModal.style.display = 'none';
            clearErrors();
        });

        document.getElementById('closeAkadModal').addEventListener('click', () => {
            akadModal.style.display = 'none';
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === userModal) {
                userModal.style.display = 'none';
                clearErrors();
            }
            if (e.target === akadModal) {
                akadModal.style.display = 'none';
            }
        });

        // User form submission
        userForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(userForm);
            
            loading.style.display = 'flex';
            
            fetch(`${baseUrl}/admindashboard/saveAdmin`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (data.status === 'success') {
                    showNotification(data.message);
                    userModal.style.display = 'none';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    if (data.errors) {
                        showErrors(data.errors);
                    } else {
                        showNotification(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                showNotification('Terjadi kesalahan saat menyimpan data', 'error');
            });
        });

        // Akad form submission
        akadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(akadForm);
            
            loading.style.display = 'flex';
            
            fetch(`${baseUrl}/admindashboard/saveAkad`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (data.status === 'success') {
                    showNotification(data.message);
                    akadModal.style.display = 'none';
                    
                    // Update data lokal
                    const akadId = document.getElementById('akadId').value;
                    const akadType = document.getElementById('akadType').value;
                    
                    // Update data di localStorage atau refresh halaman
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                showNotification('Terjadi kesalahan saat menyimpan data', 'error');
            });
        });

        // Delete user
        document.getElementById('deleteUserBtn').addEventListener('click', () => {
            const userId = document.getElementById('userId').value;
            
            if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
                loading.style.display = 'flex';
                
                fetch(`${baseUrl}/admindashboard/deleteAdmin/${userId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (data.status === 'success') {
                        showNotification(data.message);
                        userModal.style.display = 'none';
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    showNotification('Terjadi kesalahan saat menghapus data', 'error');
                });
            }
        });

        // Delegate events for dynamic elements
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-user')) {
                const userId = parseInt(e.target.closest('.edit-user').dataset.id);
                openUserModal(userId);
            }
            
            if (e.target.closest('.edit-akad')) {
                const button = e.target.closest('.edit-akad');
                const akadId = parseInt(button.dataset.id);
                const akadType = button.dataset.type;
                openAkadModal(akadId, akadType);
            }
        });

        function showErrors(errors) {
            clearErrors();
            for (const [field, message] of Object.entries(errors)) {
                const errorElement = document.getElementById(`error_${field}`);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            }
        }
    </script>
</body>
</html>