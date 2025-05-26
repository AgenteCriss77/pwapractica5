<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirigir según rol
switch ($_SESSION['role_id']) {
    case 1:
        header("Location: admin/dashboard.php");
        break;
    case 2:
        header("Location: librarian/dashboard.php");
        break;
    case 3:
        header("Location: reader/dashboard.php");
        break;
    default:
        session_destroy();
        header("Location: login.php");
        break;
}
exit();
?>
