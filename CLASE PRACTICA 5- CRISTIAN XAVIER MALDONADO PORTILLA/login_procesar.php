<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

$query = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$resultado = $query->get_result();

if ($usuario = $resultado->fetch_assoc()) {
    if (password_verify($contrasena, $usuario['contraseña'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        header("Location: dashboard.php");
    } else {
        header("Location: login.php?error=password");
    }
} else {
    header("Location: login.php?error=user");
}
?>
