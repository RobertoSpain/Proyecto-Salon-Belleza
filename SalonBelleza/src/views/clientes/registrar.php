<?php
echo "Vista registrar.php cargada correctamente.";

ob_start();
?>

<h2>Registro de Cliente</h2>

<!-- Mostrar mensajes de éxito o error -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="message success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="message error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Mensaje dinámico: quién está realizando el registro -->
<?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['empleado', 'encargado'])): ?>
    <div class="message info">Estás registrando un cliente como trabajador autorizado.</div>
<?php else: ?>
    <div class="message info">Registro de cliente online.</div>
<?php endif; ?>

<!-- Formulario de registro -->
<form method="POST" action="?path=sesion/registro">

    <!-- Nombre -->
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" required>
    </div>

    <!-- Correo Electrónico -->
    <div class="form-group">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" placeholder="Introduce tu correo" required>
    </div>

    <!-- Teléfono -->
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" placeholder="Introduce tu número de teléfono" required>
    </div>

    <!-- Fecha de Nacimiento -->
    <div class="form-group">
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
    </div>

    <!-- Contraseña -->
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="contrasena" placeholder="Introduce tu contraseña" required>
    </div>

    <!-- Botón de Registro -->
    <div class="form-group">
        <button type="submit">Registrar</button>
    </div>

</form>


<!-- Pie de página -->
<div class="form-footer">
    <p>¿Ya tienes cuenta? <a href="?path=sesion/login">Inicia sesión</a></p>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
