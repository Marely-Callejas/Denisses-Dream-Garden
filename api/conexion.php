<?php
// api/conexion.php

// 1. Configuración para LOCAL (XAMPP) - Solo se usará si no hay variables de entorno
$local_host = "localhost";
$local_user = "root";
$local_pass = "1234"; 
$local_db   = "denisses_dream_garden";

// 2. Intentar leer variables de entorno (Render / Aiven)
$host = getenv('DB_HOST') ?: $local_host;
$port = getenv('DB_PORT') ?: '3306'; // 3306 es el puerto por defecto de MySQL local
$user = getenv('DB_USER') ?: $local_user;
$pass = getenv('DB_PASS') ?: $local_pass;
$db   = getenv('DB_NAME') ?: $local_db;

// 3. Configurar DSN y Opciones
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 4. Si estamos en Render (Aiven), agregar configuración SSL obligatoria
if (getenv('DB_HOST')) {
    // Render guarda los "Secret Files" en /etc/secrets/ (aquí estará nuestro ca.pem)
    $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/secrets/ca.pem';
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; // Para evitar problemas de validación de host
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Compatibilidad: Tu código antiguo usa $conn (mysqli). 
    // Para no reescribir todos tus archivos PHP, crearemos una conexión mysqli simulada.
    // NOTA: Lo ideal es migrar todo a PDO, pero esto te salvará ahora.
    $conn = new mysqli($host, $user, $pass, $db, (int)$port);
    
    if ($conn->connect_error) {
        throw new Exception("Error mysqli: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

} catch (PDOException $e) {
    // Si falla PDO, intentamos mysqli como respaldo (útil en local si PDO no está activo)
    $conn = @new mysqli($host, $user, $pass, $db, (int)$port);
    if ($conn->connect_error) {
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
        exit;
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    exit;
}
?>
