<?php
// 1. Obtener y sanitizar el ID del producto recibido por GET
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Consulta con prepared statement
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();

// 3. Si no existe el producto, mostrar alerta
if (!$item):
?>
    <div class="container py-5 text-center my-5">
        <div class="alert alert-warning d-inline-block p-4 shadow-sm">
            <h3 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Producto no encontrado</h3>
            <p class="text-muted">El artículo que estás buscando no existe o ya no está disponible en catálogo.</p>
            <a class="btn btn-dark" href="index.php">Volver al catálogo</a>
        </div>
    </div>
<?php 
return;
endif;

// 4. Preparar datos visuales
$esta_rebajado = !is_null($item['precio_rebajado']) && $item['precio_rebajado'] > 0;
$estrellas = round($item['valoracion'] ?? 5);
$foto = (!empty($item['imagen']) && file_exists('assets/productos/' . $item['imagen'])) 
        ? 'assets/productos/' . $item['imagen'] 
        : 'https://dummyimage.com/600x700/dee2e6/6c757d.jpg';
?>

<!-- Sección Detalle del Producto -->
<section class="py-5">
    <div class="container px-4 px-lg-5 my-4">
        <!-- Migas de pan / Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary">Tienda</a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?= htmlspecialchars($item['nombre']) ?></li>
            </ol>
        </nav>

        <div class="row gx-4 gx-lg-5 align-items-center">
            <!-- Imagen del producto -->
            <div class="col-md-6 mb-4 mb-md-0 text-center">
                <div class="position-relative d-inline-block border rounded shadow-sm overflow-hidden bg-white">
                    <?php if ($esta_rebajado): ?>
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 text-uppercase fs-6">Oferta</span>
                    <?php endif; ?>
                    <img class="img-fluid" 
                         src="<?= htmlspecialchars($foto) ?>" 
                         alt="<?= htmlspecialchars($item['nombre']) ?>" 
                         style="max-height: 520px; width: 100%; object-fit: cover;" />
                </div>
            </div>

            <!-- Información y compra -->
            <div class="col-md-6">
                <div class="small text-muted mb-1">REF: <span class="fw-bold"><?= htmlspecialchars($item['referencia'] ?? 'XAX-00' . $item['id']) ?></span></div>
                <h1 class="display-6 fw-bolder mb-2"><?= htmlspecialchars($item['nombre']) ?></h1>

                <!-- Valoración -->
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2 small">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $estrellas) ? '<i class="bi-star-fill"></i> ' : '<i class="bi-star"></i> ';
                        }
                        ?>
                    </div>
                    <span class="text-muted small">(<?= number_format($item['valoracion'] ?? 5, 1) ?> / 5)</span>
                </div>

                <!-- Precios -->
                <div class="fs-4 mb-3">
                    <?php if ($esta_rebajado): ?>
                        <span class="text-muted text-decoration-line-through me-2 fs-5">$<?= number_format($item['precio'], 2) ?></span>
                        <span class="text-danger fw-bold">$<?= number_format($item['precio_rebajado'], 2) ?></span>
                    <?php else: ?>
                        <span class="fw-bold text-dark">$<?= number_format($item['precio'], 2) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Descripción -->
                <p class="lead fs-6 text-secondary mb-4"><?= nl2br(htmlspecialchars($item['descripcion'] ?? 'Sin descripción detallada.')) ?></p>

                <!-- Bloque de compra -->
                <div class="mb-4" id="product-container" data-id="<?= $item['id'] ?>">
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase">Talla / Modelo</label>
                            <select class="form-select" id="select-talla" name="talla">
                                <option value="S">Talla S</option>
                                <option value="M" selected>Talla M</option>
                                <option value="L">Talla L</option>
                                <option value="XL">Talla XL</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button id="btn-toggle-cart" class="btn btn-dark flex-shrink-0 flex-grow-1 py-2" type="button">
                            <i class="bi bi-cart-fill me-1"></i> Añadir al carrito
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary py-2" title="Volver al catálogo">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Insignias informativas -->
                <div class="border-top pt-3">
                    <div class="row text-muted small g-2">
                        <div class="col-6"><i class="bi bi-truck text-dark me-1"></i> Envío rápido 24/48h</div>
                        <div class="col-6"><i class="bi bi-arrow-repeat text-dark me-1"></i> Devolución gratuita</div>
                        <div class="col-6"><i class="bi bi-shield-check text-dark me-1"></i> Pago 100% seguro</div>
                        <div class="col-6"><i class="bi bi-check2-circle text-dark me-1"></i> Producto oficial Xaxes</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>