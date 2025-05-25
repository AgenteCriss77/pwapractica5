<?php
include 'db.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$rol_id = $_POST['rol_id'];

$query = $conexion->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol_id) VALUES (?, ?, ?, ?)");
$query->bind_param("sssi", $nombre, $email, $contrasena, $rol_id);
$query->execute();

header("Location: login.php");
?>
