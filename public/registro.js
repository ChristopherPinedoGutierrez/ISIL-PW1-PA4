// registro.js - Lógica de registro de usuario
const STORAGE_USER = 'bodega_usuario';

document.getElementById('registroForm').onsubmit = async function (e) {
  e.preventDefault();
  const nombre = document.getElementById('nombre').value.trim();
  const correo = document.getElementById('correo').value.trim();
  const clave = document.getElementById('clave').value;
  const direccion = document.getElementById('direccion').value.trim();
  const telefono = document.getElementById('telefono').value.trim();
  const mensaje = document.getElementById('mensaje');
  mensaje.textContent = '';

  if (!nombre || !correo || !clave || !direccion || !telefono) {
    mensaje.textContent = 'Completa todos los campos.';
    return;
  }

  try {
    const res = await fetch('../api/usuarios.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nombre, correo, clave, direccion, telefono })
    });
    const data = await res.json();
    if (res.ok && data.id) {
      mensaje.classList.remove('text-danger');
      mensaje.classList.add('text-success');
      mensaje.textContent = 'Usuario registrado correctamente. Redirigiendo...';
      setTimeout(() => {
        window.location.href = 'login.html';
      }, 1500);
    } else {
      mensaje.classList.remove('text-success');
      mensaje.classList.add('text-danger');
      mensaje.textContent = data.message || 'Error al registrar usuario.';
    }
  } catch (err) {
    mensaje.classList.remove('text-success');
    mensaje.classList.add('text-danger');
    mensaje.textContent = 'Error de conexión con el servidor.';
  }
};
