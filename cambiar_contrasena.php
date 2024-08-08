<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
} else {
    header("Location: usuarios.php");
    exit;
}

// Consultar el nombre del usuario
$consulta_usuario = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$consulta_usuario->bindParam(1, $usuario_id, PDO::PARAM_INT);
$consulta_usuario->execute();
$nombre_usuario = $consulta_usuario->fetchColumn();
$consulta_usuario->closeCursor();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    
    // Actualizar la contraseña en la base de datos
    $consulta = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $consulta->bindParam(1, $nueva_contrasena);
    $consulta->bindParam(2, $usuario_id, PDO::PARAM_INT);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        $mensaje = "Contraseña actualizada exitosamente.";
    } else {
        $mensaje = "Error al actualizar la contraseña.";
    }
    $consulta->closeCursor();
    header("Location: usuarios.php?mensaje=" . urlencode($mensaje));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña de <?php echo htmlspecialchars($nombre_usuario); ?></title>
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

        img {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #6A5ACD; /* Morado oscuro */
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #6A5ACD; /* Morado oscuro */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #483D8B; /* Morado más oscuro */
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #6A5ACD; /* Morado oscuro */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #483D8B; /* Morado más oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cambiar Contraseña de <?php echo htmlspecialchars($nombre_usuario); ?></h1>
        <img src="imagenes/pass.png" alt="Imagen de cambiar Contraseña">
        <form action="cambiar_contrasena.php?id=<?php echo urlencode($usuario_id); ?>" method="post">
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" name="nueva_contrasena" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
        <a href="usuarios.php">Volver</a>
    </div>
</body>
</html>