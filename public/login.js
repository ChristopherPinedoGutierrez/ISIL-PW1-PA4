// login.js - Lógica de autenticación para login.html
const STORAGE_USER = 'bodega_usuario';

document.getElementById('loginForm').onsubmit = async function (e) {
  e.preventDefault();
  const correo = document.getElementById('correo').value.trim();
  const clave = document.getElementById('clave').value;
  const mensaje = document.getElementById('mensaje');
  mensaje.textContent = '';

  if (!correo || !clave) {
    mensaje.textContent = 'Completa todos los campos.';
    return;
  }

  try {
    const res = await fetch('../api/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ correo, clave })
    });
    const data = await res.json();
    if (res.ok && data.usuario) {
      // Guardar usuario en localStorage y redirigir
      localStorage.setItem(STORAGE_USER, JSON.stringify(data.usuario));
      window.location.href = 'index.html';
    } else {
      mensaje.textContent = data.message || 'Error de autenticación.';
    }
  } catch (err) {
    mensaje.textContent = 'Error de conexión con el servidor.';
  }
};
