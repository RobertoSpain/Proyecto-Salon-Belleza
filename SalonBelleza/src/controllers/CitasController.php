<?php
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Cita;
use Rober\SalonBelleza\Models\Horario;
use Rober\SalonBelleza\Models\Servicio;
use Rober\SalonBelleza\Models\Empleado;
use Rober\SalonBelleza\Models\Cliente; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CitasController {
    private $cita;
    private $horario;
    private $servicio;
    private $empleado;
    private $cliente; 

    public function __construct() {
        $this->cita = new Cita();
        $this->horario = new Horario();
        $this->servicio = new Servicio();
        $this->empleado = new Empleado();
        $this->cliente = new Cliente(); 
    }

    // Cancelar una cita - Solo clientes.
     
public function cancelar() {
    $this->validarRol(['cliente']);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idCita = $_POST['id_cita'] ?? null;
  $idCliente = $_SESSION['usuario']['id'];

 if (!$idCita || !$this->cita->esCitaDelCliente($idCita, $idCliente)) {
    $_SESSION['error'] = "No puedes cancelar esta cita.";
    header('Location: ?path=citas/misCitas');
    exit();
           }

    if ($this->cita->eliminar($idCita)) {
        $_SESSION['success'] = "Cita anulada correctamente.";
         } else {
            $_SESSION['error'] = "Error al anular la cita.";
          }
   }
     header('Location: ?path=citas/misCitas');
    exit();
 }

    
    // Registrar un nuevo cliente - Encargados, empleados o clientes.
     
 public function registrar() {
  $this->validarRol(['encargado', 'empleado', 'cliente']);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $nombre = $_POST['nombre'] ?? null;
   $email = $_POST['email'] ?? null;
   $telefono = $_POST['telefono'] ?? null;
   $fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;
   $password = $_POST['password'] ?? null;

  if (!$nombre || !$email || !$telefono || !$fechaNacimiento || !$password) {
     $_SESSION['error'] = "Todos los campos son obligatorios.";
    } else {
         try {
         $resultado = $this->cliente->registrar(
        $nombre, 
         $email, 
       $telefono, 
       $fechaNacimiento, 
        $password 
                 );
   if ($resultado) {
               $_SESSION['success'] = "Cliente registrado con éxito.";
                header('Location: ?path=clientes/listar');
                 exit();
       } else {
                $_SESSION['error'] = "Error al registrar el cliente.";
                 }
         } catch (\Exception $e) {
             $_SESSION['error'] = "Error: " . $e->getMessage();
             }
         }
    }
        require_once __DIR__ . '/../views/clientes/registrar.php';
    }

    
    // Agregar una nueva cita - Clientes, Encargados y Empleados.
     
  public function agregar() {
     $this->validarRol(['cliente', 'encargado', 'empleado']);
     $servicios = $this->servicio->obtenerTodos();
     $empleados = $this->empleado->obtenerTodos();
     $clientes = $this->cliente->listar(); 

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $idCliente = $_POST['id_cliente'] ?? ($_SESSION['usuario']['rol'] === 'cliente' ? $_SESSION['usuario']['id'] : null);
     $idEmpleado = $_POST['id_empleado'] ?? null;
     $idServicio = $_POST['id_servicio'] ?? null;
     $fecha = $_POST['fecha'] ?? null;
     $hora = $_POST['hora'] ?? null;

 if (!$idCliente || !$idEmpleado || !$idServicio || !$fecha || !$hora) {
   $_SESSION['error'] = "Todos los campos son obligatorios.";
    } elseif (!$this->cita->esHorarioValido($fecha, $hora)) {
   $_SESSION['error'] = "El horario seleccionado no es válido.";
    } elseif (!$this->cita->verificarDisponibilidad($idEmpleado, $fecha, $hora)) {
    $_SESSION['error'] = "El empleado no está disponible en ese horario.";
     } else {
         if ($this->cita->crear($idEmpleado, $idCliente, $idServicio, $fecha, $hora)) {
         $_SESSION['success'] = "Cita creada con éxito.";
          header('Location: ?path=citas/listar');
          exit();
          } else {
             $_SESSION['error'] = "Error al crear la cita.";
           }
        }
    }
        require_once __DIR__ . '/../views/citas/agregar.php';
    }

    
    // Listar todas las citas - Solo encargados.
     
    public function listar() {
        $this->validarRol(['encargado']);
        try {
            $citas = $this->cita->obtenerTodas();
            require_once __DIR__ . '/../views/citas/listar.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al cargar las citas: " . $e->getMessage();
            header('Location: ?path=encargados/dashboard');
            exit();
        }
    }

    
    // Mostrar horarios libres.
    public function horariosLibres() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $fecha = $_GET['fecha'] ?? date('Y-m-d');
            $horariosLibres = $this->cita->obtenerHorariosLibres($fecha);
            $horasOcupadas = $this->cita->obtenerHorasOcupadas($fecha);

            require_once __DIR__ . '/../views/horarios/horarios_libres.php';
        }
    }

     // Mostrar detalles de una cita específica.
     
    public function detalleCita() {
        $this->validarRol(['cliente', 'empleado', 'encargado']);
        $idCita = $_GET['id'] ?? null;

        if (!$idCita) {
            $_SESSION['error'] = "ID de cita no proporcionado.";
            header('Location: ?path=citas/misCitas');
            exit();
        }

        $cita = $this->cita->obtenerPorId($idCita);
        if (!$cita) {
            $_SESSION['error'] = "Cita no encontrada.";
            header('Location: ?path=citas/misCitas');
            exit();
        }

        require_once __DIR__ . '/../views/citas/detalle.php';
    }

    
     // Ver las citas del usuario actual.
     
    public function misCitas() {
        $this->validarRol(['cliente', 'empleado']);
        $idUsuario = $_SESSION['usuario']['id'];
        $rol = $_SESSION['usuario']['rol'];

        $citas = ($rol === 'cliente')
            ? $this->cita->obtenerPorCliente($idUsuario)
            : $this->cita->obtenerPorEmpleado($idUsuario);

        require_once __DIR__ . '/../views/citas/misCitas.php';
    }

    
     // Valida que el usuario tenga el rol adecuado.
     
    private function validarRol($rolesPermitidos) {
        if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], $rolesPermitidos)) {
            $_SESSION['error'] = "Acceso denegado.";
            header('Location: ?path=sesion/login');
            exit();
        }
    }
}
