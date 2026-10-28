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

// 3. Crear la conexión mysqli FORZANDO la base de datos correcta
try {
    // El cuarto parámetro ($db) es crucial aquí.
    $conn = new mysqli($host, $user, $pass, $db, (int)$port);
    
    if ($conn->connect_error) {
        throw new Exception("Error mysqli: " . $conn->connect_error);
    }

    // 4. FORZAR la selección de la base de datos por si acaso
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
