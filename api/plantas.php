<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/conexion.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión con la base de datos."]);
    exit;
}

$conn->set_charset("utf8mb4");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$rawInput = file_get_contents("php://input");
$inputData = json_decode($rawInput, true);

// =========================================================================
// ACCIÓN: AGREGAR PLANTA
// =========================================================================
if ($action === 'add_planta') {
    if (!isset($inputData['nombre_comun'], $inputData['nombre_cientifico'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
        exit;
    }

    $nombreComun = trim($inputData['nombre_comun']);
    $nombreCientifico = trim($inputData['nombre_cientifico']);
    $idRegion = isset($inputData['id_region']) ? (int)$inputData['id_region'] : 1;
    $riego = isset($inputData['riego']) ? trim($inputData['riego']) : 'Moderado';
    $luz = isset($inputData['luz']) ? trim($inputData['luz']) : 'Sol directo';
    $suelo = isset($inputData['suelo']) ? trim($inputData['suelo']) : 'Suelo bien drenado';
    $cuidados = isset($inputData['cuidados']) ? trim($inputData['cuidados']) : '';
    $imagenUrl = isset($inputData['imagen_url']) ? trim($inputData['imagen_url']) : 'img/planta_default.jpg';

    $stmt = $conn->prepare("INSERT INTO `Planta` (nombre_comun, nombre_cientifico, id_region, riego, luz, suelo, cuidados, imagen_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssisssss", $nombreComun, $nombreCientifico, $idRegion, $riego, $luz, $suelo, $cuidados, $imagenUrl);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Planta registrada con éxito."]);
            exit;
        }
    }
    echo json_encode(["success" => false, "message" => "Error SQL al insertar la planta."]);
    exit;
}

// =========================================================================
// ACCIÓN: ACTUALIZAR PLANTA
// =========================================================================
if ($action === 'update_planta') {
    if (!isset($inputData['id_planta'], $inputData['nombre_comun'], $inputData['nombre_cientifico'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos."]);
        exit;
    }

    $idPlanta = (int)$inputData['id_planta'];
    $nombreComun = trim($inputData['nombre_comun']);
    $nombreCientifico = trim($inputData['nombre_cientifico']);
    $idRegion = isset($inputData['id_region']) ? (int)$inputData['id_region'] : 1;
    $riego = isset($inputData['riego']) ? trim($inputData['riego']) : 'Moderado';
    $luz = isset($inputData['luz']) ? trim($inputData['luz']) : 'Sol directo';
    $suelo = isset($inputData['suelo']) ? trim($inputData['suelo']) : 'Suelo bien drenado';
    $cuidados = isset($inputData['cuidados']) ? trim($inputData['cuidados']) : '';
    $imagenUrl = isset($inputData['imagen_url']) ? trim($inputData['imagen_url']) : 'img/planta_default.jpg';

    $stmt = $conn->prepare("UPDATE `Planta` SET nombre_comun = ?, nombre_cientifico = ?, id_region = ?, riego = ?, luz = ?, suelo = ?, cuidados = ?, imagen_url = ? WHERE id_planta = ?");
    if ($stmt) {
        $stmt->bind_param("ssisssssi", $nombreComun, $nombreCientifico, $idRegion, $riego, $luz, $suelo, $cuidados, $imagenUrl, $idPlanta);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Planta actualizada."]);
            exit;
        }
    }
    echo json_encode(["success" => false, "message" => "Error al actualizar."]);
    exit;
}

// =========================================================================
// ACCIÓN: ELIMINAR PLANTA
// =========================================================================
if ($action === 'delete_planta') {
    if (isset($inputData['id_planta'])) {
        $idPlanta = (int)$inputData['id_planta'];
        $conn->query("DELETE FROM `Planta` WHERE id_planta = {$idPlanta}");
        echo json_encode(["success" => true, "message" => "Planta eliminada."]);
        exit;
    }
}

// =========================================================================
// OBTENER TODAS LAS PLANTAS (Ya no se vuelven a insertar las base)
// =========================================================================
$query = "SELECT id_planta, nombre_comun, nombre_cientifico, id_region, riego, luz, suelo, cuidados, imagen_url,
          CASE id_region 
            WHEN 1 THEN 'Central'
            WHEN 2 THEN 'Occidental'
            WHEN 3 THEN 'Oriental'
            ELSE 'Central'
          END AS region 
          FROM `Planta` 
          ORDER BY id_planta ASC";

$res = $conn->query($query);
$plantas = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $plantas[] = $row;
    }
}

echo json_encode(["success" => true, "plantas" => $plantas]);
?>
