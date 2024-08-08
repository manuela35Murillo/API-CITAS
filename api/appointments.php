<?php
header("Content-Type: application/json");

// Incluir el archivo de conexión
require_once(__DIR__ . "/../conexion.php");

// Obtener el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Inicializar respuesta
$response = [];

try {
    // Obtener el parámetro 'endpoint' de la consulta
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

    if ($endpoint === 'usuarios') {
        switch ($method) {
            case 'GET':
                // Preparar y ejecutar la consulta para obtener usuarios
                $stmt = $pdo->prepare("SELECT * FROM usuarios");
                $stmt->execute();
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response = [
                    "status" => "success",
                    "data" => $usuarios
                ];
                break;

            case 'POST':
                // Obtener el cuerpo de la solicitud
                $data = json_decode(file_get_contents('php://input'), true);

                // Validar y asignar datos
                $nombre = $pdo->quote($data['nombre']);
                $usuario = $pdo->quote($data['usuario']);
                $email = $pdo->quote($data['email']);
                $password = $pdo->quote($data['password']);
                $direccion = $pdo->quote($data['direccion']);
                $privilegios = $pdo->quote($data['privilegios']);
                $fecha_creacion = $pdo->quote($data['fecha_creacion']);

                // Preparar y ejecutar la consulta para insertar un nuevo usuario
                $sql = "INSERT INTO usuarios (nombre, usuario, email, password, direccion, privilegios, fecha_creacion) 
                        VALUES ($nombre, $usuario, $email, $password, $direccion, $privilegios, $fecha_creacion)";
                $pdo->exec($sql);

                $response = [
                    "status" => "success",
                    "message" => "Usuario creado con éxito"
                ];
                break;

            case 'PUT':
                // Obtener el cuerpo de la solicitud
                $data = json_decode(file_get_contents('php://input'), true);

                // Validar y asignar datos
                $id = intval($data['id']);
                $nombre = $pdo->quote($data['nombre']);
                $usuario = $pdo->quote($data['usuario']);
                $email = $pdo->quote($data['email']);
                $password = $pdo->quote($data['password']);
                $direccion = $pdo->quote($data['direccion']);
                $privilegios = $pdo->quote($data['privilegios']);

                // Preparar y ejecutar la consulta para actualizar un usuario
                $sql = "UPDATE usuarios SET 
                        nombre = $nombre,
                        usuario = $usuario,
                        email = $email,
                        password = $password,
                        direccion = $direccion,
                        privilegios = $privilegios
                        WHERE id = $id";
                $pdo->exec($sql);

                $response = [
                    "status" => "success",
                    "message" => "Usuario actualizado con éxito"
                ];
                break;

            case 'DELETE':
                // Obtener el cuerpo de la solicitud
                $data = json_decode(file_get_contents('php://input'), true);

                // Validar y asignar datos
                $id = intval($data['id']);

                // Preparar y ejecutar la consulta para eliminar un usuario
                $sql = "DELETE FROM usuarios WHERE id = $id";
                $pdo->exec($sql);

                $response = [
                    "status" => "success",
                    "message" => "Usuario eliminado con éxito"
                ];
                break;

            default:
                $response = [
                    "status" => "error",
                    "message" => "Método no soportado"
                ];
                break;
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Endpoint no válido"
        ];
    }
} catch (PDOException $e) {
    $response = [
        "status" => "error",
        "message" => "Error en la base de datos: " . $e->getMessage()
    ];
}

// Enviar la respuesta como JSON
echo json_encode($response);
?>