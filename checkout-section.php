<?php
// Obtenemos los productos para recuperar nombres y precios de los artículos comprados
$res = $conexion->query("SELECT id, nombre, precio, precio_rebajado, imagen FROM productos");
$catalogo = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $precio_final = (!is_null($row['precio_rebajado']) && $row['precio_rebajado'] > 0) 
                        ? (float)$row['precio_rebajado'] 
                        : (float)$row['precio'];

        $catalogo[$row['id']] = [
            'nombre' => $row['nombre'],
            'precio' => $precio_final
        ];
    }
}
?>

<script>
    window.CATALOGO_PRODUCTOS = <?= json_encode($catalogo); ?>;
</script>

<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 my-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?p=carrito" class="text-decoration-none text-secondary">Carrito</a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Tramitar Pedido</li>
            </ol>
        </nav>

        <h1 class="display-6 fw-bolder mb-4"><i class="bi bi-credit-card-2-front me-2"></i>Finalizar Compra</h1>

        <div class="row gx-4 gx-lg-5">
            <!-- Formulario de Envío y Pago -->
            <div class="col-lg-8 mb-4">
                <form id="form-checkout" action="index.php?p=pedido-completado" method="POST" class="needs-validation" novalidate>
                    <!-- Datos de Envío -->
                    <div class="card border-0 shadow-sm p-4 mb-4 bg-white">
                        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-truck me-2"></i>Dirección de Envío</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required>
                                <div class="invalid-feedback">Introduce tu nombre.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Apellidos *</label>
                                <input type="text" class="form-control" name="apellidos" required>
                                <div class="invalid-feedback">Introduce tus apellidos.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Correo electrónico *</label>
                                <input type="email" class="form-control" name="email" placeholder="tu@correo.com" required>
                                <div class="invalid-feedback">Introduce un correo electrónico válido.</div>
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label small fw-bold">Dirección / Calle *</label>
                                <input type="text" class="form-control" name="direccion" placeholder="Calle, número, piso..." required>
                                <div class="invalid-feedback">Introduce tu dirección de entrega.</div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label small fw-bold">Código Postal *</label>
                                <input type="text" class="form-control" name="cp" pattern="^[0-9]{5}$" placeholder="28001" required>
                                <div class="invalid-feedback">Introduce un CP de 5 dígitos.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Ciudad *</label>
                                <input type="text" class="form-control" name="ciudad" required>
                                <div class="invalid-feedback">Introduce la ciudad.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Teléfono de contacto *</label>
                                <input type="tel" class="form-control" name="telefono" pattern="^[0-9\+\s]{9,15}$" placeholder="612345678" required>
                                <div class="invalid-feedback">Introduce un teléfono de contacto válido.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Métodos de Pago -->
                    <div class="card border-0 shadow-sm p-4 mb-4 bg-white">
                        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-wallet2 me-2"></i>Método de Pago</h5>
                        
                        <div class="mb-3">
                            <!-- Opción Tarjeta -->
                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input" type="radio" name="metodo_pago" id="pago-tarjeta" value="tarjeta" checked>
                                <label class="form-check-label fw-bold d-flex justify-content-between align-items-center w-100" for="pago-tarjeta">
                                    <span><i class="bi bi-credit-card me-2"></i>Tarjeta de Crédito / Débito</span>
                                    <span class="fs-5 text-muted"><i class="bi bi-credit-card-2-front"></i></span>
                                </label>
                            </div>

                            <!-- Campos dinámicos de Tarjeta -->
                            <div id="campos-tarjeta" class="p-3 border rounded mb-3 bg-white">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small">Número de la tarjeta</label>
                                        <input type="text" class="form-control" id="card-num" placeholder="1234 5678 9101 1121" maxlength="19" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Caducidad</label>
                                        <input type="text" class="form-control" id="card-exp" placeholder="MM/AA" maxlength="5" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">CVV</label>
                                        <input type="text" class="form-control" id="card-cvv" placeholder="123" maxlength="3" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Opción Bizum -->
                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input" type="radio" name="metodo_pago" id="pago-bizum" value="bizum">
                                <label class="form-check-label fw-bold d-flex justify-content-between align-items-center w-100" for="pago-bizum">
                                    <span><i class="bi bi-phone me-2"></i>Bizum</span>
                                    <span class="badge bg-primary">Instantáneo</span>
                                </label>
                            </div>
                            <div id="campos-bizum" class="p-3 border rounded mb-3 bg-white d-none">
                                <label class="form-label small">Teléfono registrado en Bizum</label>
                                <input type="tel" class="form-control" id="bizum-tel" placeholder="612345678" pattern="^[0-9]{9}$">
                            </div>

                            <!-- Opción PayPal -->
                            <div class="form-check p-3 border rounded bg-light">
                                <input class="form-check-input" type="radio" name="metodo_pago" id="pago-paypal" value="paypal">
                                <label class="form-check-label fw-bold d-flex justify-content-between align-items-center w-100" for="pago-paypal">
                                    <span><i class="bi bi-paypal text-primary me-2"></i>PayPal</span>
                                    <span class="small text-muted">Redirección segura</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-dark btn-lg w-100 py-3 shadow-sm fw-bold" type="submit">
                        <i class="bi bi-shield-lock-fill me-2"></i>Confirmar y Pagar
                    </button>
                </form>
            </div>

            <!-- Resumen del Pedido -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-white sticky-top" style="top: 2rem;">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Artículos a Comprar</h5>
                    
                    <ul class="list-group list-group-flush mb-3" id="checkout-items-list">
                        <!-- Rellenado por JS -->
                    </ul>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="checkout-subtotal">0.00 €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Gastos de Envío</span>
                        <span class="text-success fw-bold">Gratis</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total a Pagar</span>
                        <span class="fs-4 fw-bolder text-danger" id="checkout-total">0.00 €</span>
                    </div>

                    <div class="small text-muted text-center">
                        <i class="bi bi-shield-check text-success me-1"></i> Transacción cifrada con protocolo SSL de 256 bits
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script para validar formulario, alternar métodos de pago y vaciar el carrito al pagar -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // 1. Mostrar resumen de productos guardados en localStorage
    const listContainer = document.getElementById("checkout-items-list");
    const subtotalEl = document.getElementById("checkout-subtotal");
    const totalEl = document.getElementById("checkout-total");

    const carrito = JSON.parse(localStorage.getItem("carrito_map")) || {};
    const keys = Object.keys(carrito);

    let total = 0;
    if (keys.length === 0) {
        listContainer.innerHTML = '<li class="list-group-item px-0 text-muted small">No hay productos en el carrito.</li>';
    } else {
        listContainer.innerHTML = '';
        keys.forEach(k => {
            const [id, talla] = k.split("-");
            const prod = window.CATALOGO_PRODUCTOS[id];
            if (!prod) return;

            const cant = carrito[k];
            const sub = prod.precio * cant;
            total += sub;

            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between lh-sm px-0 bg-transparent";
            li.innerHTML = `
                <div>
                    <h6 class="my-0 small fw-bold">${prod.nombre}</h6>
                    <small class="text-muted">Talla: ${talla} × ${cant}</small>
                </div>
                <span class="text-muted small fw-bold">${sub.toFixed(2)} €</span>
            `;
            listContainer.appendChild(li);
        });
    }

    subtotalEl.textContent = `${total.toFixed(2)} €`;
    totalEl.textContent = `${total.toFixed(2)} €`;

    // 2. Alternar campos según método de pago seleccionado
    const radTarjeta = document.getElementById("pago-tarjeta");
    const radBizum = document.getElementById("pago-bizum");
    const radPaypal = document.getElementById("pago-paypal");

    const camposTarjeta = document.getElementById("campos-tarjeta");
    const camposBizum = document.getElementById("campos-bizum");

    const inputsTarjeta = camposTarjeta.querySelectorAll("input");
    const inputBizum = camposBizum.querySelector("input");

    function updateMetodoPago() {
        if (radTarjeta.checked) {
            camposTarjeta.classList.remove("d-none");
            camposBizum.classList.add("d-none");
            inputsTarjeta.forEach(i => i.required = true);
            if (inputBizum) inputBizum.required = false;
        } else if (radBizum.checked) {
            camposTarjeta.classList.add("d-none");
            camposBizum.classList.remove("d-none");
            inputsTarjeta.forEach(i => i.required = false);
            if (inputBizum) inputBizum.required = true;
        } else {
            camposTarjeta.classList.add("d-none");
            camposBizum.classList.add("d-none");
            inputsTarjeta.forEach(i => i.required = false);
            if (inputBizum) inputBizum.required = false;
        }
    }

    [radTarjeta, radBizum, radPaypal].forEach(r => r.addEventListener("change", updateMetodoPago));

    // 3. Validación y vaciado de carrito al enviar
    const form = document.getElementById("form-checkout");
    form.addEventListener("submit", (e) => {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            // Vaciar el carrito en localStorage para que el contador vuelva a 0
            localStorage.removeItem("carrito_map");
            const badge = document.getElementById("cart-count");
            if (badge) badge.textContent = "0";
        }
        form.classList.add("was-validated");
    });
});
</script>