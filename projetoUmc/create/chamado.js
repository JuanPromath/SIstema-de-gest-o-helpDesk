// Quando o usuário digitar nos campos, o conteúdo aparece do outro lado
const boInput = document.getElementById('bo');
const cpfInput = document.getElementById('cliente');

const boDisplay = document.getElementById('boDisplay');
const cpfDisplay = document.getElementById('cpfDisplay');

boInput.addEventListener('input', () => {
  boDisplay.textContent = boInput.value || '—';
});

cpfInput.addEventListener('input', () => {
  cpfDisplay.textContent = cpfInput.value || '—';
});



