<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50">
<header class="bg-white shadow-lg border-b-4 border-emerald-500">
  <div class="flex items-center justify-between px-6 py-4">
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 rounded-lg overflow-hidden transform scale-150">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Koperasi" class="w-full h-full object-cover">
      </div>

      <div>
        <h1 class="text-2xl font-bold text-gray-800">Koperasi Syariah K-Samara</h1>
        <p class="text-sm text-gray-600">Dashboard Administrasi</p>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <div class="relative">
        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer"></i>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs notification-badge">3</span>
      </div>

      <!-- Dropdown Profile dengan Logout -->
      <div class="relative group">
        <div class="flex items-center space-x-2 cursor-pointer">
          <img src="<?= base_url('assets/images/danzz.png') ?>" alt="Foto Admin" class="w-10 h-10 rounded-full object-cover">
          <div>
            <p class="text-sm font-medium text-gray-800">Admin Koperasi</p>
            <p class="text-xs text-gray-600">Administrator</p>
          </div>
          <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
        </div>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-user mr-2"></i>Profile
          </a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-cog mr-2"></i>Settings
          </a>
          <div class="border-t border-gray-100"></div>
          <form id="logoutForm" action="<?= base_url('auth/login') ?>" method="POST">
            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
              <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="flex">
  <aside class="w-64 bg-gradient-to-b from-emerald-800 to-emerald-900 min-h-screen text-white">
  <nav class="mt-8">
    <ul class="space-y-2 px-4">
      <li>
        <a href="<?= base_url('admin') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard Utama</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/members') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-users"></i>
          <span>Manajemen Anggota</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/savings') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-coins"></i>
          <span>Manajemen Simpanan</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/financing') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-hand-holding-usd"></i>
          <span>Manajemen Pinjaman</span>
        </a>
      </li>

      <!-- Menu Manajemen Angsuran -->
      <li>
        <a href="<?= base_url('admin/dashboard_admin/installments') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg bg-emerald-700">
          <i class="fas fa-calendar-check"></i>
          <span>Manajemen Angsuran</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/transactions') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-exchange-alt"></i>
          <span>Transaksi Umum</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/reports') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-chart-bar"></i>
          <span>Laporan & Analisis</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/settings') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-cog"></i>
          <span>Pengaturan</span>
        </a>
      </li>
      <li><a href="<?= base_url('admin/dashboard_admin/extras') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg"><i class="fas fa-plus-circle"></i><span>Fitur Tambahan</span></a></li>
    </ul>
  </nav>
</aside>
<main class="flex-1 p-6 overflow-x-auto">
        <!-- Main Content -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600">Kelola pembayaran angsuran anggota untuk semua jenis pembiayaan</p>
        </div>

            <!-- Alert Notifikasi -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Loading Overlay -->
            <div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center space-x-3">
                    <i class="fas fa-spinner fa-spin text-emerald-600"></i>
                    <span class="text-gray-700">Memproses pembayaran...</span>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b">
                    <nav class="flex -mb-px">
                        <button class="tab-button active py-4 px-6 text-sm font-medium border-b-2 border-emerald-500 text-emerald-600" data-tab="qard">
                            Qard <span class="ml-2 bg-emerald-100 text-emerald-600 py-1 px-2 rounded-full text-xs"><?= count($qard) ?></span>
                        </button>
                        <button class="tab-button py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="murabahah">
                            Murabahah <span class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs"><?= count($murabahah) ?></span>
                        </button>
                        <button class="tab-button py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="mudharabah">
                            Mudharabah <span class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs"><?= count($mudharabah) ?></span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Qard Tab Content -->
                    <div class="tab-content active fade-in" id="qard-content">
                        <?= renderTable($qard, 'qard', 'id_qard') ?>
                    </div>

                    <!-- Murabahah Tab Content -->
                    <div class="tab-content hidden fade-in" id="murabahah-content">
                        <?= renderTable($murabahah, 'murabahah', 'id_mr') ?>
                    </div>

                    <!-- Mudharabah Tab Content -->
                    <div class="tab-content hidden fade-in" id="mudharabah-content">
                        <?= renderTable($mudharabah, 'mudharabah', 'id_md') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

    <!-- Modal Bayar Angsuran -->
    <div id="bayarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Bayar Angsuran</h3>
                    <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Form dengan method POST langsung -->
                <form action="<?= base_url('admin/dashboard_admin/angsuran/bayar') ?>" method="POST" class="mt-4" id="bayarForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="jenis" id="jenis_angsuran">
                    <input type="hidden" name="id" id="id_angsuran">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sisa Pembayaran</label>
                        <input type="text" id="sisa_pembayaran" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tenor Dibayar</label>
                        <input type="text" id="tenor_dibayar" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required min="1">
                        <p class="mt-1 text-sm text-gray-500">Masukkan jumlah pembayaran angsuran</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition duration-200" id="submitBtn">
                            Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
                btn.classList.add('text-gray-500');
            });
            
            // Add active class to clicked tab
            button.classList.add('active', 'border-emerald-500', 'text-emerald-600');
            button.classList.remove('text-gray-500');
            
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });
            
            // Show selected tab content
            const tabId = button.getAttribute('data-tab');
            const tabContent = document.getElementById(`${tabId}-content`);
            tabContent.classList.remove('hidden');
            tabContent.classList.add('active');
        });
    });

    // Modal functionality
    const modal = document.getElementById('bayarModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeModal = document.getElementById('closeModal');
    const bayarForm = document.getElementById('bayarForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    document.querySelectorAll('.bayar-btn').forEach(button => {
        button.addEventListener('click', function() {
            const jenis = this.getAttribute('data-jenis');
            const id = this.getAttribute('data-id');
            
            document.getElementById('jenis_angsuran').value = jenis;
            document.getElementById('id_angsuran').value = id;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';
            this.disabled = true;
            
            // Gunakan fetch dengan error handling yang lebih baik
            fetch(`<?= base_url('admin/dashboard_admin/angsuran/detail') ?>?jenis=${jenis}&id=${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Cek content type sebelum parse JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new TypeError('Response bukan JSON');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const sisa = data.sisa_pembayaran;
                    const tenorDibayar = data.tenor_dibayar;
                    const totalTenor = data.jml_angsuran;
                    
                    document.getElementById('sisa_pembayaran').value = 'Rp ' + sisa.toLocaleString('id-ID');
                    document.getElementById('tenor_dibayar').value = tenorDibayar + ' / ' + totalTenor + ' bulan';
                    document.getElementById('jumlah_bayar').setAttribute('max', sisa);
                    document.getElementById('jumlah_bayar').value = '';
                    modal.classList.remove('hidden');
                } else {
                    showAlert('error', 'Gagal mengambil data angsuran: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.name === 'TypeError') {
                    showAlert('error', 'Terjadi kesalahan format data. Silakan coba lagi.');
                } else {
                    showAlert('error', 'Terjadi kesalahan saat mengambil data: ' + error.message);
                }
            })
            .finally(() => {
                // Restore button state
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // Form submission dengan redirect biasa (tanpa AJAX)
    bayarForm.addEventListener('submit', function(e) {
        const jumlahBayar = document.getElementById('jumlah_bayar').value;
        const maxBayar = document.getElementById('jumlah_bayar').getAttribute('max');
        
        // Validasi client-side
        if (!jumlahBayar || jumlahBayar <= 0) {
            e.preventDefault();
            showAlert('error', 'Masukkan jumlah pembayaran yang valid');
            return;
        }
        
        if (parseInt(jumlahBayar) > parseInt(maxBayar)) {
            e.preventDefault();
            showAlert('error', 'Jumlah bayar tidak boleh melebihi sisa pembayaran');
            return;
        }
        
        // Show loading
        loadingOverlay.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        
        // Biarkan form submit normal, loading akan hilang setelah page reload
    });

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        // Hapus alert existing
        const existingAlert = document.querySelector('.alert-custom');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alertClass = type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700';
        const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-custom ${alertClass} border px-4 py-3 rounded mb-6 fade-in flex items-center`;
        alertDiv.innerHTML = `
            <i class="fas ${icon} mr-2"></i>
            ${message}
        `;
        
        document.querySelector('.mb-8').after(alertDiv);
        
        // Auto remove setelah 5 detik
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Validate payment amount
    document.getElementById('jumlah_bayar').addEventListener('input', function() {
        const max = parseInt(this.getAttribute('max'));
        const value = parseInt(this.value) || 0;
        
        if (value > max) {
            this.value = max;
        }
        
        // Add visual feedback
        if (value > 0 && value <= max) {
            this.classList.remove('border-red-300');
            this.classList.add('border-green-300');
        } else {
            this.classList.remove('border-green-300');
            this.classList.add('border-red-300');
        }
    });

    // Add keyboard shortcut for modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });

    // Jika halaman dimuat ulang setelah submit, sembunyikan loading
    window.addEventListener('load', () => {
        loadingOverlay.classList.add('hidden');
    });
    </script>
<script>
// JavaScript untuk handle logout dengan konfirmasi
document.addEventListener('DOMContentLoaded', function() {
  const logoutForm = document.getElementById('logoutForm');

  if (logoutForm) {
    logoutForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (confirm('Apakah Anda yakin ingin logout?')) {
        // Jika menggunakan AJAX
        // fetch(this.action, { method: 'POST' })
        //   .then(response => {
        //     if (response.ok) {
        //       window.location.href = '<?= base_url() ?>';
        //     }
        //   });

        // Jika menggunakan form submit biasa
        this.submit();
      }
    });
  }
});
</script>
</body>
</html>

<?php
// Helper function to render table
function renderTable($data, $type, $idField) {
    if(empty($data)) {
        return '
            <div class="text-center py-8">
                <i class="fas fa-file-invoice text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Belum ada data angsuran ' . ucfirst($type) . '</p>
            </div>
        ';
    }
    
    $html = '
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran/Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenor Dibayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terbayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    ';
    
    foreach($data as $item) {
        // Validasi data yang diperlukan
        $jml_angsuran = isset($item['jml_angsuran']) && $item['jml_angsuran'] > 0 ? $item['jml_angsuran'] : 1;
        $jml_pinjam = $item['jml_pinjam'] ?? 0;
        $jml_terbayar = $item['jml_terbayar'] ?? 0;
        $angsuran_per_bulan = $jml_pinjam / $jml_angsuran;
        
        // Hitung tenor dibayar berdasarkan data yang tersedia
        $tenor_dibayar = $item['tenor_dibayar'] ?? $item['sisa_tenor'] ?? 0;
        if ($type === 'qard') {
            $tenor_dibayar = $item['tenor_dibayar'] ?? 0;
        } else {
            // Untuk murabahah dan mudharabah, hitung tenor dibayar dari sisa tenor
            $tenor_dibayar = $jml_angsuran - ($item['sisa_tenor'] ?? $jml_angsuran);
        }
        
        $sisa_pinjaman = $jml_pinjam - $jml_terbayar;
        
        // TENTUKAN STATUS BERDASARKAN SISA PEMBAYARAN
        // Jika sisa pinjaman <= 0, maka status lunas
        if ($sisa_pinjaman <= 0) {
            $status = 'lunas';
            $status_class = 'bg-green-100 text-green-800';
        } else {
            // Gunakan status dari database, atau default ke 'aktif'
            $status = $item['status'] ?? 'aktif';
            $status_class = $status == 'aktif' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800';
        }
        
        // Validasi field ID
        $itemId = $item[$idField] ?? null;
        if (!$itemId) {
            continue; // Skip item jika ID tidak valid
        }
        
        $html .= '
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">' . ($item['nama_lengkap'] ?? 'N/A') . '</div>
                    <div class="text-sm text-gray-500">' . ($item['nomor_anggota'] ?? 'N/A') . '</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ' . (isset($item['tanggal']) ? date('d/m/Y', strtotime($item['tanggal'])) : 'N/A') . '
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rp ' . number_format($jml_pinjam, 0, ',', '.') . '
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rp ' . number_format($angsuran_per_bulan, 0, ',', '.') . '
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ' . $tenor_dibayar . ' / ' . $jml_angsuran . ' bulan
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rp ' . number_format($jml_terbayar, 0, ',', '.') . '
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <span class="text-orange-600">Rp ' . number_format($sisa_pinjaman, 0, ',', '.') . '</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $status_class . '">
                        ' . ucfirst($status) . '
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        ';
        
        if($status != 'lunas') {
            $html .= '
                <button class="bayar-btn bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-4 rounded-lg text-sm transition duration-200 flex items-center space-x-2"
                        data-jenis="' . $type . '" 
                        data-id="' . $itemId . '">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Bayar</span>
                </button>
            ';
        } else {
            $html .= '
                <span class="text-gray-400 flex items-center space-x-1">
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