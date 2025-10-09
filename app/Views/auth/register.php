<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun Baru</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/register.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .step {
      display: none;
    }

    .step.active {
      display: block;
    }

    .button-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-top: 15px;
    }

    .button-group button {
      flex: 1;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .back {
      background-color: #ccc;
    }

    .next, .register {
      background-color: #4CAF50;
      color: white;
    }
    .camera-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 10px 0;
}

video {
  width: 100%;
  max-width: 250px;   /* ukuran kamera */
  border-radius: 10px;
  box-shadow: 0 0 6px rgba(0,0,0,0.2);
}

#preview {
  width: 100%;
  max-width: 250px;   /* ukuran preview foto */
  border-radius: 10px;
  box-shadow: 0 0 6px rgba(0,0,0,0.2);
}

#takePhoto {
  margin-top: 8px;
  padding: 8px 14px;
  flex: 1;
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
video {
  width: 100%;
  max-width: 250px;
  border-radius: 10px;
  box-shadow: 0 0 6px rgba(0,0,0,0.2);
  transform: scaleX(-1); /* flip horizontal supaya gak mirror */
}
#ktpCamera,
#ktpPreview 
{
  width: 100%;
  max-width: 250px; /* Samain dengan foto diri */
  border-radius: 10px;
  box-shadow: 0 0 6px rgba(0,0,0,0.2);
}
  </style>
</head>
<body>
  <div class="box">
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="logo">
    <h2>Buat Akun Baru</h2>
    <form id="registerForm" method="post" action="<?= base_url('register/store') ?>">

      <!-- STEP 1 -->
      <div class="step active">
        <input type="text" name="nama_lengkap" placeholder="Isi Nama Lengkap" required>
        <input type="email" name="email" placeholder="Masukan Email Anda" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="button-group">
          <button type="button" class="next">Next</button>
        </div>
      </div>

  <!-- STEP 2 -->
<div class="step">
  <input type="number" name="no_ktp" placeholder="Nomor KTP" required>

 <!-- Kamera Langsung -->
<div class="camera-container">
  <button type="button" id="openCamera">Foto Diri</button>
  <video id="camera" autoplay playsinline style="display:none;"></video>
  <canvas id="canvas" style="display:none;"></canvas>
  <button type="button" id="takePhoto" style="display:none;">Ambil Foto</button>
  <img id="preview" style="display:none; margin-top:10px; border-radius:8px;">
  <input type="hidden" name="foto_diri" id="foto_diri">
</div>

<!-- Foto KTP -->
<div class="camera-container">
  <button type="button" id="openKtpCamera">Foto KTP</button>
  <video id="ktpCamera" autoplay playsinline style="display:none;"></video>
  <canvas id="ktpCanvas" style="display:none;"></canvas>
  <button type="button" id="takeKtpPhoto" style="display:none;">Ambil Foto KTP</button>
  <img id="ktpPreview" style="display:none; margin-top:10px; border-radius:8px;">
  <input type="hidden" name="foto_ktp" id="foto_ktp">
</div>

  <input type="number" name="no_hp" placeholder="Nomor HP" required>
  <input type="number" name="nomor_hp_keluarga" placeholder="Nomor Opsional (Keluarga)">
  <div class="button-group">
    <button type="button" class="back">Back</button>
    <button type="submit" class="register">Register</button>
  </div>
</div>

</div>

    </form>
  </div>

 <script>
  const steps = document.querySelectorAll('.step');
const nextBtn = document.querySelector('.next');
const backBtn = document.querySelector('.back');
const form = document.getElementById('registerForm');

// Validasi sebelum next
nextBtn.addEventListener('click', () => {
  // Ambil semua input di Step 1
  const step1Inputs = steps[0].querySelectorAll('input[required]');
  let valid = true;

  step1Inputs.forEach(input => {
    if (!input.value.trim()) {
      input.style.border = "2px solid red"; // kasih tanda merah
      valid = false;
    } else {
      input.style.border = "1px solid #ccc"; // reset kalau udah diisi
    }
  });

  if (!valid) {
    alert("Harap isi semua field sebelum lanjut!");
    return; // stop biar ga lanjut
  }

  // kalau valid, baru ganti step
  steps[0].classList.remove('active');
  steps[1].classList.add('active');
});

// tombol back
backBtn.addEventListener('click', () => {
  steps[1].classList.remove('active');
  steps[0].classList.add('active');
});

// Redirect ke login setelah register
// form.addEventListener('submit', function(e) {
//   e.preventDefault(); 
//   alert("Registrasi berhasil!");
//   window.location.href = "<?= base_url('login') ?>"; 
// });


  const video = document.getElementById('camera');
const canvas = document.getElementById('canvas');
const openCameraBtn = document.getElementById('openCamera');
const takePhotoBtn = document.getElementById('takePhoto');
const preview = document.getElementById('preview');
const fotoInput = document.getElementById('foto_diri');
const ctx = canvas.getContext('2d');
let stream; // simpan stream kamera biar bisa dimatiin

// Buka kamera pas tombol ditekan
openCameraBtn.addEventListener('click', () => {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(s => {
      stream = s;
      video.srcObject = stream;
      video.style.display = "block";
      takePhotoBtn.style.display = "inline-block";
      openCameraBtn.style.display = "none"; // sembunyiin tombol buka kamera
    })
    .catch(err => {
      alert("Gagal akses kamera: " + err);
    });
});

// Ambil foto
takePhotoBtn.addEventListener('click', () => {
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;

  // Biar gak mirror
  ctx.save();
  ctx.translate(canvas.width, 0);
  ctx.scale(-1, 1);
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  ctx.restore();

  const dataURL = canvas.toDataURL("image/png");
  preview.src = dataURL;
  preview.style.display = "block";
  fotoInput.value = dataURL; // simpan base64 ke input hidden

  // Matikan kamera setelah ambil foto
  stream.getTracks().forEach(track => track.stop());
  video.style.display = "none";
  takePhotoBtn.style.display = "none";
  openCameraBtn.style.display = "inline-block"; // bisa buka kamera lagi kalau mau foto ulang
});

const ktpVideo = document.getElementById('ktpCamera');
const ktpCanvas = document.getElementById('ktpCanvas');
const openKtpCameraBtn = document.getElementById('openKtpCamera');
const takeKtpPhotoBtn = document.getElementById('takeKtpPhoto');
const ktpPreview = document.getElementById('ktpPreview');
const ktpInput = document.getElementById('foto_ktp');
const ktpCtx = ktpCanvas.getContext('2d');
let ktpStream;

// Buka kamera untuk foto KTP
openKtpCameraBtn.addEventListener('click', () => {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(s => {
      ktpStream = s;
      ktpVideo.srcObject = ktpStream;
      ktpVideo.style.display = "block";
      takeKtpPhotoBtn.style.display = "inline-block";
      openKtpCameraBtn.style.display = "none";
    })
    .catch(err => {
      alert("Gagal akses kamera: " + err);
    });
});

// Ambil foto KTP
takeKtpPhotoBtn.addEventListener('click', () => {
  ktpCanvas.width = ktpVideo.videoWidth;
  ktpCanvas.height = ktpVideo.videoHeight;

  ktpCtx.save();
  ktpCtx.translate(ktpCanvas.width, 0);
  ktpCtx.scale(-1, 1);
  ktpCtx.drawImage(ktpVideo, 0, 0, ktpCanvas.width, ktpCanvas.height);
  ktpCtx.restore();

  const dataURL = ktpCanvas.toDataURL("image/png");
  ktpPreview.src = dataURL;
  ktpPreview.style.display = "block";
  ktpInput.value = dataURL;

  ktpStream.getTracks().forEach(track => track.stop());
  ktpVideo.style.display = "none";
  takeKtpPhotoBtn.style.display = "none";
  openKtpCameraBtn.style.display = "inline-block";
});
</script>

</body>
</html>
