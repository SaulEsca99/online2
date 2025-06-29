<?php
/**
 * Diagnóstico para resolver problemas con index.php
 * Coloca este archivo en la raíz de tu servidor (donde está .htaccess)
 * Accede a: https://upiitascholar.com/diagnostico_index.php
 */

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Diagnóstico index.php - UPIITA Finder</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }";
echo "h1, h2 { color: #333; }";
echo ".section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }";
echo ".ok { color: #28a745; font-weight: bold; }";
echo ".error { color: #dc3545; font-weight: bold; }";
echo ".warning { color: #ffc107; font-weight: bold; }";
echo ".code { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; font-family: monospace; margin: 10px 0; }";
echo ".button { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }";
echo ".button:hover { background: #0056b3; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>🔍 Diagnóstico de index.php - UPIITA Finder</h1>";

// 1. Verificar ubicación actual
echo "<div class='section'>";
echo "<h2>📍 Ubicación actual del script</h2>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Archivo actual:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>URL solicitada:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "</div>";

// 2. Verificar si existe index.php
echo "<div class='section'>";
echo "<h2>📁 Verificación de archivos index</h2>";

$posibles_index = [
    'index.php',
    'index.html',
    'Public/index.php',
    'public/index.php'
];

foreach ($posibles_index as $archivo) {
    if (file_exists($archivo)) {
        echo "<p class='ok'>✅ Encontrado: $archivo</p>";
        echo "<div class='code'>";
        echo "Tamaño: " . filesize($archivo) . " bytes<br>";
        echo "Última modificación: " . date('Y-m-d H:i:s', filemtime($archivo));
        echo "</div>";
    } else {
        echo "<p class='error'>❌ No encontrado: $archivo</p>";
    }
}
echo "</div>";

// 3. Crear index.php si no existe
if (!file_exists('index.php')) {
    echo "<div class='section'>";
    echo "<h2>⚠️ No se encontró index.php en la raíz</h2>";
    echo "<p>El archivo index.php no existe en la raíz del servidor.</p>";
    
    // Buscar si existe en Public/
    if (file_exists('Public/index.php')) {
        echo "<p class='warning'>Se encontró index.php en el directorio Public/</p>";
        echo "<p>Opciones disponibles:</p>";
        echo "<a href='?action=copy' class='button'>📋 Copiar Public/index.php a la raíz</a>";
        echo "<a href='?action=redirect' class='button'>↪️ Crear index.php con redirección</a>";
        echo "<a href='?action=create' class='button'>🆕 Crear nuevo index.php</a>";
    } else {
        echo "<p>Se creará un nuevo archivo index.php</p>";
        echo "<a href='?action=create' class='button'>🆕 Crear index.php</a>";
    }
    echo "</div>";
}

// 4. Procesar acciones
if (isset($_GET['action'])) {
    echo "<div class='section'>";
    echo "<h2>🔧 Ejecutando acción: " . htmlspecialchars($_GET['action']) . "</h2>";
    
    switch ($_GET['action']) {
        case 'copy':
            if (file_exists('Public/index.php') && !file_exists('index.php')) {
                if (copy('Public/index.php', 'index.php')) {
                    echo "<p class='ok'>✅ index.php copiado exitosamente desde Public/</p>";
                } else {
                    echo "<p class='error'>❌ Error al copiar el archivo</p>";
                }
            }
            break;
            
        case 'redirect':
            $redirect_content = '<?php
// Redirección al index.php en Public/
header("Location: Public/index.php");
exit();
?>';
            if (file_put_contents('index.php', $redirect_content)) {
                echo "<p class='ok'>✅ index.php creado con redirección a Public/</p>";
            } else {
                echo "<p class='error'>❌ Error al crear el archivo</p>";
            }
            break;
            
        case 'create':
            $index_content = '<?php
// index.php - Página principal de UPIITA Finder
require_once __DIR__ . \'/includes/header.php\';
?>

<style>
.hero-section {
    background: linear-gradient(135deg, #003366 0%, #005599 100%);
    color: white;
    padding: 80px 20px;
    text-align: center;
    margin-bottom: 40px;
}

.hero-section h1 {
    font-size: 3em;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-section p {
    font-size: 1.3em;
    margin-bottom: 30px;
}

.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px auto;
    max-width: 1200px;
    padding: 0 20px;
}

.feature-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
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
}

.feature-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-block;
    padding: 12px 30px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #0056b3;
}
</style>

<div class="hero-section">
    <h1>Bienvenido a UPIITA Finder</h1>
    <p>Tu sistema de navegación inteligente para la UPIITA</p>
    <a href="pages/mapa-interactivo.php" class="btn-primary">
        <i class="fas fa-map"></i> Explorar el Campus
    </a>
</div>

<div class="features">
    <div class="feature-card">
        <i class="fas fa-route"></i>
        <h3>Calcula Rutas</h3>
        <p>Encuentra el camino más corto entre cualquier punto del campus.</p>
        <a href="pages/mapa-rutas.php" class="btn-primary">Calcular Ruta</a>
    </div>
    
    <div class="feature-card">
        <i class="fas fa-map-marked-alt"></i>
        <h3>Mapa Interactivo</h3>
        <p>Explora todos los edificios y aulas de manera visual e intuitiva.</p>
        <a href="pages/mapa-interactivo.php" class="btn-primary">Ver Mapa</a>
    </div>
    
    <div class="feature-card">
        <i class="fas fa-user"></i>
        <h3>Tu Cuenta</h3>
        <p>Guarda tus rutas favoritas y personaliza tu experiencia.</p>
        <a href="Public/login.php" class="btn-primary">Iniciar Sesión</a>
    </div>
</div>

<?php require_once __DIR__ . \'/includes/footer.php\'; ?>';
            
            if (file_put_contents('index.php', $index_content)) {
                echo "<p class='ok'>✅ Nuevo index.php creado exitosamente</p>";
            } else {
                echo "<p class='error'>❌ Error al crear el archivo</p>";
            }
            break;
    }
    
    echo "<p><a href='diagnostico_index.php' class='button'>🔄 Recargar diagnóstico</a></p>";
    echo "</div>";
}

// 5. Verificar .htaccess
echo "<div class='section'>";
echo "<h2>📄 Verificación de .htaccess</h2>";
if (file_exists('.htaccess')) {
    echo "<p class='ok'>✅ .htaccess existe</p>";
    echo "<div class='code'>";
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
    echo "</div>";
} else {
    echo "<p class='error'>❌ .htaccess no encontrado</p>";
}
echo "</div>";

// 6. Verificar estructura de directorios
echo "<div class='section'>";
echo "<h2>📂 Estructura de directorios</h2>";
$directorios = ['includes', 'css', 'js', 'images', 'pages', 'Public', 'api'];
foreach ($directorios as $dir) {
    if (is_dir($dir)) {
        echo "<p class='ok'>✅ $dir/</p>";
    } else {
        echo "<p class='error'>❌ $dir/ (no encontrado)</p>";
    }
}
echo "</div>";

// 7. Probar acceso a URLs
echo "<div class='section'>";
echo "<h2>🔗 Prueba de URLs</h2>";
echo "<p>Después de corregir, prueba estos enlaces:</p>";
echo "<ul>";
echo "<li><a href='https://upiitascholar.com/' target='_blank'>https://upiitascholar.com/</a></li>";
echo "<li><a href='https://upiitascholar.com/index.php' target='_blank'>https://upiitascholar.com/index.php</a></li>";
echo "<li><a href='https://upiitascholar.com/pages/mapa-interactivo.php' target='_blank'>Mapa Interactivo</a></li>";
echo "<li><a href='https://upiitascholar.com/pages/mapa-rutas.php' target='_blank'>Calcular Rutas</a></li>";
echo "</ul>";
echo "</div>";

echo "</div>";
echo "</body>";
echo "</html>";
?>