<?php
// Consultamos los productos a la base de datos
$resultado = $conexion->query("SELECT id, referencia, nombre, precio, precio_rebajado, descripcion, valoracion FROM productos");

if ($resultado) {
    $productos = $resultado->fetch_all(MYSQLI_ASSOC);
} else {
    $productos = [];
}
?>
<!-- Products section -->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="fw-bolder mb-4 text-center">Nuestros Productos</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php if (empty($productos)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No hay productos disponibles.</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <?php 
                        $esta_rebajado = !is_null($producto['precio_rebajado']) && $producto['precio_rebajado'] > 0;
                        $estrellas = round($producto['valoracion']);
                    ?>
                    <div class="col mb-5">
                        <div class="card h-100 shadow-sm">
                            <?php if ($esta_rebajado): ?>
                                <div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Oferta</div>
                            <?php endif; ?>
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="<?= htmlspecialchars($producto['nombre']) ?>" />
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
            <?php endif; ?>
        </div>
    </div>
</section>