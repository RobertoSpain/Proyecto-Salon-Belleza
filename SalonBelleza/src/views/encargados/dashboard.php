<?php
ob_start();
?>

<header>
    <h1>Panel del Encargado</h1>
    <p>Bienvenido, <?= htmlspecialchars($usuario['nombre'] ?? 'Encargado'); ?>.</p>
</header>

<main>
    <h2>Opciones Disponibles</h2>
    <div class="options">
        <a href="?path=empleados/registrarCliente">Registrar Cliente</a>
        <a href="?path=empleados/registrar">Registrar Empleado</a>
        <a href="?path=empleados/listar">Listar Empleados</a>
        <a href="?path=servicios/listar">Listar Servicios</a>
        <a href="?path=horarios/listar_todos">Gestionar Horarios</a> 
        <a href="?path=empleados/crearCita">Crear Nueva Cita</a>
        <a href="?path=empleados/verCitas">Gestionar Citas</a>
        <a href="?path=sesion/logout">Cerrar Sesión</a>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y'); ?> Salon de Belleza - Panel del Encargado</p>
</footer>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
