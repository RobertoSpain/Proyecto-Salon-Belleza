<?php
ob_start();
?>

<h1>Reservar una Cita</h1>

<div class="form-container">
<!-- Mostrar mensajes de error o éxito -->
   <?php if (isset($_SESSION['error'])): ?>
      <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
      <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
     <p class="success"><?= htmlspecialchars($_SESSION['success']) ?></p>
     <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <!-- Enlace para ver los horarios libres -->
   <div style="margin-bottom: 20px;">
      <a href="?path=citas/horarios_libres" style="text-decoration: none; color: #007BFF; font-weight: bold;">
          Ver Horarios Libres
      </a>
  </div>

  <!-- Formulario de Reservar Cita -->
  <form action="?path=citas/agregar" method="POST" id="reservar-cita">
     <!-- Selección de Cliente -->
     <?php if ($_SESSION['usuario']['rol'] === 'encargado' || $_SESSION['usuario']['rol'] === 'empleado'): ?>
       <label for="id_cliente">Cliente:</label>
        <select name="id_cliente" id="id_cliente" required>
        <option value="">Selecciona un cliente</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?= htmlspecialchars($cliente['id_cliente']) ?>">
                <?= htmlspecialchars($cliente['nombre']) ?>
             </option>
             <?php endforeach; ?>
         </select>
     <?php else: ?>
       <input type="hidden" name="id_cliente" value="<?= $_SESSION['usuario']['id'] ?>">
  <?php endif; ?>

   <!-- Selección de Servicio -->
    <label for="id_servicio">Servicio:</label>
    <select name="id_servicio" id="id_servicio" required>
    <option value="">Selecciona un servicio</option>
    <?php foreach ($servicios as $servicio): ?>
    <option value="<?= htmlspecialchars($servicio['id_servicio']) ?>"><?= htmlspecialchars($servicio['nombre']) . " - " . number_format($servicio['precio'], 2) ?>€</option>
         <?php endforeach; ?>
    </select>

  <!-- Selección de Empleado -->
    <label for="id_empleado">Empleado:</label>
    <select name="id_empleado" id="id_empleado" required>
    <option value="">Selecciona un empleado</option>
       <?php foreach ($empleados as $empleado): ?>
          <option value="<?= htmlspecialchars($empleado['id_empleado']) ?>"><?= htmlspecialchars($empleado['nombre'] . " - " . $empleado['especialidad']) ?></option>
        <?php endforeach; ?>
    </select>

     <!-- Fecha de la Cita -->
     <label for="fecha">Fecha:</label>
     <input type="date" name="fecha" id="fecha" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>">

     <!-- Hora de la Cita -->
     <label for="hora">Hora:</label>
     <input type="time" name="hora" id="hora" required value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>">

   <!-- Botón para enviar -->
     <button type="submit">Reservar Cita</button>
 </form>

   <!-- Botón para volver -->
    <div style="margin-top: 20px;">
        <a href="?path=citas/misCitas" style="text-decoration: none; color: #6c757d;">Volver a Mis Citas</a>
    </div>
</div>

<?php
$contenido = ob_get_clean();

require_once __DIR__ . '/../layout/layoutGeneral.php';
?>
