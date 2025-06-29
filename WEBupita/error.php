<?php
// error.php - Página de manejo de errores
// Coloca este archivo en la raíz del servidor

// Obtener código de error
$error_code = isset($_GET['code']) ? intval($_GET['code']) : 404;

// Mensajes de error personalizados
$errors = [
    400 => [
        'title' => 'Solicitud Incorrecta',
        'message' => 'La solicitud no pudo ser procesada debido a una sintaxis incorrecta.',
        'icon' => '❌'
    ],
    401 => [
        'title' => 'No Autorizado',
        'message' => 'Necesitas iniciar sesión para acceder a esta página.',
        'icon' => '🔒'
    ],
    403 => [
        'title' => 'Acceso Prohibido',
        'message' => 'No tienes permisos para acceder a este recurso.',
        'icon' => '⛔'
    ],
    404 => [
        'title' => 'Página No Encontrada',
        'message' => 'Lo sentimos, la página que buscas no existe o ha sido movida.',
        'icon' => '🔍'
    ],
    500 => [
        'title' => 'Error del Servidor',
        'message' => 'Algo salió mal en nuestro servidor. Estamos trabajando para solucionarlo.',
        'icon' => '⚠️'
    ],
    503 => [
        'title' => 'Servicio No Disponible',
        'message' => 'El sitio está en mantenimiento. Por favor, vuelve más tarde.',
        'icon' => '🔧'
    ]
];

// Obtener información del error
$error_info = isset($errors[$error_code]) ? $errors[$error_code] : $errors[404];

// Establecer código de respuesta HTTP
http_response_code($error_code);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $error_code ?> - UPIITA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #003366;
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .error-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .error-details {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #999;
        }
        
        @media (max-width: 480px) {
            .error-container {
                padding: 40px 20px;
            }
            
            .error-code {
                font-size: 80px;
            }
            
            .error-title {
                font-size: 24px;
            }
            
            .error-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon"><?= $error_info['icon'] ?></div>
        <div class="error-code"><?= $error_code ?></div>
        <h1 class="error-title"><?= $error_info['title'] ?></h1>
        <p class="error-message"><?= $error_info['message'] ?></p>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">Ir al Inicio</a>
            <?php if ($error_code === 401): ?>
                <a href="/Public/login.php" class="btn btn-secondary">Iniciar Sesión</a>
            <?php else: ?>
                <a href="javascript:history.back()" class="btn btn-secondary">Regresar</a>
            <?php endif; ?>
        </div>
        
        <div class="error-details">
            <p>Error <?= $error_code ?> • <?= date('d/m/Y H:i:s') ?></p>
            <?php if (isset($_SERVER['REQUEST_URI'])): ?>
                <p style="margin-top: 5px; word-break: break-all;">
                    URL: <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>