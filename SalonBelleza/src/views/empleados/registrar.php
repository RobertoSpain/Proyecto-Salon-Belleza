<?php
ob_start();
?>

<a href="?path=encargados/dashboard">Volver al Panel del Empleado</a>

<h1>Registrar Empleado</h1>
<form method="POST" action="?path=empleados/registro">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label for="especialidad">Especialidad:</label>
    <input type="text" name="especialidad" required><br>

    <label for="email">Correo Electrónico:</label>
    <input type="text" name="email" placeholder="Introduce tu email" required><br>


    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono" required><br>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Registrar</button>
</form>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
