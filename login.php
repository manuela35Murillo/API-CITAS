<?php
session_start();
require_once 'conexion.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar las credenciales
$query = "SELECT id, email, password FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) {
        // Credenciales correctas, iniciar sesión
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        header('Location: dashboard.php');
        exit();
    } else {
        // Contraseña incorrecta
        header('Location: index.php?error=contraseña');
        exit();
    }
} else {
    // Email no encontrado
    header('Location: index.php?error=email');
    exit();
}
?>
