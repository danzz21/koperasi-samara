lucide.createIcons();

const jumlahUangInput = document.getElementById('jumlahUang');
const marginInput = document.getElementById('margin');
const cicilanInput = document.getElementById('cicilan');

const hasilMargin = document.getElementById('hasilMargin');
const hasilCicilan = document.getElementById('hasilCicilan');
const hasilTanggungan = document.getElementById('hasilTanggungan');
const hasilTotal = document.getElementById('hasilTotal');

function hitungSimulasi() {
  const jumlah = parseFloat(jumlahUangInput.value) || 0;
  const marginPersen = parseFloat(marginInput.value) || 0;
  const bulan = parseFloat(cicilanInput.value) || 1;

  if (jumlah <= 0 || bulan <= 0) {
    hasilMargin.textContent = "Rp 0 /bulan";
    hasilCicilan.textContent = "0 x";
    hasilTanggungan.textContent = "Rp 0";
    hasilTotal.textContent = "Rp 0";
    return;
  }

  const totalMargin = jumlah * (marginPersen / 100);
  const marginPerBulan = totalMargin / bulan;
  const pokokPerBulan = jumlah / bulan;
  const tanggunganPerBulan = pokokPerBulan + marginPerBulan;
  const totalBayar = tanggunganPerBulan * bulan;

  hasilMargin.textContent = `Rp ${Math.round(marginPerBulan).toLocaleString()} /bulan`;
  hasilCicilan.textContent = `${bulan} x`;
  hasilTanggungan.textContent = `Rp ${Math.round(tanggunganPerBulan).toLocaleString()}`;
  hasilTotal.textContent = `Rp ${Math.round(totalBayar).toLocaleString()}`;
}

jumlahUangInput.addEventListener('input', hitungSimulasi);
marginInput.addEventListener('input', hitungSimulasi);
cicilanInput.addEventListener('input', hitungSimulasi);
