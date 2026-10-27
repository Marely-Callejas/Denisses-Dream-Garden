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

// 3. Iniciar conexión
$conn = mysqli_init();

if (getenv('DB_HOST')) {
    // Si estamos en Render, configuramos SSL
    // Usamos MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT para evitar rechazos por el certificado
    $ssl_flag = MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
} else {
    $ssl_flag = 0;
}

// 4. Intentar conectar
$conexion = mysqli_real_connect($conn, $host, $user, $pass, $db, (int)$port, NULL, $ssl_flag);

if (!$conexion) {
    // ESTO NOS DIRÁ EL ERROR EXACTO
    die("ERROR DE CONEXIÓN: " . mysqli_connect_error());
}

// 5. Charset
mysqli_set_charset($conn, "utf8mb4");
?>
