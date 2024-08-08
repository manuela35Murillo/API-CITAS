<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'conexion.php'; // Asegúrate de que el archivo de conexión esté incluido

// Obtener el nombre de usuario y privilegios del usuario actual
$usuario_id = $_SESSION['usuario_id'];

try {
    // Preparar y ejecutar la consulta
    $consulta = $pdo->prepare("SELECT nombre, privilegios FROM usuarios WHERE id = :id");
    $consulta->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $consulta->execute();
    
    // Obtener el resultado
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si se encontró el usuario
    if ($usuario) {
        $nombre_usuario = $usuario['nombre'];
        $privilegios = $usuario['privilegios'];
    } else {
        $nombre_usuario = 'Usuario no encontrado';
        $privilegios = 'usuario'; // Valor por defecto en caso de error
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
    <title>Inicio</title>
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

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px; /* Espacio entre botones */
        }

        a {
            display: block;
            width: 100%;
            max-width: 300px;
            padding: 10px;
            color: white;
            background-color: #6A5ACD; /* Morado oscuro */
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        a:hover {
            background-color: #483D8B; /* Morado más oscuro */
            transform: scale(1.05);
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        /* Logout Animation Styles */
        .fade-out {
            animation: fadeOut 2s forwards;
        }

        .rise-up {
            position: relative;
            animation: riseUp 1s forwards, explode 0.5s 1s forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        @keyframes riseUp {
            to {
                transform: translateY(-50px);
            }
        }

        @keyframes explode {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(2) translateY(-50px);
            }
        }
    </style>
    <script>
        function logout(event) {
            event.preventDefault();
            const otherButtons = document.querySelectorAll('a:not(#logoutButton)');
            otherButtons.forEach(button => {
                button.classList.add('fade-out');
            });
            const logoutButton = document.getElementById('logoutButton');
            logoutButton.classList.add('rise-up');
            setTimeout(() => {
                window.location.href = 'cerrar_sesion.php';
            }, 1500); // Tiempo de la animación antes de redirigir
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido <?php echo htmlspecialchars($nombre_usuario); ?></h1>
        <img src="imagenes/inicio.png" alt="Imagen de inicio">
        <div class="button-container">
            <a href="perfil.php">Perfil</a>
            <a href="agendar_cita.php">Agendar Cita</a>
            <a href="ver_citas.php">Ver Citas</a>

            <!-- Mostrar los enlaces solo si el usuario es administrador -->
            <?php if ($privilegios == 'administrador'): ?>
                <a href="usuarios.php">Usuarios</a>
                <a href="nuevousuario.php">Agregar Usuario</a>
            <?php endif; ?>

            <a href="cerrar_sesion.php" id="logoutButton" onclick="logout(event)">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>