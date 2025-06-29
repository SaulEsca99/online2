<?php
// Ruta: WEBupita/pages/mapa-rutas.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/header.php';
?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="content">
        <h1 class="page-title">Mapa Interactivo con Rutas</h1>

        <!-- Panel de control de rutas -->
        <div class="route-controls" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                <!-- Selector de origen -->
                <div>
                    <label for="origen" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        <i class="fas fa-map-marker-alt" style="color: #28a745;"></i> Origen:
                    </label>
                    <select id="origen" class="form-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Selecciona punto de origen...</option>
                    </select>
                </div>

                <!-- Selector de destino -->
                <div>
                    <label for="destino" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        <i class="fas fa-map-marker-alt" style="color: #dc3545;"></i> Destino:
                    </label>
                    <select id="destino" class="form-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Selecciona punto de destino...</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div style="display: flex; gap: 10px;">
                    <button id="btnCalcular" class="btn btn-primary" onclick="calcularRuta()" style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-route"></i> Calcular Ruta
                    </button>
                    <button id="btnLimpiar" class="btn btn-secondary" onclick="limpiarFormulario()" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Barra de búsqueda -->
            <div style="margin-top: 15px;">
                <input type="text" id="buscarLugar" placeholder="Buscar aula o lugar..."
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <!-- Opciones adicionales -->
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="guardarFavorito">
                        <span>Guardar como ruta favorita</span>
                    </label>
                    <input type="text" id="nombreRuta" placeholder="Nombre para la ruta favorita..."
                           style="width: 100%; padding: 6px; margin-top: 8px; border: 1px solid #ddd; border-radius: 4px; display: none;">
                </div>
            <?php endif; ?>
        </div>

        <!-- Información de la ruta -->
        <div id="rutaInfo" class="route-info" style="display: none; background: #e8f5e8; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <h3 style="margin: 0 0 10px 0; color: #155724;">
                <i class="fas fa-info-circle"></i> Información de la Ruta
            </h3>
            <div id="rutaDetalles"></div>
        </div>

        <!-- Contenedor del mapa -->
        <div class="map-container" style="position: relative; width: 100%; height: 600px; border: 2px solid #003366; border-radius: 8px; overflow: hidden;">
            <canvas id="mapaCanvas" width="1000" height="600" style="display: block; background: #f0f8ff;"></canvas>

            <!-- Controles del mapa -->
            <div class="map-controls" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 10px; border-radius: 4px;">
                <button id="zoomIn" style="display: block; margin-bottom: 5px; padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer;">
                    <i class="fas fa-plus"></i> Zoom +
                </button>
                <button id="zoomOut" style="display: block; padding: 5px 10px; border: 1px solid #ddd; background: white; cursor: pointer;">
                    <i class="fas fa-minus"></i> Zoom -
                </button>
            </div>
        </div>

        <!-- Leyenda del mapa -->
        <div class="map-legend" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <h3 style="margin: 0 0 15px 0;">Leyenda</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #28a745; border-radius: 50%;"></div>
                    <span>Punto de Origen</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #dc3545; border-radius: 50%;"></div>
                    <span>Punto de Destino</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 30px; height: 4px; background: #007bff;"></div>
                    <span>Ruta Calculada</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #3498db; border: 2px solid #333;"></div>
                    <span>Aulas</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #e74c3c; border: 2px solid #333;"></div>
                    <span>Edificio de Gobierno</span>
                </div>
            </div>
        </div>

        <!-- Rutas favoritas (solo para usuarios logueados) -->
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="favorite-routes" style="margin-top: 30px;">
                <h2 class="section-title">Mis Rutas Favoritas</h2>
                <div id="rutasFavoritas" style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <p style="text-align: center; color: #666;">Cargando rutas favoritas...</p>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Configuración global
        window.API_BASE_URL = 'https://upiitascholar.com';
        window.BASE_URL = 'https://upiitascholar.com';

        // Corregir todas las llamadas fetch
        document.addEventListener('DOMContentLoaded', function() {
            // Asegurar que los elementos existan
            const origen = document.getElementById('origen');
            const destino = document.getElementById('destino');
            
            if (!origen || !destino) {
                console.error('❌ Elementos origen/destino no encontrados');
                location.reload(); // Recargar si no se encuentran
            }
        });

        // Variables globales
        let canvas, ctx;
        let rutaActual = null;
        let zoom = 1;
        let offsetX = 0, offsetY = 0;
        let isDragging = false;
        let lastMouseX, lastMouseY;

        // Configurar rutas base para APIs
        const BASE_PATH = '/TecnologiasParaElDesarrolloDeAplicacionesWeb/SchoolPathFinder/WEBupita';

        // Coordenadas de edificios principales (ejemplo simplificado)
        const edificios = {
            'A1': { x: 200, y: 150, width: 80, height: 60, color: '#3498db', nombre: 'Edificio A1' },
            'A2': { x: 350, y: 150, width: 80, height: 60, color: '#e91e63', nombre: 'Edificio A2' },
            'A3': { x: 500, y: 150, width: 80, height: 60, color: '#f39c12', nombre: 'Edificio A3' },
            'A4': { x: 650, y: 150, width: 80, height: 60, color: '#2ecc71', nombre: 'Edificio A4' },
            'LC': { x: 350, y: 300, width: 120, height: 80, color: '#34495e', nombre: 'Lab. Central' },
            'EG': { x: 550, y: 350, width: 100, height: 70, color: '#f1c40f', nombre: 'Ed. Gobierno' },
            'EP': { x: 200, y: 400, width: 100, height: 60, color: '#e74c3c', nombre: 'Lab. Pesados' }
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Llamar a cargarLugares cuando el DOM esté listo
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Iniciando sistema de mapas...');
                cargarLugares();
                
                // Inicializar canvas y otros componentes
                canvas = document.getElementById('mapaCanvas');
                if (canvas) {
                    ctx = canvas.getContext('2d');
                    configurarCanvas();
                    configurarEventos();
                    dibujarMapa();
                }
            });

            // Función para cargar lugares disponibles
            async function cargarLugares() {
                try {
                    console.log('📍 Cargando lugares...');
                    
                    const response = await fetch('https://upiitascholar.com/api/buscar_lugares.php');
                    const result = await response.json();
                    
                    console.log('Respuesta de API:', result);
                    
                    if (result.success && result.data) {
                        const origen = document.getElementById('origen');
                        const destino = document.getElementById('destino');
                        
                        if (!origen || !destino) {
                            console.error('❌ Selectores no encontrados');
                            return;
                        }
                        
                        // Limpiar selectores
                        origen.innerHTML = '<option value="">Selecciona punto de origen...</option>';
                        destino.innerHTML = '<option value="">Selecciona punto de destino...</option>';
                        
                        // Procesar cada edificio
                        result.data.forEach(edificioData => {
                            // Crear grupo de opciones para cada edificio
                            const optgroupOrigen = document.createElement('optgroup');
                            optgroupOrigen.label = edificioData.edificio;
                            
                            const optgroupDestino = document.createElement('optgroup');
                            optgroupDestino.label = edificioData.edificio;
                            
                            // Agregar cada lugar del edificio
                            edificioData.lugares.forEach(lugar => {
                                const optionOrigen = document.createElement('option');
                                optionOrigen.value = lugar.valor_completo;
                                optionOrigen.textContent = `${lugar.codigo} - ${lugar.nombre} (Piso ${lugar.piso})`;
                                
                                const optionDestino = optionOrigen.cloneNode(true);
                                
                                optgroupOrigen.appendChild(optionOrigen);
                                optgroupDestino.appendChild(optionDestino);
                            });
                            
                            origen.appendChild(optgroupOrigen);
                            destino.appendChild(optgroupDestino);
                        });
                        
                        console.log('✅ Lugares cargados exitosamente');
                        mostrarMensaje('Lugares cargados correctamente', 'success');
                        
                    } else {
                        console.error('❌ Error en la respuesta:', result);
                        mostrarMensaje('Error al cargar los lugares', 'error');
                    }
                } catch (error) {
                    console.error('❌ Error cargando lugares:', error);
                    mostrarMensaje('Error de conexión al cargar lugares', 'error');
                }
            }

            // Función para mostrar mensajes
            function mostrarMensaje(mensaje, tipo = 'info') {
                // Buscar si ya existe un contenedor de mensajes
                let msgContainer = document.getElementById('mensaje-sistema');
                
                if (!msgContainer) {
                    msgContainer = document.createElement('div');
                    msgContainer.id = 'mensaje-sistema';
                    msgContainer.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        transition: all 0.3s ease;
                    `;
                    document.body.appendChild(msgContainer);
                }
                
                const alertDiv = document.createElement('div');
                alertDiv.style.cssText = `
                    padding: 15px 20px;
                    margin-bottom: 10px;
                    background: ${tipo === 'error' ? '#f44336' : tipo === 'success' ? '#4CAF50' : '#2196F3'};
                    color: white;
                    border-radius: 4px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                    animation: slideIn 0.3s ease;
                `;
                alertDiv.textContent = mensaje;
                
                msgContainer.appendChild(alertDiv);
                
                // Remover después de 5 segundos
                setTimeout(() => {
                    alertDiv.style.opacity = '0';
                    setTimeout(() => alertDiv.remove(), 300);
                }, 5000);
            }

            // Agregar estilos para la animación
            if (!document.getElementById('mensaje-estilos')) {
                const style = document.createElement('style');
                style.id = 'mensaje-estilos';
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            // Verificar si hay una ruta para cargar desde favoritos
            const rutaParaCargar = sessionStorage.getItem('cargarRuta');
            if (rutaParaCargar) {
                const datos = JSON.parse(rutaParaCargar);
                sessionStorage.removeItem('cargarRuta'); // Limpiar después de usar

                // Esperar a que se carguen los lugares y luego cargar la ruta
                setTimeout(() => {
                    document.getElementById('origen').value = datos.origen;
                    document.getElementById('destino').value = datos.destino;
                    calcularRuta();
                }, 1000);
            }

            // Event listeners
            document.getElementById('calcularRuta').addEventListener('click', calcularRuta);
            document.getElementById('limpiarRuta').addEventListener('click', limpiarRuta);
            document.getElementById('buscarLugar').addEventListener('input', buscarLugares);
            document.getElementById('zoomIn').addEventListener('click', () => cambiarZoom(1.2));
            document.getElementById('zoomOut').addEventListener('click', () => cambiarZoom(0.8));

            // Control de zoom con rueda del mouse
            canvas.addEventListener('wheel', function(e) {
                e.preventDefault();
                const factor = e.deltaY > 0 ? 0.9 : 1.1;
                cambiarZoom(factor);
            });

            // Control de arrastrar
            canvas.addEventListener('mousedown', startDrag);
            canvas.addEventListener('mousemove', drag);
            canvas.addEventListener('mouseup', stopDrag);
            canvas.addEventListener('mouseleave', stopDrag);

            // Checkbox para guardar favorito
            const checkbox = document.getElementById('guardarFavorito');
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    const input = document.getElementById('nombreRuta');
                    input.style.display = this.checked ? 'block' : 'none';
                });
            }

            // Cargar rutas favoritas si el usuario está logueado
            <?php if (isset($_SESSION['usuario_id'])): ?>
            cargarRutasFavoritas();
            <?php endif; ?>
        });

        // Mejor manejo de mensajes y errores
        function mostrarMensaje(mensaje, tipo = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo}`;
            alertDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                background: ${tipo === 'error' ? '#f44336' : '#4CAF50'};
                color: white;
                border-radius: 4px;
                z-index: 9999;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            `;
            alertDiv.textContent = mensaje;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function resizeCanvas() {
            const container = canvas.parentElement;
            canvas.width = container.clientWidth;
            canvas.height = container.clientHeight;
            dibujarMapa();
        }

        function dibujarMapa() {
            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Aplicar transformaciones de zoom y pan
            ctx.save();
            ctx.scale(zoom, zoom);
            ctx.translate(offsetX, offsetY);

            // Dibujar edificios
            Object.entries(edificios).forEach(([codigo, edificio]) => {
                ctx.fillStyle = edificio.color;
                ctx.fillRect(edificio.x, edificio.y, edificio.width, edificio.height);

                ctx.strokeStyle = '#333';
                ctx.lineWidth = 2;
                ctx.strokeRect(edificio.x, edificio.y, edificio.width, edificio.height);

                // Etiqueta del edificio
                ctx.fillStyle = 'white';
                ctx.font = 'bold 12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(codigo,
                    edificio.x + edificio.width/2,
                    edificio.y + edificio.height/2 + 4
                );
            });

            // Dibujar conexiones principales (simplificado)
            ctx.strokeStyle = '#ccc';
            ctx.lineWidth = 2;
            ctx.setLineDash([5, 5]);

            // Algunas conexiones de ejemplo
            dibujarLinea(200, 150, 350, 150); // A1 a A2
            dibujarLinea(350, 150, 500, 150); // A2 a A3
            dibujarLinea(500, 150, 650, 150); // A3 a A4
            dibujarLinea(350, 210, 350, 300); // A2 a LC
            dibujarLinea(470, 300, 550, 350); // LC a EG
            dibujarLinea(300, 380, 550, 380); // Conexión horizontal

            ctx.setLineDash([]);

            // Dibujar ruta actual si existe
            if (rutaActual && rutaActual.ruta_detallada) {
                dibujarRuta(rutaActual.ruta_detallada);
            }

            ctx.restore();
        }

        function dibujarLinea(x1, y1, x2, y2) {
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
        }

        function dibujarRuta(rutaDetallada) {
            if (rutaDetallada.length < 2) return;

            // Línea de la ruta
            ctx.strokeStyle = '#007bff';
            ctx.lineWidth = 4;
            ctx.setLineDash([]);

            ctx.beginPath();
            ctx.moveTo(rutaDetallada[0].coordenada_x, rutaDetallada[0].coordenada_y);

            for (let i = 1; i < rutaDetallada.length; i++) {
                ctx.lineTo(rutaDetallada[i].coordenada_x, rutaDetallada[i].coordenada_y);
            }
            ctx.stroke();

            // Marcadores de inicio y fin
            rutaDetallada.forEach((punto, index) => {
                if (index === 0) {
                    // Punto de origen (verde)
                    dibujarMarcador(punto.coordenada_x, punto.coordenada_y, '#28a745', 'O');
                } else if (index === rutaDetallada.length - 1) {
                    // Punto de destino (rojo)
                    dibujarMarcador(punto.coordenada_x, punto.coordenada_y, '#dc3545', 'D');
                } else {
                    // Puntos intermedios (azul)
                    dibujarMarcador(punto.coordenada_x, punto.coordenada_y, '#17a2b8', '•');
                }
            });
        }

        function dibujarMarcador(x, y, color, texto) {
            ctx.fillStyle = color;
            ctx.beginPath();
            ctx.arc(x, y, 8, 0, 2 * Math.PI);
            ctx.fill();

            ctx.strokeStyle = 'white';
            ctx.lineWidth = 2;
            ctx.stroke();

            ctx.fillStyle = 'white';
            ctx.font = 'bold 10px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(texto, x, y + 3);
        }

        function cambiarZoom(factor) {
            zoom *= factor;
            zoom = Math.max(0.5, Math.min(3, zoom)); // Limitar zoom
            dibujarMapa();
        }

        function startDrag(e) {
            isDragging = true;
            lastMouseX = e.offsetX;
            lastMouseY = e.offsetY;
            canvas.style.cursor = 'grabbing';
        }

        function drag(e) {
            if (!isDragging) return;

            const deltaX = (e.offsetX - lastMouseX) / zoom;
            const deltaY = (e.offsetY - lastMouseY) / zoom;

            offsetX += deltaX;
            offsetY += deltaY;

            lastMouseX = e.offsetX;
            lastMouseY = e.offsetY;

            dibujarMapa();
        }

        function stopDrag() {
            isDragging = false;
            canvas.style.cursor = 'grab';
        }

        async function cargarLugares() {
            try {
                const response = await fetch(BASE_PATH + '/api/buscar_lugares.php');
                const data = await response.json();

                if (data.success) {
                    const origenSelect = document.getElementById('origen');
                    const destinoSelect = document.getElementById('destino');

                    // Limpiar selects
                    origenSelect.innerHTML = '<option value="">Selecciona punto de origen...</option>';
                    destinoSelect.innerHTML = '<option value="">Selecciona punto de destino...</option>';

                    // Agregar opciones agrupadas por edificio
                    data.data.forEach(edificio => {
                        const origenGroup = document.createElement('optgroup');
                        const destinoGroup = document.createElement('optgroup');
                        origenGroup.label = edificio.edificio;
                        destinoGroup.label = edificio.edificio;

                        edificio.lugares.forEach(lugar => {
                            const origenOption = document.createElement('option');
                            const destinoOption = document.createElement('option');

                            origenOption.value = lugar.valor_completo;
                            origenOption.textContent = `${lugar.codigo} - ${lugar.nombre}`;
                            destinoOption.value = lugar.valor_completo;
                            destinoOption.textContent = `${lugar.codigo} - ${lugar.nombre}`;

                            origenGroup.appendChild(origenOption);
                            destinoGroup.appendChild(destinoOption);
                        });

                        origenSelect.appendChild(origenGroup);
                        destinoSelect.appendChild(destinoGroup);
                    });
                }
            } catch (error) {
                console.error('Error cargando lugares:', error);
                mostrarMensaje('Error cargando lugares: ' + error.message, 'error');
            }
        }

        async function buscarLugares() {
            const termino = document.getElementById('buscarLugar').value;
            if (termino.length < 2) {
                cargarLugares();
                return;
            }

            try {
                const response = await fetch(BASE_PATH + `/api/buscar_lugares.php?q=${encodeURIComponent(termino)}`);
                const data = await response.json();

                if (data.success) {
                    // Actualizar selectores con resultados de búsqueda
                    // Similar a cargarLugares() pero con los resultados filtrados
                }
            } catch (error) {
                console.error('Error en búsqueda:', error);
            }
        }

        async function calcularRuta() {
            const origen = document.getElementById('origen').value;
            const destino = document.getElementById('destino').value;
            
            if (!origen || !destino) {
                mostrarMensaje('Por favor selecciona origen y destino', 'error');
                return;
            }
            
            if (origen === destino) {
                mostrarMensaje('El origen y destino no pueden ser iguales', 'error');
                return;
            }
            
            try {
                console.log('🛣️ Calculando ruta...', { origen, destino });
                
                // Parsear los valores (formato: "tipo_id")
                const [origenTipo, origenId] = origen.split('_');
                const [destinoTipo, destinoId] = destino.split('_');
                
                const payload = {
                    origen_tipo: origenTipo,
                    origen_id: parseInt(origenId),
                    destino_tipo: destinoTipo,
                    destino_id: parseInt(destinoId)
                };
                
                console.log('Payload:', payload);
                
                const response = await fetch('https://upiitascholar.com/api/calcular_ruta.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                console.log('Respuesta:', data);
                
                if (data.success && data.ruta) {
                    rutaActual = data.ruta;
                    mostrarInformacionRuta(rutaActual);
                    dibujarMapa(); // Redibujar el mapa con la ruta
                    mostrarMensaje('Ruta calculada exitosamente', 'success');
                    
                    // Si hay checkbox de guardar favorito
                    const guardarFavorito = document.getElementById('guardarFavorito');
                    if (guardarFavorito && guardarFavorito.checked) {
                        guardarRutaFavorita();
                    }
                } else {
                    mostrarMensaje(data.error || 'No se pudo calcular la ruta', 'error');
                }
            } catch (error) {
                console.error('❌ Error calculando ruta:', error);
                mostrarMensaje('Error de conexión al calcular la ruta', 'error');
            }
        }

        function mostrarInformacionRuta(ruta) {
            const rutaInfo = document.getElementById('rutaInfo');
            const rutaDetalles = document.getElementById('rutaDetalles');
            
            if (!rutaInfo || !rutaDetalles) return;
            
            // Mostrar el contenedor de información
            rutaInfo.style.display = 'block';
            
            // Crear el HTML con la información de la ruta
            let html = `
        <div class="ruta-resumen">
            <div class="ruta-stat">
                <i class="fas fa-route"></i>
                <span class="stat-label">Distancia:</span>
                <span class="stat-value">${ruta.distancia_total.toFixed(1)} metros</span>
            </div>
            <div class="ruta-stat">
                <i class="fas fa-shoe-prints"></i>
                <span class="stat-label">Pasos:</span>
                <span class="stat-value">${ruta.numero_pasos}</span>
            </div>
            <div class="ruta-stat">
                <i class="fas fa-map-marker-alt"></i>
                <span class="stat-label">Puntos:</span>
                <span class="stat-value">${ruta.ruta_detallada.length}</span>
            </div>
        </div>
        
        <div class="ruta-detalle">
            <h4>Recorrido detallado:</h4>
            <ol class="ruta-pasos">
    `;
    
            // Agregar cada punto del recorrido
            ruta.ruta_detallada.forEach((punto, index) => {
                const icono = punto.tipo === 'aula' ? 
                    '<i class="fas fa-door-open"></i>' : 
                    '<i class="fas fa-map-signs"></i>';
                    
                const esInicio = index === 0;
                const esFinal = index === ruta.ruta_detallada.length - 1;
                
                html += `
            <li class="ruta-paso ${esInicio ? 'inicio' : ''} ${esFinal ? 'final' : ''}">
                ${icono}
                <span class="paso-nombre">${punto.nombre}</span>
                ${punto.distancia_acumulada > 0 ? 
                    `<span class="paso-distancia">(${punto.distancia_acumulada.toFixed(1)}m)</span>` : 
                    ''}
            </li>
        `;
            });
            
            html += `
            </ol>
        </div>
    `;
    
    rutaDetalles.innerHTML = html;
    
    // Agregar estilos si no existen
    if (!document.getElementById('ruta-estilos')) {
        const style = document.createElement('style');
        style.id = 'ruta-estilos';
        style.textContent = `
            .ruta-resumen {
                display: flex;
                justify-content: space-around;
                padding: 20px;
                background: #f5f5f5;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .ruta-stat {
                text-align: center;
            }
            .ruta-stat i {
                font-size: 24px;
                color: #007bff;
                display: block;
                margin-bottom: 8px;
            }
            .stat-label {
                display: block;
                font-size: 12px;
                color: #666;
            }
            .stat-value {
                display: block;
                font-size: 18px;
                font-weight: bold;
                color: #333;
            }
            .ruta-pasos {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .ruta-paso {
                padding: 10px 15px;
                border-left: 3px solid #ddd;
                margin-left: 20px;
                position: relative;
                transition: all 0.3s ease;
            }
            .ruta-paso:hover {
                background: #f9f9f9;
                border-left-color: #007bff;
            }
            .ruta-paso.inicio {
                border-left-color: #28a745;
            }
            .ruta-paso.final {
                border-left-color: #dc3545;
            }
            .ruta-paso i {
                margin-right: 10px;
                color: #666;
            }
            .paso-nombre {
                font-weight: 500;
            }
            .paso-distancia {
                color: #999;
                font-size: 12px;
                margin-left: 10px;
            }
        `;
        document.head.appendChild(style);
    }
}

        function limpiarRuta() {
            rutaActual = null;
            document.getElementById('rutaInfo').style.display = 'none';
            document.getElementById('origen').value = '';
            document.getElementById('destino').value = '';
            dibujarMapa();
            mostrarMensaje('Mapa limpiado', 'info');
        }

        <?php if (isset($_SESSION['usuario_id'])): ?>
        async function cargarRutasFavoritas() {
            try {
                const response = await fetch(BASE_PATH + '/api/rutas_favoritas.php');
                const data = await response.json();

                const container = document.getElementById('rutasFavoritas');

                if (data.success && data.rutas.length > 0) {
                    let html = '';
                    data.rutas.forEach(ruta => {
                        html += `
                    <div class="favorite-route-item" style="background: white; padding: 15px; margin-bottom: 10px; border-radius: 4px; border-left: 4px solid #007bff;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 8px 0; color: #003366;">${ruta.nombre_ruta}</h4>
                                <p style="margin: 0; color: #666;">
                                    <i class="fas fa-map-marker-alt" style="color: #28a745;"></i> ${ruta.origen_codigo}
                                    <i class="fas fa-arrow-right" style="margin: 0 8px;"></i>
                                    <i class="fas fa-map-marker-alt" style="color: #dc3545;"></i> ${ruta.destino_codigo}
                                </p>
                                <small style="color: #999;">Creada: ${new Date(ruta.fecha_creacion).toLocaleDateString()}</small>
                            </div>
                            <button onclick="cargarRutaFavorita('${ruta.origen_tipo}_${ruta.origen_id}', '${ruta.destino_tipo}_${ruta.destino_id}')"
                                    style="padding: 6px 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-route"></i> Cargar
                            </button>
                        </div>
                    </div>
                `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p style="text-align: center; color: #666;">No tienes rutas favoritas guardadas</p>';
                }
            } catch (error) {
                console.error('Error cargando rutas favoritas:', error);
            }
        }

        function cargarRutaFavorita(origen, destino) {
            document.getElementById('origen').value = origen;
            document.getElementById('destino').value = destino;
            calcularRuta();
        }
        <?php endif; ?>
    </script>

    <style>
        .form-select:focus, .btn:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        #mapaCanvas {
            cursor: grab;
        }

        .route-info {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

<script src="../js/corregir_rutas_ajax.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>