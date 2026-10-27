<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usamos __DIR__ para que siempre encuentre conexion.php sin importar desde dónde se llame
require_once __DIR__ . '/conexion.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión con la base de datos."]);
    exit;
}

$conn->set_charset("utf8");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$rawInput = file_get_contents("php://input");
$inputData = json_decode($rawInput, true);

// =========================================================================
// ACCIÓN: AGREGAR PLANTA
// =========================================================================
if ($action === 'add_planta') {
    if (!isset($inputData['nombre_comun'], $inputData['nombre_cientifico'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios para registrar la planta."]);
        exit;
    }

    $nombreComun = trim($inputData['nombre_comun']);
    $nombreCientifico = trim($inputData['nombre_cientifico']);
    $idRegion = isset($inputData['id_region']) ? (int)$inputData['id_region'] : 1;
    $riego = isset($inputData['riego']) && !empty(trim($inputData['riego'])) ? trim($inputData['riego']) : 'Moderado';
    $luz = isset($inputData['luz']) && !empty(trim($inputData['luz'])) ? trim($inputData['luz']) : 'Sol directo';
    $suelo = isset($inputData['suelo']) && !empty(trim($inputData['suelo'])) ? trim($inputData['suelo']) : 'Suelo bien drenado';
    $cuidados = isset($inputData['cuidados']) ? trim($inputData['cuidados']) : '';
    $imagenUrl = isset($inputData['imagen_url']) && !empty(trim($inputData['imagen_url'])) ? trim($inputData['imagen_url']) : 'img/planta_default.jpg';

    $stmt = $conn->prepare("INSERT INTO planta (nombre_comun, nombre_cientifico, id_region, riego, luz, suelo, cuidados, imagen_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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
if ($action === 'update_planta' || (isset($inputData['id_planta']) && !isset($_GET['action']))) {
    if (!isset($inputData['id_planta'], $inputData['nombre_comun'], $inputData['nombre_cientifico'])) {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios para actualizar."]);
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

    $stmt = $conn->prepare("UPDATE planta SET nombre_comun = ?, nombre_cientifico = ?, id_region = ?, riego = ?, luz = ?, suelo = ?, cuidados = ?, imagen_url = ? WHERE id_planta = ?");
    if ($stmt) {
        $stmt->bind_param("ssisssssi", $nombreComun, $nombreCientifico, $idRegion, $riego, $luz, $suelo, $cuidados, $imagenUrl, $idPlanta);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Planta actualizada correctamente."]);
            exit;
        }
    }

    echo json_encode(["success" => false, "message" => "Error al actualizar en la base de datos."]);
    exit;
}

// =========================================================================
// ACCIÓN: ELIMINAR PLANTA
// =========================================================================
if ($action === 'delete_planta') {
    if (isset($inputData['id_planta'])) {
        $idPlanta = (int)$inputData['id_planta'];
        $conn->query("DELETE FROM planta WHERE id_planta = {$idPlanta}");
        echo json_encode(["success" => true, "message" => "Planta eliminada correctamente."]);
        exit;
    }
}

// =========================================================================
// GARANTIZAR LAS 32 PLANTAS BASE
// =========================================================================
function asegurarPlantasBase($conn) {
    $plantasBase = [
        [1, 'Conacaste', 'Enterolobium cyclocarpum', 1, 'Bajo', 'Sol directo', 'Suelo resistente', 'Árbol frondoso característico del paisaje salvadoreño.', 'img/conacaste.jpg'],
        [2, 'Jícaro o Morro', 'Crescentia alata', 3, 'Bajo', 'Sol directo', 'Suelo arcilloso', 'Típico del clima seco oriental. De su fruto se elabora horchata y artesanías.', 'img/morro.jpg_large'],
        [3, 'Madrecacao', 'Gliricidia sepium', 3, 'Bajo', 'Sol directo', 'Adaptable a suelos pobres', 'Abundante en el campo salvadoreño. Utilizado como sombra para el café.', 'img/madrecacao.jpg'],
        [4, 'Marañón', 'Anacardium occidentale', 3, 'Bajo', 'Sol directo', 'Suelo arenoso / pobre', 'Cultivo emblemático oriental. Se consume su pseudofruto y semilla.', 'img/marañon.jpg'],
        [5, 'Loroco', 'Fernaldia pandurata', 3, 'Moderado', 'Sol / Sombra', 'Suelo fértil y cálido', 'Enredadera nativa muy apreciada en la gastronomía salvadoreña.', 'img/loroco.jpg'],
        [6, 'Coyol', 'Acrocomia aculeata', 3, 'Bajo', 'Sol directo', 'Suelo seco y pedregoso', 'Palma espinosa típica de las zonas cálidas del país.', 'img/coyol.jpg'],
        [7, 'Nance', 'Byrsonima crassifolia', 3, 'Bajo', 'Sol directo', 'Suelo ácido y pedregoso', 'Muy común en las zonas cálidas. Fruto pequeño de aroma fuerte.', 'img/nance.jpg'],
        [8, 'Jocote de Corona', 'Spondias purpurea', 3, 'Bajo', 'Sol directo', 'Suelo pedregoso y seco', 'Muy popular en la temporada de verano salvadoreña.', 'img/jocote.jpg'],
        [9, 'Cortéz Blanco', 'Handroanthus chrysanthus', 3, 'Bajo', 'Sol directo', 'Suelo bien drenado', 'En temporada seca se cubre por completo de hermosas flores amarillas.', 'img/cortes blanco.jpg'],
        [10, 'Carao', 'Cassia grandis', 3, 'Bajo', 'Sol directo', 'Suelo seco', 'Árbol oriental frondoso de abundantes flores rosadas y vaina leñosa.', 'img/carao.jpg'],
        [11, 'Maquilíshuat', 'Tabebuia rosea', 1, 'Moderado', 'Sol directo', 'Suelo bien drenado', 'Árbol Nacional de El Salvador. Produce vistosas flores rosadas.', 'img/maquilishuat.png'],
        [12, 'Izote', 'Yucca aloifolia', 1, 'Bajo', 'Sol directo / Sombra', 'Suelo arenoso', 'Flor Nacional de El Salvador. Muy utilizada en platillos locales.', 'img/Flor de Izote.jpg'],
        [13, 'Copinol', 'Hymenaea courbaril', 1, 'Bajo', 'Sol directo', 'Suelo arcilloso', 'Árbol frondoso nativo con semillas comestibles encarradas en cascarón duro.', 'img/Hymenaea-courbaril.jpg'],
        [14, 'San Andrés', 'Tecoma stans', 1, 'Moderado', 'Sol directo', 'Suelo bien drenado', 'Arbusto de bellas flores amarillas acampanadas.', 'img/San andres.webp'],
        [15, 'Chichipince', 'Hamelia patens', 1, 'Moderado', 'Sol / Sombra parcial', 'Suelo húmedo', 'Planta medicinal nativa de flores tubulares rojas que atraen colibríes.', 'img/chichipince.jpg'],
        [16, 'Pito', 'Erythrina berteroana', 1, 'Moderado', 'Sol directo', 'Suelo fértil', 'Árbol de flores rojas comúnmente consumidas en sopas y guisados.', 'img/arból de pitos.jpg'],
        [17, 'Helecho Nido de Ave', 'Asplenium nidus', 1, 'Frecuente', 'Sombra / Luz filtrada', 'Rico en materia orgánica', 'Cultivado y nativo en zonas boscosas de sotobosque.', 'img/Helecho.jpg'],
        [18, 'Barba de Viejo', 'Tillandsia usneoides', 1, 'Frecuente', 'Luz indirecta', 'Epífita (Cuelga de ramas)', 'Planta aérea que absorbe nutrientes de la humedad del aire.', 'img/Barba de viejo.webp'],
        [19, 'Jiote', 'Bursera simaruba', 1, 'Muy Bajo', 'Sol directo', 'Suelo árido / pedregoso', 'Famoso por su corteza cobriza que se exfolia naturalmente.', 'img/jiote.jpg'],
        [20, 'Tempisque', 'Sideroxylon capiri', 1, 'Bajo', 'Sol directo', 'Suelo franco', 'Árbol grande de madera resistente y frutos pequeños comestibles.', 'img/tempisque.jpg'],
        [21, 'Bálsamo', 'Myroxylon balsamum', 1, 'Moderado', 'Sol directo', 'Suelo profundo y húmedo', 'Exclusivo y nativo de la Cordillera del Bálsamo en El Salvador.', 'img/balsamo.jpg'],
        [22, 'Cafeto', 'Coffea arabica', 1, 'Abundante', 'Sombra parcial', 'Suelo volcánico rico', 'Prospera en los cordones cordilleranos de alta elevación.', 'img/cafe.jpg'],
        [23, 'Cacao', 'Theobroma cacao', 1, 'Alto', 'Sombra parcial', 'Suelo arcilloso-arenoso', 'Cultivo ancestral mesoamericano usado para elaborar chocolate.', 'img/cacao.jpg'],
        [24, 'Aguacate Criollo', 'Persea americana', 2, 'Moderado', 'Sol directo', 'Suelo profundo y drenado', 'Árbol frondoso tradicional de la zona occidental.', 'img/aguacate.jpg'],
        [25, 'Cartucho', 'Zantedeschia aethiopica', 1, 'Abundante', 'Sombra parcial', 'Suelo muy húmedo', 'Muy cultivado en las zonas frescas y montañosas.', 'img/cartucho.jpg'],
        [26, 'Hortensia', 'Hydrangea macrophylla', 2, 'Frecuente', 'Sombra parcial', 'Suelo ácido', 'Planta icónica de los climas fríos y neblinosos del occidente.', 'img/hortencia.webp'],
        [27, 'Pino de Las Neblinas', 'Pinus oocarpa', 2, 'Moderado', 'Sol directo', 'Suelo ácido/volcánico', 'Árbol dominante en los pinares de alta montaña.', 'img/pino.jpg'],
        [28, 'Zapote', 'Pouteria sapota', 2, 'Moderado', 'Sol directo', 'Suelo profundo y húmedo', 'Árbol frutal de gran tamaño con pulpa dulce y anaranjada.', 'img/zapote.jpg'],
        [29, 'Pacaya', 'Chamaedorea tepejilote', 2, 'Abundante', 'Sombra densa', 'Suelo rico en humus', 'Palma del sotobosque cuya inflorescencia es consumida en la gastronomía.', 'img/pacaya.webp'],
        [30, 'Arrayán', 'Psidium saratogense', 2, 'Moderado', 'Sol directo', 'Suelo volcánico', 'Arbusto de frutos ácidos aromatizados usados en refrescos y dulces.', 'img/arrayan.jpg'],
        [31, 'Chipilín', 'Crotalaria longirostrata', 1, 'Moderado', 'Sol directo', 'Suelo fértil y bien drenado', 'Hierba nativa de uso culinario tradicional en sopas y tamales.', 'img/chipilin.jpg'],
        [32, 'Buganvilla / Veranera', 'Bougainvillea spectabilis', 1, 'Moderado', 'Sol directo', 'Suelo bien drenado', 'Enredadera ornamental muy popular en jardines y vallas.', 'img/gubamvilias.jpg']
    ];

    foreach ($plantasBase as $p) {
        $stmt = $conn->prepare("INSERT INTO planta (id_planta, nombre_comun, nombre_cientifico, id_region, riego, luz, suelo, cuidados, imagen_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombre_comun = VALUES(nombre_comun), nombre_cientifico = VALUES(nombre_cientifico), id_region = VALUES(id_region), riego = VALUES(riego), luz = VALUES(luz), suelo = VALUES(suelo), cuidados = VALUES(cuidados), imagen_url = VALUES(imagen_url)");
        if ($stmt) {
            $stmt->bind_param("ississsss", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8]);
            $stmt->execute();
        }
    }
}

asegurarPlantasBase($conn);

$query = "SELECT id_planta, nombre_comun, nombre_cientifico, id_region, riego, luz, suelo, cuidados, imagen_url,
          CASE id_region 
            WHEN 1 THEN 'Central'
            WHEN 2 THEN 'Occidental'
            WHEN 3 THEN 'Oriental'
            ELSE 'Central'
          END AS region 
          FROM planta 
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