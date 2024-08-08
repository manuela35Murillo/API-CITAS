<?php
// conexion.php

// Configuración de la base de datos
$host = 'localhost'; // Dirección del servidor de base de datos
$db = 'agenda_citas'; // Nombre de la base de datos
$user = 'root'; // Usuario de MySQL (cambia si es diferente)
$pass = ''; // Contraseña de MySQL (cambia si es diferente)

try {
    // Crear una nueva instancia de PDO para la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Configurar el manejo de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: Configurar el modo de emulación de consultas
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // Manejar errores de conexión
    echo 'Error de conexión: ' . $e->getMessage();
    exit;
}
?>