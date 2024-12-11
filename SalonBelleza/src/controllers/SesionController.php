<?php 
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Usuario;

class SesionController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Iniciar sesión si no está activa
        }
    }

    // Acción para manejar el inicio de sesión
 public function login() {
    if (isset($_SESSION['usuario'])) {
     $this->redirigirSegunRol($_SESSION['usuario']['rol']); }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $email = trim($_POST['email'] ?? '');
      $password = trim($_POST['password'] ?? '');

      if (empty($email) || empty($password)) {
         $_SESSION['error'] = "Por favor, completa todos los campos.";
         header('Location: ?path=sesion/login');
          exit();
     }

    try {
   // Verificar credenciales usando el modelo
     $usuario = $this->usuarioModel->verificarCredenciales($email, $password);

     if ($usuario) {
      $_SESSION['usuario'] = [
         'id' => $usuario['id'],
           'nombre' => $usuario['nombre'],
           'rol' => strtolower(trim($usuario['rol'])) ];

    // Redirigir al dashboard según el rol
       $this->redirigirSegunRol($_SESSION['usuario']['rol']);
          } else {
               $_SESSION['error'] = "Correo o contraseña incorrecta.";
                header('Location: ?path=sesion/login');
                exit();
           }
     } catch (\Exception $e) {
        $_SESSION['error'] = "Error al procesar la solicitud: " . $e->getMessage();
         header('Location: ?path=sesion/login');
         exit();
           }
       }
        require_once __DIR__ . '/../views/sesion/login.php';
    }
    
    // Acción para registrar usuarios
 public function registro() {
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre = trim($_POST['nombre'] ?? '');
      $correo = trim($_POST['correo'] ?? '');
      $telefono = trim($_POST['telefono'] ?? '');
      $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
      $contrasena = trim($_POST['contrasena'] ?? '');
    
  // Validar campos vacíos
     if (empty($nombre) || empty($correo) || empty($telefono) || empty($fecha_nacimiento) || empty($contrasena)) {
     $_SESSION['error'] = "Por favor, completa todos los campos.";
      header('Location: ?path=sesion/registro');
           exit();
      }
    
 // Validar formato de correo
     if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
         $_SESSION['error'] = "El correo electrónico no es válido.";
         header('Location: ?path=sesion/registro');
          exit();
 }
    
     // Encriptar la contraseña
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);
    
    try {
          // Registrar al usuario usando el modelo
           if ($this->usuarioModel->registrarUsuario($nombre, $correo, $telefono, $fecha_nacimiento, $contrasena_encriptada)) {
               $_SESSION['success'] = "Registro exitoso. ¡Ahora puedes iniciar sesión!";
                header('Location: ?path=sesion/login');
                exit();
           } else {
             $_SESSION['error'] = "El correo electrónico ya está registrado.";
              header('Location: ?path=sesion/registro');
               exit();
            }
         } catch (\Exception $e) {
            $_SESSION['error'] = "Error al registrar el usuario.";
            header('Location: ?path=sesion/registro');
             exit();
           }
     }
    
        // Cargar la vista de registro
        require_once __DIR__ . '/../views/clientes/registrar.php';
    }
    
    // Redirigir según el rol del usuario
    private function redirigirSegunRol($rol) {
        switch ($rol) {
            case 'cliente':
                header('Location: ?path=clientes/dashboard');
                break;
            case 'empleado':
                header('Location: ?path=empleados/dashboard');
                break;
            case 'encargado':
                header('Location: ?path=encargados/dashboard');
                break;
            default:
                $_SESSION['error'] = "Rol desconocido. Acceso no autorizado.";
                header('Location: ?path=sesion/login');
                break;
        }
        exit();
    }

    // Cerrar sesión
    public function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header('Location: ?path=sesion/login');
        exit();
    }
}
