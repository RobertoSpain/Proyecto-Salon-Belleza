<?php
ob_start();
?>

<h1>Crear Nueva Cita</h1>

<form method="POST" action="?path=empleados/crearCita">
    <label for="id_cliente">ID Cliente:</label>
    <input type="text" name="id_cliente" id="id_cliente" required>

    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" id="fecha" required>

    <label for="hora">Hora:</label>
    <input type="time" name="hora" id="hora" required>

    <button type="submit">Crear Cita</button>
</form>

<a href="?path=empleados/verCitas" class="btn btn-secondary">Volver a Mis Citas</a>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
