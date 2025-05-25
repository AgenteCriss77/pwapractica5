<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4">Registro de Usuario</h2>
            <form action="registro_procesar.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol</label>
                    <select id="rol_id" name="rol_id" class="form-select">
                        <option value="1">Administrador</option>
                        <option value="2">Gerente de proyecto</option>
                        <option value="3">Miembro del equipo</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
