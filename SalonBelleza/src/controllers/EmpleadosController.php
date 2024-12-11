<?php
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Empleado;
use Rober\SalonBelleza\Models\Cliente;
use Rober\SalonBelleza\Models\Cita;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class EmpleadosController {
    private $empleadoModel;
    private $clienteModel;
    private $citaModel;

    public function __construct() {
        $this->empleadoModel = new Empleado();
        $this->clienteModel = new Cliente();
        $this->citaModel = new Cita();
    }

    private function verificarAcceso($rolesPermitidos, $redireccion = 'sesion/login') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rolActual = $_SESSION['usuario']['rol'] ?? null;

        if (!in_array($rolActual, (array)$rolesPermitidos)) {
            $_SESSION['error'] = "Acceso denegado. No tienes permisos.";
            header("Location: ?path={$redireccion}");
            exit();
        }
    }

    private function redireccionar($ruta, $mensaje = null, $tipo = 'error') {
        if ($mensaje) {
            $_SESSION[$tipo] = $mensaje;
        }
        header("Location: ?path={$ruta}");
        exit();
    }

    public function dashboard() {
        $this->verificarAcceso(['empleado', 'encargado']);
        require_once __DIR__ . '/../views/empleados/dashboard.php';
    }

    public function verCitas() {
        $this->verificarAcceso(['empleado', 'encargado']);
        $idEmpleado = $_SESSION['usuario']['id'] ?? null;

        try {
            $citas = ($_SESSION['usuario']['rol'] === 'encargado') 
                ? $this->citaModel->obtenerTodas()
                : $this->citaModel->obtenerPorEmpleado($idEmpleado);

            if (empty($citas)) {
                $_SESSION['info'] = "No hay citas disponibles en este momento.";
            }

            require_once __DIR__ . '/../views/empleados/citas.php';
        } catch (Exception $e) {
            error_log("Error al obtener citas: " . $e->getMessage());
            $this->redireccionar('empleados/dashboard', "Error al obtener las citas.");
        }
    }

    public function crearCita() {
        $this->verificarAcceso(['empleado', 'encargado']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = $_POST['id_empleado'] ?? $_SESSION['usuario']['id'];
            $idCliente = $_POST['id_cliente'] ?? null;
            $idServicio = $_POST['id_servicio'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $hora = $_POST['hora'] ?? null;

            if (!$idEmpleado || !$idCliente || !$idServicio || !$fecha || !$hora) {
                $this->redireccionar('empleados/crearCita', "Todos los campos son obligatorios.");
            }

            if (!$this->citaModel->esHorarioValido($fecha, $hora)) {
                $this->redireccionar('empleados/crearCita', "La fecha y hora seleccionadas no son válidas.");
            }

            if ($this->citaModel->crear($idEmpleado, $idCliente, $idServicio, $fecha, $hora)) {
                $this->redireccionar('empleados/verCitas', "Cita creada con éxito.", 'success');
            } else {
                $this->redireccionar('empleados/crearCita', "Error al crear la cita.");
            }
        }

        require_once __DIR__ . '/../views/empleados/crear_cita.php';
    }

    public function anularCita() {
        $this->verificarAcceso(['empleado', 'encargado']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCita = $_POST['id_cita'] ?? null;

            if ($idCita && $this->citaModel->eliminar($idCita)) {
                $this->redireccionar('empleados/verCitas', "Cita anulada correctamente.", 'success');
            } else {
                $this->redireccionar('empleados/verCitas', "Error al anular la cita.");
            }
        }
    }

    public function registrar() {
        $this->verificarAcceso(['encargado']);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $especialidad = $_POST['especialidad'] ?? null;
            $email = $_POST['email'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$nombre || !$especialidad || !$email || !$telefono || !$password) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header('Location: ?path=empleados/registrar');
                exit();
            }

            if ($this->empleadoModel->registrar($nombre, $especialidad, $email, $telefono, $password)) {
                $_SESSION['success'] = "Empleado registrado con éxito.";
                header('Location: ?path=encargados/dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Error al registrar el empleado.";
            }
        }
    
        require_once __DIR__ . '/../views/empleados/registrar.php';
    }

    public function listar() {
        $this->verificarAcceso(['encargado']);

        try {
            $empleados = $this->empleadoModel->obtenerTodos();
            require_once __DIR__ . '/../views/empleados/listar.php';
        } catch (Exception $e) {
            error_log("Error al obtener la lista de empleados: " . $e->getMessage());
            $this->redireccionar('encargados/dashboard', "Error al obtener la lista de empleados.");
        }
    }

    public function dashboardEncargado() {
        $this->verificarAcceso(['encargado']);
        require_once __DIR__ . '/../views/encargados/dashboard.php';
    }
}
