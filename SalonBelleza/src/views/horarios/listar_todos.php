<?php require_once __DIR__ . '/../layout/layoutGeneral.php'; ?>

<h1>Lista de Horarios de Todos los Empleados</h1>

<a href="?path=horarios/agregar" class="btn btn-success" style="margin-bottom: 15px;">+ Nuevo Horario</a>

<?php if (isset($horarios) && !empty($horarios)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Horario</th>
                <th>Nombre del Empleado</th>
                <th>Día de la Semana</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Acciones</th>
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
                    <td>
                        <a href="?path=horarios/editar&id=<?= htmlspecialchars($horario['id_horario']) ?>" class="btn">Editar</a>
                        <a href="?path=horarios/eliminar&id=<?= htmlspecialchars($horario['id_horario']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('¿Seguro que deseas eliminar este horario?');">
                           Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay horarios disponibles.</p>
<?php endif; ?>

<div class="back-link">
    <a href="?path=encargados/dashboard">Volver al Panel del Encargado</a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
