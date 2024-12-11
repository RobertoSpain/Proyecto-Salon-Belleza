<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Horario</title>
</head>
<body>
    <h1>Agregar Nuevo Horario</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="?path=horarios/agregar">
        <label for="id_empleado">Empleado:</label>
        <select name="id_empleado" id="id_empleado" required>
            <?php foreach ($empleados as $empleado): ?>
                <option value="<?= htmlspecialchars($empleado['id_empleado']) ?>">
                    <?= htmlspecialchars($empleado['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="dia_semana">Día de la Semana:</label>
    <select name="dia_semana" id="dia_semana" required>
        <option value="">Seleccione un día</option>
        <option value="Lunes">Lunes</option>
        <option value="Martes">Martes</option>
        <option value="Miércoles">Miércoles</option>
        <option value="Jueves">Jueves</option>
        <option value="Viernes">Viernes</option>
    </select><br>

        <label for="hora_inicio">Hora de Inicio:</label>
        <input type="time" name="hora_inicio" id="hora_inicio" required><br>

        <label for="hora_fin">Hora de Fin:</label>
        <input type="time" name="hora_fin" id="hora_fin" required><br>

        <button type="submit">Guardar Horario</button>
    </form>

    <a href="?path=horarios/listar_todos">Volver a la Lista de Horarios</a>
</body>
</html>