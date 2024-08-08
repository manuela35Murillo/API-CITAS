git config --global user.name "FIRST_NAME LAST_NAME"<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Preparar y ejecutar la consulta con PDO
    $consulta = $pdo->prepare("SELECT id, fecha, hora FROM citas WHERE usuario_id = :usuario_id");
    $consulta->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $consulta->execute();
    
    // Obtener todos los resultados
    $citas = $consulta->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Ver Citas</title>
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
            width: 70%;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #6A5ACD; /* Morado oscuro */
            color: white;
        }

        td {
            color: black;
        }

        .acciones {
            text-align: center;
        }

        .acciones button {
            padding: 5px 10px;
            background-color: #DC143C; /* Rojo */
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .acciones button:hover {
            background-color: #8B0000; /* Rojo oscuro */
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #6A5ACD; /* Morado oscuro */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        a:hover {
            background-color: #483D8B; /* Morado más oscuro */
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
        function confirmarCancelacion(citaId, fecha) {
            const confirmacion = confirm(`¿Está seguro que desea cancelar la cita para el día ${fecha}?`);
            if (confirmacion) {
                document.getElementById(`cancelar-form-${citaId}`).submit();
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
        <h1>Mis Citas</h1>
        <img src="Imagenes/citas.png" alt="Imagen de citas">
        <?php if (count($citas) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Fecha de la Cita</th>
                    <th>Hora de la Cita</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cita['id']); ?></td>
                        <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                        <td class="acciones">
                            <form id="cancelar-form-<?php echo $cita['id']; ?>" action="cancelar_cita.php" method="post" style="display:inline;">
                                <input type="hidden" name="cita_id" value="<?php echo htmlspecialchars($cita['id']); ?>">
                                <button type="button" onclick="confirmarCancelacion('<?php echo htmlspecialchars($cita['id']); ?>', '<?php echo htmlspecialchars($cita['fecha']); ?>')">Cancelar Cita</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No tienes citas agendadas.</p>
        <?php endif; ?>
        <a href="inicio.php">Volver</a>
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