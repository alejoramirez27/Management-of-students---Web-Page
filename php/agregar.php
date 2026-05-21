<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Use POST."]);
    exit;
}

$host     = getenv('DB_HOST')     ?: "localhost";
$dbname   = getenv('DB_NAME')     ?: "Universidad";
$user     = getenv('DB_USER')     ?: "postgres";
$password = getenv('DB_PASSWORD') ?: "alejo0127";

$dsn = "pgsql:host=$host;dbname=$dbname";

try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW);
    $nombre = $nombre ? htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8') : null;
    $edad   = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_NUMBER_INT);

    if (empty($nombre) || $edad === false || $edad === null || $edad < 0 || $edad > 150) {
        http_response_code(400);
        echo json_encode(["error" => "Nombre requerido y edad entre 0 y 150."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO estudiantes (nombre, edad) VALUES (?, ?) RETURNING id");
    $stmt->execute([$nombre, (int)$edad]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(201);
    echo json_encode([
        "mensaje" => "Estudiante añadido exitosamente",
        "id"      => $row['id'],
        "nombre"  => $nombre,
        "edad"    => (int)$edad
    ]);

} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Error al añadir el estudiante."]);
}
?>