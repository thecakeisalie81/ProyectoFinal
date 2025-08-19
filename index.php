<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="#">Auto-Todo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">Sobre Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contacto</a></li>
                <li class="nav-item ms-2">
                    <a class="btn btn-warning text-dark" href="Login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HOME -->
<section id="home" class="bg-light text-center" style="margin-top:56px;">
    <div class="container">
        <h1 class="display-4">AUTO TODO CUIDA DE SU VEHÍCULO</h1>
    </div>
</section>

<!-- SERVICIOS -->
<section id="services" class="bg-light py-5">
    <div class="container text-center">
        <h2 class="mb-5">Nuestros Servicios</h2>
        <div class="row g-4">
            <!-- Servicio 1 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/ac.avif" class="card-img-top" alt="Mantenimiento y reparación de A/C">
                    <div class="card-body">
                        <h5 class="card-title">Mantenimiento y Reparación de A/C</h5>
                        <p class="card-text">Servicio especializado para asegurar el óptimo rendimiento de su aire acondicionado.</p>
                    </div>
                </div>
            </div>
            <!-- Servicio 2 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/escaneo.avif" class="card-img-top" alt="Escaneo">
                    <div class="card-body">
                        <h5 class="card-title">Escaneo</h5>
                        <p class="card-text">Diagnóstico preciso con herramientas de escaneo computarizado para detectar fallas rápidamente.</p>
                    </div>
                </div>
            </div>
            <!-- Servicio 3 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/inyectores.avif" class="card-img-top" alt="Limpieza de inyectores">
                    <div class="card-body">
                        <h5 class="card-title">Limpieza de Inyectores</h5>
                        <p class="card-text">Limpieza profesional para mejorar el rendimiento del motor y reducir el consumo de combustible.</p>
                    </div>
                </div>
            </div>
            <!-- Servicio 4 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/mecanica.avif" class="card-img-top" alt="Mecánica rápida">
                    <div class="card-body">
                        <h5 class="card-title">Mecánica Rápida</h5>
                        <p class="card-text">Soluciones mecánicas rápidas y efectivas para que su vehículo vuelva a la carretera sin demoras.</p>
                    </div>
                </div>
            </div>
            <!-- Servicio 5 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/repuestos.jpe" class="card-img-top" alt="Venta de repuestos y productos químicos">
                    <div class="card-body">
                        <h5 class="card-title">Venta de Repuestos y Productos Químicos</h5>
                        <p class="card-text">Amplia gama de repuestos originales y productos químicos para el cuidado de su vehículo.</p>
                    </div>
                </div>
            </div>
            <!-- Servicio 6 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/index/alineacion.webp" class="card-img-top" alt="Alineación de dirección">
                    <div class="card-body">
                        <h5 class="card-title">Alineación de Dirección</h5>
                        <p class="card-text">Ajuste preciso para una conducción más segura y un desgaste uniforme de los neumáticos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SOBRE NOSOTROS -->
<section id="about">
    <div class="container text-center">
        <h2 class="mb-4">Sobre Nosotros</h2>
        <p class="text-muted">Somos una empresa dedicada a brindar servicios de alta calidad a nuestros clientes. Nuestro equipo cuenta con años de experiencia en el sector, ofreciendo soluciones efectivas y adaptadas a cada necesidad.</p>
        <img src="assets/img/index/equipo.png" class="card-img-top" alt="Mantenimiento y reparación de A/C">
    </div>
</section>


<!-- CONTACTO -->
<section id="contact">
    <div class="container text-center">
        <h2 class="mb-4">Contáctanos</h2>
        <p><strong>Teléfono:</strong> +506 8547 1314</p>
        <p><strong>Correo:</strong> autotodo21@gmail.com</p>
        <p><strong>Dirección:</strong> 200 metros sur Iglesia Evangélica El Encanto, Pital, Costa Rica</p>
    </div>
</section>

<!-- FOOTER -->
<footer class="text-center">
    <p>© 2025 Auto-Todo. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
