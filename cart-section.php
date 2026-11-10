<?php
// Obtenemos todos los productos para cruzarlos con el localStorage
$res = $conexion->query("SELECT id, nombre, referencia, precio, precio_rebajado, imagen FROM productos");
$catalogo = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        // Calcular el precio real (rebajado si aplica)
        $precio_final = (!is_null($row['precio_rebajado']) && $row['precio_rebajado'] > 0) 
                        ? (float)$row['precio_rebajado'] 
                        : (float)$row['precio'];
        
        $foto = (!empty($row['imagen']) && file_exists('assets/productos/' . $row['imagen'])) 
                ? 'assets/productos/' . $row['imagen'] 
                : 'https://dummyimage.com/150x150/dee2e6/6c757d.jpg';

        $catalogo[$row['id']] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'referencia' => $row['referencia'],
            'precio' => $precio_final,
            'foto' => $foto
        ];
    }
}
?>

<!-- Pasamos el catálogo de productos a JS de forma segura -->
<script>
    window.CATALOGO_PRODUCTOS = <?= json_encode($catalogo); ?>;
</script>

<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 my-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary">Inicio</a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Mi Carrito</li>
            </ol>
        </nav>

        <h1 class="display-6 fw-bolder mb-4"><i class="bi bi-cart3 me-2"></i>Cesta de la Compra</h1>

        <!-- Si el carrito está vacío -->
        <div id="cart-empty" class="alert alert-secondary text-center py-5 shadow-sm d-none">
            <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
            <h4 class="fw-bold">Tu carrito está vacío</h4>
            <p class="text-muted">No has añadido ninguna prenda ni accesorio todavía.</p>
            <a href="index.php" class="btn btn-dark mt-2"><i class="bi bi-arrow-left me-1"></i> Explorar Catálogo</a>
        </div>

        <!-- Contenedor con la tabla y el resumen -->
        <div id="cart-content" class="row gx-4 gx-lg-5">
            <!-- Tabla de productos -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 bg-white">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" style="width: 45%;">Producto</th>
                                    <th scope="col" class="text-center">Precio</th>
                                    <th scope="col" class="text-center" style="width: 150px;">Cantidad</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-table-body">
                                <!-- Se rellena dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-white p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Resumen del Pedido</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="cart-subtotal">0.00 €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Envío</span>
                        <span class="text-success fw-bold">Gratis</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total</span>
                        <span class="fs-4 fw-bolder text-danger" id="cart-total">0.00 €</span>
                    </div>

                <a href="index.php?p=checkout" class="btn btn-dark w-100 py-2 mb-2">
                    <i class="bi bi-lock-fill me-1"></i> Tramitar Pedido
                </a>
                <a href="index.php" class="btn btn-outline-secondary w-100 py-2">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>
</section>