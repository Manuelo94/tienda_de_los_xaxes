<?php
// Consulta normal y corriente de todos los productos
$resultado = $conexion->query("SELECT * FROM productos");
$productos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

// Sacamos el primer producto para ponerlo en grande
$mas_vendido = !empty($productos) ? array_shift($productos) : null;
?>

<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5">

        <!-- ================= PRODUCTO DESTACADO EN GRANDE ================= -->
        <?php if ($mas_vendido): ?>
            <?php 
                $foto_top = (!empty($mas_vendido['imagen']) && file_exists('assets/productos/' . $mas_vendido['imagen']))
                    ? 'assets/productos/' . $mas_vendido['imagen']
                    : 'https://dummyimage.com/600x400/dee2e6/6c757d.jpg';
                $rebajado_top = !is_null($mas_vendido['precio_rebajado']) && $mas_vendido['precio_rebajado'] > 0;
            ?>
            <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                <div class="row g-0 align-items-center">
                    <div class="col-md-6 text-center bg-white p-3">
                        <img src="<?= htmlspecialchars($foto_top) ?>" 
                             alt="<?= htmlspecialchars($mas_vendido['nombre']) ?>" 
                             class="img-fluid rounded" 
                             style="max-height: 380px; object-fit: cover;" />
                    </div>
                    <div class="col-md-6 p-4 p-lg-5">
                        <span class="badge bg-danger mb-2 px-3 py-2 text-uppercase">Más vendido del mes</span>
                        <h2 class="fw-bolder"><?= htmlspecialchars($mas_vendido['nombre']) ?></h2>
                        <p class="text-muted small">Ref: <?= htmlspecialchars($mas_vendido['referencia']) ?></p>
                        
                        <p class="text-secondary"><?= htmlspecialchars($mas_vendido['descripcion']) ?></p>
                        
                        <div class="fs-4 mb-4">
                            <?php if ($rebajado_top): ?>
                                <span class="text-muted text-decoration-line-through me-2">$<?= number_format($mas_vendido['precio'], 2) ?></span>
                                <span class="text-danger fw-bold">$<?= number_format($mas_vendido['precio_rebajado'], 2) ?></span>
                            <?php else: ?>
                                <span class="fw-bold">$<?= number_format($mas_vendido['precio'], 2) ?></span>
                            <?php endif; ?>
                        </div>

                        <a class="btn btn-dark btn-lg" href="index.php?p=informacion-producto&id=<?= $mas_vendido['id'] ?>">Ver Producto</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ================= RESTO DE PRODUCTOS ================= -->
        <h3 class="fw-bolder mb-4 text-center">Nuestra Colección</h3>
        
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php foreach ($productos as $producto): ?>
                <?php 
                    $esta_rebajado = !is_null($producto['precio_rebajado']) && $producto['precio_rebajado'] > 0;
                    $estrellas = round($producto['valoracion']);
                    $foto = (!empty($producto['imagen']) && file_exists('assets/productos/' . $producto['imagen']))
                        ? 'assets/productos/' . $producto['imagen']
                        : 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
                ?>
                <div class="col mb-5">
                    <div class="card h-100 shadow-sm">
                        <?php if ($esta_rebajado): ?>
                            <div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Oferta</div>
                        <?php endif; ?>

                        <img class="card-img-top" 
                             src="<?= htmlspecialchars($foto) ?>" 
                             alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                             style="height: 200px; object-fit: cover;" />

                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $estrellas) ? '<i class="bi-star-fill"></i>' : '<i class="bi-star"></i>';
                                    }
                                    ?>
                                </div>
                                <?php if ($esta_rebajado): ?>
                                    <span class="text-muted text-decoration-line-through me-1">$<?= number_format($producto['precio'], 2) ?></span>
                                    <span class="fw-bold">$<?= number_format($producto['precio_rebajado'], 2) ?></span>
                                <?php else: ?>
                                    <span class="fw-bold">$<?= number_format($producto['precio'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="index.php?p=informacion-producto&id=<?= $producto['id'] ?>">Ver Producto</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>