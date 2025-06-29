<?php
/**
 * Archivo de prueba para verificar que todos los assets se cargan correctamente
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Assets - UPIITA Finder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .asset-test {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .status-icon {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🔍 Verificación de Assets - UPIITA Finder</h1>
    
    <div class="test-section">
        <h2>📋 Verificación de Archivos CSS</h2>
        
        <div class="asset-test">
            <span class="status-icon" id="css-main-status">⏳</span>
            <div>
                <strong>CSS Principal:</strong> 
                <a href="https://upiitascholar.com/css/styles.css" target="_blank">
                    https://upiitascholar.com/css/styles.css
                </a>
                <div id="css-main-result"></div>
            </div>
        </div>
        
        <div class="asset-test">
            <span class="status-icon" id="fontawesome-status">⏳</span>
            <div>
                <strong>Font Awesome:</strong> 
                <a href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" target="_blank">
                    CDN Font Awesome
                </a>
                <div id="fontawesome-result"></div>
            </div>
        </div>
        
        <div class="asset-test">
            <span class="status-icon" id="bootstrap-status">⏳</span>
            <div>
                <strong>Bootstrap:</strong> 
                <a href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" target="_blank">
                    CDN Bootstrap
                </a>
                <div id="bootstrap-result"></div>
            </div>
        </div>
    </div>

    <div class="test-section">
        <h2>📜 Verificación de Archivos JavaScript</h2>
        
        <div class="asset-test">
            <span class="status-icon" id="js-main-status">⏳</span>
            <div>
                <strong>Script Principal:</strong> 
                <a href="https://upiitascholar.com/js/main.js" target="_blank">
                    https://upiitascholar.com/js/main.js
                </a>
                <div id="js-main-result"></div>
            </div>
        </div>
        
        <div class="asset-test">
            <span class="status-icon" id="js-mapa-status">⏳</span>
            <div>
                <strong>Mapa Realista:</strong> 
                <a href="https://upiitascholar.com/js/MapaRealista.js" target="_blank">
                    https://upiitascholar.com/js/MapaRealista.js
                </a>
                <div id="js-mapa-result"></div>
            </div>
        </div>
        
        <div class="asset-test">
            <span class="status-icon" id="js-ajax-status">⏳</span>
            <div>
                <strong>AJAX Rutas:</strong> 
                <a href="https://upiitascholar.com/js/corregir_rutas_ajax.js" target="_blank">
                    https://upiitascholar.com/js/corregir_rutas_ajax.js
                </a>
                <div id="js-ajax-result"></div>
            </div>
        </div>
    </div>

    <div class="test-section">
        <h2>🌐 Verificación de API Endpoints</h2>
        
        <div class="asset-test">
            <span class="status-icon" id="api-lugares-status">⏳</span>
            <div>
                <strong>API Buscar Lugares:</strong> 
                <a href="https://upiitascholar.com/api/buscar_lugares.php" target="_blank">
                    https://upiitascholar.com/api/buscar_lugares.php
                </a>
                <div id="api-lugares-result"></div>
            </div>
        </div>
        
        <div class="asset-test">
            <span class="status-icon" id="api-rutas-status">⏳</span>
            <div>
                <strong>API Calcular Rutas:</strong> 
                <a href="https://upiitascholar.com/api/calcular_ruta.php" target="_blank">
                    https://upiitascholar.com/api/calcular_ruta.php
                </a>
                <div id="api-rutas-result"></div>
            </div>
        </div>
    </div>

    <div class="test-section">
        <h2>📊 Resumen de Resultados</h2>
        <div id="summary"></div>
    </div>

    <script>
        // Función para probar carga de CSS
        function testCSS(url, statusElementId, resultElementId) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = url;
            
            const statusElement = document.getElementById(statusElementId);
            const resultElement = document.getElementById(resultElementId);
            
            link.onload = function() {
                statusElement.textContent = '✅';
                statusElement.className = 'status-icon success';
                resultElement.innerHTML = '<span class="success">✓ Cargado exitosamente</span>';
            };
            
            link.onerror = function() {
                statusElement.textContent = '❌';
                statusElement.className = 'status-icon error';
                resultElement.innerHTML = '<span class="error">✗ Error al cargar</span>';
            };
            
            document.head.appendChild(link);
        }

        // Función para probar carga de JS
        function testJS(url, statusElementId, resultElementId) {
            const statusElement = document.getElementById(statusElementId);
            const resultElement = document.getElementById(resultElementId);
            
            fetch(url, { method: 'HEAD' })
                .then(response => {
                    if (response.ok) {
                        statusElement.textContent = '✅';
                        statusElement.className = 'status-icon success';
                        resultElement.innerHTML = '<span class="success">✓ Disponible</span>';
                    } else {
                        throw new Error('Not found');
                    }
                })
                .catch(() => {
                    statusElement.textContent = '❌';
                    statusElement.className = 'status-icon error';
                    resultElement.innerHTML = '<span class="error">✗ No disponible</span>';
                });
        }

        // Función para probar API endpoints
        function testAPI(url, statusElementId, resultElementId) {
            const statusElement = document.getElementById(statusElementId);
            const resultElement = document.getElementById(resultElementId);
            
            fetch(url)
                .then(response => {
                    if (response.ok) {
                        statusElement.textContent = '✅';
                        statusElement.className = 'status-icon success';
                        resultElement.innerHTML = '<span class="success">✓ API respondiendo</span>';
                    } else {
                        throw new Error('API error');
                    }
                })
                .catch(() => {
                    statusElement.textContent = '❌';
                    statusElement.className = 'status-icon error';
                    resultElement.innerHTML = '<span class="error">✗ API no disponible</span>';
                });
        }

        // Ejecutar pruebas
        document.addEventListener('DOMContentLoaded', function() {
            // Probar CSS
            testCSS('https://upiitascholar.com/css/styles.css', 'css-main-status', 'css-main-result');
            testCSS('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', 'fontawesome-status', 'fontawesome-result');
            testCSS('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', 'bootstrap-status', 'bootstrap-result');
            
            // Probar JS
            testJS('https://upiitascholar.com/js/main.js', 'js-main-status', 'js-main-result');
            testJS('https://upiitascholar.com/js/MapaRealista.js', 'js-mapa-status', 'js-mapa-result');
            testJS('https://upiitascholar.com/js/corregir_rutas_ajax.js', 'js-ajax-status', 'js-ajax-result');
            
            // Probar APIs
            testAPI('https://upiitascholar.com/api/buscar_lugares.php', 'api-lugares-status', 'api-lugares-result');
            testAPI('https://upiitascholar.com/api/calcular_ruta.php', 'api-rutas-status', 'api-rutas-result');
            
            // Mostrar resumen después de 5 segundos
            setTimeout(function() {
                const successCount = document.querySelectorAll('.success').length;
                const errorCount = document.querySelectorAll('.error').length;
                const total = successCount + errorCount;
                
                const summaryDiv = document.getElementById('summary');
                summaryDiv.innerHTML = `
                    <div style="padding: 15px; background: ${errorCount === 0 ? '#d4edda' : '#f8d7da'}; border-radius: 6px; border: 1px solid ${errorCount === 0 ? '#c3e6cb' : '#f5c6cb'};">
                        <h3 style="margin: 0 0 10px 0; color: ${errorCount === 0 ? '#155724' : '#721c24'};">
                            ${errorCount === 0 ? '🎉 Todos los assets están funcionando correctamente' : '⚠️ Algunos assets tienen problemas'}
                        </h3>
                        <p style="margin: 0; color: ${errorCount === 0 ? '#155724' : '#721c24'};">
                            ✅ Assets funcionando: ${successCount}/${total}<br>
                            ${errorCount > 0 ? `❌ Assets con problemas: ${errorCount}/${total}` : ''}
                        </p>
                    </div>
                `;
            }, 5000);
        });
    </script>
</body>
</html>
