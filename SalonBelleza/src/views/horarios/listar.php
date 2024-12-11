<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../layout/layoutGeneral.php'; 
?>

<h1>Lista de Horarios de Empleados</h1>

<?php if (isset($horarios) && !empty($horarios)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Horario</th>
                <th>Nombre del Empleado</th>
                <th>Día de la Semana</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horarios as $horario): ?>
                <tr>
                    <td><?= htmlspecialchars($horario['id_horario']) ?></td>
                    <td><?= htmlspecialchars($horario['nombre_empleado']) ?></td>
                    <td><?= htmlspecialchars($horario['dia_semana']) ?></td>
                    <td><?= htmlspecialchars($horario['hora_inicio']) ?></td>
                    <td><?= htmlspecialchars($horario['hora_fin']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center;">No hay horarios disponibles.</p>
<?php endif; ?>

<div class="back-link">
    <a href="?path=encargados/dashboard">Volver al Panel del Encargado</a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
