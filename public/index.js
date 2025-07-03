// index.js - Lógica de la página principal

// Utilidades para localStorage
const STORAGE_USER = 'bodega_usuario';
const STORAGE_CARRITO = 'bodega_carrito';

function getUsuario() {
  return JSON.parse(localStorage.getItem(STORAGE_USER));
}
function setUsuario(usuario) {
  localStorage.setItem(STORAGE_USER, JSON.stringify(usuario));
}
function removeUsuario() {
  localStorage.removeItem(STORAGE_USER);
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

// Actualiza el navbar según el estado de sesión
function actualizarNavbar() {
  const usuario = getUsuario();
  const btnCarrito = document.getElementById('btnCarrito');
  const badgeCarrito = document.getElementById('badgeCarrito');
  const btnLogin = document.getElementById('btnLogin');
  const userDropdown = document.getElementById('userDropdown');

  // Carrito habilitado solo si hay usuario
  btnCarrito.disabled = !usuario;
  badgeCarrito.textContent = getCarrito().reduce((acc, item) => acc + item.cantidad, 0);

  if (usuario) {
    btnLogin.classList.add('d-none');
    userDropdown.classList.remove('d-none');
  } else {
    btnLogin.classList.remove('d-none');
    userDropdown.classList.add('d-none');
  }
}

// Renderiza los productos en cards
function renderizarProductos(productos) {
  const row = document.getElementById('productosRow');
  row.innerHTML = '';
  const usuario = getUsuario();
  productos.forEach(prod => {
    const col = document.createElement('div');
    col.className = 'col-12 col-sm-6 col-md-4 col-lg-3';
    // Card
    const card = document.createElement('div');
    card.className = 'card h-100 shadow-sm';
    card.innerHTML = `
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">${prod.nombre}</h5>
        <p class="card-text small">${prod.descripcion}</p>
        <p class="mb-1"><strong>S/ ${Number(prod.precio).toFixed(2)}</strong></p>
        <div class="d-flex align-items-center justify-between mb-2">
          <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary btn-sm btn-menos" type="button">-</button>
            <input type="number" class="form-control form-control-sm mx-2 text-center cantidad-input" value="1" min="1" max="${prod.stock}" style="width:60px;">
            <button class="btn btn-outline-secondary btn-sm btn-mas" type="button">+</button>
          </div>
          <button class="btn btn-success btn-sm btn-agregar ms-2" ${!usuario ? 'disabled' : ''} title="Agregar al carrito">
            <i class="fa-solid fa-cart-plus"></i>
          </button>
        </div>
      </div>
    `;
    // Eventos de cantidad
    const inputCantidad = card.querySelector('.cantidad-input');
    card.querySelector('.btn-menos').onclick = () => {
      let val = parseInt(inputCantidad.value);
      if (val > 1) inputCantidad.value = val - 1;
    };
    card.querySelector('.btn-mas').onclick = () => {
      let val = parseInt(inputCantidad.value);
      if (val < prod.stock) inputCantidad.value = val + 1;
    };
    // Evento agregar al carrito
    card.querySelector('.btn-agregar').onclick = () => {
      agregarAlCarrito(prod, parseInt(inputCantidad.value));
    };
    col.appendChild(card);
    row.appendChild(col);
  });
}

// Agrega un producto al carrito
function agregarAlCarrito(producto, cantidad) {
  let carrito = getCarrito();
  const idx = carrito.findIndex(item => item.producto_id === producto.id);
  if (idx >= 0) {
    carrito[idx].cantidad += cantidad;
  } else {
    carrito.push({
      producto_id: producto.id,
      nombre: producto.nombre,
      precio_unitario: producto.precio,
      cantidad
    });
  }
  setCarrito(carrito);
  actualizarNavbar();
}

// Eventos de login/logout/carrito
function setupEventosNavbar() {
  document.getElementById('btnLogin').onclick = () => {
    window.location.href = 'login.html';
  };
  document.getElementById('btnLogout').onclick = () => {
    removeUsuario();
    clearCarrito();
    actualizarNavbar();
    window.location.reload();
  };
  document.getElementById('btnCarrito').onclick = () => {
    if (getUsuario()) {
      window.location.href = 'carrito.html';
    }
  };
}

// Cargar productos desde la API
function cargarProductos() {
  fetch('../api/productos.php')
    .then(res => res.json())
    .then(data => renderizarProductos(data));
}

// Inicialización
window.onload = () => {
  actualizarNavbar();
  setupEventosNavbar();
  cargarProductos();
};
