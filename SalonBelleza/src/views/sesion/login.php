
<?php 
require_once __DIR__ . '/../layout/layoutGeneral.php'; 
?>

<div class="form-container">
    <h1>Iniciar Sesión</h1>

    <!-- Mostrar mensajes de error o éxito -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error-message" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success-message" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Formulario de inicio de sesión -->
    <form method="POST" action="?path=sesion/login" aria-labelledby="login-title">
        <div class="form-group">
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" placeholder="Introduce tu correo" required 
                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required minlength="4">
        </div>
        <div class="form-group">
            <button type="submit">Iniciar Sesión</button>
        </div>
    </form>

    <div class="form-footer">
    <a href="?path=sesion/registro">Regístrate aquí</a>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
