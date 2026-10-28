function obtenerCarrito() {
    return JSON.parse(localStorage.getItem("carritoElf")) || [];
}

function guardarCarrito(carrito) {
    localStorage.setItem("carritoElf", JSON.stringify(carrito));
}

function agregarAlCarrito(nombre, precio) {
    let carrito = obtenerCarrito();
    let producto = carrito.find(item => item.nombre === nombre);

    if (producto) {
        producto.cantidad++;
    } else {
        carrito.push({ nombre: nombre, precio: precio, cantidad: 1 });
    }

    guardarCarrito(carrito);
    alert("Producto agregado al carrito.");
}

function eliminarDelCarrito(indice) {
    let carrito = obtenerCarrito();
    carrito.splice(indice, 1);
    guardarCarrito(carrito);
    mostrarCarrito();
}

function vaciarCarrito() {
    localStorage.removeItem("carritoElf");
    mostrarCarrito();
}

function mostrarCarrito() {
    const contenedor = document.getElementById("contenido-carrito");
    if (!contenedor) return;

    const carrito = obtenerCarrito();
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p class="carrito-vacio">Tu carrito está vacío.</p><a class="boton" href="labios.html">Explorar productos</a>';
        return;
    }

    let total = 0;
    let html = '<div class="tabla-carrito"><div class="fila-carrito encabezado-carrito"><span>Producto</span><span>Precio</span><span>Cantidad</span><span>Subtotal</span><span></span></div>';
    carrito.forEach((item, indice) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        html += `<div class="fila-carrito"><span>${item.nombre}</span><span>$${item.precio.toFixed(2)} MXN</span><span>${item.cantidad}</span><span>$${subtotal.toFixed(2)} MXN</span><button class="boton-quitar" type="button" onclick="eliminarDelCarrito(${indice})">Quitar</button></div>`;
    });
    html += '</div>';
    html += `<p class="total-carrito">Total: $${total.toFixed(2)} MXN</p>`;
    html += '<p>Este carrito es una demostración. No se realiza ningún pago ni se guarda una compra en SQL.</p>';
    html += '<button class="boton-comprar" type="button" onclick="vaciarCarrito()">Vaciar carrito</button>';
    contenedor.innerHTML = html;
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("button[data-nombre][data-precio]").forEach(function (boton) {
        boton.addEventListener("click", function () {
            agregarAlCarrito(boton.dataset.nombre, Number(boton.dataset.precio));
        });
    });
});
