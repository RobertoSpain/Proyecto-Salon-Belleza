<?php
// Captura el contenido principal
ob_start();
?>

<h1>Mis Citas Programadas</h1>

<!-- Mensajes de éxito o error -->
<?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Tabla de citas -->
<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($citas) && is_array($citas)): ?>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= htmlspecialchars($cita['empleado'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cita['fecha'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($cita['hora'] ?? 'N/A') ?></td>
                    <td>
                        <!-- Mostrar ID de la cita para depuración -->
                        <p style="color: blue;">ID Cita: <?= htmlspecialchars($cita['id_cita'] ?? 'No ID') ?></p>

                        <!-- Botón para anular cita -->
                        <form action="?path=citas/cancelar" method="POST" style="display:inline;">
                            <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['id_cita'] ?? '') ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas anular esta cita?');">
                                Anular
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No tienes citas programadas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<!-- Botón para regresar al panel principal -->
<a href="?path=clientes/dashboard" class="btn-back">Volver al Panel Principal</a>

<?php
// Guarda el contenido principal
$contenido = ob_get_clean();

// Incluye el layout general
require_once __DIR__ . '/../layout/layoutGeneral.php';
