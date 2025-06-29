<?php
// Ruta: WEBupita/includes/conexion.php
// Configuración para servidor IONOS

$host = 'db5018125047.hosting-data.io';
$db   = 'dbs14383894';
$user = 'dbu632087';
$pass = 'Upiita2024!';
$charset = 'utf8mb4';
$port = 3306; // Puerto estándar de MySQL
$dbname = $db;  // Variable adicional para compatibilidad

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
    PDO::ATTR_TIMEOUT            => 30, // Timeout de 30 segundos
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Configurar timezone
    $pdo->exec("SET time_zone = '+00:00'");
    
    // Mensaje de éxito solo para pruebas (quitar en producción)
    // echo "Conexión exitosa a IONOS<br>";

} catch (PDOException $e) {
    // Log del error real
    error_log('Error de conexión a la base de datos IONOS: ' . $e->getMessage());

    // Mostrar error específico para IONOS
    if (php_sapi_name() === 'cli') {
        die("Error de conexión a la base de datos: " . $e->getMessage() . "\n");
    } else {
        // Mensaje de error más específico para IONOS
        $error_msg = "Error de conexión a la base de datos IONOS.<br>";
        $error_msg .= "Verifica que:<br>";
        $error_msg .= "1. Los datos de conexión sean correctos<br>";
        $error_msg .= "2. La base de datos 'dbs14382122' exista en IONOS<br>";
        $error_msg .= "3. El usuario 'dbu2064690' tenga permisos<br>";
        $error_msg .= "4. El servidor permita conexiones externas<br>";
        $error_msg .= "<br>Error técnico: " . $e->getMessage();
        
        // Solo mostrar en modo debug
        if (isset($_GET['debug'])) {
            die($error_msg);
        } else {
            die("Error de conexión a la base de datos. Contacta al administrador.");
        }
    }
}

// Función auxiliar para ejecutar consultas con manejo de errores
function ejecutarConsulta($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log('Error en consulta SQL: ' . $e->getMessage() . ' | SQL: ' . $sql);
        throw $e;
    }
}

// Función para verificar si la base de datos está configurada correctamente
function verificarBaseDatos($pdo) {
    try {
        // Verificar que existan las tablas principales
        $tablas = ['Edificios', 'Aulas', 'PuntosConexion', 'Rutas', 'usuarios', 'RutasFavoritas'];

        foreach ($tablas as $tabla) {
            $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
            if ($stmt->rowCount() === 0) {
                throw new Exception("La tabla '$tabla' no existe en la base de datos de IONOS.");
            }
        }

        // Verificar que la vista existe
        $stmt = $pdo->query("SHOW TABLES LIKE 'vista_lugares'");
        if ($stmt->rowCount() === 0) {
            // La vista es opcional, solo advertir
            error_log("Advertencia: La vista 'vista_lugares' no existe en IONOS");
        }

        return true;

    } catch (Exception $e) {
        error_log('Error verificando base de datos IONOS: ' . $e->getMessage());
        return false;
    }
}

// Ejecutar verificación solo si no estamos en una API
if (!isset($_SERVER['REQUEST_URI']) || strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
    if (!verificarBaseDatos($pdo)) {
        if (php_sapi_name() !== 'cli') {
            $error_msg = "<h3 style='color: red;'>La base de datos de IONOS no está configurada correctamente.</h3>";
            $error_msg .= "<p><strong>Pasos a verificar:</strong></p>";
            $error_msg .= "<ol>";
            $error_msg .= "<li>Verifica que hayas importado el archivo SQL en phpMyAdmin de IONOS</li>";
            $error_msg .= "<li>Confirma que todas las tablas estén presentes</li>";
            $error_msg .= "<li>Revisa los permisos del usuario de la base de datos</li>";
            $error_msg .= "<li>Contacta al soporte de IONOS si persiste el problema</li>";
            $error_msg .= "</ol>";
            
            // Solo mostrar en modo debug
            if (isset($_GET['debug'])) {
                die($error_msg);
            }
        }
    }
}
?>