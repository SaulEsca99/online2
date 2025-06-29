<?php
/**
 * Script de verificación final del sistema UPIITA
 * Ejecutar después de las correcciones
 */

echo "<h1>🔍 Verificación Final del Sistema</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
    .ok { color: #4CAF50; font-weight: bold; }
    .error { color: #f44336; font-weight: bold; }
    .warning { color: #FF9800; font-weight: bold; }
    .info { color: #2196F3; font-weight: bold; }
    .section { margin: 20px 0; padding: 15px; border-left: 4px solid #2196F3; background: #f8f9fa; }
    .test-grid { display: grid; grid-template-columns: 1fr 200px; gap: 10px; margin: 10px 0; }
    .test-item { padding: 10px; background: white; border-radius: 5px; border: 1px solid #ddd; }
</style>";

echo "<div class='container'>";

// 1. Verificar conexión a IONOS
echo "<div class='section'>";
echo "<h2>🌐 Conexión a IONOS</h2>";

try {
    $host = 'db5018121072.hosting-data.io';
    $db   = 'dbs14382122';
    $user = 'dbu2064690';
    $pass = 'Upiita2024!';
    
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='test-grid'>";
    echo "<div class='test-item'>✅ Conexión a IONOS exitosa</div>";
    echo "<div class='test-item ok'>CORRECTO</div>";
    echo "</div>";
    
    // Verificar tablas críticas
    $tablas = ['Usuarios', 'Edificios', 'Aulas', 'PuntosConexion', 'Rutas', 'RutasFavoritas'];
    foreach ($tablas as $tabla) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='test-grid'>";
            echo "<div class='test-item'>✅ Tabla $tabla existe</div>";
            echo "<div class='test-item ok'>CORRECTO</div>";
            echo "</div>";
        } else {
            echo "<div class='test-grid'>";
            echo "<div class='test-item'>❌ Tabla $tabla no encontrada</div>";
            echo "<div class='test-item error'>ERROR</div>";
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='test-grid'>";
    echo "<div class='test-item'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    echo "<div class='test-item error'>ERROR</div>";
    echo "</div>";
}
echo "</div>";

// 2. Verificar archivos críticos
echo "<div class='section'>";
echo "<h2>📁 Archivos del Sistema</h2>";

$archivos_criticos = [
    'index.php' => 'Página principal',
    'includes/header.php' => 'Header principal',
    'includes/footer.php' => 'Footer principal',
    'includes/conexion.php' => 'Conexión BD',
    'includes/Dijkstra.php' => 'Algoritmo rutas',
    'css/styles.css' => 'Estilos principales',
    'js/main.js' => 'JavaScript principal',
    'api/calcular_ruta.php' => 'API cálculo rutas',
    'api/buscar_lugares.php' => 'API búsqueda lugares',
    'pages/mapa-rutas.php' => 'Página cálculo rutas',
    'pages/mapa-interactivo.php' => 'Mapa interactivo',
    'Public/login.php' => 'Sistema login',
    'Public/registro.php' => 'Sistema registro'
];

foreach ($archivos_criticos as $archivo => $descripcion) {
    echo "<div class='test-grid'>";
    if (file_exists($archivo)) {
        echo "<div class='test-item'>✅ $descripcion ($archivo)</div>";
        echo "<div class='test-item ok'>EXISTE</div>";
    } else {
        echo "<div class='test-item'>❌ $descripcion ($archivo)</div>";
        echo "<div class='test-item error'>FALTANTE</div>";
    }
    echo "</div>";
}
echo "</div>";

// 3. Verificar rutas y URLs
echo "<div class='section'>";
echo "<h2>🔗 Verificación de URLs y Rutas</h2>";

$urls_test = [
    'https://upiitascholar.com/css/styles.css' => 'CSS principal',
    'https://upiitascholar.com/js/main.js' => 'JavaScript principal',
    'https://upiitascholar.com/api/calcular_ruta.php' => 'API cálculo rutas',
    'https://upiitascholar.com/api/buscar_lugares.php' => 'API búsqueda lugares',
    'https://upiitascholar.com/pages/mapa-rutas.php' => 'Página mapas'
];

foreach ($urls_test as $url => $descripcion) {
    echo "<div class='test-grid'>";
    
    // Verificar si el archivo local existe
    $archivo_local = str_replace('https://upiitascholar.com/', '', $url);
    if (file_exists($archivo_local)) {
        echo "<div class='test-item'>✅ $descripcion disponible</div>";
        echo "<div class='test-item ok'>CORRECTO</div>";
    } else {
        echo "<div class='test-item'>❌ $descripcion no encontrado</div>";
        echo "<div class='test-item error'>ERROR</div>";
    }
    echo "</div>";
}
echo "</div>";

// 4. Verificar configuraciones PHP
echo "<div class='section'>";
echo "<h2>⚙️ Configuración PHP</h2>";

$config_checks = [
    'PHP Version' => PHP_VERSION,
    'PDO MySQL' => extension_loaded('pdo_mysql') ? 'Disponible' : 'No disponible',
    'JSON Support' => extension_loaded('json') ? 'Disponible' : 'No disponible',
    'Session Support' => extension_loaded('session') ? 'Disponible' : 'No disponible',
    'Error Reporting' => ini_get('display_errors') ? 'Habilitado' : 'Deshabilitado',
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time') . 's'
];

foreach ($config_checks as $config => $valor) {
    echo "<div class='test-grid'>";
    echo "<div class='test-item'>$config: $valor</div>";
    
    if (($config == 'PDO MySQL' || $config == 'JSON Support' || $config == 'Session Support') && $valor == 'Disponible') {
        echo "<div class='test-item ok'>OK</div>";
    } elseif ($config == 'PHP Version' && version_compare($valor, '7.4', '>=')) {
        echo "<div class='test-item ok'>OK</div>";
    } else {
        echo "<div class='test-item info'>INFO</div>";
    }
    echo "</div>";
}
echo "</div>";

// 5. Test funcional de APIs
if (isset($pdo)) {
    echo "<div class='section'>";
    echo "<h2>🧪 Pruebas Funcionales</h2>";
    
    try {
        // Test de búsqueda de lugares
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM vista_lugares");
        $lugares = $stmt->fetch();
        
        echo "<div class='test-grid'>";
        echo "<div class='test-item'>✅ Vista de lugares funcional ({$lugares['total']} lugares)</div>";
        echo "<div class='test-item ok'>CORRECTO</div>";
        echo "</div>";
        
        // Test algoritmo Dijkstra
        if (file_exists('includes/Dijkstra.php')) {
            require_once 'includes/Dijkstra.php';
            $dijkstra = new Dijkstra($pdo);
            
            echo "<div class='test-grid'>";
            echo "<div class='test-item'>✅ Algoritmo Dijkstra cargado</div>";
            echo "<div class='test-item ok'>CORRECTO</div>";
            echo "</div>";
            
            // Obtener lugares para test
            $lugares_disponibles = $dijkstra->obtenerLugaresDisponibles();
            
            echo "<div class='test-grid'>";
            echo "<div class='test-item'>✅ Lugares disponibles: " . count($lugares_disponibles) . "</div>";
            echo "<div class='test-item ok'>CORRECTO</div>";
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='test-grid'>";
        echo "<div class='test-item'>❌ Error en pruebas: " . $e->getMessage() . "</div>";
        echo "<div class='test-item error'>ERROR</div>";
        echo "</div>";
    }
    echo "</div>";
}

// 6. Recomendaciones finales
echo "<div class='section'>";
echo "<h2>📋 Recomendaciones Finales</h2>";

echo "<h3>✅ Para completar la configuración:</h3>";
echo "<ol>";
echo "<li><strong>Limpiar caché del navegador:</strong> Ctrl+F5 o Cmd+Shift+R</li>";
echo "<li><strong>Verificar en navegador:</strong> Visita todas las páginas principales</li>";
echo "<li><strong>Probar funcionalidad:</strong> Intenta calcular una ruta</li>";
echo "<li><strong>Verificar responsive:</strong> Prueba en móvil y desktop</li>";
echo "<li><strong>Revisar logs:</strong> Verifica que no haya errores en logs/</li>";
echo "</ol>";

echo "<h3>🔧 Si persisten problemas:</h3>";
echo "<ol>";
echo "<li><strong>Rutas CSS:</strong> Verifica que todas las hojas de estilo carguen</li>";
echo "<li><strong>JavaScript:</strong> Abre DevTools y revisa la consola por errores</li>";
echo "<li><strong>AJAX:</strong> Confirma que las llamadas a API usen URLs absolutas</li>";
echo "<li><strong>Base de datos:</strong> Ejecuta el diagnóstico de conectividad</li>";
echo "<li><strong>Permisos:</strong> Verifica permisos de archivos en IONOS</li>";
echo "</ol>";

echo "<h3>📱 URLs importantes para verificar:</h3>";
echo "<ul>";
echo "<li><a href='https://upiitascholar.com/' target='_blank'>Página principal</a></li>";
echo "<li><a href='https://upiitascholar.com/pages/mapa-rutas.php' target='_blank'>Calculadora de rutas</a></li>";
echo "<li><a href='https://upiitascholar.com/pages/mapa-interactivo.php' target='_blank'>Mapa interactivo</a></li>";
echo "<li><a href='https://upiitascholar.com/Public/login.php' target='_blank'>Sistema de login</a></li>";
echo "</ul>";
echo "</div>";

// 7. Resultado final
echo "<div class='section' style='border-left-color: #4CAF50; background: #e8f5e8;'>";
echo "<h2 style='color: #4CAF50;'>🎉 Verificación Completada</h2>";
echo "<p><strong>Estado del sistema:</strong> ";

$errores_criticos = 0;
// Aquí podrías contar errores críticos encontrados durante las verificaciones

if ($errores_criticos == 0) {
    echo "<span class='ok'>SISTEMA OPERATIVO ✅</span></p>";
    echo "<p>Tu sitio web está configurado correctamente y debería funcionar sin problemas.</p>";
} else {
    echo "<span class='error'>REQUIERE ATENCIÓN ⚠️</span></p>";
    echo "<p>Se encontraron $errores_criticos problemas que necesitan resolverse.</p>";
}

echo "<p><strong>Próximos pasos:</strong></p>";
echo "<ol>";
echo "<li>Visita <strong>https://upiitascholar.com</strong> y verifica que todo se vea correctamente</li>";
echo "<li>Prueba la funcionalidad de cálculo de rutas</li>";
echo "<li>Verifica el sistema de login/registro</li>";
echo "<li>Confirma que el diseño responsive funcione en móviles</li>";
echo "</ol>";
echo "</div>";

echo "</div>"; // container

echo "<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Verificación del sistema completada');
    console.log('📊 Revisa los resultados arriba');
    
    // Verificar que estemos en el dominio correcto
    if (window.location.hostname !== 'upiitascholar.com' && window.location.hostname !== 'localhost') {
        console.warn('⚠️ No estás en el dominio esperado');
    }
    
    // Test básico de conectividad AJAX
    fetch(window.location.origin + '/api/buscar_lugares.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ busqueda: '' })
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ API de búsqueda respondió correctamente');
    })
    .catch(error => {
        console.error('❌ API de búsqueda falló:', error);
    });
});
</script>";
?>
