<?php
ob_start();
?>

<!-- Mensaje de bienvenida con el nombre del cliente -->
<h1>Bienvenido al Panel del Cliente</h1>
<h2 class="welcome">
    <?php if (isset($_SESSION['usuario']['nombre'])): ?>
        Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> 👋
    <?php else: ?>
        Bienvenido, Usuario Anónimo
    <?php endif; ?>
</h2>



<!-- Mensajes de éxito o error -->
<?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<p style="margin-top: 30px;">Desde aquí puedes reservar citas, consultar tus citas programadas y cerrar sesión.</p>

<?php
$contenido = ob_get_clean();

require_once __DIR__ . '/../layout/layoutGeneral.php';
