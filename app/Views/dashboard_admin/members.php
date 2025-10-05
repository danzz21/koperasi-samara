<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Anggota</title>
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

            <!-- FORM SEARCH -->
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
                            $id = $data['id_anggota'];
                            $nama = ucwords($data['nama_lengkap']);
                            $ktp = $data['no_ktp'];
                            $status = $data['status'] ?? 'Menunggu Verifikasi';
                            $tanggal = isset($data['tanggal_daftar']) ? date("d M Y", strtotime($data['tanggal_daftar'])) : '-';
                            $urlDetail = base_url('admin/detail-anggota/' . $id);
                            $badge = (strtolower($status) == 'aktif') ?
                                "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>$status</span>" :
                                "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>$status</span>";
                        ?>
                        <tr class='table-row cursor-pointer hover:bg-gray-100' onclick="window.location.href='<?= $urlDetail ?>'">
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $id ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'><?= $nama ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $ktp ?></td>
                            <td class='px-6 py-4 whitespace-nowrap'><?= $badge ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'><?= $tanggal ?></td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2'>
                                <button onclick="event.stopPropagation(); window.location.href='<?= base_url('admin/edit-anggota/' . $id) ?>'" class='text-blue-600 hover:text-blue-900'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <button onclick="event.stopPropagation(); window.location.href='<?= base_url('admin/detail-anggota/' . $id) ?>'" class='text-green-600 hover:text-green-900'>
                                    <i class='fas fa-eye'></i>
                                </button>
                                <button onclick="event.stopPropagation(); window.print();" class='text-orange-600 hover:text-orange-900'>
                                    <i class='fas fa-print'></i>
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
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tambah Anggota Baru</h3>
        
        <form action="<?= base_url('admin/dashboard_admin/simpan') ?>" method="post" enctype="multipart/form-data">


            <!-- STEP 1 -->
            <div id="step1" class="step">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP</label>
                    <input type="text" name="no_ktp" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" required />
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('memberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="button" onclick="nextStep()" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700">
                        Next
                    </button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div id="step2" class="step hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Diri</label>
                    <input type="file" name="foto_diri" accept="image/*" onchange="previewImage(event, 'previewDiri')" class="w-full border border-gray-300 rounded-md px-3 py-2" />
                    <img id="previewDiri" class="mt-2 w-24 h-24 object-cover rounded-md hidden">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto KTP</label>
                    <input type="file" name="foto_ktp" accept="image/*" onchange="previewImage(event, 'previewKtp')" class="w-full border border-gray-300 rounded-md px-3 py-2" />
                    <img id="previewKtp" class="mt-2 w-24 h-24 object-cover rounded-md hidden">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="no_telp" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500" />
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="prevStep()" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600">
                        Previous
                    </button>
                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
            document.getElementById(id).classList.remove("flex");
        }
 let currentStep = 1;
    function nextStep() {
        document.getElementById("step1").classList.add("hidden");
        document.getElementById("step2").classList.remove("hidden");
        currentStep = 2;
    }
    function prevStep() {
        document.getElementById("step2").classList.add("hidden");
        document.getElementById("step1").classList.remove("hidden");
        currentStep = 1;
    }

    function previewImage(event, previewId) {
        const file = event.target.files[0];
        const preview = document.getElementById(previewId);
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove("hidden");
            }
            reader.readAsDataURL(file);
        }
    }
        // Live search anggota
        const searchInput = document.getElementById('searchInput');
        const anggotaTableBody = document.getElementById('anggotaTableBody');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchTimeout = setTimeout(() => {
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
                            anggotaTableBody.innerHTML += `
                                <tr class='table-row cursor-pointer hover:bg-gray-100' onclick="window.location.href='${item.urlDetail}'">
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${item.id}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>${item.nama_lengkap}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${item.no_ktp}</td>
                                    <td class='px-6 py-4 whitespace-nowrap'>${badge}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${item.tanggal_daftar}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2'>
                                        <button onclick="event.stopPropagation(); window.location.href='/admin/edit-anggota/${item.id}'" class='text-blue-600 hover:text-blue-900'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button onclick="event.stopPropagation(); window.location.href='${item.urlDetail}'" class='text-green-600 hover:text-green-900'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <button onclick="event.stopPropagation(); window.print();" class='text-orange-600 hover:text-orange-900'>
                                            <i class='fas fa-print'></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    });
            }, 300); // debounce 300ms
        });
    </script>
</body>
</html>
