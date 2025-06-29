<?php
/**
 * Script para corregir todas las rutas después de mover a la raíz
 * Ejecutar desde la raíz del proyecto: php corregir_rutas.php
 */

// Configuración
$base_url = 'https://upiitascholar.com';
$archivos_a_corregir = [];

echo "🔧 Iniciando corrección de rutas...\n";

// Función para encontrar todos los archivos PHP
function encontrarArchivos($directorio, &$archivos) {
    $items = scandir($directorio);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        
        $ruta_completa = $directorio . '/' . $item;
        if (is_dir($ruta_completa)) {
            encontrarArchivos($ruta_completa, $archivos);
        } elseif (pathinfo($item, PATHINFO_EXTENSION) == 'php') {
            $archivos[] = $ruta_completa;
        }
    }
}

// Encontrar todos los archivos PHP
encontrarArchivos('.', $archivos_a_corregir);

$correcciones = 0;

foreach ($archivos_a_corregir as $archivo) {
    echo "📁 Procesando: $archivo\n";
    
    $contenido = file_get_contents($archivo);
    $contenido_original = $contenido;
    
    // Correcciones específicas
    
    // 1. Rutas de CSS
    $contenido = preg_replace(
        '/href=["\']\.\.\/css\//',
        'href="' . $base_url . '/css/',
        $contenido
    );
    
    $contenido = preg_replace(
        '/href=["\']css\//',
        'href="' . $base_url . '/css/',
        $contenido
    );
    
    // 2. Rutas de JavaScript
    $contenido = preg_replace(
        '/src=["\']\.\.\/js\//',
        'src="' . $base_url . '/js/',
        $contenido
    );
    
    $contenido = preg_replace(
        '/src=["\']js\//',
        'src="' . $base_url . '/js/',
        $contenido
    );
    
    // 3. Rutas de imágenes
    $contenido = preg_replace(
        '/src=["\']\.\.\/images\//',
        'src="' . $base_url . '/images/',
        $contenido
    );
    
    // 4. Correcciones de includes/requires
    $contenido = preg_replace(
        '/require_once ["\']\.\.\/includes\//',
        "require_once __DIR__ . '/../includes/",
        $contenido
    );
    
    $contenido = preg_replace(
        '/include ["\']\.\.\/includes\//',
        "include __DIR__ . '/../includes/",
        $contenido
    );
    
    // 5. Enlaces internos
    $contenido = preg_replace(
        '/href=["\']\.\.\/pages\//',
        'href="' . $base_url . '/pages/',
        $contenido
    );
    
    $contenido = preg_replace(
        '/href=["\']\.\.\/Public\//',
        'href="' . $base_url . '/Public/',
        $contenido
    );
    
    // 6. Acciones de formularios
    $contenido = preg_replace(
        '/action=["\']\.\.\/api\//',
        'action="' . $base_url . '/api/',
        $contenido
    );
    
    // 7. Rutas absolutas desde raíz para archivos en subdirectorios
    if (strpos($archivo, 'pages/') !== false || strpos($archivo, 'Public/') !== false) {
        // Para archivos en subdirectorios, usar rutas absolutas
        $contenido = str_replace('href="css/', 'href="' . $base_url . '/css/', $contenido);
        $contenido = str_replace('src="js/', 'src="' . $base_url . '/js/', $contenido);
        $contenido = str_replace('src="images/', 'src="' . $base_url . '/images/', $contenido);
    }
    
    // Si hubo cambios, guardar el archivo
    if ($contenido !== $contenido_original) {
        file_put_contents($archivo, $contenido);
        echo "   ✅ Corregido\n";
        $correcciones++;
    } else {
        echo "   ⏭️  Sin cambios\n";
    }
}

echo "\n🎉 Corrección completada!\n";
echo "📊 Archivos procesados: " . count($archivos_a_corregir) . "\n";
echo "🔧 Archivos corregidos: $correcciones\n";

// Crear archivo de configuración base si no existe
if (!file_exists('includes/config.php')) {
    $config_content = '<?php
/**
 * Configuración base del sistema
 */

// Configuración de rutas
define("BASE_URL", "' . $base_url . '");
define("ROOT_PATH", __DIR__ . "/../");

// Función helper para generar URLs
function url($path = "") {
    return BASE_URL . "/" . ltrim($path, "/");
}

// Configuración de base de datos
define("DB_HOST", "localhost");
define("DB_NAME", "upiita");
define("DB_USER", "root");
define("DB_PASS", "");

// Rutas de directorios importantes
define("INCLUDES_PATH", ROOT_PATH . "includes/");
define("CSS_PATH", ROOT_PATH . "css/");
define("JS_PATH", ROOT_PATH . "js/");
define("IMAGES_PATH", ROOT_PATH . "images/");
?>';
    
    file_put_contents('includes/config.php', $config_content);
    echo "📝 Archivo de configuración creado: includes/config.php\n";
}

echo "\n🚀 Siguiente paso: Actualizar el header principal\n";
echo "   Edita includes/header.php para usar las nuevas configuraciones\n\n";
?>
// Script para corregir rutas AJAX después del cambio a raíz
// Agregar al final de los archivos JavaScript en las páginas de mapas

// Configurar URL base dinámicamente
window.API_BASE_URL = 'https://upiitascholar.com';
window.BASE_PATH = 'https://upiitascholar.com';

// Función para corregir rutas de API
function corregirRutasAPI() {
    // Buscar y corregir todas las llamadas fetch en el archivo
    const scripts = document.getElementsByTagName('script');
    
    for (let script of scripts) {
        let content = script.innerHTML;
        
        // Correcciones de rutas AJAX más comunes
        content = content.replace(/\/WEBupita\/api\//g, '/api/');
        content = content.replace(/\.\.\/api\//g, '/api/');
        content = content.replace(/BASE_PATH \+ '\/api\//g, "window.API_BASE_URL + '/api/");
        content = content.replace(/fetch\(['"]\/api\//g, "fetch(window.API_BASE_URL + '/api/");
        
        // Si el contenido cambió, reemplazar el script
        if (content !== script.innerHTML) {
            const newScript = document.createElement('script');
            newScript.innerHTML = content;
            script.parentNode.replaceChild(newScript, script);
        }
    }
}

// Función específica para corregir cálculo de rutas
function corregirCalculoRutas() {
    // Buscar función calcularRuta y corregir URL
    if (window.calcularRuta) {
        const originalFunction = window.calcularRuta;
        window.calcularRuta = async function() {
            // Reemplazar cualquier llamada fetch con la URL correcta
            const originalFetch = window.fetch;
            window.fetch = function(url, options) {
                if (url.includes('/api/calcular_ruta.php')) {
                    url = window.API_BASE_URL + '/api/calcular_ruta.php';
                }
                return originalFetch(url, options);
            };
            
            await originalFunction();
            
            // Restaurar fetch original
            window.fetch = originalFetch;
        };
    }
}

// Función para cargar lugares con URL correcta  
function corregirCargarLugares() {
    if (window.cargarLugares) {
        const originalFunction = window.cargarLugares;
        window.cargarLugares = async function() {
            const originalFetch = window.fetch;
            window.fetch = function(url, options) {
                if (url.includes('/api/buscar_lugares.php')) {
                    url = window.API_BASE_URL + '/api/buscar_lugares.php';
                }
                return originalFetch(url, options);
            };
            
            await originalFunction();
            window.fetch = originalFetch;
        };
    }
}

// Ejecutar correcciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Aplicando correcciones de rutas AJAX...');
    
    setTimeout(() => {
        corregirRutasAPI();
        corregirCalculoRutas(); 
        corregirCargarLugares();
        console.log('✅ Correcciones aplicadas');
    }, 100);
    
    // Verificar que los elementos existan
    const origen = document.getElementById('origen');
    const destino = document.getElementById('destino');
    
    if (origen && destino) {
        console.log('✅ Elementos de origen y destino encontrados');
        
        // Asegurar que el cambio de origen funcione
        origen.addEventListener('change', function() {
            console.log('Origen seleccionado:', this.value);
        });
        
        destino.addEventListener('change', function() {
            console.log('Destino seleccionado:', this.value);
        });
    } else {
        console.error('❌ No se encontraron elementos origen/destino');
    }
    
    // Verificar que el CSS se haya cargado
    const styles = getComputedStyle(document.body);
    if (styles.fontFamily) {
        console.log('✅ CSS cargado correctamente');
    } else {
        console.warn('⚠️  Problema con la carga de CSS');
        // Cargar CSS manualmente
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = window.API_BASE_URL + '/css/styles.css';
        document.head.appendChild(link);
    }
});

// Función para debuggear problemas
function debuggearSistema() {
    console.log('🔍 Debug del sistema:');
    console.log('- API_BASE_URL:', window.API_BASE_URL);
    console.log('- Origen element:', document.getElementById('origen'));
    console.log('- Destino element:', document.getElementById('destino'));
    console.log('- CSS loaded:', !!document.querySelector('link[href*="styles.css"]'));
    
    // Verificar funciones
    console.log('- calcularRuta function:', typeof window.calcularRuta);
    console.log('- cargarLugares function:', typeof window.cargarLugares);
}

// Llamar debug en caso de problemas
// debuggearSistema();