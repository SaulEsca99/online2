<?php
// diagnostico_api.php
echo "<h2>Diagnóstico de APIs</h2>";

$apis = [
    'buscar_lugares.php' => 'https://upiitascholar.com/api/buscar_lugares.php',
    'calcular_ruta.php' => 'https://upiitascholar.com/api/calcular_ruta.php',
    'mapa_coordenadas.php' => 'https://upiitascholar.com/api/mapa_coordenadas.php'
];

foreach ($apis as $nombre => $url) {
    echo "<h3>Probando: $nombre</h3>";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<p>Código HTTP: $httpCode</p>";
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data) {
            echo "<pre>" . print_r($data, true) . "</pre>";
        } else {
            echo "<p>Respuesta no es JSON válido</p>";
        }
    } else {
        echo "<p style='color:red;'>Error: No se pudo conectar</p>";
    }
}
?>