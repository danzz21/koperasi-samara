<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Tambahan</title>
</head>
<body>
    <div class="mb-6">
                 <!-- Content untuk Fitur Tambahan -->
<section class="p-6">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Fitur Tambahan</h2>
        <p class="text-gray-600">Fitur pendukung operasional koperasi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Pencarian Cepat -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-search text-4xl text-emerald-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Pencarian Cepat</h3>
                <p class="text-gray-600 mb-4">Cari anggota dan transaksi dengan mudah</p>
                <input type="text" id="searchInput" placeholder="Cari anggota/transaksi..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <div id="searchResults" class="mt-2 text-left hidden">
                    <!-- Results will appear here -->
                </div>
            </div>
        </div>

        <!-- Export/Import Data -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-download text-4xl text-blue-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Export/Import Data</h3>
                <p class="text-gray-600 mb-4">Kelola data dengan file Excel atau CSV</p>
                <div class="space-y-2">
                    <select id="exportType" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                    </select>
                    <button onclick="exportData()" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Export Data
                    </button>
                    
                    <input type="file" id="importFile" accept=".csv,.xlsx" class="hidden">
                    <button onclick="document.getElementById('importFile').click()" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Import Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-bell text-4xl text-purple-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Notifikasi</h3>
                <p class="text-gray-600 mb-4">Pengaturan notifikasi WhatsApp & Email</p>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" id="whatsappNotif" checked class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 text-sm">WhatsApp</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="emailNotif" checked class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 text-sm">Email</span>
                    </label>
                    <button onclick="saveNotificationSettings()" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors mt-2">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup & Restore -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-database text-4xl text-orange-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Backup & Restore</h3>
                <p class="text-gray-600 mb-4">Kelola backup database sistem</p>
                <div class="space-y-2">
                    <button onclick="backupDatabase()" class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition-colors">
                        Backup Now
                    </button>
                    <input type="file" id="restoreFile" accept=".sql" class="hidden">
                    <button onclick="document.getElementById('restoreFile').click()" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        Restore
                    </button>
                </div>
            </div>
        </div>

        <!-- Audit Log -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-history text-4xl text-red-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Audit Log</h3>
                <p class="text-gray-600 mb-4">Riwayat aktivitas pengguna sistem</p>
                <button onclick="showAuditLog()" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors">
                    Lihat Log
                </button>
                <div id="auditLogContent" class="mt-4 hidden max-h-60 overflow-y-auto">
                    <!-- Log content will appear here -->
                </div>
            </div>
        </div>

        <!-- Pengaturan Sistem -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="text-center">
                <i class="fas fa-cog text-4xl text-gray-500 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Pengaturan Sistem</h3>
                <p class="text-gray-600 mb-4">Konfigurasi umum aplikasi</p>
                <button onclick="showSystemConfig()" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Konfigurasi
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Modal untuk Audit Log -->
<div id="auditLogModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-96 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Audit Log System</h3>
            <button onclick="closeAuditLog()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="auditLogList"></div>
    </div>
</div>

<!-- JavaScript untuk interaksi -->
<script>
// Search Functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length > 2) {
        searchData(query);
    } else {
        document.getElementById('searchResults').classList.add('hidden');
    }
});

async function searchData(query) {
    try {
        const response = await fetch('<?= base_url('extras/search') ?>?q=' + encodeURIComponent(query));
        const data = await response.json();
        
        let html = '<div class="bg-gray-50 p-3 rounded-lg">';
        
        if (data.members && data.members.length > 0) {
            html += '<p class="font-semibold text-sm mb-2">Anggota:</p>';
            data.members.forEach(member => {
                html += `<div class="text-xs p-1 border-b">${member.nama}</div>`;
            });
        }
        
        if (data.transactions && data.transactions.length > 0) {
            html += '<p class="font-semibold text-sm mt-2 mb-2">Transaksi:</p>';
            data.transactions.forEach(transaction => {
                html += `<div class="text-xs p-1 border-b">${transaction.kode_transaksi}</div>`;
            });
        }
        
        if (data.members.length === 0 && data.transactions.length === 0) {
            html += '<p class="text-xs text-gray-500">Tidak ada hasil ditemukan</p>';
        }
        
        html += '</div>';
        
        document.getElementById('searchResults').innerHTML = html;
        document.getElementById('searchResults').classList.remove('hidden');
    } catch (error) {
        console.error('Search error:', error);
    }
}

// Export Data
async function exportData() {
    const type = document.getElementById('exportType').value;
    window.location.href = `<?= base_url('extras/export') ?>?type=${type}`;
}

// Import Data
document.getElementById('importFile').addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        
        try {
            const response = await fetch('<?= base_url('extras/import') ?>', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            alert(result.message);
            
            // Reset file input
            e.target.value = '';
        } catch (error) {
            alert('Error importing data: ' + error.message);
        }
    }
});

// Notification Settings
async function saveNotificationSettings() {
    const whatsapp = document.getElementById('whatsappNotif').checked;
    const email = document.getElementById('emailNotif').checked;
    
    try {
        const response = await fetch('<?= base_url('extras/update-notification') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `whatsapp=${whatsapp ? 1 : 0}&email=${email ? 1 : 0}`
        });
        
        const result = await response.json();
        alert(result.message);
    } catch (error) {
        alert('Error saving settings: ' + error.message);
    }
}

// Backup Database
async function backupDatabase() {
    if (confirm('Apakah Anda yakin ingin melakukan backup database?')) {
        try {
            const response = await fetch('<?= base_url('extras/backup') ?>');
            if (response.ok) {
                // Trigger download
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `backup-${new Date().toISOString().split('T')[0]}.sql`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } else {
                const result = await response.json();
                alert(result.message);
            }
        } catch (error) {
            alert('Error creating backup: ' + error.message);
        }
    }
}

// Restore Database
document.getElementById('restoreFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && confirm('PERINGATAN: Restore database akan mengganti data yang ada. Lanjutkan?')) {
        // Implement restore functionality here
        alert('Fitur restore akan diimplementasi');
        e.target.value = '';
    }
});

// Audit Log
async function showAuditLog() {
    try {
        const response = await fetch('<?= base_url('extras/audit-log') ?>');
        const logs = await response.json();
        
        let html = '';
        if (logs && logs.length > 0) {
            logs.forEach(log => {
                html += `
                    <div class="border-b py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">${log.action || 'Unknown Action'}</span>
                            <span class="text-sm text-gray-500">${log.created_at || ''}</span>
                        </div>
                        <p class="text-sm text-gray-600">${log.description || ''}</p>
                        <p class="text-xs text-gray-500">User: ${log.user_id || 'System'}</p>
                    </div>
                `;
            });
        } else {
            html = '<p class="text-gray-500">Tidak ada log tersedia</p>';
        }
        
        document.getElementById('auditLogList').innerHTML = html;
        document.getElementById('auditLogModal').classList.remove('hidden');
    } catch (error) {
        alert('Error loading audit log: ' + error.message);
    }
}

function closeAuditLog() {
    document.getElementById('auditLogModal').classList.add('hidden');
}

function displaySearchResults(data) {
    let html = '<div class="bg-white border border-gray-200 rounded-lg shadow-sm max-h-60 overflow-y-auto">';
    
    // Tampilkan hasil anggota
    if (data.members && data.members.length > 0) {
        html += '<div class="p-3 border-b">';
        html += '<p class="font-semibold text-emerald-600 text-sm mb-2 flex items-center">';
        html += '<i class="fas fa-users mr-2"></i> Anggota:';
        html += '</p>';
        
        data.members.forEach(member => {
            const status = member.status === 'aktif' ? 
                '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">AKTIF</span>' : 
                '<span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">NONAKTIF</span>';
            
            html += `
                <div class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer border-b border-gray-100 last:border-b-0">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-emerald-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-800">${member.nama_lengkap || 'N/A'}</p>
                            ${status}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-medium">ID:</span> ${member.nomor_angosta || '-'} | 
                            <span class="font-medium">KTP:</span> ${member.no_ktp || '-'}
                        </p>
                        <p class="text-xs text-gray-500">
                            ${member.email || ''} | ${member.telepon || ''}
                        </p>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    } else {
        html += '<div class="p-3 border-b">';
        html += '<p class="text-sm text-gray-500 text-center py-2">Tidak ada anggota ditemukan</p>';
        html += '</div>';
    }
    
    // Tampilkan hasil transaksi
    if (data.transactions && data.transactions.length > 0) {
        html += '<div class="p-3">';
        html += '<p class="font-semibold text-blue-600 text-sm mb-2 flex items-center">';
        html += '<i class="fas fa-exchange-alt mr-2"></i> Transaksi:';
        html += '</p>';
        
        data.transactions.forEach(transaction => {
            const jenis = transaction.jenis_transaksi || 'TRANSAKSI';
            const jumlah = transaction.jumlah || transaction.jml_pinjam || 0;
            const formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(jumlah);
            
            const status = transaction.status ? 
                `<span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">${transaction.status}</span>` : '';
            
            const tanggal = transaction.tanggal ? 
                new Date(transaction.tanggal).toLocaleDateString('id-ID') : '-';
            
            html += `
                <div class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer border-b border-gray-100 last:border-b-0">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-receipt text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-800">${jenis}</p>
                            ${status}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-medium">Jumlah:</span> ${formattedAmount}
                        </p>
                        <p class="text-xs text-gray-500">
                            <span class="font-medium">Tanggal:</span> ${tanggal} | 
                            <span class="font-medium">ID:</span> ${transaction.id_qard || transaction.id_murabahah || transaction.id_mudharabah || '-'}
                        </p>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    } else {
        html += '<div class="p-3">';
        html += '<p class="text-sm text-gray-500 text-center py-2">Tidak ada transaksi ditemukan</p>';
        html += '</div>';
    }
    
    // Total hasil
    const totalMembers = data.members ? data.members.length : 0;
    const totalTransactions = data.transactions ? data.transactions.length : 0;
    
    html += `<div class="bg-gray-50 px-3 py-2 border-t">
        <p class="text-xs text-gray-500 text-center">
            Ditemukan: ${totalMembers} anggota, ${totalTransactions} transaksi
        </p>
    </div>`;
    
    html += '</div>';
    
    document.getElementById('searchResults').innerHTML = html;
    document.getElementById('searchResults').classList.remove('hidden');
}

// System Configuration
function showSystemConfig() {
    alert('Fitur konfigurasi sistem akan diimplementasi dengan form settings yang lengkap');
}

// Load notification settings on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load saved notification settings
    fetch('<?= base_url('extras/get-notification-settings') ?>')
        .then(response => response.json())
        .then(settings => {
            if (settings) {
                document.getElementById('whatsappNotif').checked = settings.whatsapp;
                document.getElementById('emailNotif').checked = settings.email;
            }
        })
        .catch(error => console.error('Error loading settings:', error));
});

</script>
</body>
</html>