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

    $dni      = filter_input(INPUT_POST, 'dni',      FILTER_UNSAFE_RAW);
    $nombre   = filter_input(INPUT_POST, 'nombre',   FILTER_UNSAFE_RAW);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_UNSAFE_RAW);

    $dni      = $dni      ? htmlspecialchars(trim($dni),      ENT_QUOTES, 'UTF-8') : null;
    $nombre   = $nombre   ? htmlspecialchars(trim($nombre),   ENT_QUOTES, 'UTF-8') : null;
    $apellido = $apellido ? htmlspecialchars(trim($apellido), ENT_QUOTES, 'UTF-8') : null;

    if (empty($dni) || empty($nombre) || empty($apellido)) {
        http_response_code(400);
        echo json_encode(["error" => "Todos los campos son obligatorios."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO Cliente (Dni, NombreCompleto, Apellido) VALUES (?, ?, ?)");
    $stmt->execute([$dni, $nombre, $apellido]);

    http_response_code(201);
    echo json_encode([
        "mensaje"  => "Cliente añadido exitosamente",
        "dni"      => $dni,
        "nombre"   => $nombre,
        "apellido" => $apellido
    ]);

} catch (PDOException $e) {
    error_log("Error al añadir cliente: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Error al procesar la solicitud."]);
}
?>