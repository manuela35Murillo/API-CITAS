<?php
require_once 'conexion.php'; // Asegúrate de que este archivo incluya la conexión PDO
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cita_id'])) {
    $cita_id = $_POST['cita_id'];
    $usuario_id = $_SESSION['usuario_id'];

    try {
        // Preparar y ejecutar la consulta con PDO
        $consulta = $pdo->prepare("DELETE FROM citas WHERE id = :cita_id AND usuario_id = :usuario_id");
        $consulta->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $consulta->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $mensaje = "¡La cita ha sido cancelada exitosamente!";
            $tipo_mensaje = "success"; // Para estilos específicos de éxito
        } else {
            $mensaje = "No se encontró la cita o no tienes permiso para cancelarla.";
            $tipo_mensaje = "error"; // Para estilos específicos de error
        }
    } catch (PDOException $e) {
        $mensaje = 'Error al cancelar la cita: ' . $e->getMessage();
        $tipo_mensaje = "error";
    }

    header("Location: ver_citas.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit;
} else {
    header("Location: ver_citas.php?mensaje=" . urlencode("ID de cita no proporcionado.") . "&tipo=" . urlencode("error"));
    exit;
}
?>