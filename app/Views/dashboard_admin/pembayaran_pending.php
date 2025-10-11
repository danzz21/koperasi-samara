<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #10b981;
            --primary-light: #34d399;
            --primary-dark: #059669;
            --secondary: #06b6d4;
            --accent: #0ea5e9;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #cbd5e1;
            --border-radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8fafc;
            color: var(--dark);
            line-height: 1.6;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--dark);
            color: white;
            padding: 1.5rem 0;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid #374151;
            margin-bottom: 1rem;
        }

        .sidebar-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: #374151;
            color: white;
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .header {
            background: white;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border-radius: var(--border-radius);
        }

        .header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            margin: 0;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: var(--primary);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .pembayaran-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .pembayaran-item:last-child {
            border-bottom: none;
        }

        .pembayaran-info {
            flex: 1;
        }

        .pembayaran-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .pembayaran-detail {
            font-size: 14px;
            color: var(--gray);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .pembayaran-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--gray);
            color: var(--dark);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 400px;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: 6px;
            margin-bottom: 1rem;
            font-family: inherit;
        }

        .bukti-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .bukti-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .pembayaran-item {
                flex-direction: column;
            }
            
            .pembayaran-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>
                    <i data-lucide="clock"></i>
                    Pembayaran Menunggu Verifikasi
                </h1>
            </div>

            <?php if (!empty($pembayaran_pending)): ?>
                <div class="card">
                    <div class="card-header">
                        Total <?= count($pembayaran_pending) ?> Pembayaran Menunggu
                    </div>
                    
                   <?php foreach ($pembayaran_pending as $pembayaran): ?>
    <div class="pembayaran-item">
        <div class="pembayaran-info">
            <div class="pembayaran-title">
                <?= esc($pembayaran->jenis_pinjaman) ?> - Angsuran Ke-<?= esc($pembayaran->angsuran_ke) ?>
            </div>
            <div class="pembayaran-detail">
                <span><strong>ID Anggota:</strong> <?= esc($pembayaran->id_anggota) ?></span>
                <span><strong>Jumlah:</strong> Rp <?= number_format($pembayaran->jumlah_bayar, 0, ',', '.') ?></span>
                <span><strong>Tanggal:</strong> <?= date('d M Y', strtotime($pembayaran->tanggal_bayar)) ?></span>
                <?php if (!empty($pembayaran->bukti_bayar)): ?>
                    <span>
                        <a href="<?= base_url('uploads/bukti_bayar/' . $pembayaran->bukti_bayar) ?>" 
                           target="_blank" 
                           class="bukti-link">
                            Lihat Bukti
                        </a>
                    </span>
                <?php endif; ?>
            </div>
            <div style="font-size: 12px; color: var(--gray); margin-top: 0.5rem;">
                <strong>Diajukan:</strong> <?= date('d M Y H:i', strtotime($pembayaran->created_at)) ?>
            </div>
            <!-- ✅ DEBUG: Tampilkan ID yang benar -->
            <div style="font-size: 11px; color: #666; margin-top: 0.25rem;">
                <strong>ID Pembayaran:</strong> <?= $pembayaran->id ?>
            </div>
        </div>
        <div class="pembayaran-actions">
            <!-- ✅ GUNAKAN id BUKAN id_pembayaran -->
            <button class="btn btn-success" 
                    onclick="verifikasiPembayaran(<?= $pembayaran->id ?>)" 
                    <?= (!$pembayaran->id) ? 'disabled' : '' ?>>
                <i data-lucide="check"></i>
                Verifikasi
            </button>
            <button class="btn btn-danger" 
                    onclick="tolakPembayaran(<?= $pembayaran->id ?>)" 
                    <?= (!$pembayaran->id) ? 'disabled' : '' ?>>
                <i data-lucide="x"></i>
                Tolak
            </button>
        </div>
    </div>
<?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i data-lucide="check-circle" style="width: 64px; height: 64px; margin-bottom: 1rem;"></i>
                    <h3>Tidak ada pembayaran yang menunggu verifikasi</h3>
                    <p>Semua pembayaran sudah diproses</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal Tolak Pembayaran -->
    <div id="modalTolak" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 1rem;">Tolak Pembayaran</h3>
            <form id="formTolak">
                <input type="hidden" name="id_pembayaran" id="id_pembayaran_tolak">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Alasan Penolakan</label>
                    <textarea name="alasan" class="form-input" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" onclick="tutupModalTolak()" class="btn btn-outline" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn btn-danger" style="flex: 1;">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Verifikasi pembayaran
        f// Verifikasi pembayaran
// Verifikasi pembayaran
function verifikasiPembayaran(id) {
    console.log('Verifikasi pembayaran ID:', id);
    
    // Validasi ID
    if (!id || id == 0) {
        alert('❌ ID pembayaran tidak valid');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
        // Show loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i> Memproses...';
        button.disabled = true;
        
        fetch(`/admin/pembayaran/verifikasi/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (response.status === 405) {
                throw new Error('Method tidak diizinkan. Periksa route.');
            }
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.status === 'success') {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                lucide.createIcons();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan: ' + error.message);
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
            lucide.createIcons();
        });
    }
}

// Tolak pembayaran
function tolakPembayaran(id) {
    document.getElementById('id_pembayaran_tolak').value = id;
    document.getElementById('modalTolak').style.display = 'flex';
}

function tutupModalTolak() {
    document.getElementById('modalTolak').style.display = 'none';
    document.getElementById('formTolak').reset();
}

// Handle form tolak
document.getElementById('formTolak').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('id_pembayaran_tolak').value;
    
    console.log('Tolak pembayaran ID:', id);
    
    fetch(`/admin/pembayaran/tolak/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            alert('✅ ' + data.message);
            tutupModalTolak();
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menolak pembayaran. Periksa console untuk detail.');
    });
});

        // Tolak pembayaran
        function tolakPembayaran(id) {
            document.getElementById('id_pembayaran_tolak').value = id;
            document.getElementById('modalTolak').style.display = 'flex';
        }

        function tutupModalTolak() {
            document.getElementById('modalTolak').style.display = 'none';
            document.getElementById('formTolak').reset();
        }

        // Handle form tolak
        document.getElementById('formTolak').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const id = document.getElementById('id_pembayaran_tolak').value;
            
            fetch(`<?= base_url('admin/pembayaran/tolak/') ?>${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    tutupModalTolak();
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menolak pembayaran');
            });
        });

        // Tutup modal ketika klik di luar
        document.getElementById('modalTolak').addEventListener('click', function(e) {
            if (e.target === this) {
                tutupModalTolak();
            }
        });
    </script>
</body>
</html>