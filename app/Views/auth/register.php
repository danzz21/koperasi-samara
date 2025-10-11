<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun Baru</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #10b981;
      --primary-light: #34d399;
      --primary-dark: #059669;
      --secondary: #06b6d4;
      --secondary-light: #22d3ee;
      --accent: #0ea5e9;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --dark: #1e293b;
      --light: #f8fafc;
      --gray: #64748b;
      --gray-light: #cbd5e1;
      --border-radius: 20px;
      --border-radius-sm: 12px;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 10px -2px rgba(0, 0, 0, 0.05);
      --shadow-lg: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }

    body {
      background: linear-gradient(135deg, 
        rgba(16, 185, 129, 0.15) 0%, 
        rgba(6, 182, 212, 0.15) 50%, 
        rgba(14, 165, 233, 0.1) 100%);
      color: var(--dark);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      line-height: 1.6;
      position: relative;
    }

    /* Background pattern yang subtle */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
      z-index: -1;
    }

    .box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: var(--border-radius);
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.8);
      width: 100%;
      max-width: 500px;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 6px;
      background: var(--gradient-primary);
    }

    .box img {
      display: block;
      margin: 0 auto 25px;
      max-width: 140px;
      filter: drop-shadow(0 3px 5px rgba(16, 185, 129, 0.2));
    }

    .box h2 {
      text-align: center;
      color: var(--dark);
      margin-bottom: 30px;
      font-weight: 700;
      font-size: 28px;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Step Indicator */
    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 35px;
      position: relative;
    }

    .step-indicator::before {
      content: '';
      position: absolute;
      top: 20px;
      left: 0;
      width: 100%;
      height: 4px;
      background: var(--gray-light);
      z-index: 1;
    }

    .step-indicator .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 2;
    }

    .step-indicator .step .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: white;
      color: var(--gray);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      margin-bottom: 10px;
      border: 3px solid var(--gray-light);
      transition: var(--transition);
      box-shadow: var(--shadow);
    }

    .step-indicator .step.active .step-number {
      background: var(--gradient-primary);
      color: white;
      border-color: var(--primary);
      box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
      transform: scale(1.1);
    }

    .step-indicator .step.completed .step-number {
      background: var(--gradient-primary);
      color: white;
      border-color: var(--primary);
    }

    .step-indicator .step .step-label {
      font-size: 13px;
      color: var(--gray);
      font-weight: 600;
      text-align: center;
    }

    .step-indicator .step.active .step-label {
      color: var(--primary);
      font-weight: 700;
    }

    /* Progress Line */
    .progress-line {
      position: absolute;
      top: 20px;
      left: 0;
      height: 4px;
      background: var(--gradient-primary);
      z-index: 2;
      transition: var(--transition);
      width: 0%;
    }

    .step-1 .progress-line {
      width: 0%;
    }

    .step-2 .progress-line {
      width: 50%;
    }

    .step-3 .progress-line {
      width: 100%;
    }

    /* Form Steps */
    .form-step {
      display: none;
    }

    .form-step.active {
      display: block;
      animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes fadeInUp {
      from { 
        opacity: 0; 
        transform: translateY(20px); 
      }
      to { 
        opacity: 1; 
        transform: translateY(0); 
      }
    }

    input {
      width: 100%;
      padding: 16px 18px;
      margin: 12px 0;
      border: 2px solid rgba(203, 213, 225, 0.5);
      border-radius: var(--border-radius-sm);
      font-size: 15px;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
      background-color: rgba(248, 250, 252, 0.8);
      color: var(--dark);
    }

    input::placeholder {
      color: var(--gray);
    }

    input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
      background-color: white;
      transform: translateY(-2px);
    }

    .input-error {
      border-color: var(--danger) !important;
      box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
    }

    /* Button Styles */
    .button-group {
      display: flex;
      justify-content: space-between;
      gap: 15px;
      margin-top: 30px;
    }

    .button-group button {
      flex: 1;
      padding: 16px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: var(--border-radius-sm);
      cursor: pointer;
      transition: var(--transition);
      font-family: 'Inter', sans-serif;
      position: relative;
      overflow: hidden;
    }

    .back {
      background-color: rgba(255, 255, 255, 0.9);
      color: var(--primary);
      border: 2px solid var(--primary);
      backdrop-filter: blur(5px);
    }

    /* HOVER EFFECT UNTUK BACK BUTTON - HANYA ACTIVE */
    .back:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
      border-color: var(--primary-light);
    }

    .next, .register {
      background: var(--gradient-primary);
      color: white;
      box-shadow: var(--shadow);
    }

    /* HOVER EFFECT YANG DIPERBAIKI - HANYA ACTIVE TANPA GANTI WARNA */
    .next:hover, .register:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }

    .next:active, .register:active {
      transform: translateY(-1px);
    }

    /* Camera Styles */
    .camera-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 20px 0;
      padding: 20px;
      background: linear-gradient(135deg, rgba(240, 253, 249, 0.7) 0%, rgba(236, 253, 245, 0.7) 100%);
      border-radius: var(--border-radius-sm);
      border: 2px dashed var(--primary-light);
      backdrop-filter: blur(5px);
    }

    .camera-container h3 {
      color: var(--primary);
      margin-bottom: 15px;
      font-size: 16px;
      text-align: center;
      font-weight: 600;
    }

    .camera-container button {
      padding: 14px 24px;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: var(--border-radius-sm);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin: 8px 0;
      font-family: 'Inter', sans-serif;
      box-shadow: var(--shadow);
    }

    /* HOVER EFFECT UNTUK CAMERA BUTTON - HANYA ACTIVE */
    .camera-container button:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .camera-container button:active {
      transform: translateY(0);
    }

    video {
      width: 100%;
      max-width: 280px;
      border-radius: var(--border-radius-sm);
      box-shadow: var(--shadow);
      transform: scaleX(-1);
      margin: 15px 0;
      border: 2px solid var(--primary);
    }

    #preview, #ktpPreview {
      width: 100%;
      max-width: 280px;
      border-radius: var(--border-radius-sm);
      box-shadow: var(--shadow);
      margin: 15px 0;
      border: 2px solid var(--primary);
    }

    #takePhoto, #takeKtpPhoto {
      margin-top: 10px;
      padding: 12px 20px;
      font-size: 14px;
    }

    #ktpCamera, #ktpPreview {
      width: 100%;
      max-width: 280px;
      border-radius: var(--border-radius-sm);
      box-shadow: var(--shadow);
      border: 2px solid var(--primary);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .box {
        padding: 25px 20px;
      }
      
      .button-group {
        flex-direction: column;
      }
      
      .step-indicator .step .step-label {
        font-size: 11px;
      }
      
      input {
        padding: 14px 16px;
      }
    }

    /* Additional decorative elements */
    .watermark {
      position: absolute;
      bottom: 10px;
      right: 15px;
      font-size: 12px;
      color: var(--gray-light);
      font-weight: 500;
    }

    .form-note {
      text-align: center;
      color: var(--gray);
      font-size: 14px;
      margin-top: 20px;
      padding: 10px;
      background: rgba(248, 250, 252, 0.7);
      border-radius: var(--border-radius-sm);
      backdrop-filter: blur(5px);
    }
  </style>
</head>
<body>
  <div class="box">
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="logo">
    <h2>Buat Akun Baru</h2>
    
    <!-- Step Indicator -->
    <div class="step-indicator step-1">
      <div class="progress-line"></div>
      <div class="step active">
        <div class="step-number">1</div>
        <div class="step-label">Data Diri</div>
      </div>
      <div class="step">
        <div class="step-number">2</div>
        <div class="step-label">Kontak & Rekening</div>
      </div>
      <div class="step">
        <div class="step-number">3</div>
        <div class="step-label">Dokumen</div>
      </div>
    </div>
    
    <form id="registerForm" method="post" action="<?= base_url('register/store') ?>">

      <!-- STEP 1 -->
      <div class="form-step active">
        <input type="text" name="nama_lengkap" placeholder="Isi Nama Lengkap" required>
        <input type="email" name="email" placeholder="Masukan Email Anda" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="button-group">
          <button type="button" class="next">Selanjutnya</button>
        </div>
      </div>

      <!-- STEP 2 -->
      <div class="form-step">
        <input type="number" name="no_hp" placeholder="Nomor HP" required>
        <input type="number" name="nomor_hp_keluarga" placeholder="Nomor Opsional (Keluarga)">
        <input type="text" name="no_rekening" placeholder="Nomor Rekening" required>
        <input type="text" name="atasnama_rekening" placeholder="Atas Nama Rekening" required>
        
        <div class="button-group">
          <button type="button" class="back">Kembali</button>
          <button type="button" class="next">Selanjutnya</button>
        </div>
      </div>

      <!-- STEP 3 -->
      <div class="form-step">
        <input type="number" name="no_ktp" placeholder="Nomor KTP" required>

        <!-- Kamera Langsung -->
        <div class="camera-container">
          <h3>Foto Diri</h3>
          <button type="button" id="openCamera">Buka Kamera</button>
          <video id="camera" autoplay playsinline style="display:none;"></video>
          <canvas id="canvas" style="display:none;"></canvas>
          <button type="button" id="takePhoto" style="display:none;">Ambil Foto</button>
          <img id="preview" style="display:none;">
          <input type="hidden" name="foto_diri" id="foto_diri">
        </div>

        <!-- Foto KTP -->
        <div class="camera-container">
          <h3>Foto KTP</h3>
          <button type="button" id="openKtpCamera">Buka Kamera</button>
          <video id="ktpCamera" autoplay playsinline style="display:none;"></video>
          <canvas id="ktpCanvas" style="display:none;"></canvas>
          <button type="button" id="takeKtpPhoto" style="display:none;">Ambil Foto KTP</button>
          <img id="ktpPreview" style="display:none;">
          <input type="hidden" name="foto_ktp" id="foto_ktp">
        </div>
        
        <div class="button-group">
          <button type="button" class="back">Kembali</button>
          <button type="submit" class="register">Daftar Sekarang</button>
        </div>
        
        <div class="form-note">
          Pastikan semua data yang Anda isi sudah benar dan valid.
        </div>
      </div>
    </form>
    
    <div class="watermark">Premium Registration</div>
  </div>

  <script>
    // Step Management
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step-indicator .step');
    const stepContainer = document.querySelector('.step-indicator');
    const progressLine = document.querySelector('.progress-line');
    const nextBtns = document.querySelectorAll('.next');
    const backBtns = document.querySelectorAll('.back');
    const form = document.getElementById('registerForm');
    const registerBtn = document.querySelector('.register');

    let currentStep = 0;

    // Update step indicator
    function updateStepIndicator(step) {
      stepIndicators.forEach((indicator, index) => {
        if (index < step) {
          indicator.classList.add('completed');
          indicator.classList.remove('active');
        } else if (index === step) {
          indicator.classList.add('active');
          indicator.classList.remove('completed');
        } else {
          indicator.classList.remove('active', 'completed');
        }
      });
      
      // Update progress line
      stepContainer.classList.remove('step-1', 'step-2', 'step-3');
      stepContainer.classList.add(`step-${step + 1}`);
      
      if (step === 0) {
        progressLine.style.width = '0%';
      } else if (step === 1) {
        progressLine.style.width = '50%';
      } else {
        progressLine.style.width = '100%';
      }
    }

    // Validasi sebelum next
    function validateStep(step) {
      const stepInputs = steps[step].querySelectorAll('input[required]');
      let valid = true;

      stepInputs.forEach(input => {
        if (!input.value.trim()) {
          input.classList.add('input-error');
          valid = false;
        } else {
          input.classList.remove('input-error');
        }
      });

      return valid;
    }

    // Navigasi ke step berikutnya
    function goToNextStep() {
      if (!validateStep(currentStep)) {
        alert("Harap isi semua field sebelum lanjut!");
        return;
      }

      steps[currentStep].classList.remove('active');
      currentStep++;
      steps[currentStep].classList.add('active');
      updateStepIndicator(currentStep);
    }

    // Navigasi ke step sebelumnya
    function goToPrevStep() {
      steps[currentStep].classList.remove('active');
      currentStep--;
      steps[currentStep].classList.add('active');
      updateStepIndicator(currentStep);
    }

    // Event listeners untuk tombol next
    nextBtns.forEach(btn => {
      btn.addEventListener('click', goToNextStep);
    });

    // Event listeners untuk tombol back
    backBtns.forEach(btn => {
      btn.addEventListener('click', goToPrevStep);
    });

    // Validasi sebelum submit form
    registerBtn.addEventListener('click', function(e) {
      e.preventDefault(); // Mencegah submit langsung
      
      if (!validateStep(currentStep)) {
        alert("Harap isi semua field yang wajib diisi!");
        return;
      }

      // Validasi foto
      const fotoDiri = document.getElementById('foto_diri').value;
      const fotoKtp = document.getElementById('foto_ktp').value;
      
      if (!fotoDiri) {
        alert("Harap ambil foto diri terlebih dahulu!");
        return;
      }
      
      if (!fotoKtp) {
        alert("Harap ambil foto KTP terlebih dahulu!");
        return;
      }

      // Jika semua valid, submit form
      form.submit();
    });

    // Camera functionality
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const openCameraBtn = document.getElementById('openCamera');
    const takePhotoBtn = document.getElementById('takePhoto');
    const preview = document.getElementById('preview');
    const fotoInput = document.getElementById('foto_diri');
    const ctx = canvas.getContext('2d');
    let stream;

    // Buka kamera pas tombol ditekan
    openCameraBtn.addEventListener('click', () => {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(s => {
          stream = s;
          video.srcObject = stream;
          video.style.display = "block";
          takePhotoBtn.style.display = "inline-block";
          openCameraBtn.style.display = "none";
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
      fotoInput.value = dataURL;

      // Matikan kamera setelah ambil foto
      stream.getTracks().forEach(track => track.stop());
      video.style.display = "none";
      takePhotoBtn.style.display = "none";
      openCameraBtn.style.display = "inline-block";
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