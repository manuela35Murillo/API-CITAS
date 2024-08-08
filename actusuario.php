<?php
require_once 'conexion.php'; // Asegúrate de que este archivo incluya la conexión PDO
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Obtener el ID del usuario desde la URL
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
} else {
    header("Location: usuarios.php");
    exit;
}

// Obtener los datos del usuario para prellenar el formulario
try {
    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $consulta->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        header("Location: usuarios.php?mensaje=Usuario no encontrado&type=error");
        exit;
    }
} catch (PDOException $e) {
    echo '<p>Error al ejecutar la consulta: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    try {
        $consulta_update = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, direccion = :direccion WHERE id = :id");
        $consulta_update->bindParam(':nombre', $nombre);
        $consulta_update->bindParam(':email', $email);
        $consulta_update->bindParam(':direccion', $direccion);
        $consulta_update->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $consulta_update->execute();

        if ($consulta_update->rowCount() > 0) {
            $mensaje = "Usuario actualizado exitosamente.";
            $tipo = "exito";
        } else {
            $mensaje = "No se realizaron cambios en el usuario.";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar el usuario: " . htmlspecialchars($e->getMessage());
        $tipo = "error";
    }
    
    header("Location: usuarios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
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

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
            font-weight: bold;
        }

        input[type="text"], input[type="email"] {
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
            margin-bottom: 10px; /* Añadido margen para separar del enlace "Volver" */
        }

        button:hover {
            background-color: #483D8B; /* Morado más oscuro */
        }

        .mensaje {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }

        .mensaje.exito {
            background-color: #4CAF50; /* Verde */
        }

        .mensaje.error {
            background-color: #f44336; /* Rojo */
        }

        .volver {
            display: inline-block;
            margin-top: 10px;
            color: #6A5ACD; /* Morado oscuro */
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: block;
            transition: color 0.3s ease;
        }

        .volver:hover {
            color: #483D8B; /* Morado más oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Actualizar Usuario</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo htmlspecialchars($tipo); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="actusuario.php?id=<?php echo htmlspecialchars($usuario_id); ?>" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
            
            <button type="submit">Actualizar Usuario</button>
        </form>
        
        <a href="usuarios.php" class="volver">Volver</a>
    </div>
</body>
</html>