<?php
// test_sistema.php - Sistema de verificación completo para IONOS

// Configuración de errores para pruebas
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para medir tiempo de ejecución
$tiempo_inicio = microtime(true);

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPIITA Finder - Verificación del Sistema</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 2.5rem; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content { padding: 30px; }
        .ok { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .section { 
            margin: 30px 0; 
            padding: 20px; 
            border-radius: 10px; 
            background: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .section h2 { 
            margin: 0 0 15px 0; 
            color: #333; 
            font-size: 1.5rem;
        }
        .test-item { 
            padding: 8px 0; 
            border-bottom: 1px solid #eee; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-item:last-child { border-bottom: none; }
        .test-name { flex: 1; }
        .test-result { font-weight: bold; min-width: 100px; text-align: right; }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
            margin: 20px 0; 
        }
        .stat-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            text-align: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .stat-number { font-size: 2rem; font-weight: bold; color: #007bff; }
        .stat-label { color: #666; margin-top: 5px; }
        .btn { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block; 
            margin: 5px;
            transition: background 0.3s ease;
        }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .performance-bar {
            width: 100%;
            height: 20px;
            background: #eee;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .performance-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);
            transition: width 0.3s ease;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .test-item { flex-direction: column; align-items: flex-start; }
            .test-result { margin-top: 5px; }
        }
    </style>
</head>
<body>';

echo '<div class="container">';
echo '<div class="header">';
echo '<h1>🔧 UPIITA Finder</h1>';
echo '<p>Sistema de Verificación y Diagnóstico</p>';
echo '</div>';

echo '<div class="content">';

$errores_totales = 0;
$advertencias_totales = 0;
$pruebas_exitosas = 0;

// 1. Verificar entorno del servidor
echo '<div class="section">';
echo '<h2>🌐 Entorno del Servidor</h2>';

$tests_servidor = [
    'PHP Version' => [
        'test' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'value' => PHP_VERSION,
        'required' => '7.4+'
    ],
    'PDO Extension' => [
        'test' => extension_loaded('pdo'),
        'value' => extension_loaded('pdo') ? 'Disponible' : 'No disponible',
        'required' => 'Requerido'
    ],
    'PDO MySQL' => [
        'test' => extension_loaded('pdo_mysql'),
        'value' => extension_loaded('pdo_mysql') ? 'Disponible' : 'No disponible',
        'required' => 'Requerido'
    ],
    'JSON Extension' => [
        'test' => extension_loaded('json'),
        'value' => extension_loaded('json') ? 'Disponible' : 'No disponible',
        'required' => 'Requerido'
    ],
    'Session Support' => [
        'test' => function_exists('session_start'),
        'value' => function_exists('session_start') ? 'Disponible' : 'No disponible',
        'required' => 'Requerido'
    ]
];

foreach ($tests_servidor as $test_name => $test_data) {
    echo '<div class="test-item">';
    echo '<span class="test-name">' . $test_name . ': ' . $test_data['value'] . '</span>';
    if ($test_data['test']) {
        echo '<span class="test-result ok">✓ OK</span>';
        $pruebas_exitosas++;
    } else {
        echo '<span class="test-result error">✗ FALLA</span>';
        $errores_totales++;
    }
    echo '</div>';
}
echo '</div>';

// 2. Verificar conexión a base de datos
echo '<div class="section">';
echo '<h2>🗄️ Base de Datos</h2>';

try {
    require_once 'includes/conexion.php';
    echo '<div class="test-item">';
    echo '<span class="test-name">Conexión a IONOS MySQL</span>';
    echo '<span class="test-result ok">✓ CONECTADO</span>';
    echo '</div>';
    $pruebas_exitosas++;

    // Verificar tablas
    $tablas_requeridas = ['Edificios', 'Aulas', 'PuntosConexion', 'Rutas', 'usuarios', 'RutasFavoritas'];
    $tablas_existentes = 0;
    
    foreach ($tablas_requeridas as $tabla) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $tabla");
            $count = $stmt->fetchColumn();
            echo '<div class="test-item">';
            echo '<span class="test-name">Tabla ' . $tabla . '</span>';
            echo '<span class="test-result ok">✓ ' . $count . ' registros</span>';
            echo '</div>';
            $tablas_existentes++;
            $pruebas_exitosas++;
        } catch (Exception $e) {
            echo '<div class="test-item">';
            echo '<span class="test-name">Tabla ' . $tabla . '</span>';
            echo '<span class="test-result error">✗ NO EXISTE</span>';
            echo '</div>';
            $errores_totales++;
        }
    }

    // Verificar vista
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM vista_lugares");
        $lugares_count = $stmt->fetchColumn();
        echo '<div class="test-item">';
        echo '<span class="test-name">Vista vista_lugares</span>';
        echo '<span class="test-result ok">✓ ' . $lugares_count . ' lugares</span>';
        echo '</div>';
        $pruebas_exitosas++;
    } catch (Exception $e) {
        echo '<div class="test-item">';
        echo '<span class="test-name">Vista vista_lugares</span>';
        echo '<span class="test-result error">✗ NO EXISTE</span>';
        echo '</div>';
        $errores_totales++;
    }

} catch (Exception $e) {
    echo '<div class="test-item">';
    echo '<span class="test-name">Conexión a Base de Datos</span>';
    echo '<span class="test-result error">✗ ERROR: ' . $e->getMessage() . '</span>';
    echo '</div>';
    $errores_totales++;
}
echo '</div>';

// 3. Verificar archivos del sistema
echo '<div class="section">';
echo '<h2>📁 Archivos del Sistema</h2>';

$archivos_criticos = [
    'includes/conexion.php' => 'Configuración de BD',
    'includes/header.php' => 'Header del sitio',
    'includes/Dijkstra.php' => 'Algoritmo de rutas',
    'css/styles.css' => 'Estilos principales',
    'js/main.js' => 'JavaScript principal',
    'pages/mapa-interactivo.php' => 'Mapa interactivo',
    'pages/mapa-rutas.php' => 'Calculadora de rutas',
    'pages/conocenos.php' => 'Página acerca de',
    'Public/login.php' => 'Sistema de login',
    'Public/registro.php' => 'Sistema de registro',
    'Public/index.php' => 'Portal principal',
    'Public/mapa.php' => 'Mapa clásico',
    'Public/favoritos.php' => 'Rutas favoritas',
    'Public/perfil.php' => 'Perfil de usuario',
    'Public/logout.php' => 'Cierre de sesión'
];

foreach ($archivos_criticos as $archivo => $descripcion) {
    echo '<div class="test-item">';
    echo '<span class="test-name">' . $descripcion . ' (' . $archivo . ')</span>';
    if (file_exists($archivo)) {
        echo '<span class="test-result ok">✓ EXISTE</span>';
        $pruebas_exitosas++;
    } else {
        echo '<span class="test-result error">✗ FALTANTE</span>';
        $errores_totales++;
    }
    echo '</div>';
}
echo '</div>';

// 4. Verificar funcionalidad del sistema
if (isset($pdo)) {
    echo '<div class="section">';
    echo '<h2>⚙️ Funcionalidad del Sistema</h2>';

    // Verificar algoritmo Dijkstra
    try {
        require_once 'includes/Dijkstra.php';
        $dijkstra = new Dijkstra($pdo);
        
        echo '<div class="test-item">';
        echo '<span class="test-name">Algoritmo Dijkstra</span>';
        echo '<span class="test-result ok">✓ CARGADO</span>';
        echo '</div>';
        $pruebas_exitosas++;

        // Obtener lugares disponibles
        $lugares = $dijkstra->obtenerLugaresDisponibles();
        echo '<div class="test-item">';
        echo '<span class="test-name">Lugares disponibles</span>';
        echo '<span class="test-result ok">✓ ' . count($lugares) . ' lugares</span>';
        echo '</div>';
        $pruebas_exitosas++;

        // Probar cálculo de ruta
        if (count($lugares) >= 2) {
            $origen = $lugares[0];
            $destino = $lugares[1];
            
            try {
                $resultado = $dijkstra->calcularRutaMasCorta(
                    $origen['tipo'], 
                    $origen['id'], 
                    $destino['tipo'], 
                    $destino['id']
                );
                
                echo '<div class="test-item">';
                echo '<span class="test-name">Cálculo de rutas</span>';
                if ($resultado['encontrada']) {
                    echo '<span class="test-result ok">✓ FUNCIONAL (' . round($resultado['distancia_total'], 1) . 'm)</span>';
                    $pruebas_exitosas++;
                } else {
                    echo '<span class="test-result warning">⚠ SIN RUTA</span>';
                    $advertencias_totales++;
                }
                echo '</div>';
            } catch (Exception $e) {
                echo '<div class="test-item">';
                echo '<span class="test-name">Cálculo de rutas</span>';
                echo '<span class="test-result error">✗ ERROR: ' . $e->getMessage() . '</span>';
                echo '</div>';
                $errores_totales++;
            }
        }
    } catch (Exception $e) {
        echo '<div class="test-item">';
        echo '<span class="test-name">Algoritmo Dijkstra</span>';
        echo '<span class="test-result error">✗ ERROR: ' . $e->getMessage() . '</span>';
        echo '</div>';
        $errores_totales++;
    }

    // Verificar sesiones
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    echo '<div class="test-item">';
    echo '<span class="test-name">Sistema de sesiones</span>';
    echo '<span class="test-result ok">✓ ACTIVO (ID: ' . substr(session_id(), 0, 8) . '...)</span>';
    echo '</div>';
    $pruebas_exitosas++;

    echo '</div>';
}

// 5. Estadísticas del sistema
if (isset($pdo)) {
    echo '<div class="section">';
    echo '<h2>📊 Estadísticas del Sistema</h2>';
    
    try {
        $stats = obtenerEstadisticasSistema($pdo);
        
        echo '<div class="stats-grid">';
        echo '<div class="stat-card">';
        echo '<div class="stat-number">' . $stats['edificios'] . '</div>';
        echo '<div class="stat-label">Edificios</div>';
        echo '</div>';
        
        echo '<div class="stat-card">';
        echo '<div class="stat-number">' . $stats['aulas_mapeadas'] . '</div>';
        echo '<div class="stat-label">Aulas Mapeadas</div>';
        echo '</div>';
        
        echo '<div class="stat-card">';
        echo '<div class="stat-number">' . $stats['rutas'] . '</div>';
        echo '<div class="stat-label">Rutas Disponibles</div>';
        echo '</div>';
        
        echo '<div class="stat-card">';
        echo '<div class="stat-number">' . $stats['usuarios'] . '</div>';
        echo '<div class="stat-label">Usuarios Registrados</div>';
        echo '</div>';
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="test-item">';
        echo '<span class="test-name">Estadísticas</span>';
        echo '<span class="test-result error">✗ ERROR: ' . $e->getMessage() . '</span>';
        echo '</div>';
    }
    echo '</div>';
}

// 6. Resumen y rendimiento
$tiempo_total = round((microtime(true) - $tiempo_inicio) * 1000, 2);
$total_pruebas = $pruebas_exitosas + $errores_totales + $advertencias_totales;
$porcentaje_exito = $total_pruebas > 0 ? round(($pruebas_exitosas / $total_pruebas) * 100, 1) : 0;

echo '<div class="section">';
echo '<h2>📈 Resumen de Verificación</h2>';

echo '<div class="stats-grid">';
echo '<div class="stat-card">';
echo '<div class="stat-number" style="color: #28a745;">' . $pruebas_exitosas . '</div>';
echo '<div class="stat-label">Pruebas Exitosas</div>';
echo '</div>';

echo '<div class="stat-card">';
echo '<div class="stat-number" style="color: #ffc107;">' . $advertencias_totales . '</div>';
echo '<div class="stat-label">Advertencias</div>';
echo '</div>';

echo '<div class="stat-card">';
echo '<div class="stat-number" style="color: #dc3545;">' . $errores_totales . '</div>';
echo '<div class="stat-label">Errores</div>';
echo '</div>';

echo '<div class="stat-card">';
echo '<div class="stat-number" style="color: #17a2b8;">' . $tiempo_total . 'ms</div>';
echo '<div class="stat-label">Tiempo de Ejecución</div>';
echo '</div>';
echo '</div>';

// Barra de rendimiento
echo '<div style="margin: 20px 0;">';
echo '<h4>Rendimiento General: ' . $porcentaje_exito . '%</h4>';
echo '<div class="performance-bar">';
echo '<div class="performance-fill" style="width: ' . $porcentaje_exito . '%;"></div>';
echo '</div>';
echo '</div>';

// Estado general
if ($errores_totales === 0) {
    echo '<div class="test-item" style="background: #d4edda; padding: 15px; border-radius: 8px;">';
    echo '<span class="ok">🎉 ¡Sistema completamente funcional! Todos los componentes están operando correctamente.</span>';
    echo '</div>';
} elseif ($errores_totales <= 2) {
    echo '<div class="test-item" style="background: #fff3cd; padding: 15px; border-radius: 8px;">';
    echo '<span class="warning">⚠️ Sistema mayormente funcional con algunos problemas menores que necesitan atención.</span>';
    echo '</div>';
} else {
    echo '<div class="test-item" style="background: #f8d7da; padding: 15px; border-radius: 8px;">';
    echo '<span class="error">❌ Sistema con problemas críticos. Se requiere configuración adicional.</span>';
    echo '</div>';
}

echo '</div>';

// Enlaces de navegación
echo '<div style="text-align: center; margin: 30px 0;">';
echo '<a href="index.php" class="btn btn-success">🏠 Ir al Sitio Principal</a>';
echo '<a href="pages/mapa-interactivo.php" class="btn">🗺️ Probar Mapa</a>';
echo '<a href="pages/login.php" class="btn">🔑 Iniciar Sesión</a>';
echo '</div>';

echo '</div>'; // content

echo '<div class="footer">';
echo '<p><strong>UPIITA Finder</strong> - Sistema de Navegación del Campus</p>';
echo '<p>Verificación completada en ' . $tiempo_total . 'ms | ' . date('d/m/Y H:i:s') . '</p>';
echo '</div>';

echo '</div>'; // container
echo '</body></html>';
?>