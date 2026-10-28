<?php
// api/conexion.php

// 1. Datos locales (XAMPP)
$local_host = "localhost";
$local_user = "root";
$local_pass = "1234"; 
$local_db   = "denisses_dream_garden";

// 2. Datos de Render / Aiven
$host = getenv('DB_HOST') ?: $local_host;
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USER') ?: $local_user;
$pass = getenv('DB_PASS') ?: $local_pass;
$db   = getenv('DB_NAME') ?: $local_db;

// 3. Construir el DSN (Data Source Name) para PDO
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

// 4. Configurar opciones de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 5. Si estamos en Render, agregar la ruta del certificado SSL
if (getenv('DB_HOST')) {
    $ssl_ca = '/etc/secrets/ca.pem';
    if (!file_exists($ssl_ca)) {
        die(json_encode(["success" => false, "message" => "Error: Certificado SSL no encontrado en " . $ssl_ca]));
    }
    $options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

// 6. Crear la conexión PDO (para compatibilidad)
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Error de conexión PDO: " . $e->getMessage()]);
    exit;
}

// 7. Crear la conexión mysqli (la que usan tus archivos)
try {
    // IMPORTANTE: Pasamos $db como 4º parámetro para que intente conectar a la BD correcta
    $conn = new mysqli($host, $user, $pass, $db, (int)$port);
    if ($conn->connect_error) {
        throw new Exception("Error mysqli: " . $conn->connect_error);
    }

    // 8. FORZAR la selección de la base de datos (por si acaso)
    // Esto es lo que soluciona el error de 'defaultdb'
    if (!$conn->select_db($db)) {
        throw new Exception("No se pudo seleccionar la base de datos: " . $db);
    }
    
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
    exit;
}
?>
