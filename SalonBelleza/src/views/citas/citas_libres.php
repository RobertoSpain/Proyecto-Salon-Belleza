<?php
// Captura el contenido principal
ob_start();
?>

<h1>Buscar Citas Libres</h1>

<!-- Formulario para buscar horarios disponibles -->
<div class="form-container">
    <form method="GET" action="?path=citas/citas_libres">
        <label for="id_empleado">Empleado:</label>
        <select name="id_empleado" id="id_empleado" required>
            <option value="">Selecciona un empleado</option>
            <?php foreach ($empleados as $empleado): ?>
                <option value="<?= htmlspecialchars($empleado['id_empleado']) ?>">
                    <?= htmlspecialchars($empleado['nombre'] . " - " . $empleado['especialidad']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required min="<?= date('Y-m-d') ?>">

        <button type="submit" class="btn">Buscar Citas Libres</button>
    </form>
</div>

<!-- Tabla de citas libres -->
<?php if (!empty($citasLibres)): ?>
    <h2>Citas Libres</h2>
    <table>
        <thead>
            <tr>
                <th>Hora Disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citasLibres as $cita): ?>
                <tr>
                    <td><?= htmlspecialchars($cita['hora']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay citas disponibles para este empleado en la fecha seleccionada.</p>
<?php endif; ?>

<!-- Botón para regresar a la página principal -->
<a href="?path=home" class="btn btn-back">Volver</a>

<?php
// Guarda el contenido capturado
$contenido = ob_get_clean();

// Incluye el layout general
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
