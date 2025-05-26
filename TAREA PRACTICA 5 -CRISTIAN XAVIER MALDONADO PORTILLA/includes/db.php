<?php
// Configuración conexión a la base de datos
$host = "localhost";      // Cambiar si es necesario
$user = "root";           // Cambiar por tu usuario MySQL
$password = "";           // Cambiar por tu contraseña MySQL
$dbname = "biblioteca_online";   // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer codificación utf8
$conn->set_charset("utf8");
?>
