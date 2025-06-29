<?php
// Archivo: test_conexion_ionos.php
// Prueba específica para la conexión con IONOS

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Prueba de Conexión IONOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .ok { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>";

// Configuración de conexión
$host = 'db5018121072.hosting-data.io';
$db   = 'dbs14382122';
$user = 'dbu2064690';
$pass = 'Upiita2024!';
$charset = 'utf8mb4';
$port = 3306;

echo "<h2>📋 Datos de Conexión</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Base de datos:</strong> $db</li>";
echo "<li><strong>Usuario:</strong> $user</li>";
echo "<li><strong>Puerto:</strong> $port</li>";
echo "<li><strong>Charset:</strong> $charset</li>";
echo "</ul>";

echo "<h2>🔗 Intentando Conexión...</h2>";

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
    PDO::ATTR_TIMEOUT            => 30
];

try {
    echo "<p class='info'>Conectando a IONOS...</p>";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<p class='ok'>✅ Conexión exitosa a IONOS</p>";

    // Verificar versión de MySQL
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "<p class='info'>📊 Versión MySQL: {$version['version']}</p>";

    // Verificar tablas
    echo "<h3>📋 Verificando Tablas</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p class='error'>❌ No se encontraron tablas. ¿Importaste el archivo SQL?</p>";
    } else {
        echo "<p class='ok'>✅ Se encontraron " . count($tables) . " tablas:</p>";
        echo "<ul>";
        foreach($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }

    // Verificar tablas específicas del proyecto
    echo "<h3>🔍 Verificando Tablas del Proyecto</h3>";
    $tablas_necesarias = ['Edificios', 'Aulas', 'PuntosConexion', 'Rutas', 'usuarios', 'RutasFavoritas'];
    
    foreach ($tablas_necesarias as $tabla) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
            $count = $stmt->fetch();
            echo "<p class='ok'>✅ $tabla: {$count['total']} registros</p>";
        } else {
            echo "<p class='error'>❌ Tabla '$tabla' no encontrada</p>";
        }
    }

    // Verificar vista
    echo "<h3>👁️ Verificando Vista</h3>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'vista_lugares'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='ok'>✅ Vista 'vista_lugares' existe</p>";
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM vista_lugares");
            $count = $stmt->fetch();
            echo "<p class='info'>📊 Total lugares disponibles: {$count['total']}</p>";
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ Vista existe pero hay error al consultar: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ Vista 'vista_lugares' no encontrada (opcional)</p>";
    }

    // Prueba de consulta simple
    echo "<h3>🧪 Prueba de Consulta</h3>";
    try {
        $stmt = $pdo->query("SELECT idEdificio, nombre FROM Edificios LIMIT 3");
        $edificios = $stmt->fetchAll();
        if (empty($edificios)) {
            echo "<p class='warning'>⚠️ No hay datos en la tabla Edificios</p>";
        } else {
            echo "<p class='ok'>✅ Consulta exitosa. Primeros edificios:</p>";
            echo "<ul>";
            foreach ($edificios as $edificio) {
                echo "<li>ID: {$edificio['idEdificio']} - {$edificio['nombre']}</li>";
            }
            echo "</ul>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error en consulta: " . $e->getMessage() . "</p>";
    }

    echo "<h2>🎉 Diagnóstico Completo</h2>";
    echo "<p class='ok'>La conexión a IONOS está funcionando correctamente.</p>";
    echo "<p class='info'>Puedes proceder a usar tu aplicación web.</p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<h3>🔧 Posibles soluciones:</h3>";
    echo "<ul>";
    echo "<li>Verifica que los datos de conexión sean correctos</li>";
    echo "<li>Confirma que la base de datos existe en tu panel de IONOS</li>";
    echo "<li>Revisa que el usuario tenga permisos correctos</li>";
    echo "<li>Verifica que IONOS permita conexiones desde tu IP</li>";
    echo "<li>Contacta al soporte de IONOS si persiste</li>";
    echo "</ul>";
    
    echo "<h3>📝 Información técnica del error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>DSN usado: " . htmlspecialchars($dsn) . "</pre>";
}

echo "<hr>";
echo "<p><small>Fecha de prueba: " . date('Y-m-d H:i:s') . "</small></p>";
?>