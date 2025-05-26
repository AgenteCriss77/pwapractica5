<?php
session_start();

// Redirigir a login si no hay sesión activa
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Función para verificar rol actual
function checkRole($role_name) {
    // Mapear roles a IDs
    $roles = [
        'Administrator' => 1,
        'Librarian' => 2,
        'Reader' => 3
    ];
    if (!isset($_SESSION['role_id'])) return false;
    return $_SESSION['role_id'] === $roles[$role_name];
}
?>
