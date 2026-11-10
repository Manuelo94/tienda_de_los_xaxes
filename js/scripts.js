document.addEventListener("DOMContentLoaded", () => {
    const badgeCarrito = document.getElementById("cart-count");
    const container = document.getElementById("product-container");
    const selectTalla = document.getElementById("select-talla");
    const btnToggleCart = document.getElementById("btn-toggle-cart");

    // 1. Obtener carrito como objeto: { "id-talla": cantidad, ... }
    function getCarritoMap() {
        try {
            return JSON.parse(localStorage.getItem("carrito_map")) || {};
        } catch (e) {
            return {};
        }
    }

    function saveCarritoMap(map) {
        localStorage.setItem("carrito_map", JSON.stringify(map));
        updateBadge();
    }

    // 2. Actualizar el contador global del navbar
    function updateBadge() {
        if (!badgeCarrito) return;
        const map = getCarritoMap();
        // Suma de todas las cantidades
        const total = Object.values(map).reduce((acc, curr) => acc + curr, 0);
        badgeCarrito.textContent = total;
    }

    updateBadge();

    // ==========================================
    // LÓGICA DE LA FICHA DE PRODUCTO
    // ==========================================
    if (container && selectTalla && btnToggleCart) {
        const prodId = container.getAttribute("data-id");

        function checkEstadoBoton() {
            const talla = selectTalla.value;
            const claveItem = `${prodId}-${talla}`;
            const map = getCarritoMap();

            if (map.hasOwnProperty(claveItem)) {
                btnToggleCart.innerHTML = '<i class="bi bi-cart-x-fill me-1"></i> Quitar del carrito';
                btnToggleCart.className = "btn btn-outline-danger flex-shrink-0 flex-grow-1 py-2";
                btnToggleCart.dataset.action = "remove";
            } else {
                btnToggleCart.innerHTML = '<i class="bi bi-cart-fill me-1"></i> Añadir al carrito';
                btnToggleCart.className = "btn btn-dark flex-shrink-0 flex-grow-1 py-2";
                btnToggleCart.dataset.action = "add";
            }
        }

        checkEstadoBoton();
        selectTalla.addEventListener("change", checkEstadoBoton);

        btnToggleCart.addEventListener("click", () => {
            const talla = selectTalla.value;
            const claveItem = `${prodId}-${talla}`;
            let map = getCarritoMap();

            if (btnToggleCart.dataset.action === "add") {
                map[claveItem] = 1; // Se añade con cantidad 1
            } else {
                delete map[claveItem]; // Se retira del carrito
            }

            saveCarritoMap(map);
            checkEstadoBoton();
        });
    }

    // ==========================================
    // LÓGICA DE LA VENTANA DEL CARRITO
    // ==========================================
    const tableBody = document.getElementById("cart-table-body");
    const cartEmpty = document.getElementById("cart-empty");
    const cartContent = document.getElementById("cart-content");
    const totalElem = document.getElementById("cart-total");
    const subtotalElem = document.getElementById("cart-subtotal");

    if (tableBody && window.CATALOGO_PRODUCTOS) {
        function renderCarritoTable() {
            const map = getCarritoMap();
            const keys = Object.keys(map);

            if (keys.length === 0) {
                cartEmpty.classList.remove("d-none");
                cartContent.classList.add("d-none");
                return;
            }

            cartEmpty.classList.add("d-none");
            cartContent.classList.remove("d-none");
            tableBody.innerHTML = "";

            let totalCompra = 0;

            keys.forEach(clave => {
                const [id, talla] = clave.split("-");
                const prod = window.CATALOGO_PRODUCTOS[id];
                if (!prod) return;

                const cantidad = map[clave];
                const subtotal = prod.precio * cantidad;
                totalCompra += subtotal;

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${prod.foto}" alt="${prod.nombre}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded me-3 border">
                            <div>
                                <h6 class="mb-0 fw-bold">${prod.nombre}</h6>
                                <span class="badge bg-secondary">Talla: ${talla}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center fw-bold">$${prod.precio.toFixed(2)}</td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: auto;">
                            <button class="btn btn-outline-secondary btn-qty-minus" data-key="${clave}" type="button">-</button>
                            <input type="text" class="form-control text-center bg-white" value="${cantidad}" readonly>
                            <button class="btn btn-outline-secondary btn-qty-plus" data-key="${clave}" type="button">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">$${subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btn-delete-item" data-key="${clave}" title="Eliminar artículo">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });

            subtotalElem.textContent = `$${totalCompra.toFixed(2)}`;
            totalElem.textContent = `$${totalCompra.toFixed(2)}`;
        }

        renderCarritoTable();

        // Control de clics en la tabla (sumar, restar, borrar)
        tableBody.addEventListener("click", (e) => {
            const btnMinus = e.target.closest(".btn-qty-minus");
            const btnPlus = e.target.closest(".btn-qty-plus");
            const btnDelete = e.target.closest(".btn-delete-item");
            let map = getCarritoMap();

            if (btnMinus) {
                const key = btnMinus.getAttribute("data-key");
                if (map[key] > 1) {
                    map[key] -= 1;
                } else {
                    delete map[key];
                }
                saveCarritoMap(map);
                renderCarritoTable();
            }

            if (btnPlus) {
                const key = btnPlus.getAttribute("data-key");
                map[key] += 1;
                saveCarritoMap(map);
                renderCarritoTable();
            }

            if (btnDelete) {
                const key = btnDelete.getAttribute("data-key");
                delete map[key];
                saveCarritoMap(map);
                renderCarritoTable();
            }
        });
    }
});