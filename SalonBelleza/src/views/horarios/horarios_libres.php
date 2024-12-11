<h1>Horarios Libres</h1>

<!-- Formulario para buscar por fecha -->
<form method="GET" action="?path=citas/horarios_libres">
    <label for="fecha">Seleccionar Fecha:</label>
    <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($_GET['fecha'] ?? date('Y-m-d')) ?>">
    <button type="submit">Buscar</button>
</form>

<!-- Mostrar los horarios -->
<h2>Horarios Disponibles para <?= htmlspecialchars($fecha) ?>:</h2>
<ul>
    <?php foreach ($horariosLibres as $hora): ?>
        <?php if (in_array($hora, $horasOcupadas)): ?>
            <li style="color: red; font-weight: bold;"><?= htmlspecialchars($hora) ?> (Ocupado)</li>
        <?php else: ?>
            <li><?= htmlspecialchars($hora) ?> (Libre)</li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<a href="?path=citas/agregar">Volver a Reservar Cita</a>
