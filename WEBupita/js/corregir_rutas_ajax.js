// Script para corregir rutas AJAX después del cambio a raíz
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
