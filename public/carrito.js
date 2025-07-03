// carrito.js - Lógica para la página de carrito
const STORAGE_USER = 'bodega_usuario';
const STORAGE_CARRITO = 'bodega_carrito';

function getUsuario() {
  return JSON.parse(localStorage.getItem(STORAGE_USER));
}
function getCarrito() {
  return JSON.parse(localStorage.getItem(STORAGE_CARRITO)) || [];
}
function setCarrito(carrito) {
  localStorage.setItem(STORAGE_CARRITO, JSON.stringify(carrito));
}
function clearCarrito() {
  localStorage.removeItem(STORAGE_CARRITO);
}

function renderizarCarrito() {
  const carrito = getCarrito();
  const container = document.getElementById('carritoContainer');
  const mensaje = document.getElementById('mensaje');
  mensaje.textContent = '';
  if (!carrito.length) {
    container.innerHTML = '<div class="alert alert-info">Tu carrito está vacío.</div>';
    document.getElementById('btnConfirmar').disabled = true;
    return;
  }
  let total = 0;
  let html = `<div class="table-responsive"><table class="table align-middle">
    <thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr></thead><tbody>`;
  carrito.forEach((item, idx) => {
    const subtotal = item.precio_unitario * item.cantidad;
    total += subtotal;
    html += `<tr>
      <td>${item.nombre}</td>
      <td>S/ ${Number(item.precio_unitario).toFixed(2)}</td>
      <td>
        <button class="btn btn-outline-secondary btn-sm btn-menos" data-idx="${idx}">-</button>
        <span class="mx-2">${item.cantidad}</span>
        <button class="btn btn-outline-secondary btn-sm btn-mas" data-idx="${idx}">+</button>
      </td>
      <td>S/ ${subtotal.toFixed(2)}</td>
      <td><button class="btn btn-danger btn-sm btn-eliminar" data-idx="${idx}"><i class="fa fa-trash"></i></button></td>
    </tr>`;
  });
  html += `</tbody></table></div>`;
  html += `<div class="text-end fw-bold fs-5">Total: S/ ${total.toFixed(2)}</div>`;
  container.innerHTML = html;
  document.getElementById('btnConfirmar').disabled = false;

  // Eventos de cantidad y eliminar
  container.querySelectorAll('.btn-menos').forEach(btn => {
    btn.onclick = () => {
      const idx = btn.getAttribute('data-idx');
      let carrito = getCarrito();
      if (carrito[idx].cantidad > 1) {
        carrito[idx].cantidad--;
        setCarrito(carrito);
        renderizarCarrito();
      }
    };
  });
  container.querySelectorAll('.btn-mas').forEach(btn => {
    btn.onclick = () => {
      const idx = btn.getAttribute('data-idx');
      let carrito = getCarrito();
      carrito[idx].cantidad++;
      setCarrito(carrito);
      renderizarCarrito();
    };
  });
  container.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.onclick = () => {
      const idx = btn.getAttribute('data-idx');
      let carrito = getCarrito();
      carrito.splice(idx, 1);
      setCarrito(carrito);
      renderizarCarrito();
    };
  });
}

// Confirmar pedido
async function confirmarPedido() {
  const usuario = getUsuario();
  const carrito = getCarrito();
  const mensaje = document.getElementById('mensaje');
  if (!usuario) {
    mensaje.textContent = 'Debes iniciar sesión para confirmar el pedido.';
    return;
  }
  if (!carrito.length) {
    mensaje.textContent = 'El carrito está vacío.';
    return;
  }
  const total = carrito.reduce((acc, item) => acc + item.precio_unitario * item.cantidad, 0);
  try {
    const res = await fetch('../api/pedidos.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ usuario_id: usuario.id, productos: carrito, total })
    });
    const data = await res.json();
    if (data.body) {
      console.log('Cuerpo del correo enviado:', data.body);
    }
    if (res.ok && data.pedido_id) {
      mensaje.classList.remove('text-danger');
      mensaje.classList.add('text-success');
      mensaje.textContent = '¡Pedido confirmado! Revisa tu correo.';
      clearCarrito();
      renderizarCarrito();
    } else {
      mensaje.classList.remove('text-success');
      mensaje.classList.add('text-danger');
      mensaje.textContent = data.message || 'Error al registrar el pedido.';
    }
  } catch (err) {
    mensaje.classList.remove('text-success');
    mensaje.classList.add('text-danger');
    mensaje.textContent = 'Error de conexión con el servidor.';
  }
}

document.getElementById('btnConfirmar').onclick = confirmarPedido;
window.onload = renderizarCarrito;
