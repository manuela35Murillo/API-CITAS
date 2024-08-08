<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Preparar y ejecutar la consulta con PDO
    $consulta = $pdo->prepare("SELECT nombre, email, direccion FROM usuarios WHERE id = :id");
    $consulta->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $consulta->execute();
    
    // Obtener el resultado
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si se encontró el usuario
    if (!$usuario) {
        $nombre_usuario = 'Usuario no encontrado';
        $email_usuario = 'Email no disponible';
        $direccion_usuario = 'Dirección no disponible';
    } else {
        $nombre_usuario = $usuario['nombre'];
        $email_usuario = $usuario['email'];
        $direccion_usuario = $usuario['direccion'];
    }
} catch (PDOException $e) {
    echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
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
            padding: 40px;
            border: 2px solid #6A5ACD; /* Borde morado oscuro */
            border-radius: 20px; /* Bordes redondos */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
            color: #6A5ACD; /* Morado oscuro */
            font-size: 24px;
        }

        p {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
        }

        .profile-info {
            margin-bottom: 30px;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .profile-info label {
            font-weight: bold;
        }

        a {
            display: inline-block;
            width: 100%;
            max-width: 200px;
            padding: 15px;
            color: white;
            background-color: #6A5ACD; /* Morado oscuro */
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 16px;
        }

        a:hover {
            background-color: #483D8B; /* Morado más oscuro */
        }

        img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tu Perfil</h1>
        <img src="imagenes/perfil.png" alt="Imagen de perfil">
        <div class="profile-info">
            <p><label>Nombre:</label> <?php echo htmlspecialchars($nombre_usuario); ?></p>
            <p><label>Email:</label> <?php echo htmlspecialchars($email_usuario); ?></p>
            <p><label>Dirección:</label> <?php echo htmlspecialchars($direccion_usuario); ?></p>
        </div>
        <a href="inicio.php">Volver</a>
    </div>
</body>
</html>