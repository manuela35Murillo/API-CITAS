<?php
require_once 'conexion.php';
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($pdo)) {
        $consulta = $pdo->prepare("SELECT id, usuario, password FROM usuarios WHERE email = :email");
        $consulta->bindParam(':email', $email);
        $consulta->execute();
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Comparar las contraseñas sin encriptar
            if ($password === $usuario['password']) {
                $_SESSION['usuario_id'] = $usuario['id'];
                header("Location: inicio.php");
                exit;
            } else {
                $mensaje = "Contraseña incorrecta";
            }
        } else {
            $mensaje = "El email ingresado no existe en la base de datos";
        }
    } else {
        $mensaje = "No se pudo conectar a la base de datos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F1F1F1;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #FFFFFF;
            padding: 20px;
            border: 2px solid #6A5ACD; /* Borde morado oscuro */
            border-radius: 20px; /* Bordes redondos */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            color: #6A5ACD; /* Morado oscuro */
            width: 100%;
            max-width: 300px;
            margin-bottom: 10px;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #6A5ACD; /* Morado oscuro */
            border-radius: 10px;
            box-sizing: border-box;
            background-color: #f2f2f2; /* Gris claro */
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendamiento de Citas</h1>
        <img src="imagenes/login.png" alt="Imagen de login">
        <?php if (isset($mensaje)): ?>
            <p style="color: red;"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="index.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>