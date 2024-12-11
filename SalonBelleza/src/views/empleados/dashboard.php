<?php
ob_start();
?>

<div class="container">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h1>
    <p>Este es el panel de empleados.</p>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para registrar clientes -->
    <h2>Registrar Cliente</h2>
    <form method="POST" action="?path=empleados/registrarCliente">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Registrar Cliente</button>
    </form>

    <!-- Opciones para manejar citas -->
    <h2>Gestión de Citas</h2>
    <div class="links">
        <a href="?path=empleados/verCitas">Ver Mis Citas</a>
        <a href="?path=empleados/crearCita">Crear Nueva Cita</a>
    </div>

    <!-- Enlaces adicionales -->
    <h2>Opciones</h2>
    <div class="links">
        <a href="?path=sesion/logout">Cerrar sesión</a>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
