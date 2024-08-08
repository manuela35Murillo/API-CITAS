<?php
require_once 'conexion.php'; // Asegúrate de que este archivo incluya la conexión PDO
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

try {
    // Preparar y ejecutar la consulta con PDO
    $consulta = $pdo->prepare("SELECT * FROM usuarios");
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p>Error al ejecutar la consulta: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 2px solid #6A5ACD; /* Borde morado oscuro */
            border-radius: 20px; /* Bordes redondos */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow-x: auto; /* Agregar desplazamiento horizontal */
        }

        h1 {
            color: #6A5ACD; /* Morado oscuro */
        }

        img {
            display: block; /* Para centrar la imagen */
            margin: 0 auto; /* Centrar la imagen horizontalmente */
            margin-bottom: 20px; /* Espacio inferior entre la imagen y la tabla */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid #6A5ACD; /* Borde morado oscuro */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #6A5ACD; /* Morado oscuro */
            color: white;
            font-weight: bold;
        }

        td {
            color: black;
        }

        .acciones {
            text-align: center;
        }

        .acciones a button {
            padding: 5px 10px;
            background-color: #6A5ACD;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 5px;
        }

        .acciones a button:hover {
            background-color: #483D8B; /* Morado más oscuro */
        }

        .acciones form button {
            padding: 5px 10px;
            background-color: #DC143C; /* Rojo */
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 5px;
        }

        .acciones form button:hover {
            background-color: #8B0000; /* Rojo oscuro */
        }

        a.volver {
            display: inline-block;
            margin-top: 20px;
            color: #6A5ACD; /* Morado oscuro */
            text-decoration: none;
            font-weight: bold;
        }

        a.volver:hover {
            color: #483D8B; /* Morado más oscuro */
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            text-align: center;
            border-radius: 10px;
        }

        .modal-content p {
            color: #6A5ACD; /* Morado oscuro */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function confirmarEliminacion(usuarioId) {
            const confirmacion = confirm('¿Está seguro que desea eliminar este usuario?');
            if (confirmacion) {
                document.getElementById(`eliminar-form-${usuarioId}`).submit();
            }
        }

        function mostrarModal(mensaje) {
            var modal = document.getElementById("myModal");
            var modalMensaje = document.getElementById("modalMensaje");
            modalMensaje.textContent = mensaje;
            modal.style.display = "flex";
        }

        window.onload = function() {
            <?php if (isset($_GET['mensaje'])): ?>
                mostrarModal("<?php echo htmlspecialchars($_GET['mensaje']); ?>");
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Usuarios</h1>
        <img src="imagenes/usuarios.png" alt="Imagen de usuarios">

        <?php if (!empty($resultado)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Privilegios</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($resultado as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['privilegios']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['fecha_creacion']); ?></td>
                        <td class="acciones">
                            <a href="actusuario.php?id=<?php echo htmlspecialchars($usuario['id']); ?>">
                                <button><i class="fas fa-edit"></i></button>
                            </a>
                            <a href="cambiar_contrasena.php?id=<?php echo htmlspecialchars($usuario['id']); ?>">
                                <button><i class="fas fa-key"></i></button>
                            </a>
                            <form id="eliminar-form-<?php echo htmlspecialchars($usuario['id']); ?>" action="eliminar_usuario.php" method="post" style="display:inline;">
                                <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
                                <button type="button" onclick="confirmarEliminacion('<?php echo htmlspecialchars($usuario['id']); ?>')" style="background-color: #DC143C;"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hay usuarios registrados.</p>
        <?php endif; ?>

        <a href="inicio.php" class="volver">Volver</a>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
            <p id="modalMensaje"></p>
        </div>
    </div>
</body>
</html>