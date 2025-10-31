<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white shadow-md rounded-lg p-6 w-full max-w-lg">
    <h1 class="text-xl font-bold mb-4">Edit Data Anggota</h1>

    <form action="<?= base_url('admin/update-anggota/'.$anggota['id_anggota']) ?>" method="post" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= esc($anggota['nama_lengkap']) ?>"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">No. KTP</label>
            <input type="text" name="no_ktp" value="<?= esc($anggota['no_ktp']) ?>"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="Aktif" <?= $anggota['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Menunggu Verifikasi" <?= $anggota['status'] == 'Menunggu Verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                <option value="Nonaktif" <?= $anggota['status'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Tanggal Daftar</label>
            <input type="date" name="tanggal_daftar" value="<?= esc($anggota['tanggal_daftar']) ?>"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex space-x-2">
            <a href="<?= base_url('admin/detail-anggota/'.$anggota['id_anggota']) ?>" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Simpan</button>
        </div>
    </form>
</div>

</body>
</html>
