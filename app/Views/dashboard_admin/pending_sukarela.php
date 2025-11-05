
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Setoran Sukarela Pending</title>
</head>
<body>

<h2 style="font-family: Arial, sans-serif; color: #000000ff; margin-bottom: 15px; font-weight: bold; text-align: center; font-size: 20px;">
  Daftar Setoran Sukarela Pending
</h2>

<?php if (empty($pending)): ?>
  <div style="text-align:center; font-family: Arial, sans-serif; color: #777; font-style: italic; margin-top: 20px;">
    Tidak ada setoran pending saat ini.
  </div>
<?php else: ?>
<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
  <thead>
    <tr style="background: #198754; color: white; text-align: left;">
      <th style="padding: 12px;">Nama</th>
      <th style="padding: 12px;">ID Anggota</th>
      <th style="padding: 12px;">Tanggal</th>
      <th style="padding: 12px;">Jumlah</th>
      <th style="padding: 12px;">Bukti Transfer</th>
      <th style="padding: 12px; text-align:center;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pending as $p): ?>
      <tr style="border-bottom: 1px solid #ddd; transition: background 0.3s;" 
          onmouseover="this.style.background='#f9f9f9'" 
          onmouseout="this.style.background='white'">
        <td style="padding: 12px;"><?= esc($p['nama_lengkap']) ?></td>
        <td style="padding: 12px;"><?= esc($p['nomor_anggota']) ?></td>
        <td style="padding: 12px;"><?= date('d-m-Y', strtotime($p['tanggal'])) ?></td>
        <td style="padding: 12px;">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
        <td style="padding: 12px;">
          <?php if (!empty($p['bukti'])): ?>
            <a href="javascript:void(0)" onclick="showImageModal('<?= base_url('uploads/' . $p['bukti']) ?>')"
               style="color: #007bff; text-decoration: none; font-weight: bold;">Lihat Bukti</a>
          <?php else: ?>
            <span style="color: gray;">Tidak ada</span>
          <?php endif; ?>
        </td>
        <td style="padding: 12px; text-align:center;">
          <a href="<?= base_url('admin/approve-sukarela/' . $p['id_ss']) ?>" 
             style="background: #4CAF50; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-right:5px;">
             ✅ Setujui
          </a>
          <a href="<?= base_url('admin/reject-sukarela/' . $p['id_ss']) ?>" 
             onclick="return confirm('Tolak setoran ini?')" 
             style="background: #f44336; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold;">
             ❌ Tolak
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

</body>
</html>

<!-- Modal untuk menampilkan gambar bukti transfer -->
<div id="imageModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
        <img id="modalImage" src="" alt="Bukti Transfer" style="max-width: 100%; max-height: 100%; display: block;">
        <button onclick="closeImageModal()" style="position: absolute; top: -10px; right: -10px; background: red; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-size: 18px;">×</button>
    </div>
</div>

<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'block';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Klik di luar modal untuk menutup
document.getElementById('imageModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeImageModal();
    }
});
</script>
