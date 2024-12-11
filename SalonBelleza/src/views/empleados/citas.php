<?php
ob_start();
?>

<h1>Mis Citas</h1>

<!-- Mensajes informativos -->
<?php if (!empty($_SESSION['info'])): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($_SESSION['info']); unset($_SESSION['info']); ?>
    </div>
<?php endif; ?>

<!-- Tabla de citas -->
<?php if (!empty($citas)): ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Cita</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= htmlspecialchars($cita['id_cita']) ?></td>
                    <td><?= htmlspecialchars($cita['cliente']) ?></td>
                    <td><?= htmlspecialchars($cita['servicio']) ?></td>
                    <td><?= htmlspecialchars($cita['fecha']) ?></td>
                    <td><?= htmlspecialchars($cita['hora']) ?></td>
                    <td>
                        <!-- Botón para anular la cita -->
                        <form method="POST" action="?path=empleados/anularCita" style="display:inline;">
                            <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['id_cita']) ?>">
                            <button type="submit">Anular</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tienes citas asignadas.</p>
<?php endif; ?>

<div>
    <a href="?path=empleados/crearCita" class="btn btn-success">Crear Nueva Cita</a>
</div>

<div>
    <a href="?path=empleados/dashboard" class="btn btn-secondary">Volver al Panel</a>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
