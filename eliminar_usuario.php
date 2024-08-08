<?php
require_once 'conexion.php'; // Asegúrate de que 'conexion.php' esté configurado para usar PDO
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];

    try {
        // Preparar y ejecutar la consulta para eliminar el usuario
        $consulta = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $consulta->bindParam(1, $usuario_id, PDO::PARAM_INT);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $mensaje = "Usuario eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar el usuario.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al ejecutar la consulta: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos (opcional, PDO generalmente maneja esto automáticamente)
    $pdo = null;

    header("Location: usuarios.php?mensaje=" . urlencode($mensaje));
    exit;
}
?>