<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usamos __DIR__ para asegurar que siempre encuentre conexion.php
require_once __DIR__ . '/conexion.php';

// Verificación de seguridad
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión con la base de datos."]);
    exit;
}

$conn->set_charset("utf8");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$rawInput = file_get_contents("php://input");
$inputData = json_decode($rawInput, true);

// =========================================================================
// OBTENER TODAS LAS PREGUNTAS DEL QUIZ (Haciendo JOIN con Respuesta)
// =========================================================================
if ($action === 'get_preguntas' || empty($action)) {
    // Consultamos todas las preguntas
    $resPreguntas = $conn->query("SELECT id_pregunta, enunciado FROM pregunta ORDER BY id_pregunta ASC");
    
    $preguntas = [];

    if ($resPreguntas && $resPreguntas->num_rows > 0) {
        while ($rowPregunta = $resPreguntas->fetch_assoc()) {
            $idPregunta = (int)$rowPregunta['id_pregunta'];
            
            // Para cada pregunta, buscamos sus respuestas
            $resRespuestas = $conn->query("SELECT id_respuesta, texto_respuesta, es_correcta FROM respuesta WHERE id_pregunta = {$idPregunta} ORDER BY id_respuesta ASC");
            
            $opciones = [];
            $letras = ['A', 'B', 'C', 'D', 'E'];
            $contador = 0;

            if ($resRespuestas && $resRespuestas->num_rows > 0) {
                while ($rowResp = $resRespuestas->fetch_assoc()) {
                    $letra = isset($letras[$contador]) ? $letras[$contador] : 'X';
                    $opciones[] = [
                        "id" => (int)$rowResp['id_respuesta'],
                        "letter" => $letra,
                        "text" => $rowResp['texto_respuesta'],
                        "correct" => ($rowResp['es_correcta'] == 1)
                    ];
                    $contador++;
                }
            }

            $preguntas[] = [
                "id" => $idPregunta,
                "question" => $rowPregunta['enunciado'],
                "options" => $opciones
            ];
        }
    }

    echo json_encode(["success" => true, "preguntas" => $preguntas]);
    exit;
}

// =========================================================================
// GUARDAR PUNTAJE DEL QUIZ (Cuando el usuario termina)
// =========================================================================
if ($action === 'save_score' || (isset($inputData['id_usuario']) && isset($inputData['puntaje']) && !isset($_GET['action']))) {
    if (!isset($inputData['id_usuario'], $inputData['puntaje'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos para guardar el puntaje."]);
        exit;
    }

    $idUsuario = (int)$inputData['id_usuario'];
    $puntaje = (int)$inputData['puntaje'];
    $totalPreguntas = isset($inputData['total_preguntas']) ? (int)$inputData['total_preguntas'] : 0;

    // Ajustamos el nombre de la columna según tu SQL (fecha_intento)
    $stmt = $conn->prepare("INSERT INTO quiz (id_usuario, puntaje, total_preguntas, fecha_intento) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("iii", $idUsuario, $puntaje, $totalPreguntas);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Puntaje guardado correctamente."]);
            exit;
        }
    }

    echo json_encode(["success" => false, "message" => "Error al guardar el puntaje."]);
    exit;
}

// =========================================================================
// AGREGAR NUEVA PREGUNTA (Admin)
// =========================================================================
if ($action === 'add_pregunta') {
    if (!isset($inputData['enunciado'], $inputData['opciones'])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos para agregar la pregunta."]);
        exit;
    }

    $enunciado = trim($inputData['enunciado']);
    $opciones = $inputData['opciones'];

    if (count($opciones) < 3) {
        echo json_encode(["success" => false, "message" => "Se requieren al menos 3 opciones."]);
        exit;
    }

    // Insertar la pregunta
    $stmtP = $conn->prepare("INSERT INTO pregunta (enunciado, id_region) VALUES (?, 1)");
    if (!$stmtP) {
        echo json_encode(["success" => false, "message" => "Error al preparar la pregunta."]);
        exit;
    }

    $stmtP->bind_param("s", $enunciado);
    if (!$stmtP->execute()) {
        echo json_encode(["success" => false, "message" => "Error al guardar la pregunta."]);
        exit;
    }

    $idPreguntaNueva = $conn->insert_id;

    // Insertar las respuestas
    $stmtR = $conn->prepare("INSERT INTO respuesta (id_pregunta, texto_respuesta, es_correcta) VALUES (?, ?, ?)");
    if ($stmtR) {
        foreach ($opciones as $opcion) {
            $texto = trim($opcion['texto']);
            $correcta = !empty($opcion['correcta']) ? 1 : 0;
            $stmtR->bind_param("isi", $idPreguntaNueva, $texto, $correcta);
            $stmtR->execute();
        }
    }

    echo json_encode(["success" => true, "message" => "Pregunta agregada correctamente."]);
    exit;
}

// =========================================================================
// ACTUALIZAR PREGUNTA (Admin)
// =========================================================================
if ($action === 'update_pregunta') {
    if (!isset($inputData['id_pregunta'], $inputData['enunciado'], $inputData['opciones'])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos para actualizar."]);
        exit;
    }

    $idPregunta = (int)$inputData['id_pregunta'];
    $enunciado = trim($inputData['enunciado']);
    $opciones = $inputData['opciones'];

    // Actualizar el enunciado
    $stmtP = $conn->prepare("UPDATE pregunta SET enunciado = ? WHERE id_pregunta = ?");
    if ($stmtP) {
        $stmtP->bind_param("si", $enunciado, $idPregunta);
        $stmtP->execute();
    }

    // Eliminar las respuestas anteriores
    $conn->query("DELETE FROM respuesta WHERE id_pregunta = {$idPregunta}");

    // Insertar las nuevas respuestas
    $stmtR = $conn->prepare("INSERT INTO respuesta (id_pregunta, texto_respuesta, es_correcta) VALUES (?, ?, ?)");
    if ($stmtR) {
        foreach ($opciones as $opcion) {
            $texto = trim($opcion['texto']);
            $correcta = !empty($opcion['correcta']) ? 1 : 0;
            $stmtR->bind_param("isi", $idPregunta, $texto, $correcta);
            $stmtR->execute();
        }
    }

    echo json_encode(["success" => true, "message" => "Pregunta actualizada correctamente."]);
    exit;
}

// =========================================================================
// ELIMINAR PREGUNTA (Admin)
// =========================================================================
if ($action === 'delete_pregunta') {
    if (isset($inputData['id_pregunta'])) {
        $idPregunta = (int)$inputData['id_pregunta'];
        
        // Al eliminar la pregunta, las respuestas se eliminan por FK en cascada (según tu SQL)
        $stmt = $conn->prepare("DELETE FROM pregunta WHERE id_pregunta = ?");
        if ($stmt) {
            $stmt->bind_param("i", $idPregunta);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Pregunta eliminada."]);
                exit;
            }
        }
        echo json_encode(["success" => false, "message" => "No se pudo eliminar la pregunta."]);
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Acción no válida en quiz.php."]);
?>
