<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2 style="font-family: Arial, sans-serif; color: #000000ff; margin-bottom: 15px; font-weight: bold; text-align: center; font-size: 20px;">
  Daftar Anggota Pending
</h2>


<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
  <thead>
    <tr style="background: #198754; color: white; text-align: left;">
      <th style="padding: 12px;">Nama</th>
      <th style="padding: 12px;">Email</th>
      <th style="padding: 12px; text-align:center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($anggota as $a): ?>
      <tr style="border-bottom: 1px solid #ddd; transition: background 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
        <td style="padding: 12px;"><?= $a['nama_lengkap'] ?></td>
        <td style="padding: 12px;"><?= $a['email'] ?></td>
        <td style="padding: 12px; text-align:center;">
          <a href="<?= base_url('admin/verify/'.$a['id']) ?>" 
             style="background: #4CAF50; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-right:5px;">
             ✅ Terima
          </a>
          <a href="<?= base_url('admin/reject/'.$a['id']) ?>" 
             onclick="return confirm('Yakin ingin menolak pendaftaran ini?')"
             style="background: #f44336; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold;">
             ❌ Tolak
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>