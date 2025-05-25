<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <h2 class="mb-4">Bienvenido al Sistema de Gestión de Tareas</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="lead mb-4">Por favor, inicia sesión o regístrate para comenzar.</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="login.php" class="btn btn-primary btn-lg me-2">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-secondary btn-lg">Registrarse</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
