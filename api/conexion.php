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

// 3. Crear la conexión mysqli
$conn = mysqli_init();

if (!$conn) {
    die(json_encode(["success" => false, "message" => "Error al inicializar mysqli."]));
}

// 4. Configurar SSL si estamos en Render
if (getenv('DB_HOST')) {
    $ssl_ca = '/etc/secrets/ca.pem';
    if (!file_exists($ssl_ca)) {
        die(json_encode(["success" => false, "message" => "Error: Certificado SSL no encontrado."]));
    }
    mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);
}

// 5. Conectar a la base de datos (forzando SSL en Render)
$conexion_exitosa = @mysqli_real_connect($conn, $host, $user, $pass, $db, (int)$port, NULL, MYSQLI_CLIENT_SSL);

if (!$conexion_exitosa) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Error de conexión: " . mysqli_connect_error()]);
    exit;
}

// 6. ¡ESTA ES LA LÍNEA CLAVE! Forzar la base de datos correcta
if (!$conn->select_db($db)) {
    die(json_encode(["success" => false, "message" => "No se pudo seleccionar la base de datos: " . $db]));
}

// 7. Establecer el charset
mysqli_set_charset($conn, "utf8mb4");
?>
