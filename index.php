<?php
// 1. Incluir el archivo de conexión
require_once 'conexion.php';
$pagina = isset($_GET['p']) ?$_GET['p'] : null;

// 2. Realizar la consulta a la tabla de productos
$resultado =$conexion->query("SELECT id, referencia, nombre, precio, precio_rebajado, descripcion, valoracion FROM productos");

// 3. Comprobar que no haya errores en la consulta
if (!$resultado) {
    die("Error en la consulta de productos: " . $conexion->error);
}

// 4. Guardar todos los productos en un array asociativo
$productos =$resultado->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Tienda de los Xaxes</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand fw-bold" href="index.php">TIENDA DE LOS XAXES</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?p=sobre-nosotros">Sobre nosotros</a></li>
                    </ul>
                    <div class="d-flex">
                        <a href="index.php?p=carrito" class="btn btn-outline-dark">
                            <i class="bi-cart-fill me-1"></i>
                            Carrito
                            <span id="cart-count" class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO CENTRAL DINÁMICO -->
        <?php
        switch ($pagina) {
            case "sobre-nosotros":
                include 'contact-us.html';
                break;

            case "solicitud-recibida":
                echo '<div class="container py-5 text-center my-5">';
                echo '    <div class="alert alert-success d-inline-block p-4 shadow-sm">';
                echo '        <h2 class="fw-bolder mb-3"><i class="bi bi-check-circle-fill"></i> ¡Solicitud recibida!</h2>';
                echo '        <p class="lead mb-4">Hemos recibido tu mensaje correctamente.</p>';
                echo '        <a class="btn btn-primary" href="index.php">Volver a la tienda</a>';
                echo '    </div>';
                echo '</div>';
                break;

            case "informacion-producto":
                include 'product-detail.php';
                break;

            case null:
            case "":
            case "tienda":
                include 'products-section.php';
                break;

            case "carrito":
                include 'cart-section.php';
                break;

            default:
                echo '<div class="container py-5 text-center my-5"><h2>Página no encontrada</h2><a class="btn btn-dark mt-3" href="index.php">Volver a Inicio</a></div>';
                break;
        }
        ?>

        <!-- Footer -->
        <footer class="bg-dark text-white pt-5 pb-4 mt-5">
            <div class="container text-center text-md-start">
                <div class="row text-center text-md-start">
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 fw-bold text-warning">Los Xaxes</h5>
                        <p class="text-white-50 small">
                            Tu tienda urbana de referencia. Moda, accesorios y colecciones exclusivas.
                        </p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 fw-bold text-white">Navegación</h6>
                        <p><a href="index.php" class="text-white-50 text-decoration-none small">Inicio</a></p>
                        <p><a href="index.php?p=sobre-nosotros" class="text-white-50 text-decoration-none small">Sobre Nosotros</a></p> 
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 fw-bold text-white">Contacto</h6>
                        <p class="text-white-50 small mb-2"><i class="bi bi-envelope-fill text-warning me-2"></i> soporte@losxaxes.local</p>
                    </div>
                </div>
                <hr class="my-4 border-secondary opacity-50">
                <div class="text-center text-white-50 small">
                    &copy; <?= date('Y'); ?> <strong>Tienda de los Xaxes</strong>. Todos los derechos reservados.
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS y Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>