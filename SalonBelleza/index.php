<?php

session_start(); // Iniciar la sesión al comienzo del script
require_once __DIR__ . '/vendor/autoload.php';

// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Importar controladores
use Rober\SalonBelleza\Controllers\HomeController;
use Rober\SalonBelleza\Controllers\ClientesController;
use Rober\SalonBelleza\Controllers\ServiciosController;
use Rober\SalonBelleza\Controllers\CitasController;
use Rober\SalonBelleza\Controllers\SesionController;
use Rober\SalonBelleza\Controllers\EmpleadosController;
use Rober\SalonBelleza\Controllers\HorariosController;

// Definir la ruta (path) por defecto
$path = $_GET['path'] ?? 'home';

// Rutas públicas permitidas sin autenticación
$rutasPublicas = ['sesion/login', 'sesion/registro', 'home'];

// Redirigir si no hay sesión y la ruta no es pública
if (!isset($_SESSION['usuario']) && !in_array($path, $rutasPublicas)) {
    header('Location: ?path=sesion/login');
    exit();
}

// Función helper para verificar permisos
function verificarAcceso($rolesPermitidos) {
    if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], (array)$rolesPermitidos)) {
        $_SESSION['error'] = "Acceso denegado. No tienes permisos.";
        header('Location: ?path=sesion/login');
        exit();
    }
}

try {
    switch ($path) {
        // -----------------------------
        // 1. Rutas de Sesión
        // -----------------------------
        case 'sesion/login':
            $controller = new SesionController();
            $controller->login();
            break;

        case 'sesion/registro':
            $controller = new SesionController();
            $controller->registro();
            break;

        case 'sesion/logout':
            $controller = new SesionController();
            $controller->logout();
            break;
                

        // -----------------------------
        // 2. Rutas de Clientes
        // -----------------------------
      
        case 'clientes/dashboard':
            verificarAcceso('cliente');
            $controller = new ClientesController();
            $controller->dashboard();
            break;
        

        
         case 'clientes/registrar':
            verificarAcceso(['empleado', 'encargado']);
            $controller = new ClientesController();
            $controller->registrar();
             break;
            

        // -----------------------------
        // 3. Rutas de Empleados
        // -----------------------------
        case 'empleados/dashboard':
            verificarAcceso(['empleado', 'encargado']);
            $controller = new EmpleadosController();
            $controller->dashboard();
            break;

        case 'empleados/verCitas':
            verificarAcceso(['empleado', 'encargado']);
            $controller = new EmpleadosController();
            $controller->verCitas();
            break;

        case 'empleados/anularCita':
            verificarAcceso(['empleado', 'encargado']);
            $controller = new EmpleadosController();
            $controller->anularCita();
            break;

        case 'empleados/listar':
            verificarAcceso(['encargado']);
            $controller = new EmpleadosController();
            $controller->listar();
            break;

        case 'empleados/registrar':
            verificarAcceso('encargado');
            $controller = new EmpleadosController();
            $controller->registrar();
            break;

        
    case 'empleados/registrarCliente':
         verificarAcceso(['empleado', 'encargado']);
        $controller = new ClientesController();
        $controller->registrar();
            break;
                       
        // -----------------------------
        // 4. Rutas de Encargados
        // -----------------------------
        case 'encargados/dashboard':
            verificarAcceso('encargado');
            $controller = new EmpleadosController();
            $controller->dashboardEncargado();
            break;

        // -----------------------------
        // 5. Rutas de Horarios
        // -----------------------------
        case 'horarios/listar_todos':
            verificarAcceso('encargado');
            $controller = new HorariosController();
            $controller->listarTodos();
            break;

        // -----------------------------
        // 6. Rutas de Citas
        // -----------------------------
        case 'citas/horarios_libres':
            $controller = new CitasController();
            $controller->horariosLibres();
            break;

        case 'citas/listar':
            verificarAcceso('encargado');
            $controller = new CitasController();
            $controller->listar();
            break;

        case 'citas/agregar':
            verificarAcceso(['cliente', 'encargado', 'empleado']);
            $controller = new CitasController();
            $controller->agregar();
            break;    
                        
        case 'citas/misCitas':
            verificarAcceso('cliente');
            $controller = new CitasController();
            $controller->misCitas();
            break;

        case 'citas/cancelar':
            verificarAcceso('cliente');
            $controller = new CitasController();
            $controller->cancelar();
            break;

        // -----------------------------
        // 7. Rutas de Servicios
        // -----------------------------

        case 'servicios/listar':
            $controller = new ServiciosController();
            $controller->listar();
            break;
        
    
        case 'servicios/agregar':
            verificarAcceso('encargado');
            $controller = new ServiciosController();
            $controller->agregar();
            break;

        // -----------------------------
        // 8. Página de inicio
        // -----------------------------
        case 'home':
            if (isset($_SESSION['usuario']['rol'])) {
                $rol = $_SESSION['usuario']['rol'];
                switch ($rol) {
                    case 'cliente':
                        header('Location: ?path=clientes/dashboard');
                        exit();
                    case 'empleado':
                        header('Location: ?path=empleados/dashboard');
                        exit();
                    case 'encargado':
                        header('Location: ?path=encargados/dashboard');
                        exit();
                }
            }
            $controller = new HomeController();
            $controller->index();
            break;

        // -----------------------------
        // 9. Ruta por defecto
        // -----------------------------
        default:
            throw new Exception("Error 404: Ruta no encontrada.");
    }
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='?path=home'>Volver a la página principal</a>";
}
