<?php
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Cliente;
use Exception;

class ClientesController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->iniciarSesion();
    }

    // Iniciar sesión si no está activa
    private function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();}
    }

 private function validarRol($rolesPermitidos) {
   if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], (array)$rolesPermitidos)) {
     $_SESSION['error'] = "Acceso denegado. No tienes permisos.";
      header('Location: ?path=sesion/login');
      exit();}
    }
    
/**
 * Cargar una vista con datos opcionales.
 * @param string $vista 
 * @param array $datos 
 */
private function cargarVista(string $vista, array $datos = []) {
    extract($datos); 
    require_once __DIR__ . "/../views/{$vista}.php";
}

    // Método para registrar clientes
 public function registrar() {
     $esAutorizado = isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['empleado', 'encargado']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
     $fechaNacimiento = trim($_POST['fecha_nacimiento'] ?? '');
     $password = trim($_POST['password'] ?? '');
  if (empty($nombre) || empty($correo) || empty($telefono) || empty($fechaNacimiento) || empty($password)) {
   $_SESSION['error'] = "Todos los campos son obligatorios.";
    header('Location: ?path=clientes/registrar');
        exit();
            }
    
// Validar formato de correo
     if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {  $_SESSION['error'] = "El correo electrónico no es válido.";
        header('Location: ?path=clientes/registrar');
     exit(); }
    
try {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
     // Registrar el cliente usando el modelo
    if ($this->clienteModel->registrar($nombre, $correo, $telefono, $fechaNacimiento, $passwordHash)) {
    $_SESSION['success'] = $esAutorizado 
     ? "Cliente registrado correctamente por trabajador." 
     : "Registro exitoso. Bienvenido.";
       if (!$esAutorizado) {
          $_SESSION['usuario'] = [
            'nombre' => $nombre,
            'rol' => 'cliente',
             'correo' => $correo];
         header('Location: ?path=clientes/dashboard');
        } else {
                header('Location: ?path=empleados/dashboard');
            }
            exit();
        } else {
                 $_SESSION['error'] = "El correo electrónico ya está registrado.";
            }
        } catch (Exception $e) {
             $_SESSION['error'] = "Error al registrar el cliente: " . $e->getMessage();
        }
    
     header('Location: ?path=clientes/registrar');
        exit();
        }
        $this->cargarVista('clientes/registrar', ['esAutorizado' => $esAutorizado]);
    }
    
    // Método para cargar la vista del cliente
    public function dashboard() {
        $this->validarRol(['cliente']);
        $nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
        require_once __DIR__ . '/../views/clientes/dashboard.php';
    }
}       
