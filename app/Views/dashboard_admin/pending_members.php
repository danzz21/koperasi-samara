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
      <th style="padding: 12px; text-align:center;">Foto Diri</th>
      <th style="padding: 12px; text-align:center;">Foto KTP</th>
      <th style="padding: 12px; text-align:center;">Foto Diri + KTP</th>
      <th style="padding: 12px; text-align:center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($anggota as $a): ?>
      <tr style="border-bottom: 1px solid #ddd; transition: background 0.3s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
        <td style="padding: 12px;"><?= $a['nama_lengkap'] ?></td>
        <td style="padding: 12px;"><?= $a['email'] ?></td>
        <td style="padding: 12px; text-align:center;">
          <?php if(!empty($a['foto_diri'])): ?>
            <a href="#" onclick="showImageModal('<?= base_url('writable/uploads/' . $a['foto_diri']) ?>', 'Foto Diri - <?= $a['nama_lengkap'] ?>')" style="color: #007bff; text-decoration: none; font-weight: bold;">👁️ Lihat</a>
          <?php else: ?>
            <span style="color: #999;">Tidak ada</span>
          <?php endif; ?>
        </td>
        <td style="padding: 12px; text-align:center;">
          <?php if(!empty($a['foto_ktp'])): ?>
            <a href="#" onclick="showImageModal('<?= base_url('writable/uploads/' . $a['foto_ktp']) ?>', 'Foto KTP - <?= $a['nama_lengkap'] ?>')" style="color: #007bff; text-decoration: none; font-weight: bold;">👁️ Lihat</a>
          <?php else: ?>
            <span style="color: #999;">Tidak ada</span>
          <?php endif; ?>
        </td>
        <td style="padding: 12px; text-align:center;">
          <?php if(!empty($a['foto_diri_ktp'])): ?>
            <a href="#" onclick="showImageModal('<?= base_url('writable/uploads/' . $a['foto_diri_ktp']) ?>', 'Foto Diri + KTP - <?= $a['nama_lengkap'] ?>')" style="color: #007bff; text-decoration: none; font-weight: bold;">👁️ Lihat</a>
          <?php else: ?>
            <span style="color: #999;">Tidak ada</span>
          <?php endif; ?>  
        </td>
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

<!-- Modal untuk menampilkan gambar -->
<div id="imageModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);">
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
    <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%; border-radius: 8px;">
    <div style="text-align: center; margin-top: 10px;">
      <button onclick="closeImageModal()" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Tutup</button>
    </div>
  </div>
</div>

<script>
function showImageModal(imageSrc, title) {
  document.getElementById('modalImage').src = imageSrc;
  document.getElementById('modalImage').alt = title;
  document.getElementById('imageModal').style.display = 'block';
}

function closeImageModal() {
  document.getElementById('imageModal').style.display = 'none';
  document.getElementById('modalImage').src = '';
}

// Tutup modal ketika klik di luar gambar
document.getElementById('imageModal').addEventListener('click', function(event) {
  if (event.target === this) {
    closeImageModal();
  }
});
</script>

</body>
</html>