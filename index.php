<?php
// 1. Incluir el archivo de conexión
require_once 'conexion.php';
$pagina = isset($_GET['p']) ? $_GET['p'] : null;

// 2. Realizar la consulta a la tabla de productos
$resultado = $conexion->query("SELECT id, referencia, nombre, precio, precio_rebajado, descripcion, valoracion FROM productos");

// 3. Comprobar que no haya errores en la consulta
if (!$resultado) {
    die("Error en la consulta de productos: " . $conexion->error);
}

// 4. Guardar todos los productos en un array asociativo
$productos = $resultado->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Item - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#!">TIENDA DE LOS XAXES</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php ">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?p=sobre-nosotros">Sobre nosotros</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!">All Products</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                                <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <!-- CONTENIDO CENTRAL DINÁMICO -->
    <?php
    switch ($pagina) {
        case "sobre-nosotros":
            // Si hacen clic en "Sobre nosotros", carga la página de contacto
            include 'contact-us.html';
            break;

        case "solicitud-recibida":
            // Mensaje de confirmación tras enviar el formulario de contacto
            echo '<div class="container py-5 text-center my-5">';
            echo '    <div class="alert alert-success d-inline-block p-4 shadow-sm">';
            echo '        <h2 class="fw-bolder mb-3"><i class="bi bi-check-circle-fill"></i> ¡Solicitud recibida!</h2>';
            echo '        <p class="lead mb-4">Hemos recibido tu mensaje correctamente. Nos pondremos en contacto contigo lo antes posible.</p>';
            echo '        <a class="btn btn-primary" href="index.php">Volver a la tienda</a>';
            echo '    </div>';
            echo '</div>';
            break;

        case "informacion-producto":
            echo '<div class="container py-5 text-center"><h2>Detalle de producto en construcción</h2></div>';
            break;

        case null:
        case "":
        case "tienda":
            // Cuando entras a la portada o a la tienda, carga los productos
            include 'products-section.php';
            break;

        default:
            // Solo sale si escriben en la URL algo inventado como ?p=loquesea
            echo '<div class="container py-5 text-center"><h2>Página no encontrada</h2></div>';
            break;
    }
    ?>
       
<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">

            <!-- Columna 1: Marca y descripción -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-warning">Los Xaxes</h5>
                <p class="text-white-50 small">
                    Tu tienda urbana de referencia. Moda, accesorios y colecciones exclusivas fabricadas con los mejores materiales. Diseñado para destacar en cada momento.
                </p>
                <div class="d-flex justify-content-center justify-content-md-start gap-3 mt-3">
                    <a href="#!" class="text-white text-decoration-none fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#!" class="text-white text-decoration-none fs-5"><i class="bi bi-facebook"></i></a>
                </div>
            </div>

            <!-- Columna 2: Enlaces rápidos -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 fw-bold text-white">Navegación</h6>
                <p><a href="index.php" class="text-white-50 text-decoration-none small">Inicio</a></p>
                <p><a href="index.php?p=sobre-nosotros" class="text-white-50 text-decoration-none small">Sobre Nosotros</a></p> 
            </div>

            <!-- Columna 3: Atención al cliente -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 fw-bold text-white">Ayuda y Soporte</h6>
                <p><a href="#!" class="text-white-50 text-decoration-none small">Envíos y Entregas</a></p>
                <p><a href="#!" class="text-white-50 text-decoration-none small">Devoluciones y Garantía</a></p>
                <p><a href="#!" class="text-white-50 text-decoration-none small">Guía de Tallas</a></p>
                <p><a href="#!" class="text-white-50 text-decoration-none small">Preguntas Frecuentes (FAQ)</a></p>
            </div>

            <!-- Columna 4: Información de contacto -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 fw-bold text-white">Contacto</h6>
                <p class="text-white-50 small mb-2"><i class="bi bi-geo-alt-fill text-warning me-2"></i> Calle Gran Vía 28, Madrid, España</p>
                <p class="text-white-50 small mb-2"><i class="bi bi-envelope-fill text-warning me-2"></i> soporte@losxaxes.local</p>
                <p class="text-white-50 small mb-2"><i class="bi bi-telephone-fill text-warning me-2"></i> +34 912 345 678</p>
                <p class="text-white-50 small"><i class="bi bi-clock-fill text-warning me-2"></i> Lun - Vie: 9:00 - 19:00</p>
            </div>

        </div>

        <hr class="my-4 border-secondary opacity-50">

        <!-- Fila inferior: Copyright, métodos de pago y enlaces legales -->
        <div class="row align-items-center">
            
            <div class="col-md-7 col-lg-8 text-center text-md-start">
                <p class="text-white-50 small mb-1">
                    &copy; <?= date('Y'); ?> <strong>Tienda de los Xaxes</strong>. Todos los derechos reservados.
                </p>
                <div class="small">
                    <a href="#!" class="text-white-50 text-decoration-none me-3">Aviso Legal</a>
                    <a href="#!" class="text-white-50 text-decoration-none me-3">Política de Privacidad</a>
                    <a href="#!" class="text-white-50 text-decoration-none">Política de Cookies</a>
                </div>
            </div>

            <!-- Métodos de pago aceptados -->
            <div class="col-md-5 col-lg-4 text-center text-md-end mt-3 mt-md-0">
                <span class="text-white-50 small me-2 d-block d-sm-inline mb-2 mb-sm-0">Pagos 100% seguros:</span>
                <span class="fs-4 text-white-50">
                    <i class="bi bi-credit-card-2-front me-2" title="Tarjeta"></i>
                    <i class="bi bi-paypal me-2" title="PayPal"></i>
                    <i class="bi bi-shield-check me-2" title="Pago Seguro"></i>
                    <i class="bi bi-phone" title="Bizum / Móvil"></i>
                </span>
            </div>

        </div>
    </div>
</footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
