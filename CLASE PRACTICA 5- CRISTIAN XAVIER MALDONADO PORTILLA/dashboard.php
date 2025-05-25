<?php
session_start();
include 'header.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
$rol_id = $_SESSION['rol_id'];
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title mb-4">
                        <?php
                        if ($rol_id == 1) echo "Panel de Administrador";
                        elseif ($rol_id == 2) echo "Panel de Gerente";
                        else echo "Panel de Miembro";
                        ?>
                    </h3>
                    <div class="d-grid gap-3 col-6 mx-auto">
                        <?php
                        if ($rol_id == 1) {
                            echo "<a href='usuarios.php' class='btn btn-primary btn-lg'>Gestionar Usuarios</a>";
                        } elseif ($rol_id == 2) {
                            echo "<a href='tareas.php' class='btn btn-primary btn-lg'>Gestionar Tareas</a>";
                        } else {
                            echo "<a href='tareas.php' class='btn btn-primary btn-lg'>Ver Mis Tareas</a>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
