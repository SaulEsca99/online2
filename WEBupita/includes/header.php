<?php
/**
 * Header corregido con rutas absolutas
 */

// Cargar configuración si existe
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    // Configuración básica si no existe config.php
    define('BASE_URL', 'https://upiitascholar.com');
    
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo : 'UPIITA Finder'; ?></title>
    
    <!-- CSS con ruta absoluta -->
    <link rel="stylesheet" href="https://upiitascholar.com/css/styles.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Meta tags adicionales -->
    <meta name="description" content="Sistema de navegación y localización para UPIITA - Instituto Politécnico Nacional">
    <meta name="keywords" content="UPIITA, navegación, mapa, rutas, IPN">
    <meta name="author" content="UPIITA Finder">
    
    <!-- CSS específico por página -->
    <?php if (isset($css_adicional)): ?>
        <?php foreach ($css_adicional as $css): ?>
            <link rel="stylesheet" href="https://upiitascholar.com/<?= ltrim($css, '/') ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header Principal -->
    <header class="main-header">
        <div class="container">
            <h1 class="main-title">
                <a href="https://upiitascholar.com/index.php" style="color: white; text-decoration: none;">
                    UPIITA Finder
                </a>
            </h1>
            <p class="subtitle">Sistema de Navegación y Localización</p>
        </div>
    </header>

    <!-- Navegación Principal -->
    <nav class="primary-nav">
        <a href="https://upiitascholar.com/index.php" class="nav-button <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Inicio
        </a>
        
        <a href="https://upiitascholar.com/pages/mapa-interactivo.php" class="nav-button <?= strpos($_SERVER['PHP_SELF'], 'mapa-interactivo') !== false ? 'active' : '' ?>">
            <i class="fas fa-map"></i> Mapa Interactivo
        </a>
        
        <a href="https://upiitascholar.com/pages/mapa-rutas.php" class="nav-button <?= strpos($_SERVER['PHP_SELF'], 'mapa-rutas') !== false ? 'active' : '' ?>">
            <i class="fas fa-route"></i> Calcular Rutas
        </a>
        
        <a href="https://upiitascholar.com/pages/conocenos.php" class="nav-button <?= strpos($_SERVER['PHP_SELF'], 'conocenos') !== false ? 'active' : '' ?>">
            <i class="fas fa-info-circle"></i> Conócenos
        </a>
        
        <a href="https://upiitascholar.com/pages/red-genero.php" class="nav-button <?= strpos($_SERVER['PHP_SELF'], 'red-genero') !== false ? 'active' : '' ?>">
            <i class="fas fa-venus-mars"></i> Red de Género
        </a>
        
        <a href="https://upiitascholar.com/pages/redes-sociales.php" class="nav-button <?= strpos($_SERVER['PHP_SELF'], 'redes-sociales') !== false ? 'active' : '' ?>">
            <i class="fas fa-share-alt"></i> Redes Sociales
        </a>

        <!-- Área de usuario -->
        <div class="user-area">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="https://upiitascholar.com/Public/favoritos.php" class="nav-button">
                    <i class="fas fa-heart"></i> Favoritos
                </a>
                <a href="https://upiitascholar.com/Public/perfil.php" class="nav-button">
                    <i class="fas fa-user"></i> Perfil
                </a>
                <a href="https://upiitascholar.com/Public/logout.php" class="nav-button">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            <?php else: ?>
                <a href="https://upiitascholar.com/Public/login.php" class="nav-button">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="https://upiitascholar.com/Public/registro.php" class="nav-button">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="main-container">