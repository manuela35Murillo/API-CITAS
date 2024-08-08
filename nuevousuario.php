<?php
require_once 'conexion.php'; // Asegúrate de que este archivo incluya la conexión PDO
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$mensaje = "";
$tipo = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $privilegios = "usuario";

    try {
        // Preparar y ejecutar la consulta con PDO
        $consulta = $pdo->prepare("INSERT INTO usuarios (usuario, password, nombre, email, direccion, privilegios) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta->execute([$usuario, $password, $nombre, $email, $direccion, $privilegios]);

        if ($consulta->rowCount() > 0) {
            $mensaje = "Usuario $nombre agregado exitosamente.";
            $tipo = "exito";
        } else {
            $mensaje = "Error al agregar usuario.";
            $tipo = "error";
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipo = "error";
    }

    $pdo = null; // Cerrar la conexión
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
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

        .container h1 {
            margin-bottom: 20px;
            color: #6A5ACD; /* Morado oscuro */
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            width: 100%;
            max-width: 300px;
            margin-bottom: 10px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #6A5ACD; /* Morado oscuro */
            border-radius: 10px;
            box-sizing: border-box;
            background-color: #D8BFD8; /* Morado claro */
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        button {
            width: 100%;
            max-width: 300px;
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
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: #6A5ACD; /* Morado oscuro */
            font-size: 16px;
            font-weight: bold;
        }

        a:hover {
            color: #483D8B; /* Morado más oscuro */
        }

        .boton-flotante {
            display: none;
            margin-top: 20px;
            padding: 15px 25px;
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            font-size: 16px;
            cursor: pointer;
            animation: mover 1s infinite alternate;
        }

        @keyframes mover {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-10px);
            }
        }

        .boton-flotante.error {
            background-color: #f44336; /* Rojo */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Usuario</h1>
        <img src="imagenes/nuevousuario.png" alt="Imagen de nuevo usuario">
        <form action="nuevousuario.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required>
            <button type="submit">Agregar Usuario</button>
        </form>
        <a href="inicio.php">Volver</a>
        <div id="mensaje-flotante" class="boton-flotante"></div>
    </div>

    <script>
        function mostrarBotonFlotante(mensaje, tipo) {
            const boton = document.getElementById('mensaje-flotante');
            boton.className = `boton-flotante ${tipo}`;
            boton.innerText = mensaje;
            boton.style.display = 'block';
            setTimeout(() => {
                window.location.href = 'inicio.php';
            }, 3000);
        }

        // Mostrar el botón flotante si hay un mensaje
        <?php if ($mensaje): ?>
            mostrarBotonFlotante('<?php echo htmlspecialchars($mensaje); ?>', '<?php echo htmlspecialchars($tipo); ?>');
        <?php endif; ?>
    </script>
</body>
</html>