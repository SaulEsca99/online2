<?php
// config.php - Configuración central del proyecto UPIITA
// Coloca este archivo en la raíz de tu servidor IONOS

// Detectar si estamos en desarrollo o producción
$is_production = $_SERVER['SERVER_NAME'] !== 'localhost';

// Configuración de rutas
if ($is_production) {
    // IONOS - Producción
    define('BASE_URL', 'https://upiitascholar.com');
    define('SITE_URL', 'https://upiitascholar.com');
} else {
    // Desarrollo local
    define('BASE_URL', '/TecnologiasParaElDesarrolloDeAplicacionesWeb/SchoolPathFinder/WEBupita');
    define('SITE_URL', 'http://localhost' . BASE_URL);
}

// Rutas de sistema (no cambiar)
define('ROOT_PATH', __DIR__ . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('PUBLIC_PATH', ROOT_PATH . 'Public/');
define('PAGES_PATH', ROOT_PATH . 'pages/');
define('CSS_PATH', ROOT_PATH . 'css/');
define('JS_PATH', ROOT_PATH . 'js/');
define('IMAGES_PATH', ROOT_PATH . 'images/');
define('API_PATH', ROOT_PATH . 'api/');

// Configuración de errores
if (!$is_production) {
    // Desarrollo - mostrar todos los errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Producción - ocultar errores
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . 'error.log');
}

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', $is_production ? 1 : 0);

// Charset por defecto
ini_set('default_charset', 'UTF-8');

// Configuración de límites (ajusta según lo que permita IONOS)
ini_set('max_execution_time', 60);
ini_set('memory_limit', '256M');
ini_set('post_max_size', '16M');
ini_set('upload_max_filesize', '16M');

// Función helper para URLs
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

// Función helper para rutas de sistema
function path($path = '') {
    return ROOT_PATH . ltrim($path, '/');
}

// Cargar automáticamente funciones comunes si existen
$helpers_file = INCLUDES_PATH . 'helpers.php';
if (file_exists($helpers_file)) {
    require_once $helpers_file;
}
?>