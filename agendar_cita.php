<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];

    try {
        // Verificar si ya existe una cita para el usuario en la fecha y hora especificadas
        $consulta_existencia = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE usuario_id = :usuario_id AND fecha = :fecha_cita AND hora = :hora_cita");
        $consulta_existencia->bindParam(':usuario_id', $usuario_id);
        $consulta_existencia->bindParam(':fecha_cita', $fecha_cita);
        $consulta_existencia->bindParam(':hora_cita', $hora_cita);
        $consulta_existencia->execute();
        $cantidad_citas = $consulta_existencia->fetchColumn();

        if ($cantidad_citas > 0) {
            // Si ya existe una cita, mostrar alerta
            echo '<script>alert("Ya existe una cita para este día y esa hora.");</script>';
        } else {
            // Si no existe una cita, agregarla a la base de datos
            $consulta_agregar = $pdo->prepare("INSERT INTO citas (usuario_id, fecha, hora) VALUES (:usuario_id, :fecha_cita, :hora_cita)");
            $consulta_agregar->bindParam(':usuario_id', $usuario_id);
            $consulta_agregar->bindParam(':fecha_cita', $fecha_cita);
            $consulta_agregar->bindParam(':hora_cita', $hora_cita);
            if ($consulta_agregar->execute()) {
                header("Location: ver_citas.php");
                exit;
            } else {
                echo '<script>alert("Error al agendar la cita.");</script>';
            }
        }
    } catch (PDOException $e) {
        echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 2px solid #6A5ACD; /* Borde morado oscuro */
            border-radius: 20px; /* Bordes redondos */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #6A5ACD; /* Morado oscuro */
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        input[type="date"]:focus,
        input[type="time"]:focus {
            border-color: #6A5ACD; /* Morado oscuro */
            box-shadow: 0 0 10px rgba(106, 90, 205, 0.5);
            transform: scale(1.05);
        }

        button {
            padding: 10px 20px;
            color: white;
            background-color: #6A5ACD; /* Morado oscuro */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #483D8B; /* Morado más oscuro */
        }

        .volver-container {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #6A5ACD; /* Morado oscuro */
            text-decoration: none;
            transition: color 0.3s ease;
            cursor: pointer; /* Cambia el cursor al pasar sobre el enlace */
        }

        a:hover {
            color: #483D8B; /* Morado más oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendar Cita</h1>
        <form method="POST">
            <input type="date" name="fecha_cita" required>
            <input type="time" name="hora_cita" required>
            <button type="submit">Agendar</button>
        </form>
        <div class="volver-container">
            <a href="inicio.php">Volver</a>
        </div>
    </div>
</body>
</html>