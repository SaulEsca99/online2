<?php
/**
 * verificar_index.php - Verificar y corregir problemas en index.php
 * Sube este archivo a la raíz y accede a: https://upiitascholar.com/verificar_index.php
 */

// Mostrar todos los errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Verificar index.php - UPIITA Finder</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
echo "h1, h2 { color: #333; }";
echo ".code { background: #f4f4f4; padding: 15px; border: 1px solid #ddd; margin: 10px 0; overflow-x: auto; }";
echo ".ok { color: #28a745; font-weight: bold; }";
echo ".error { color: #dc3545; font-weight: bold; }";
echo ".button { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }";
echo "pre { margin: 0; white-space: pre-wrap; word-wrap: break-word; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

echo "<h1>🔍 Verificación de index.php</h1>";

// 1. Verificar el contenido actual
echo "<h2>📄 Contenido actual de index.php</h2>";

if (file_exists('index.php')) {
    $contenido_actual = file_get_contents('index.php');
    $primeras_lineas = implode("\n", array_slice(explode("\n", $contenido_actual), 0, 20));
    
    echo "<div class='code'>";
    echo "<pre>" . htmlspecialchars($primeras_lineas) . "\n...</pre>";
    echo "</div>";
    
    // Verificar errores de sintaxis
    echo "<h3>🔧 Verificación de sintaxis PHP</h3>";
    
    // Guardar temporalmente para verificar sintaxis
    $temp_file = 'temp_check_' . uniqid() . '.php';
    file_put_contents($temp_file, $contenido_actual);
    
    $output = shell_exec("php -l $temp_file 2>&1");
    unlink($temp_file);
    
    if (strpos($output, 'No syntax errors') !== false) {
        echo "<p class='ok'>✅ No hay errores de sintaxis en index.php</p>";
    } else {
        echo "<p class='error'>❌ Error de sintaxis detectado:</p>";
        echo "<div class='code'><pre>" . htmlspecialchars($output) . "</pre></div>";
    }
    
    // Verificar problemas comunes
    echo "<h3>🔍 Verificación de problemas comunes</h3>";
    
    $problemas = [];
    
    // Verificar BOM
    if (substr($contenido_actual, 0, 3) === "\xEF\xBB\xBF") {
        $problemas[] = "Archivo con BOM (Byte Order Mark) - puede causar problemas con headers";
    }
    
    // Verificar espacios antes de <?php
    if (trim($contenido_actual) !== $contenido_actual) {
        $problemas[] = "Espacios en blanco al inicio o final del archivo";
    }
    
    // Verificar includes
    if (strpos($contenido_actual, 'include') !== false || strpos($contenido_actual, 'require') !== false) {
        preg_match_all('/(include|require)(_once)?\s*["\']([^"\']+)["\']/i', $contenido_actual, $matches);
        foreach ($matches[3] as $archivo) {
            if (!file_exists($archivo) && !file_exists(__DIR__ . '/' . $archivo)) {
                $problemas[] = "Archivo incluido no encontrado: $archivo";
            }
        }
    }
    
    if (empty($problemas)) {
        echo "<p class='ok'>✅ No se detectaron problemas comunes</p>";
    } else {
        foreach ($problemas as $problema) {
            echo "<p class='error'>❌ $problema</p>";
        }
    }
}

// 2. Opción para crear un index.php básico de prueba
echo "<h2>🔧 Opciones de corrección</h2>";
echo "<a href='?action=backup' class='button'>💾 Hacer backup del actual</a>";
echo "<a href='?action=test' class='button'>🧪 Crear index.php de prueba</a>";
echo "<a href='?action=fix' class='button'>🔨 Crear index.php completo</a>";

// Procesar acciones
if (isset($_GET['action'])) {
    echo "<h2>⚙️ Ejecutando: " . htmlspecialchars($_GET['action']) . "</h2>";
    
    switch ($_GET['action']) {
        case 'backup':
            $backup_name = 'index_backup_' . date('Y-m-d_H-i-s') . '.php';
            if (copy('index.php', $backup_name)) {
                echo "<p class='ok'>✅ Backup creado: $backup_name</p>";
            }
            break;
            
        case 'test':
            // Index de prueba simple
            $test_content = '<?php
// Index de prueba para verificar que PHP funciona
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPIITA Finder - Prueba</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #003366; }
        .info { 
            background: #e3f2fd; 
            padding: 20px; 
            border-radius: 5px; 
            margin: 20px 0;
        }
        .links a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .links a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 UPIITA Finder - Página de Prueba</h1>
        <div class="info">
            <p><strong>✅ PHP está funcionando correctamente</strong></p>
            <p>Versión PHP: <?php echo PHP_VERSION; ?></p>
            <p>Servidor: <?php echo $_SERVER["SERVER_SOFTWARE"]; ?></p>
            <p>Timestamp: <?php echo date("Y-m-d H:i:s"); ?></p>
        </div>
        <div class="links">
            <a href="pages/mapa-interactivo.php">Mapa Interactivo</a>
            <a href="pages/mapa-rutas.php">Calcular Rutas</a>
            <a href="Public/login.php">Iniciar Sesión</a>
        </div>
    </div>
</body>
</html>';
            
            if (file_put_contents('index.php', $test_content)) {
                echo "<p class='ok'>✅ Index de prueba creado</p>";
                echo "<p>Visita <a href='https://upiitascholar.com/' target='_blank'>https://upiitascholar.com/</a> para verificar</p>";
            }
            break;
            
        case 'fix':
            // Index completo funcional
            $full_content = '<?php
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
?>';
            
            if (file_put_contents('index.php', $full_content)) {
                echo "<p class='ok'>✅ Index completo creado exitosamente</p>";
                echo "<p>Visita <a href='https://upiitascholar.com/' target='_blank'>https://upiitascholar.com/</a> para verificar</p>";
            }
            break;
    }
}

// 3. Verificar errores PHP
echo "<h2>🐛 Log de errores PHP recientes</h2>";
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    $errors = file_get_contents($error_log);
    $lines = explode("\n", $errors);
    $recent_errors = array_slice($lines, -10);
    
    if (!empty($recent_errors)) {
        echo "<div class='code'>";
        echo "<pre>" . htmlspecialchars(implode("\n", $recent_errors)) . "</pre>";
        echo "</div>";
    } else {
        echo "<p>No hay errores recientes en el log</p>";
    }
} else {
    echo "<p>No se puede acceder al log de errores PHP</p>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>