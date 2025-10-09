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

<table class="w-full border text-sm">
    <thead>
        <tr class="bg-gray-100">
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">ID Anggota</th>
            <th class="p-2 border">Jenis</th>
            <th class="p-2 border">Tanggal</th>
            <th class="p-2 border">Jumlah</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pending)): ?>
            <?php foreach($pending as $p): ?>
                <tr>
                    <td class="p-2 border"><?= esc($p['nama_lengkap'] ?? 'N/A') ?></td>
                    <td class="p-2 border"><?= esc($p['nomor_anggota'] ?? 'N/A') ?></td>
                    <td class="p-2 border"><?= ucfirst($p['jenis'] ?? '') ?></td>
                    <td class="p-2 border"><?= date('d-m-Y', strtotime($p['tanggal'] ?? '')) ?></td>
                    <td class="p-2 border">Rp <?= number_format($p['jml_pinjam'] ?? 0, 0, ',', '.') ?></td>
                    <td class="p-2 border">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                            <?= ucfirst($p['status'] ?? '') ?>
                        </span>
                    </td>
                    <td class="p-2 border space-x-2">
                        <!-- PERBAIKAN: Sesuaikan URL dengan routes yang ada -->
                        <a href="<?= base_url("admin/pinjaman/verifikasi/{$p['jenis']}/{$p['id']}") ?>" 
                           class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Verifikasi
                        </a>
                        <a href="<?= base_url("admin/pinjaman/tolak/{$p['jenis']}/{$p['id']}") ?>" 
                           class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Tolak
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="p-4 border text-center text-gray-500">
                    Tidak ada data pinjaman pending
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
