<?php
// Captura el contenido principal
ob_start();
?>

<h1>
    Horarios de <?= isset($empleado['nombre']) ? htmlspecialchars($empleado['nombre']) : '<span class="error">Empleado no encontrado</span>' ?>
</h1>

<?php if (isset($empleado['nombre'])): ?>
    <?php if (!empty($horarios)): ?>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarios as $horario): ?>
                    <tr>
                        <td><?= htmlspecialchars($horario['dia_semana']) ?></td>
                        <td><?= htmlspecialchars($horario['hora_inicio']) ?></td>
                        <td><?= htmlspecialchars($horario['hora_fin']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-horarios">Este empleado no tiene horarios asignados.</p>
    <?php endif; ?>

    <div class="actions">
        <a href="?path=horarios/agregar&id_empleado=<?= htmlspecialchars($empleado['id_empleado']) ?>">Asignar Nuevo Horario</a>
        <a href="?path=empleados/listar">Volver al Listado de Empleados</a>
        <a href="?path=home">Ir a la Página Principal</a>
    </div>
<?php else: ?>
    <p class="error">No se encontró información del empleado. Por favor, verifica el ID del empleado.</p>
    <div class="actions">
        <a href="?path=empleados/listar">Volver al Listado de Empleados</a>
        <a href="?path=home">Ir a la Página Principal</a>
    </div>
<?php endif; ?>

<?php
// Guarda el contenido principal
$contenido = ob_get_clean();

// Incluye el layout general
require_once __DIR__ . '/../layout/layoutGeneral.php';
