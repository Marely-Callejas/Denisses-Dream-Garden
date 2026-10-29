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
// INSERTAR PREGUNTAS BASE SI LA TABLA ESTÁ VACÍA
// =========================================================================
$checkPreguntas = $conn->query("SELECT COUNT(*) as total FROM `Pregunta`");
if ($checkPreguntas) {
    $rowCount = $checkPreguntas->fetch_assoc();
    if ($rowCount['total'] == 0) {
        $preguntasBase = [
            ["¿Cuál es el árbol nacional de El Salvador?", "Maquilíshuat", "Bálsamo", "Conacaste", "A"],
            ["¿En qué región prospera principalmente el Cafeto en suelo volcánico?", "Occidental", "Oriental", "Central", "A"],
            ["¿Qué enredadera nativa florece en clima cálido y sus botones florales son ingrediente clave en las pupusas?", "Loroco", "Pacaya", "Chichipince", "A"],
            ["¿De qué planta de la zona oriental se aprovecha tanto su fruta jugosa como su semilla o nuez?", "Arrayán", "Marañón", "Nance", "B"],
            ["¿De cuál fruto de cáscara leñosa se fabrican tradicionalmente los huacales en el campo salvadoreño?", "Carao", "Copinol", "Jícaro o Morro", "C"],
            ["¿De qué palma con espinas de las zonas cálidas salvadoreñas se extrae jugo para preparar chicha?", "Palma Coyol", "Pacaya", "Izote", "A"],
            ["¿Qué fruta pequeña y amarilla de aroma fuerte se acostumbra vender curtida en botellas de vidrio?", "Arrayán", "Marañón", "Nance", "C"],
            ["¿Qué arbusto silvestre de El Salvador se cubre de vistosas flores amarillas en forma de campana a la orilla de los caminos?", "San Andrés", "Cortéz Blanco", "Hortensia", "A"],
            ["¿De cuál fruto histórico originario de Mesoamérica se sacan las semillas para elaborar chocolate artesanal?", "Marañón", "Cacao", "Cafeto", "B"]
        ];

        foreach ($preguntasBase as $p) {
            $stmtP = $conn->prepare("INSERT INTO `Pregunta` (enunciado, id_region) VALUES (?, 1)");
            if ($stmtP) {
                $stmtP->bind_param("s", $p[0]);
                $stmtP->execute();
                $idP = $conn->insert_id;

                $stmtR = $conn->prepare("INSERT INTO `Respuesta` (id_pregunta, texto_respuesta, es_correcta) VALUES (?, ?, ?)");
                if ($stmtR) {
                    $esA = ($p[4] == 'A') ? 1 : 0;
                    $esB = ($p[4] == 'B') ? 1 : 0;
                    $esC = ($p[4] == 'C') ? 1 : 0;

                    $stmtR->bind_param("isi", $idP, $p[1], $esA); $stmtR->execute();
                    $stmtR->bind_param("isi", $idP, $p[2], $esB); $stmtR->execute();
                    $stmtR->bind_param("isi", $idP, $p[3], $esC); $stmtR->execute();
                }
            }
        }
    }
}

// =========================================================================
// OBTENER TODAS LAS PREGUNTAS DEL QUIZ
// =========================================================================
if ($action === 'get_preguntas' || empty($action)) {
    $resPreguntas = $conn->query("SELECT id_pregunta, enunciado FROM `Pregunta` ORDER BY id_pregunta ASC");
    $preguntas = [];

    if ($resPreguntas && $resPreguntas->num_rows > 0) {
        while ($rowPregunta = $resPreguntas->fetch_assoc()) {
            $idPregunta = (int)$rowPregunta['id_pregunta'];
            
            $resRespuestas = $conn->query("SELECT id_respuesta, texto_respuesta, es_correcta FROM `Respuesta` WHERE id_pregunta = {$idPregunta} ORDER BY id_respuesta ASC");
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
// GUARDAR PUNTAJE DEL QUIZ
// =========================================================================
if ($action === 'save_score' || (isset($inputData['id_usuario']) && isset($inputData['puntaje']) && !isset($_GET['action']))) {
    if (!isset($inputData['id_usuario'], $inputData['puntaje'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos para guardar el puntaje."]);
        exit;
    }

    $idUsuario = (int)$inputData['id_usuario'];
    $puntaje = (int)$inputData['puntaje'];
    $totalPreguntas = isset($inputData['total_preguntas']) ? (int)$inputData['total_preguntas'] : 0;

    $stmt = $conn->prepare("INSERT INTO `Quiz` (id_usuario, puntaje, total_preguntas, fecha_intento) VALUES (?, ?, ?, NOW())");
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
// AGREGAR NUEVA PREGUNTA
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

    $stmtP = $conn->prepare("INSERT INTO `Pregunta` (enunciado, id_region) VALUES (?, 1)");
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

    $stmtR = $conn->prepare("INSERT INTO `Respuesta` (id_pregunta, texto_respuesta, es_correcta) VALUES (?, ?, ?)");
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
// ACTUALIZAR PREGUNTA
// =========================================================================
if ($action === 'update_pregunta') {
    if (!isset($inputData['id_pregunta'], $inputData['enunciado'], $inputData['opciones'])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos para actualizar."]);
        exit;
    }

    $idPregunta = (int)$inputData['id_pregunta'];
    $enunciado = trim($inputData['enunciado']);
    $opciones = $inputData['opciones'];

    $stmtP = $conn->prepare("UPDATE `Pregunta` SET enunciado = ? WHERE id_pregunta = ?");
    if ($stmtP) {
        $stmtP->bind_param("si", $enunciado, $idPregunta);
        $stmtP->execute();
    }

    $conn->query("DELETE FROM `Respuesta` WHERE id_pregunta = {$idPregunta}");

    $stmtR = $conn->prepare("INSERT INTO `Respuesta` (id_pregunta, texto_respuesta, es_correcta) VALUES (?, ?, ?)");
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
// ELIMINAR PREGUNTA
// =========================================================================
if ($action === 'delete_pregunta') {
    if (isset($inputData['id_pregunta'])) {
        $idPregunta = (int)$inputData['id_pregunta'];
        
        $stmt = $conn->prepare("DELETE FROM `Pregunta` WHERE id_pregunta = ?");
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
