<?php 
$title = "Bienvenido al Salón de Belleza"; 
ob_start(); 
?>

<div class="container text-center">
    <h1>Bienvenido a Salón Belleza</h1>
    <p>Disfruta de nuestros servicios exclusivos. Por favor, inicia sesión o regístrate para continuar.</p>
    <div class="btn-group">
        <a href="?path=sesion/login" class="btn btn-primary">Iniciar Sesión</a>
        <a href="?path=clientes/registrar" class="btn btn-success">Registrarse</a>
    </div>
</div>

<?php 
$contenido = ob_get_clean(); 
require_once __DIR__ . "/../layout/layoutGeneral.php"; 
?>
