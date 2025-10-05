<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Setoran Sukarela Pending</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        a {
            text-decoration: none;
            font-weight: bold;
        }

        .approve {
            color: green;
        }

        .reject {
            color: red;
        }

        .bukti-link {
            color: #007bff;
        }

        .bukti-link:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <h2>Daftar Setoran Sukarela Pending</h2>

    <?php if (empty($pending)): ?>
        <div class="no-data">Tidak ada setoran pending saat ini.</div>
    <?php else: ?>
        <table>
            <tr>
                <th>Nama</th>
                <th>ID Anggota</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($pending as $p): ?>
                <tr>
                    <td><?= esc($p['nama_lengkap']) ?></td>
                    <td><?= esc($p['nomor_anggota']) ?></td>
                    <td><?= date('d-m-Y', strtotime($p['tanggal'])) ?></td>
                    <td>Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($p['bukti']): ?>
                            <a class="bukti-link" href="<?= base_url($p['bukti']) ?>" target="_blank">Lihat Bukti</a>
                        <?php else: ?>
                            <span style="color: gray;">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="approve" href="<?= base_url('admin/approve-sukarela/' . $p['id_ss']) ?>">✅ Setujui</a> |
                        <a class="reject" href="<?= base_url('admin/reject-sukarela/' . $p['id_ss']) ?>" onclick="return confirm('Tolak setoran ini?')">❌ Tolak</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>
