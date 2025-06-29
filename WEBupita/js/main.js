// js/main.js
// Archivo principal de JavaScript para UPIITA Finder

// Configuración global
window.UPIITA_CONFIG = {
    BASE_URL: 'https://upiitascholar.com',
    API_BASE_URL: 'https://upiitascholar.com',
    
    // APIs específicas
    API: {
        CALCULAR_RUTA: 'https://upiitascholar.com/api/calcular_ruta.php',
        BUSCAR_LUGARES: 'https://upiitascholar.com/api/buscar_lugares.php',
        MAPA_COORDENADAS: 'https://upiitascholar.com/api/mapa_coordenadas.php',
        GUARDAR_FAVORITO: 'https://upiitascholar.com/api/guardar_favorito.php'
    }
};

// Función auxiliar para hacer peticiones
async function fetchAPI(endpoint, options = {}) {
    try {
        const url = endpoint.startsWith('http') ? endpoint : `${window.UPIITA_CONFIG.API_BASE_URL}/api/${endpoint}`;
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        return await response.json();
    } catch (error) {
        console.error('Error en petición API:', error);
        throw error;
    }
}

// Funciones globales que pueden ser usadas en cualquier página
window.UPIITA = {
    fetchAPI,
    
    // Mostrar notificaciones
    mostrarNotificacion: function(mensaje, tipo = 'info', duracion = 5000) {
        const notif = document.createElement('div');
        notif.className = `notification notification-${tipo}`;
        notif.textContent = mensaje;
        notif.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${tipo === 'error' ? '#f44336' : tipo === 'success' ? '#4CAF50' : '#2196F3'};
            color: white;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notif);
        
        setTimeout(() => {
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 300);
        }, duracion);
    },
    
    // Validar sesión
    verificarSesion: async function() {
        try {
            const response = await fetch(`${window.UPIITA_CONFIG.BASE_URL}/api/verificar_sesion.php`);
            const data = await response.json();
            return data.loggedin || false;
        } catch (error) {
            console.error('Error verificando sesión:', error);
            return false;
        }
    }
};

// Inicialización global
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 UPIITA Finder cargado');
    console.log('📍 URL Base:', window.UPIITA_CONFIG.BASE_URL);
});