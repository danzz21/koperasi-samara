<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpanan</title>
</head>
<body>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Simpanan</h2>
        <p class="text-gray-600">Kelola simpanan anggota koperasi</p>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Pokok</h3>
        <p class="text-2xl font-bold text-emerald-600">Rp <?= number_format($totalPokok, 0, ',', '.') ?></p>
        <p class="text-sm text-gray-600">Total dari <?= $anggotaPokok ?> anggota</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Wajib</h3>
        <p class="text-2xl font-bold text-blue-600">Rp <?= number_format($totalWajib, 0, ',', '.') ?></p>
        <p class="text-sm text-gray-600">Bulan ini</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Simpanan Sukarela</h3>
        <p class="text-2xl font-bold text-purple-600">Rp <?= number_format($totalSukarela, 0, ',', '.') ?></p>
        <p class="text-sm text-gray-600">Bulan ini</p>
    </div>
</div>


    <div class="bg-white rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Transaksi Simpanan</h3>
                <button onclick="openModal('savingsModal')" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Input Simpanan
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
    </tr>
</thead>

                <tbody class="bg-white divide-y divide-gray-200">
    <?php foreach ($pokok as $p): ?>
        <tr>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $p['tanggal'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $p['nama_lengkap'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900">Pokok</td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($p['jumlah'], 0, ',', '.') ?></td>
            <td class="px-6 py-4 text-sm text-green-700"><?= ucfirst($p['status']) ?></td>
        </tr>
    <?php endforeach; ?>

    <?php foreach ($wajib as $w): ?>
        <tr>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $w['tanggal'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $w['nama_lengkap'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900">Wajib</td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($w['jumlah'], 0, ',', '.') ?></td>
            <td class="px-6 py-4 text-sm text-green-700"><?= ucfirst($w['status']) ?></td>
        </tr>
    <?php endforeach; ?>

    <?php foreach ($sukarela as $s): ?>
        <tr>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $s['tanggal'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= $s['nama_lengkap'] ?></td>
            <td class="px-6 py-4 text-sm text-gray-900">Sukarela</td>
            <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($s['jumlah'], 0, ',', '.') ?></td>
            <td class="px-6 py-4 text-sm text-green-700"><?= ucfirst($s['status']) ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>


            </table>
        </div>
    </div>

    <div id="savingsModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Input Simpanan</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
                    <div class="relative">
                        <input id="anggotaSearch" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Cari nama anggota...">
                        <div id="anggotaResults" class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 hidden"></div>
                        <button type="button" id="semuaAnggotaBtn" class="absolute right-0 bottom-[-38px] bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs border border-blue-300 hover:bg-blue-200">Semua Anggota</button>
                        <input type="hidden" id="anggotaSelect" value="" data-all="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
                    <select id="jenisSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="all">Semua Jenis</option>
                        <option value="pokok">Simpanan Pokok</option>
                        <option value="wajib">Simpanan Wajib</option>
                        <option value="sukarela">Simpanan Sukarela</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input id="jumlahInput" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" min="0">
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('savingsModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="simpanBtn" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
// Helper format rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Autocomplete anggota
function setupAnggotaSearch() {
    const input = document.getElementById('anggotaSearch');
    const results = document.getElementById('anggotaResults');
    const hidden = document.getElementById('anggotaSelect');

    input.addEventListener('input', function() {
        const q = input.value.trim();
        if (!q) {
            results.innerHTML = '';
            results.classList.add('hidden');
            hidden.value = '';
            return;
        }

        fetch('/admin/search-anggota-nama?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';
                if (data.length === 0) {
                    results.classList.add('hidden');
                    return;
                }

                data.forEach(a => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-emerald-100 cursor-pointer';
                    div.textContent = a.nama;
                    div.dataset.id = a.id;

                    // ketika dipilih, isi input & hidden value
                    div.addEventListener('click', function() {
                        input.value = a.nama;
                        hidden.value = a.id;
                        hidden.setAttribute('data-all', '0'); // ✅ fix disini
                        results.classList.add('hidden');
                    });

                    results.appendChild(div);
                });

                results.classList.remove('hidden');
            });
    });
}

// Load simpanan ke tabel
function loadSimpanan() {
    const jenis = document.getElementById('jenisSelect').value;
    const anggota = document.getElementById('anggotaSelect').getAttribute('data-all') === '1'
        ? 'all'
        : document.getElementById('anggotaSelect').value;
    let url = `/admin/get-simpanan-list?jenis=${jenis}`;
    if (anggota && anggota !== '' && anggota !== 'all') url += `&id_anggota=${anggota}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('simpananTableBody');
            tbody.innerHTML = '';
            let rows = [];
            if (Array.isArray(data) && data.length && Array.isArray(data[0])) {
                data.forEach(arr => rows = rows.concat(arr));
            } else {
                rows = data;
            }
            rows.forEach(row => {
                tbody.innerHTML += `
                    <tr class="table-row">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.tanggal}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.anggota}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.jenis.charAt(0).toUpperCase() + row.jenis.slice(1)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(row.jumlah)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">${row.status}</span>
                        </td>
                    </tr>
                `;
            });
        });
}

function setSemuaAnggotaActive(active) {
    const anggotaSelect = document.getElementById('anggotaSelect');
    if (active) {
        anggotaSelect.setAttribute('data-all', '1');
        anggotaSelect.value = '';
        document.getElementById('anggotaSearch').value = 'Semua Anggota';
    } else {
        anggotaSelect.setAttribute('data-all', '0');
        document.getElementById('anggotaSearch').value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setupAnggotaSearch();
    loadSimpanan();
    document.getElementById('jenisSelect').addEventListener('change', loadSimpanan);
    // Tombol semua anggota toggle
    document.getElementById('semuaAnggotaBtn').addEventListener('click', function() {
        const isActive = document.getElementById('anggotaSelect').getAttribute('data-all') === '1';
        if (isActive) {
            setSemuaAnggotaActive(false);
        } else {
            document.getElementById('anggotaSearch').value = '';
            document.getElementById('anggotaSelect').value = '';
            setSemuaAnggotaActive(true);
        }
        loadSimpanan();
    });
    // Modal input simpanan
    document.querySelector('#savingsModal form').addEventListener('submit', function(e) {
        e.preventDefault();
        const anggota = document.getElementById('anggotaSelect').getAttribute('data-all') === '1'
            ? 'all'
            : document.getElementById('anggotaSelect').value;
        const jenis = document.getElementById('jenisSelect').value;
        const jumlah = document.getElementById('jumlahInput').value;
        if (!jenis || !jumlah || (!anggota && anggota !== 'all')) {
            alert('Lengkapi semua field!');
            return;
        }
        fetch('/admin/input-simpanan', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id_anggota=${encodeURIComponent(anggota)}&jenis=${encodeURIComponent(jenis)}&jumlah=${encodeURIComponent(jumlah)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal('savingsModal');
                loadSimpanan();
                document.getElementById('jumlahInput').value = '';
                alert('Data berhasil disimpan!');
            } else {
                alert('Data gagal disimpan!');
            }
        })
        .catch(() => {
            alert('Data gagal disimpan!');
        });
    });
});
</script>
</body>
</html>