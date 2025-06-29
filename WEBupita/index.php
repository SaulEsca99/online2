<?php
// index.php - Página principal de UPIITA Finder
// Verificar que existe el header
$header_file = __DIR__ . "/includes/header.php";
if (!file_exists($header_file)) {
    // Si no existe el header, usar HTML básico
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UPIITA Finder - Sistema de Navegación</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
    <?php
} else {
    require_once $header_file;
}
?>

<style>
.hero-section {
    background: linear-gradient(135deg, #003366 0%, #005599 100%);
    color: white;
    padding: 100px 20px;
    text-align: center;
}

.hero-section h1 {
    font-size: 3em;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-section p {
    font-size: 1.3em;
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-section .btn-hero {
    display: inline-block;
    padding: 15px 40px;
    background: white;
    color: #003366;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    font-size: 1.1em;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hero-section .btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.features {
    padding: 60px 20px;
    background: #f8f9fa;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    background: white;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.feature-card i {
    font-size: 3em;
    color: #007bff;
    margin-bottom: 20px;
}

.feature-card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.5em;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.feature-card .btn {
    display: inline-block;
    padding: 10px 25px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.feature-card .btn:hover {
    background: #0056b3;
}

.info-section {
    padding: 60px 20px;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.info-section h2 {
    color: #003366;
    margin-bottom: 20px;
    font-size: 2em;
}

.info-section p {
    color: #666;
    line-height: 1.8;
    font-size: 1.1em;
}
</style>

<div class="hero-section">
    <h1>Bienvenido a UPIITA Finder</h1>
    <p>Tu sistema inteligente de navegación para encontrar cualquier lugar dentro del campus UPIITA</p>
    <a href="pages/mapa-interactivo.php" class="btn-hero">
        <i class="fas fa-map"></i> Explorar el Campus
    </a>
</div>

<section class="features">
    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-route"></i>
            <h3>Calcula Rutas Óptimas</h3>
            <p>Encuentra el camino más corto entre cualquier punto del campus usando nuestro algoritmo avanzado.</p>
            <a href="pages/mapa-rutas.php" class="btn">Calcular Ruta</a>
        </div>
        
        <div class="feature-card">
            <i class="fas fa-map-marked-alt"></i>
            <h3>Mapa Interactivo</h3>
            <p>Explora todos los edificios, aulas y laboratorios de manera visual e intuitiva.</p>
            <a href="pages/mapa-interactivo.php" class="btn">Ver Mapa</a>
        </div>
        
        <div class="feature-card">
            <i class="fas fa-star"></i>
            <h3>Rutas Favoritas</h3>
            <p>Guarda tus rutas más frecuentes y accede a ellas rápidamente.</p>
            <a href="Public/login.php" class="btn">Mi Cuenta</a>
        </div>
    </div>
</section>

<section class="info-section">
    <h2>¿Qué es UPIITA Finder?</h2>
    <p>UPIITA Finder es un sistema de navegación diseñado específicamente para la comunidad de la Unidad Profesional Interdisciplinaria en Ingeniería y Tecnologías Avanzadas. Nuestro objetivo es ayudarte a encontrar cualquier ubicación dentro del campus de manera rápida y eficiente.</p>
</section>

<?php
// Verificar si existe el footer
$footer_file = __DIR__ . "/includes/footer.php";
if (!file_exists($footer_file)) {
    ?>
    </body>
    </html>
    <?php
} else {
    require_once $footer_file;
}
?>