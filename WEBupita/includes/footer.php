<?php
// Función helper para URLs si no está definida
if (!function_exists('getAssetUrl')) {
    function getAssetUrl($path) {
        // Detectar si estamos en producción o desarrollo
        $is_production = $_SERVER['SERVER_NAME'] !== 'localhost';
        
        if ($is_production) {
            return '/' . ltrim($path, '/');
        } else {
            $base_url = '/TecnologiasParaElDesarrolloDeAplicacionesWeb/SchoolPathFinder/WEBupita';
            return $base_url . '/' . ltrim($path, '/');
        }
    }
}
?>

    </div> <!-- Cierre del main-container -->

    <!-- Pie de página -->
    <footer class="main-footer">
        <div class="footer-section contact-info">
            <h3><i class="fas fa-map-marker-alt"></i> Contacto</h3>
            <p><strong>Dirección:</strong><br>
               Av. Té 950, Granjas México<br>
               Iztacalco, 08400 Ciudad de México, CDMX</p>
            
            <p><strong>Teléfono:</strong><br>
               <i class="fas fa-phone"></i> +52 55 5729 6000</p>
            
            <p><strong>Email:</strong><br>
               <i class="fas fa-envelope"></i> info@upiita.ipn.mx</p>
        </div>

        <div class="footer-section social-media">
            <h3><i class="fas fa-share-alt"></i> Redes Sociales</h3>
            <div class="social-links" style="display: flex; flex-direction: column; gap: 10px;">
                <a href="https://facebook.com/upiita.ipn" target="_blank" rel="noopener noreferrer" 
                   style="color: white; text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 4px; transition: background-color 0.3s;">
                    <i class="fab fa-facebook-f" style="color: #1877F2;"></i>
                    Facebook UPIITA
                </a>
                
                <a href="https://twitter.com/upiita_ipn" target="_blank" rel="noopener noreferrer"
                   style="color: white; text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 4px; transition: background-color 0.3s;">
                    <i class="fab fa-twitter" style="color: #1DA1F2;"></i>
                    Twitter UPIITA
                </a>
                
                <a href="https://instagram.com/upiita_oficial" target="_blank" rel="noopener noreferrer"
                   style="color: white; text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 4px; transition: background-color 0.3s;">
                    <i class="fab fa-instagram" style="color: #E4405F;"></i>
                    Instagram UPIITA
                </a>
                
                <a href="https://youtube.com/upiitaipn" target="_blank" rel="noopener noreferrer"
                   style="color: white; text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 4px; transition: background-color 0.3s;">
                    <i class="fab fa-youtube" style="color: #FF0000;"></i>
                    YouTube UPIITA
                </a>
            </div>
        </div>

        <div class="footer-section">
            <h3><i class="fas fa-link"></i> Enlaces Rápidos</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="https://upiitascholar.com/pages/conocenos.php" 
                   style="color: white; text-decoration: none; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: color 0.3s;">
                    <i class="fas fa-info-circle"></i> Acerca de UPIITA
                </a>
                
                <a href="https://upiitascholar.com/pages/oferta-educativa.php" 
                   style="color: white; text-decoration: none; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: color 0.3s;">
                    <i class="fas fa-graduation-cap"></i> Oferta Educativa
                </a>
                
                <a href="https://upiitascholar.com/pages/mapa-interactivo.php" 
                   style="color: white; text-decoration: none; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: color 0.3s;">
                    <i class="fas fa-map"></i> Mapa del Campus
                </a>
                
                <a href="https://upiitascholar.com/pages/mapa-rutas.php" 
                   style="color: white; text-decoration: none; padding: 6px 0; transition: color 0.3s;">
                    <i class="fas fa-route"></i> Calcular Rutas
                </a>
            </div>
        </div>

        <div class="copyright">
            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 20px 0;">
            <p style="margin: 0; text-align: center; opacity: 0.8;">
                <strong>© <?= date('Y') ?> UPIITA - Instituto Politécnico Nacional</strong><br>
                "Unidad Profesional Interdisciplinaria en Ingeniería y Tecnologías Avanzadas"<br>
                <small>Sistema de Navegación del Campus v2.0 - Desarrollado para la comunidad estudiantil</small>
            </p>
        </div>
    </footer>

    <!-- Scripts globales -->
    <script src="https://upiitascholar.com/js/main.js"></script>
    
    <style>
        .main-footer {
            background-color: #003366;
            color: white;
            padding: 30px;
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            margin-top: 30px;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
        }

        .main-footer h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #ffffff;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            padding-bottom: 8px;
        }

        .social-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .footer-section a:hover {
            color: #74b9ff !important;
        }

        .copyright {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
        }

        /* Estilos responsivos para el footer */
        @media (max-width: 768px) {
            .main-footer {
                flex-direction: column;
                padding: 20px;
                gap: 20px;
            }
            
            .footer-section {
                min-width: 100%;
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
            
            .footer-section div {
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .main-footer {
                padding: 15px;
            }
            
            .main-footer h3 {
                font-size: 1.1rem;
            }
            
            .social-links a,
            .footer-section a {
                font-size: 0.9rem;
            }
        }
    </style>

</body>
</html>