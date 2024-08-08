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
            color: #666;
        }

        a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Usuario</h1>
        <form action="agregar_usuario.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="direccion">Dirección:</label>
            <input name="direccion"></textarea>
            <button type="submit">Agregar Usuario</button>
            <button type="nuevousuario.php">Agregar</a>
        </form>
        <a href="inicio.php">Volver</a>
    </div>
</body>
</html>
