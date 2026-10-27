<?php
// api/conexion.php

// 1. Configuración para LOCAL (XAMPP)
$local_host = "localhost";
$local_user = "root";
$local_pass = "1234"; 
$local_db   = "denisses_dream_garden";

// 2. Leer variables de entorno (Render / Aiven)
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

// 4. Configurar SSL si estamos en Render (Aiven lo requiere)
if (getenv('DB_HOST')) {
    // La ruta correcta para los Secret Files en Render es /etc/secrets/
    mysqli_ssl_set($conn, NULL, NULL, '/etc/secrets/ca.pem', NULL, NULL);
}

// 5. Conectar a la base de datos
// Nota: Usamos @ para suprimir warnings y manejar el error nosotros mismos.
$conexion_exitosa = @mysqli_real_connect($conn, $host, $user, $pass, $db, (int)$port);

if (!$conexion_exitosa) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false, 
        "message" => "Error de conexión: " . mysqli_connect_error()
    ]);
    exit;
}

// 6. Establecer el charset
mysqli_set_charset($conn, "utf8mb4");
?>
