<?php
// Captura el contenido principal
ob_start();
?>

<h1>Reservar Cita</h1>


<h1>Reservar Cita</h1>
<form method="POST" action="?path=citas/reservar">
    <label for="servicio">Servicio:</label>
    <select name="servicio" id="servicio" required>
        <option value="">-- Selecciona un servicio --</option>
        <?php foreach ($servicios as $servicio): ?>
            <option value="<?= htmlspecialchars($servicio['id_servicio']) ?>">
                <?= htmlspecialchars($servicio['nombre']) . " - " . number_format($servicio['precio'], 2) ?>€
            </option>
        <?php endforeach; ?>
    </select><br>

    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" id="fecha" required min="<?= date('Y-m-d') ?>"><br>

    <label for="hora">Hora:</label>
    <input type="time" name="hora" id="hora" required><br>

    <button type="submit">Reservar</button>
</form>

<?php
// Guarda el contenido principal
$contenido = ob_get_clean();

// Incluye el layout general
require_once __DIR__ . '/../layout/layoutGeneral.php';
