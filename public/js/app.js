function openModal(modalId) {
  document.getElementById(modalId).classList.add('active');
}
function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('active');
}
window.onclick = function(event) {
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modal => {
    if (event.target === modal) {
      modal.classList.remove('active');
    }
  });
};
document.addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Data berhasil disimpan!');
  const openModal = document.querySelector('.modal.active');
  if (openModal) {
    openModal.classList.remove('active');
  }
});
