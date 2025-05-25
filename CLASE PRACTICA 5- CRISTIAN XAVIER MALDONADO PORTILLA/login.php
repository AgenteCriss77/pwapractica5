<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'password') {
                    echo '<div class="alert alert-danger">Contraseña incorrecta.</div>';
                } elseif ($_GET['error'] == 'user') {
                    echo '<div class="alert alert-danger">Usuario no encontrado.</div>';
                }
            }
            ?>
            <form action="login_procesar.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
