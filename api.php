<?php
header("Content-Type: application/json");

// Incluir el archivo de conexión a la base de datos
require_once(__DIR__ . "/conexion.php");

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener el endpoint solicitado
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// Manejar las solicitudes según el endpoint
switch ($endpoint) {
    case 'appointments':
        require_once(__DIR__ . "/appointments.php");
        break;
    case 'users':
        require_once(__DIR__ . "/users.php");
        break;
    default:
        echo json_encode(["message" => "Endpoint no encontrado"]);
        break;
}
?>