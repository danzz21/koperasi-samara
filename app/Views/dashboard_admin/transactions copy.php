<?php if (session()->getFlashdata('success')): ?>
  <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
    <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
    <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaksi Umum</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .animate-fade-in {
      animation: fadeIn 0.25s ease-in-out;
    }
    @keyframes fadeIn {
      from { transform: scale(0.95); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body class="bg-gray-50 px-4 py-6">

  <!-- Judul -->
  <div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">💰 Transaksi Umum</h2>
    <p class="text-gray-600">Kelola pemasukan dan pengeluaran operasional</p>
  </div>

  <!-- Ringkasan -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pemasukan Bulan Ini</h3>
      <p class="text-4xl font-bold text-green-600">Rp <?= number_format($pemasukan,0,',','.') ?></p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pengeluaran Bulan Ini</h3>
      <p class="text-4xl font-bold text-red-600">Rp <?= number_format($pengeluaran,0,',','.') ?></p>
    </div>
  </div>

  <!-- Tabel Transaksi -->
  <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="flex items-center justify-between px-6 py-4 border-b">
      <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h3>
      <div class="space-x-2">
        <button onclick="openModal('incomeModal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm">
          + Pemasukan
        </button>
        <button onclick="openModal('expenseModal')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm">
          - Pengeluaran
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Tanggal</th>
            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Deskripsi</th>
            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Kategori</th>
            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Jumlah</th>
            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Jenis</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $t): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                <td class="px-6 py-4"><?= esc($t['deskripsi']) ?></td>
                <td class="px-6 py-4"><?= esc($t['kategori']) ?></td>
                <td class="px-6 py-4 font-semibold">Rp <?= number_format($t['jumlah'],0,',','.') ?></td>
                <td class="px-6 py-4">
                  <?php if ($t['jenis'] === 'Pemasukan'): ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Pemasukan</span>
                  <?php else: ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Pengeluaran</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center py-6 text-gray-500">Belum ada transaksi</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Pemasukan -->
  <div id="incomeModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 animate-fade-in">
      <div class="flex justify-between items-center mb-6 border-b pb-3">
        <h3 class="text-xl font-bold text-gray-800">Input Pemasukan</h3>
      </div>
      <form class="space-y-4" action="<?= site_url('admin/dashboard_admin/transaction/simpan') ?>" method="post">
  <input type="hidden" name="jenis" value="Pemasukan">
        <div>
          <label>Deskripsi</label>
          <input type="text" name= "deskripsi" class="w-full px-3 py-2 border rounded-lg" />
        </div>
        <div>
          <label>Kategori</label>
          <select name="kategori" class="w-full px-3 py-2 border rounded-lg">
            <option>Bagi Hasil</option>
            <option>Jasa Administrasi</option>
            <option>Lain-lain</option>
          </select>
        </div>
        <div>
          <label>Jumlah</label>
          <input type="number" name="jumlah" class="w-full px-3 py-2 border rounded-lg" />
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" onclick="closeModal('incomeModal')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Pengeluaran -->
  <div id="expenseModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 animate-fade-in">
      <div class="flex justify-between items-center mb-6 border-b pb-3">
        <h3 class="text-xl font-bold text-gray-800">Input Pengeluaran</h3>
      </div>
      <form class="space-y-4" action="<?= base_url('admin/dashboard_admin/transaction/simpan') ?>" method="post">
  <input type="hidden" name="jenis" value="Pengeluaran">
        <div>
          <label>Deskripsi</label>
          <input type="text" name="deskripsi" class="w-full px-3 py-2 border rounded-lg" />
        </div>
        <div>
          <label>Kategori</label>
          <select name="kategori" class="w-full px-3 py-2 border rounded-lg">
            <option>Operasional</option>
            <option>Pemeliharaan</option>
            <option>Gaji</option>
            <option>Lain-lain</option>
          </select>
        </div>
        <div>
          <label>Jumlah</label>
          <input type="number" name="jumlah" class="w-full px-3 py-2 border rounded-lg" />
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" onclick="closeModal('expenseModal')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
          <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script Modal -->
  <script>
    function openModal(id) {
      const modal = document.getElementById(id);
      modal.classList.remove('hidden');
      modal.classList.add('animate-fade-in');
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      modal.classList
            modal.classList.add('hidden');
      modal.classList.remove('animate-fade-in');
    }
  </script>

</body>
</html>
