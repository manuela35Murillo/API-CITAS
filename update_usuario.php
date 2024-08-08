<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// Verificar si se ha pasado un ID de usuario
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}

$usuario_id = $_GET['id'];

try {
    // Obtener los datos del usuario
    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $consulta->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: usuarios.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $privilegios = $_POST['privilegios'];

        // Actualizar los datos del usuario
        $consulta_actualizar = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, direccion = :direccion, privilegios = :privilegios WHERE id = :id");
        $consulta_actualizar->bindParam(':nombre', $nombre);
        $consulta_actualizar->bindParam(':email', $email);
        $consulta_actualizar->bindParam(':direccion', $direccion);
        $consulta_actualizar->bindParam(':privilegios', $privilegios);
        $consulta_actualizar->bindParam(':id', $usuario_id, PDO::PARAM_INT);

        if ($consulta_actualizar->execute()) {
            header("Location: usuarios.php?mensaje=Usuario actualizado exitosamente&tipo=exito");
            exit;
        } else {
            echo '<script>alert("Error al actualizar el usuario.");</script>';
        }
    }
} catch (PDOException $e) {
    echo 'Error al ejecutar la consulta: ' . $e->getMessage();
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

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
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

        a {
            display: block;
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
        <h1>Actualizar Usuario</h1>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
            <select name="privilegios" required>
                <option value="usuario" <?php echo ($usuario['privilegios'] === 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                <option value="admin" <?php echo ($usuario['privilegios'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
            <button type="submit">Actualizar</button>
        </form>
        <a href="usuarios.php">Volver</a>
    </div>
</body>
</html>