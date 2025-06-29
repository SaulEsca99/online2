<?php
/**
 * Script específico para corregir rutas AJAX en páginas de mapas
 * Ejecutar después de mover archivos a la raíz
 */

echo "🔧 Iniciando corrección específica de rutas AJAX...\n\n";

// Archivos que contienen llamadas AJAX que necesitan corrección
$archivos_ajax = [
    'pages/mapa-rutas.php',
    'pages/mapa-rutas-realista.php',
    'pages/mapa-interactivo.php',
    'js/main.js',
    'js/mapa.js',
    'js/rutas.js'
];

$correcciones_realizadas = 0;

foreach ($archivos_ajax as $archivo) {
    if (!file_exists($archivo)) {
        echo "⏭️  Archivo no encontrado: $archivo\n";
        continue;
    }
    
    echo "📁 Procesando: $archivo\n";
    
    $contenido = file_get_contents($archivo);
    $contenido_original = $contenido;
    
    // Correcciones específicas para AJAX
    
    // 1. Corregir rutas de API con WEBupita
    $contenido = preg_replace(
        '/fetch\([\'"]\/WEBupita\/api\//',
        "fetch('https://upiitascholar.com/api/",
        $contenido
    );
    
    // 2. Corregir rutas relativas de API
    $contenido = preg_replace(
        '/fetch\([\'"]\.\.\/api\//',
        "fetch('https://upiitascholar.com/api/",
        $contenido
    );
    
    // 3. Corregir variables BASE_PATH
    $contenido = preg_replace(
        '/BASE_PATH \+ [\'"]\/api\//',
        "'https://upiitascholar.com/api/",
        $contenido
    );
    
    // 4. Corregir fetch con template literals incorrectos
    $contenido = preg_replace(
        '/fetch\(`\$\{window\.API_BASE_URL\}\/api\/([^`]+)`\)/',
        "fetch('https://upiitascholar.com/api/$1')",
        $contenido
    );
    
    // 5. Corregir fetch con concatenación incorrecta
    $contenido = preg_replace(
        '/fetch\(window\.API_BASE_URL \+ [\'"]\/api\//',
        "fetch('https://upiitascholar.com/api/",
        $contenido
    );
    
    // 6. Agregar configuración al inicio de archivos JavaScript
    if (strpos($archivo, '.js') !== false && strpos($contenido, 'window.API_BASE_URL') === false) {
        $config_js = "// Configuración de URLs\nwindow.API_BASE_URL = 'https://upiitascholar.com';\nwindow.BASE_URL = 'https://upiitascholar.com';\n\n";
        $contenido = $config_js . $contenido;
    }
    
    // 7. Para archivos PHP, agregar script de configuración
    if (strpos($archivo, '.php') !== false && strpos($contenido, 'window.API_BASE_URL') === false) {
        $config_script = "\n<script>\nwindow.API_BASE_URL = 'https://upiitascholar.com';\nwindow.BASE_URL = 'https://upiitascholar.com';\n</script>\n";
        
        // Buscar el tag </head> y insertar antes
        if (strpos($contenido, '</head>') !== false) {
            $contenido = str_replace('</head>', $config_script . '</head>', $contenido);
        } else {
            // Si no hay </head>, agregar al final
            $contenido .= $config_script;
        }
    }
    
    // 8. Corregir específicamente el problema de calcular_ruta.php
    $contenido = str_replace(
        "fetch('/api/calcular_ruta.php'",
        "fetch('https://upiitascholar.com/api/calcular_ruta.php'",
        $contenido
    );
    
    // 9. Corregir buscar_lugares.php
    $contenido = str_replace(
        "fetch('/api/buscar_lugares.php'",
        "fetch('https://upiitascholar.com/api/buscar_lugares.php'",
        $contenido
    );
    
    // 10. Agregar verificación de elementos DOM
    if (strpos($archivo, 'mapa-rutas') !== false && strpos($contenido, 'DOMContentLoaded') !== false) {
        $dom_check = "
    // Verificar que los elementos existan
    const origen = document.getElementById('origen');
    const destino = document.getElementById('destino');
    
    if (!origen || !destino) {
        console.error('❌ Elementos origen/destino no encontrados');
        console.log('🔄 Intentando recargar página...');
        setTimeout(() => location.reload(), 2000);
        return;
    }
    
    console.log('✅ Elementos de formulario encontrados correctamente');
";
        
        // Insertar después de DOMContentLoaded
        $contenido = preg_replace(
            "/(document\.addEventListener\('DOMContentLoaded',\s*function\(\)\s*\{)/",
            "$1\n" . $dom_check,
            $contenido
        );
    }
    
    // Si hubo cambios, guardar archivo
    if ($contenido !== $contenido_original) {
        if (file_put_contents($archivo, $contenido)) {
            echo "   ✅ Corregido exitosamente\n";
            $correcciones_realizadas++;
        } else {
            echo "   ❌ Error al guardar archivo\n";
        }
    } else {
        echo "   ⏭️  Sin cambios necesarios\n";
    }
    
    echo "\n";
}

// Crear archivo de configuración global para JavaScript
$config_js_global = "/**
 * Configuración global para UPIITA Finder
 * Auto-generado por script de corrección
 */

// URLs base del sistema
window.UPIITA_CONFIG = {
    BASE_URL: 'https://upiitascholar.com',
    API_BASE_URL: 'https://upiitascholar.com',
    SITE_URL: 'https://upiitascholar.com/',
    
    // APIs específicas
    API: {
        CALCULAR_RUTA: 'https://upiitascholar.com/api/calcular_ruta.php',
        BUSCAR_LUGARES: 'https://upiitascholar.com/api/buscar_lugares.php',
        MAPA_COORDENADAS: 'https://upiitascholar.com/api/mapa_coordenadas.php',
        GUARDAR_FAVORITO: 'https://upiitascholar.com/api/guardar_favorito.php'
    },
    
    // Configuración de debug
    DEBUG: false,
    
    // Función helper para hacer fetch con URL correcta
    fetchAPI: function(endpoint, options = {}) {
        const url = this.API_BASE_URL + '/api/' + endpoint.replace(/^\/+/, '');
        
        if (this.DEBUG) {
            console.log('🌐 Fetch API:', url);
        }
        
        return fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
    }
};

// Retrocompatibilidad
window.API_BASE_URL = window.UPIITA_CONFIG.API_BASE_URL;
window.BASE_URL = window.UPIITA_CONFIG.BASE_URL;

// Log de configuración
console.log('⚙️ UPIITA Config cargado:', window.UPIITA_CONFIG);
";

if (file_put_contents('js/config.js', $config_js_global)) {
    echo "📝 Archivo de configuración global creado: js/config.js\n";
    $correcciones_realizadas++;
}

// Resultado final
echo "\n🎉 Corrección de AJAX completada!\n";
echo "📊 Archivos procesados: " . count($archivos_ajax) . "\n";
echo "🔧 Correcciones realizadas: $correcciones_realizadas\n\n";

echo "✅ Próximos pasos:\n";
echo "1. Agregar <script src='js/config.js'></script> a tu header.php\n";
echo "2. Limpiar caché del navegador (Ctrl+F5)\n";
echo "3. Probar funcionalidad de cálculo de rutas\n";
echo "4. Verificar que los selectores origen/destino funcionen\n\n";

echo "🔍 Para debugging, puedes activar modo debug con:\n";
echo "   window.UPIITA_CONFIG.DEBUG = true;\n\n";
?>
