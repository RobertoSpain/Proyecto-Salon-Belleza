<?php
ob_start();
?>

<h1>Lista de Empleados</h1>

<?php if (isset($empleados) && !empty($empleados)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?= htmlspecialchars($empleado['id_empleado']) ?></td>
                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                    <td><?= htmlspecialchars($empleado['especialidad']) ?></td>
                    <td><?= htmlspecialchars($empleado['email']) ?></td>
                    <td><?= htmlspecialchars($empleado['telefono']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay empleados registrados.</p>
<?php endif; ?>

<!-- Botón dinámico: redirige según el rol del usuario -->
<?php if (isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'encargado'): ?>
    <a href="?path=encargados/dashboard">Volver al Panel del Encargado</a>
<?php else: ?>
    <a href="?path=empleados/dashboard">Volver al Panel del Empleado</a>
<?php endif; ?>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
