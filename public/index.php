<?php
header("Content-Type: application/json");

require_once '../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

$database = new Database();
$pdo = $database->getConnection();

if ($uri === '/api/events') {
    require_once '../api/events.php';
} elseif ($uri === '/api/bookings') {
    require_once '../api/bookings.php';
} elseif ($uri === '/api/users') {
    require_once '../api/users.php';
} else {
    http_response_code(404);
    echo json_encode(["message" => "Invalid endpoint"]);
}
