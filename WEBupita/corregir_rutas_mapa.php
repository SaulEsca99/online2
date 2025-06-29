<?php
/**
 * Script para corregir automáticamente las rutas en el sistema de mapas
 * Ejecutar desde la raíz del proyecto
 */

echo "🔧 Iniciando corrección de rutas del mapa...\n\n";

// Archivos a corregir
$archivos = [
    'pages/mapa-rutas.php',
    'pages/mapa-rutas-realista.php',
    'pages/mapa-interactivo.php',
    'js/MapaRealista.js'
];

$correcciones = 0;

foreach ($archivos as $archivo) {
    if (!file_exists($archivo)) {
        echo "⏭️  Archivo no encontrado: $archivo\n";
        continue;
    }
    
    echo "📁 Procesando: $archivo\n";
    
    $contenido = file_get_contents($archivo);
    $contenido_original = $contenido;
    
    // Correcciones para archivos PHP
    if (strpos($archivo, '.php') !== false) {
        // Agregar configuración de URLs si no existe
        if (strpos($contenido, 'window.API_BASE_URL') === false) {
            $config = "\n    <script>\n        // Configuración global\n        window.API_BASE_URL = 'https://upiitascholar.com';\n        window.BASE_URL = 'https://upiitascholar.com';\n    </script>\n";
            
            // Insertar antes del primer script o al final del body
            if (strpos($contenido, '<script') !== false) {
                $contenido = preg_replace('/<script/', $config . '<script', $contenido, 1);
            } else {
                $contenido = str_replace('</body>', $config . '</body>', $contenido);
            }
        }
        
        // Corregir fetch con rutas relativas
        $contenido = preg_replace(
            '/fetch\([\'"]\.\.\/api\//',
            "fetch('https://upiitascholar.com/api/",
            $contenido
        );
        
        // Corregir BASE_PATH
        $contenido = str_replace(
            "BASE_PATH + '/api/",
            "'https://upiitascholar.com/api/",
            $contenido
        );
        
        // Corregir window.location incorrectos
        $contenido = preg_replace(
            '/window\.location\.origin \+ [\'"]\/WEBupita\//',
            "'https://upiitascholar.com/",
            $contenido
        );
    }
    
    // Correcciones para archivos JS
    if (strpos($archivo, '.js') !== false) {
        // Agregar configuración al inicio si no existe
        if (strpos($contenido, 'window.API_BASE_URL') === false && strpos($contenido, 'baseUrl') === false) {
            $config = "// Configuración de URLs\nconst API_BASE_URL = window.API_BASE_URL || 'https://upiitascholar.com';\nconst BASE_URL = window.BASE_URL || 'https://upiitascholar.com';\n\n";
            $contenido = $config . $contenido;
        }
        
        // Corregir this.baseUrl
        $contenido = preg_replace(
            '/\$\{this\.baseUrl\}\/api\//',
            'https://upiitascholar.com/api/',
            $contenido
        );
        
        // Corregir fetch relativo
        $contenido = preg_replace(
            '/fetch\(`\.\.\/api\//',
            "fetch(`https://upiitascholar.com/api/",
            $contenido
        );
    }
    
    // Guardar si hubo cambios
    if ($contenido !== $contenido_original) {
        if (file_put_contents($archivo, $contenido)) {
            echo "   ✅ Corregido exitosamente\n";
            $correcciones++;
        } else {
            echo "   ❌ Error al guardar\n";
        }
    } else {
        echo "   ⏭️  Sin cambios necesarios\n";
    }
}

// Crear archivo de verificación de APIs
$verificacion = '<?php
// verificar_apis.php
header("Content-Type: text/html; charset=utf-8");

echo "<h1>Verificación de APIs del Sistema de Mapas</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .ok { color: green; }
    .error { color: red; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
</style>";

require_once "includes/conexion.php";

// APIs a verificar
$apis = [
    "buscar_lugares" => "api/buscar_lugares.php",
    "calcular_ruta" => "api/calcular_ruta.php",
    "mapa_coordenadas" => "api/mapa_coordenadas.php"
];

foreach ($apis as $nombre => $archivo) {
    echo "<h2>API: $nombre</h2>";
    
    if (!file_exists($archivo)) {
        echo "<p class=\"error\">❌ Archivo no encontrado: $archivo</p>";
        continue;
    }
    
    // Simular llamada GET
    $_SERVER["REQUEST_METHOD"] = "GET";
    ob_start();
    include $archivo;
    $response = ob_get_clean();
    
    $data = json_decode($response, true);
    
    if ($data) {
        if (isset($data["success"])) {
            if ($data["success"]) {
                echo "<p class=\"ok\">✅ API funcionando correctamente</p>";
                if (isset($data["lugares"])) {
                    echo "<p>Lugares encontrados: " . count($data["lugares"]) . "</p>";
                }
                if (isset($data["edificios"])) {
                    echo "<p>Edificios encontrados: " . count($data["edificios"]) . "</p>";
                }
            } else {
                echo "<p class=\"error\">⚠️ API respondió con error: " . ($data["error"] ?? "Error desconocido") . "</p>";
            }
        }
        echo "<details><summary>Ver respuesta completa</summary><pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre></details>";
    } else {
        echo "<p class=\"error\">❌ Respuesta no es JSON válido</p>";
        echo "<pre>$response</pre>";
    }
}

// Verificar conectividad de base de datos
echo "<h2>Conectividad de Base de Datos</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Edificios");
    $edificios = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p class=\"ok\">✅ Edificios en BD: " . $edificios["total"] . "</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Aulas WHERE coordenada_x IS NOT NULL");
    $aulas = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p class=\"ok\">✅ Aulas con coordenadas: " . $aulas["total"] . "</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Rutas");
    $rutas = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p class=\"ok\">✅ Rutas configuradas: " . $rutas["total"] . "</p>";
    
} catch (Exception $e) {
    echo "<p class=\"error\">❌ Error de BD: " . $e->getMessage() . "</p>";
}
?>';

file_put_contents('verificar_apis.php', $verificacion);

echo "\n✅ Corrección completada!\n";
echo "📊 Archivos corregidos: $correcciones\n";
echo "📝 Archivo de verificación creado: verificar_apis.php\n\n";

echo "🔍 Próximos pasos:\n";
echo "1. Ejecuta verificar_apis.php en el navegador\n";
echo "2. Limpia caché del navegador (Ctrl+F5)\n";
echo "3. Prueba el mapa de rutas\n";
echo "4. Si persisten problemas, revisa la consola del navegador\n";
?>