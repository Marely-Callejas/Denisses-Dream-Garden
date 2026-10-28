<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 1. FORZAR el uso de la conexión desde conexion.php
require_once __DIR__ . '/conexion.php';

// 2. Verificación de seguridad (si conexion.php falló, detenemos todo)
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

// 3. Forzar el charset (por si acaso)
$conn->set_charset("utf8mb4");

// OBTENER LA ACCIÓN
$action = isset($_GET['action']) ? $_GET['action'] : '';
$inputData = json_decode(file_get_contents('php://input'), true);

if (empty($action) && isset($inputData['action'])) {
    $action = $inputData['action'];
}

// FUNCIÓN PARA OBTENER ESTADÍSTICAS
function obtenerEstadisticasUsuario($conn, $id_user) {
    $statsData = ['quizzes' => 0, 'score' => 0];
    $qStats = "SELECT COUNT(*) as quizzes, IFNULL(SUM(puntaje), 0) as score 
               FROM quiz 
               WHERE id_usuario = ?";
    $stmt = $conn->prepare($qStats);
    if ($stmt) {
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $resStats = $stmt->get_result();
        if ($resStats && $rowStats = $resStats->fetch_assoc()) {
            $statsData['quizzes'] = intval($rowStats['quizzes']);
            $statsData['score'] = intval($rowStats['score']);
        }
    }
    return $statsData;
}

// -------------------------------------------------------------
// 1. INICIAR SESIÓN (LOGIN)
// -------------------------------------------------------------
if ($action === 'login') {
    $correo = isset($inputData['correo']) ? trim($inputData['correo']) : '';
    $contrasena = isset($inputData['contrasena']) ? trim($inputData['contrasena']) : '';

    if (empty($correo) || empty($contrasena)) {
        echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos.']);
        exit;
    }

    // IMPORTANTE: Usamos backticks para la tabla y columna por si hay problemas de mayúsculas
    $stmt = $conn->prepare("SELECT * FROM `Usuario` WHERE `correo` = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $passBD = '';
            if (isset($user['contrasena'])) {
                $passBD = $user['contrasena'];
            } elseif (isset($user['contraseña'])) {
                $passBD = $user['contraseña'];
            }

            if ($passBD === $contrasena || password_verify($contrasena, $passBD)) {
                $id_user = intval($user['id_usuario']);
                $user['stats'] = obtenerEstadisticasUsuario($conn, $id_user);
                unset($user['contraseña'], $user['contrasena']);

                echo json_encode(['success' => true, 'user' => $user]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico no está registrado.']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Error en el servidor.']);
    exit;
}

// -------------------------------------------------------------
// 2. OBTENER ESTADÍSTICAS DEL USUARIO
// -------------------------------------------------------------
if ($action === 'get_user_stats') {
    $id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;
    if ($id_usuario > 0) {
        $stats = obtenerEstadisticasUsuario($conn, $id_usuario);
        echo json_encode(['success' => true, 'stats' => $stats]);
        exit;
    }
    echo json_encode(['success' => true, 'stats' => ['quizzes' => 0, 'score' => 0]]);
    exit;
}

// -------------------------------------------------------------
// 3. REGISTRAR UN NUEVO USUARIO
// -------------------------------------------------------------
if ($action === 'register') {
    $nombre = isset($inputData['nombre']) ? trim($inputData['nombre']) : '';
    $correo = isset($inputData['correo']) ? trim($inputData['correo']) : '';
    $contrasena = isset($inputData['contrasena']) ? trim($inputData['contrasena']) : '';

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $stmtChk = $conn->prepare("SELECT id_usuario FROM `Usuario` WHERE `correo` = ?");
    if ($stmtChk) {
        $stmtChk->bind_param("s", $correo);
        $stmtChk->execute();
        if ($stmtChk->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Este correo ya se encuentra registrado.']);
            exit;
        }
    }

    $passHash = password_hash($contrasena, PASSWORD_DEFAULT);
    // Usamos `contraseña` porque así está en tu SQL original
    $stmt = $conn->prepare("INSERT INTO `Usuario` (`nombre`, `correo`, `contraseña`, `rol`) VALUES (?, ?, ?, 'estudiante')");
    if ($stmt) {
        $stmt->bind_param("sss", $nombre, $correo, $passHash);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito.']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario en la BD.']);
    exit;
}

// -------------------------------------------------------------
// 4. OBTENER PROGRESO DE ALUMNOS (DOCENTES/ADMIN)
// -------------------------------------------------------------
if ($action === 'get_alumnos_progreso') {
    $query = "SELECT u.id_usuario, u.nombre, u.correo, 
                     COUNT(q.id_quiz) as quizzes, 
                     IFNULL(SUM(q.puntaje), 0) as puntos
              FROM `Usuario` u
              LEFT JOIN `Quiz` q ON u.id_usuario = q.id_usuario
              WHERE u.rol = 'estudiante' OR u.rol IS NULL
              GROUP BY u.id_usuario";
    $res = $conn->query($query);
    $alumnos = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $alumnos[] = [
                'id_usuario' => intval($row['id_usuario']),
                'nombre' => $row['nombre'],
                'correo' => $row['correo'],
                'quizzes' => intval($row['quizzes']),
                'puntos' => intval($row['puntos'])
            ];
        }
    }
    echo json_encode(['success' => true, 'alumnos' => $alumnos]);
    exit;
}

// -------------------------------------------------------------
// 5. GESTIÓN DE USUARIOS PARA ADMIN
// -------------------------------------------------------------
if ($action === 'get_todos_usuarios') {
    $query = "SELECT id_usuario, nombre, correo, IFNULL(rol, 'estudiante') as rol FROM `Usuario` ORDER BY id_usuario DESC";
    $res = $conn->query($query);
    $usuarios = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }
    echo json_encode(['success' => true, 'usuarios' => $usuarios]);
    exit;
}

if ($action === 'update_rol') {
    if (isset($inputData['id_usuario'], $inputData['rol'])) {
        $id_usuario = intval($inputData['id_usuario']);
        $rol = trim($inputData['rol']);
        $stmt = $conn->prepare("UPDATE `Usuario` SET rol = ? WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param("si", $rol, $id_usuario);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el rol.']);
        exit;
    }
}

if ($action === 'delete_usuario') {
    if (isset($inputData['id_usuario'])) {
        $id_usuario = intval($inputData['id_usuario']);
        $conn->query("DELETE FROM `Quiz` WHERE id_usuario = {$id_usuario}");
        $stmt = $conn->prepare("DELETE FROM `Usuario` WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id_usuario);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
exit;
?>
