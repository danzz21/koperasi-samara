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

    input, select {
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

    input::placeholder, select::placeholder {
      color: var(--gray);
    }

    input:focus, select:focus {
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

    /* Validation Message */
    .validation-message {
      font-size: 12px;
      color: var(--danger);
      margin-top: -8px;
      margin-bottom: 8px;
      display: none;
    }

    .validation-message.show {
      display: block;
      animation: fadeInUp 0.3s ease-out;
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

    #preview, #ktpPreview, #diriKtpPreview {
      width: 100%;
      max-width: 280px;
      border-radius: var(--border-radius-sm);
      box-shadow: var(--shadow);
      margin: 15px 0;
      border: 2px solid var(--primary);
    }

    #takePhoto, #takeKtpPhoto, #takeDiriKtpPhoto {
      margin-top: 10px;
      padding: 12px 20px;
      font-size: 14px;
    }

    #ktpCamera, #ktpPreview, #diriKtpCamera {
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
      
      input, select {
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

    /* Styling untuk bank info */
    .bank-info {
      display: none;
      margin-top: 10px;
      padding: 15px;
      background: linear-gradient(135deg, rgba(240, 253, 249, 0.7) 0%, rgba(236, 253, 245, 0.7) 100%);
      border-radius: var(--border-radius-sm);
      border-left: 4px solid var(--primary);
      animation: fadeInUp 0.4s ease-out;
    }

    .bank-info p {
      margin: 5px 0;
      font-size: 14px;
      color: var(--dark);
    }

    .bank-info .bank-name {
      font-weight: 700;
      color: var(--primary);
      font-size: 16px;
    }

    /* Styling untuk instruksi foto */
    .photo-instruction {
      background: rgba(255, 255, 255, 0.8);
      padding: 12px;
      border-radius: var(--border-radius-sm);
      margin: 10px 0;
      font-size: 13px;
      color: var(--dark);
      border-left: 3px solid var(--warning);
    }

    .photo-instruction ul {
      margin: 8px 0;
      padding-left: 20px;
    }

    .photo-instruction li {
      margin: 4px 0;
    }

    /* Info ukuran foto */
    .size-info {
      font-size: 12px;
      color: var(--gray);
      margin-top: 5px;
      text-align: center;
    }

    .size-info.warning {
      color: var(--warning);
    }

    .size-info.danger {
      color: var(--danger);
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
        <input type="password" name="password" placeholder="Password (minimal 6 karakter)" minlength="6" required>
        <div class="validation-message" id="passwordValidation">Password harus minimal 6 karakter</div>
        
        <div class="button-group">
          <button type="button" class="next">Selanjutnya</button>
        </div>
      </div>

      <!-- STEP 2 -->
      <div class="form-step">
        <input type="tel" name="no_hp" placeholder="Nomor HP (11-13 digit)" minlength="11" maxlength="13" pattern="[0-9]{11,13}" required>
        <div class="validation-message" id="noHpValidation">Nomor HP harus 11-13 digit angka</div>
        
        <input type="tel" name="nomor_hp_keluarga" placeholder="Nomor Opsional (Keluarga) 11-13 digit" minlength="11" maxlength="13" pattern="[0-9]{11,13}">
        <div class="validation-message" id="noHpKeluargaValidation">Nomor HP keluarga harus 11-13 digit angka</div>
        
        <!-- Dropdown untuk memilih bank -->
        <select name="jenis_bank" id="jenis_bank" required>
          <option value="" disabled selected>Pilih Bank</option>
          <option value="BCA">BCA (Bank Central Asia)</option>
          <option value="BSI">BSI (Bank Syariah Indonesia)</option>
          <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
          <option value="Mandiri">Mandiri</option>
          <option value="BNI">BNI (Bank Negara Indonesia)</option>
          <option value="CIMB">CIMB Niaga</option>
          <option value="Danamon">Danamon</option>
          <option value="Permata">Permata</option>
          <option value="Maybank">Maybank</option>
          <option value="OCBC">OCBC NISP</option>
          <option value="Panin">Panin Bank</option>
          <option value="BTN">BTN (Bank Tabungan Negara)</option>
          <option value="Bukopin">Bukopin</option>
          <option value="Mega">Bank Mega</option>
          <option value="BJB">BJB (Bank Jabar Banten)</option>
          <option value="DKI">Bank DKI</option>
          <option value="BPD">Bank BPD Lainnya</option>
          <option value="Lainnya">Bank Lainnya</option>
        </select>
        
        <!-- Informasi bank yang dipilih -->
        <div id="bankInfo" class="bank-info">
          <p class="bank-name" id="selectedBankName"></p>
          <p id="bankDescription">Silakan isi nomor rekening untuk bank yang dipilih</p>
        </div>
        
        <!-- Input nomor rekening (akan muncul setelah bank dipilih) -->
        <div id="rekeningContainer" style="display: none;">
          <input type="text" name="no_rekening" id="no_rekening" placeholder="Nomor Rekening" required>
          <input type="text" name="atasnama_rekening" id="atasnama_rekening" placeholder="Atas Nama Rekening" required>
        </div>
        
        <div class="button-group">
          <button type="button" class="back">Kembali</button>
          <button type="button" class="next">Selanjutnya</button>
        </div>
      </div>

      <!-- STEP 3 -->
      <div class="form-step">
        <input type="text" name="no_ktp" placeholder="Nomor KTP (16 digit)" minlength="16" maxlength="16" pattern="[0-9]{16}" required>
        <div class="validation-message" id="noKtpValidation">Nomor KTP harus 16 digit angka</div>

        <!-- Foto KTP -->
        <div class="camera-container">
          <h3>Foto KTP</h3>
          <div class="photo-instruction">
            <strong>Pastikan:</strong>
            <ul>
              <li>KTP dalam kondisi jelas dan terbaca</li>
              <li>Foto diambil dalam pencahayaan yang baik</li>
              <li>Seluruh bagian KTP terlihat</li>
            </ul>
          </div>
          <button type="button" id="openKtpCamera">Buka Kamera</button>
          <video id="ktpCamera" autoplay playsinline style="display:none;"></video>
          <canvas id="ktpCanvas" style="display:none;"></canvas>
          <button type="button" id="takeKtpPhoto" style="display:none;">Ambil Foto KTP</button>
          <img id="ktpPreview" style="display:none;">
          <input type="hidden" name="foto_ktp" id="foto_ktp">
          <div class="size-info" id="sizeInfo_ktp">Maksimal 2MB</div>
        </div>

        <!-- Foto Diri dengan KTP -->
        <div class="camera-container">
          <h3>Foto Diri Memegang KTP</h3>
          <div class="photo-instruction">
            <strong>Pastikan:</strong>
            <ul>
              <li>Wajah Anda jelas terlihat</li>
              <li>KTP terlihat dengan jelas di tangan Anda</li>
              <li>Seluruh wajah dan KTP dalam satu frame</li>
              <li>Foto diambil dengan pencahayaan yang baik</li>
            </ul>
          </div>
          <button type="button" id="openDiriKtpCamera">Buka Kamera</button>
          <video id="diriKtpCamera" autoplay playsinline style="display:none;"></video>
          <canvas id="diriKtpCanvas" style="display:none;"></canvas>
          <button type="button" id="takeDiriKtpPhoto" style="display:none;">Ambil Foto Diri dengan KTP</button>
          <img id="diriKtpPreview" style="display:none;">
          <input type="hidden" name="foto_diri_ktp" id="foto_diri_ktp">
          <div class="size-info" id="sizeInfo_diri_ktp">Maksimal 2MB</div>
        </div>

        <!-- Kamera Langsung untuk Foto Diri -->
        <div class="camera-container">
          <h3>Foto Diri </h3>
          <button type="button" id="openCamera">Buka Kamera</button>
          <video id="camera" autoplay playsinline style="display:none;"></video>
          <canvas id="canvas" style="display:none;"></canvas>
          <button type="button" id="takePhoto" style="display:none;">Ambil Foto</button>
          <img id="preview" style="display:none;">
          <input type="hidden" name="foto_diri" id="foto_diri">
          <div class="size-info" id="sizeInfo_diri">Maksimal 2MB</div>
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

    // Validasi field khusus
    function validateField(input) {
      const value = input.value.trim();
      const validationMessage = document.getElementById(input.name + 'Validation');
      
      // Reset validation
      input.classList.remove('input-error');
      if (validationMessage) {
        validationMessage.classList.remove('show');
      }

      // Validasi berdasarkan tipe field
      switch(input.name) {
        case 'password':
          if (value.length < 6) {
            input.classList.add('input-error');
            if (validationMessage) validationMessage.classList.add('show');
            return false;
          }
          break;
          
        case 'no_hp':
          if (!/^\d{11,13}$/.test(value)) {
            input.classList.add('input-error');
            if (validationMessage) validationMessage.classList.add('show');
            return false;
          }
          break;
          
        case 'nomor_hp_keluarga':
          if (value && !/^\d{11,13}$/.test(value)) {
            input.classList.add('input-error');
            if (validationMessage) validationMessage.classList.add('show');
            return false;
          }
          break;
          
        case 'no_ktp':
          if (!/^\d{16}$/.test(value)) {
            input.classList.add('input-error');
            if (validationMessage) validationMessage.classList.add('show');
            return false;
          }
          break;
      }
      
      return true;
    }

    // Validasi sebelum next
    function validateStep(step) {
      const stepInputs = steps[step].querySelectorAll('input[required], select[required]');
      let valid = true;

      stepInputs.forEach(input => {
        if (!input.value.trim()) {
          input.classList.add('input-error');
          valid = false;
        } else {
          input.classList.remove('input-error');
          // Validasi field khusus
          if (!validateField(input)) {
            valid = false;
          }
        }
      });

      return valid;
    }

    // Navigasi ke step berikutnya
    function goToNextStep() {
      if (!validateStep(currentStep)) {
        alert("Harap isi semua field dengan format yang benar sebelum lanjut!");
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

    // Real-time validation untuk field khusus
    document.querySelectorAll('input[name="password"], input[name="no_hp"], input[name="nomor_hp_keluarga"], input[name="no_ktp"]').forEach(input => {
      input.addEventListener('input', function() {
        validateField(this);
      });
      
      input.addEventListener('blur', function() {
        validateField(this);
      });
    });

    // Validasi sebelum submit form
    registerBtn.addEventListener('click', function(e) {
      e.preventDefault(); // Mencegah submit langsung
      
      if (!validateStep(currentStep)) {
        alert("Harap isi semua field dengan format yang benar!");
        return;
      }

      // Validasi foto
      const fotoKtp = document.getElementById('foto_ktp').value;
      const fotoDiriKtp = document.getElementById('foto_diri_ktp').value;
      
      if (!fotoKtp) {
        alert("Harap ambil foto KTP terlebih dahulu!");
        return;
      }
      
      if (!fotoDiriKtp) {
        alert("Harap ambil foto diri dengan KTP terlebih dahulu!");
        return;
      }

      // Jika semua valid, submit form
      form.submit();
    });

    // Fungsi untuk menangani pemilihan bank
    const bankSelect = document.getElementById('jenis_bank');
    const bankInfo = document.getElementById('bankInfo');
    const selectedBankName = document.getElementById('selectedBankName');
    const bankDescription = document.getElementById('bankDescription');
    const rekeningContainer = document.getElementById('rekeningContainer');

    // Data bank dengan informasi tambahan
    const bankData = {
      'BCA': {
        name: 'BCA (Bank Central Asia)',
        description: 'BCA adalah salah satu bank swasta terbesar di Indonesia.'
      },
      'BSI': {
        name: 'BSI (Bank Syariah Indonesia)',
        description: 'BSI adalah bank syariah terbesar di Indonesia hasil merger tiga bank syariah.'
      },
      'BRI': {
        name: 'BRI (Bank Rakyat Indonesia)',
        description: 'BRI adalah bank milik pemerintah yang fokus pada UMKM.'
      },
      'Mandiri': {
        name: 'Bank Mandiri',
        description: 'Bank Mandiri adalah bank terbesar di Indonesia berdasarkan aset.'
      },
      'BNI': {
        name: 'BNI (Bank Negara Indonesia)',
        description: 'BNI adalah bank pemerintah yang didirikan sejak 1946.'
      },
      'CIMB': {
        name: 'CIMB Niaga',
        description: 'CIMB Niaga adalah bagian dari CIMB Group, grup perbankan terbesar di Malaysia.'
      },
      'Danamon': {
        name: 'Bank Danamon',
        description: 'Bank Danamon adalah bagian dari grup finansial Astra.'
      },
      'Permata': {
        name: 'Bank Permata',
        description: 'Bank Permata adalah hasil merger beberapa bank pada tahun 2002.'
      },
      'Maybank': {
        name: 'Maybank Indonesia',
        description: 'Maybank Indonesia adalah bagian dari Maybank Group, grup perbankan terbesar di Malaysia.'
      },
      'OCBC': {
        name: 'OCBC NISP',
        description: 'OCBC NISP adalah bagian dari OCBC Group, grup perbankan terbesar di Singapura.'
      },
      'Panin': {
        name: 'Panin Bank',
        description: 'Panin Bank adalah bank swasta yang didirikan pada tahun 1971.'
      },
      'BTN': {
        name: 'BTN (Bank Tabungan Negara)',
        description: 'BTN adalah bank pemerintah yang fokus pada pembiayaan perumahan.'
      },
      'Bukopin': {
        name: 'Bank Bukopin',
        description: 'Bank Bukopin awalnya didirikan untuk melayani koperasi.'
      },
      'Mega': {
        name: 'Bank Mega',
        description: 'Bank Mega adalah bagian dari CT Corp.'
      },
      'BJB': {
        name: 'BJB (Bank Jabar Banten)',
        description: 'BJB adalah bank milik pemerintah daerah Jawa Barat dan Banten.'
      },
      'DKI': {
        name: 'Bank DKI',
        description: 'Bank DKI adalah bank milik pemerintah daerah DKI Jakarta.'
      },
      'BPD': {
        name: 'Bank BPD Lainnya',
        description: 'Bank Pembangunan Daerah (BPD) lainnya di Indonesia.'
      },
      'Lainnya': {
        name: 'Bank Lainnya',
        description: 'Bank lain yang tidak tercantum dalam daftar.'
      }
    };

    // Event listener untuk perubahan pilihan bank
    bankSelect.addEventListener('change', function() {
      const selectedBank = this.value;
      
      if (selectedBank) {
        // Tampilkan informasi bank
        selectedBankName.textContent = bankData[selectedBank].name;
        bankDescription.textContent = bankData[selectedBank].description;
        bankInfo.style.display = 'block';
        
        // Tampilkan input nomor rekening
        rekeningContainer.style.display = 'block';
        
        // Tambahkan placeholder yang sesuai
        const noRekeningInput = document.getElementById('no_rekening');
        noRekeningInput.placeholder = `Nomor Rekening ${bankData[selectedBank].name}`;
        
        // Animasi untuk transisi yang smooth
        rekeningContainer.style.animation = 'fadeInUp 0.5s ease-out';
      } else {
        // Sembunyikan jika tidak ada bank yang dipilih
        bankInfo.style.display = 'none';
        rekeningContainer.style.display = 'none';
      }
    });

    // ==================== FUNGSI KOMPRESI FOTO ====================
    
    // Fungsi untuk memeriksa ukuran base64
    function getBase64Size(base64String) {
      if (!base64String) return 0;
      
      // Hitung ukuran dalam bytes
      const padding = (base64String.endsWith('==')) ? 2 : (base64String.endsWith('=')) ? 1 : 0;
      const base64Length = base64String.length - (base64String.includes('base64,') ? base64String.indexOf(',') + 1 : 0);
      return (base64Length * 3 / 4) - padding;
    }

    // Fungsi untuk kompresi image di client side
    function compressImage(base64Image, maxSizeMB = 2, callback) {
      const maxSizeBytes = maxSizeMB * 1024 * 1024;
      const currentSize = getBase64Size(base64Image);
      
      // Jika ukuran sudah di bawah batas, return asli
      if (currentSize <= maxSizeBytes) {
        callback(base64Image, currentSize);
        return;
      }
      
      const img = new Image();
      img.onload = function() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Hitung scale factor untuk mengurangi ukuran
        let width = img.width;
        let height = img.height;
        let quality = 0.8;
        
        // Kurangi dimensi jika gambar terlalu besar
        const maxDimension = 1200;
        if (width > maxDimension || height > maxDimension) {
          if (width > height) {
            height = Math.round((height * maxDimension) / width);
            width = maxDimension;
          } else {
            width = Math.round((width * maxDimension) / height);
            height = maxDimension;
          }
        }
        
        canvas.width = width;
        canvas.height = height;
        
        ctx.drawImage(img, 0, 0, width, height);
        
        // Coba beberapa kualitas sampai ukuran sesuai
        function tryCompress(q) {
          const compressedBase64 = canvas.toDataURL('image/jpeg', q);
          const compressedSize = getBase64Size(compressedBase64);
          
          if (compressedSize <= maxSizeBytes || q <= 0.3) {
            callback(compressedBase64, compressedSize);
          } else {
            setTimeout(() => tryCompress(q - 0.1), 10);
          }
        }
        
        tryCompress(quality);
      };
      
      img.onerror = function() {
        // Jika gagal load image, return original
        callback(base64Image, currentSize);
      };
      
      img.src = base64Image;
    }

    // Fungsi untuk update info ukuran
    function updateSizeInfo(elementId, sizeBytes) {
      const sizeInfo = document.getElementById(elementId);
      if (sizeInfo) {
        const sizeMB = (sizeBytes / (1024 * 1024)).toFixed(2);
        sizeInfo.textContent = `Ukuran: ${sizeMB} MB`;
        
        if (sizeMB > 1.5) {
          sizeInfo.className = 'size-info warning';
        } else if (sizeMB > 2) {
          sizeInfo.className = 'size-info danger';
        } else {
          sizeInfo.className = 'size-info';
        }
      }
    }

    // Setup camera dengan kompresi
    function setupCameraWithCompression(videoElement, takeButton, previewElement, hiddenInput, sizeInfoId) {
      takeButton.addEventListener('click', () => {
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        
        tempCanvas.width = videoElement.videoWidth;
        tempCanvas.height = videoElement.videoHeight;

        // Biar gak mirror
        tempCtx.save();
        tempCtx.translate(tempCanvas.width, 0);
        tempCtx.scale(-1, 1);
        tempCtx.drawImage(videoElement, 0, 0, tempCanvas.width, tempCanvas.height);
        tempCtx.restore();

        const originalBase64 = tempCanvas.toDataURL("image/png");
        
        // Kompres image sebelum disimpan
        compressImage(originalBase64, 2, function(compressedBase64, compressedSize) {
          previewElement.src = compressedBase64;
          previewElement.style.display = "block";
          hiddenInput.value = compressedBase64;

          // Update info ukuran
          updateSizeInfo(sizeInfoId, compressedSize);
          
          // Matikan kamera setelah ambil foto
          let currentStream = null;
          if (videoElement === video) currentStream = stream;
          if (videoElement === ktpVideo) currentStream = ktpStream;
          if (videoElement === diriKtpVideo) currentStream = diriKtpStream;
          
          if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
          }
          
          videoElement.style.display = "none";
          takeButton.style.display = "none";
          
          // Tampilkan kembali tombol buka kamera
          const openButton = videoElement.parentElement.querySelector('button[id^="open"]');
          if (openButton) {
            openButton.style.display = "inline-block";
          }
        });
      });
    }

    // Camera functionality untuk Foto Diri
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

    // Setup kompresi untuk foto diri
    setupCameraWithCompression(video, takePhotoBtn, preview, fotoInput, 'sizeInfo_diri');

    // Camera functionality untuk Foto KTP
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

    // Setup kompresi untuk foto KTP
    setupCameraWithCompression(ktpVideo, takeKtpPhotoBtn, ktpPreview, ktpInput, 'sizeInfo_ktp');

    // Camera functionality untuk Foto Diri dengan KTP
    const diriKtpVideo = document.getElementById('diriKtpCamera');
    const diriKtpCanvas = document.getElementById('diriKtpCanvas');
    const openDiriKtpCameraBtn = document.getElementById('openDiriKtpCamera');
    const takeDiriKtpPhotoBtn = document.getElementById('takeDiriKtpPhoto');
    const diriKtpPreview = document.getElementById('diriKtpPreview');
    const diriKtpInput = document.getElementById('foto_diri_ktp');
    const diriKtpCtx = diriKtpCanvas.getContext('2d');
    let diriKtpStream;

    // Buka kamera untuk foto diri dengan KTP
    openDiriKtpCameraBtn.addEventListener('click', () => {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(s => {
          diriKtpStream = s;
          diriKtpVideo.srcObject = diriKtpStream;
          diriKtpVideo.style.display = "block";
          takeDiriKtpPhotoBtn.style.display = "inline-block";
          openDiriKtpCameraBtn.style.display = "none";
        })
        .catch(err => {
          alert("Gagal akses kamera: " + err);
        });
    });

    // Setup kompresi untuk foto diri dengan KTP
    setupCameraWithCompression(diriKtpVideo, takeDiriKtpPhotoBtn, diriKtpPreview, diriKtpInput, 'sizeInfo_diri_ktp');
  </script>
</body>
</html>