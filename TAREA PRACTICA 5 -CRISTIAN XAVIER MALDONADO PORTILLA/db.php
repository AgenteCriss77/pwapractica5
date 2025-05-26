<?php
$conn = new mysqli("localhost", "root", "", "biblioteca_online");
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
