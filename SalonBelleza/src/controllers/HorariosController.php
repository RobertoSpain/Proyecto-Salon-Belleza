<?php
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Horario;
use Rober\SalonBelleza\Models\Empleado;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class HorariosController {
    private $horarioModel;
    private $empleadoModel;

    public function __construct() {
        $this->horarioModel = new Horario();
        $this->empleadoModel = new Empleado();
        $this->iniciarSesion();
    }

    // Asegurar que la sesión esté activa
    private function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Verificar si el usuario tiene el rol necesario
    private function verificarAcceso($rol) {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rol) {
            $_SESSION['error'] = "Acceso denegado. Solo los usuarios con rol '$rol' pueden acceder.";
            header('Location: ?path=sesion/login');
            exit();
        }
    }

    // Listar todos los horarios
    public function listarTodos() {
        $this->verificarAcceso('encargado');
        $horarios = $this->horarioModel->obtenerTodos();
        $this->cargarVista('horarios/listar_todos', ['horarios' => $horarios]);
    }

    // Agregar un nuevo horario
    public function agregar() {
        $this->verificarAcceso('encargado');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = $_POST['id_empleado'] ?? null;
            $diaSemana = $_POST['dia_semana'] ?? null;
            $horaInicio = $_POST['hora_inicio'] ?? null;
            $horaFin = $_POST['hora_fin'] ?? null;

            if (!$this->validarHorario($idEmpleado, $diaSemana, $horaInicio, $horaFin)) {
                $this->redireccionConError('horarios/agregar', "Todos los campos son obligatorios.");
            }

            if ($this->horarioModel->existeHorario($idEmpleado, $diaSemana, $horaInicio, $horaFin)) {
                $this->redireccionConError('horarios/agregar', "Este horario ya está asignado.");
            }

            if ($this->horarioModel->agregar($idEmpleado, $diaSemana, $horaInicio, $horaFin)) {
                $_SESSION['success'] = "Horario asignado correctamente.";
                header("Location: ?path=horarios/listar_todos");
                exit();
            } else {
                $this->redireccionConError('horarios/agregar', "Error al asignar el horario.");
            }
        }

        $empleados = $this->empleadoModel->obtenerTodos();
        $this->cargarVista('horarios/agregar', ['empleados' => $empleados]);
    }

    // Editar un horario existente
    public function editar($idHorario) {
        $this->verificarAcceso('encargado');

        if (!$idHorario) {
            $this->redireccionConError('horarios/listar_todos', "ID del horario no especificado.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = $_POST['id_empleado'] ?? null;
            $diaSemana = $_POST['dia_semana'] ?? null;
            $horaInicio = $_POST['hora_inicio'] ?? null;
            $horaFin = $_POST['hora_fin'] ?? null;

            if (!$this->validarHorario($idEmpleado, $diaSemana, $horaInicio, $horaFin)) {
                $this->redireccionConError("horarios/editar&id=$idHorario", "Todos los campos son obligatorios.");
            }

            if ($this->horarioModel->actualizar($idHorario, $idEmpleado, $diaSemana, $horaInicio, $horaFin)) {
                $_SESSION['success'] = "Horario actualizado correctamente.";
                header("Location: ?path=horarios/listar_todos");
                exit();
            } else {
                $this->redireccionConError("horarios/editar&id=$idHorario", "Error al actualizar el horario.");
            }
        }

        $horario = $this->horarioModel->obtenerPorId($idHorario);
        $empleados = $this->empleadoModel->obtenerTodos();

        if (!$horario) {
            $this->redireccionConError('horarios/listar_todos', "Horario no encontrado.");
        }

        $this->cargarVista('horarios/editar', ['horario' => $horario, 'empleados' => $empleados]);
    }

    // Eliminar un horario
    public function eliminar($idHorario) {
        $this->verificarAcceso('encargado');

        if (!$idHorario) {
            $this->redireccionConError('horarios/listar_todos', "ID del horario no especificado.");
        }

        if ($this->horarioModel->eliminar($idHorario)) {
            $_SESSION['success'] = "Horario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el horario.";
        }

        header("Location: ?path=horarios/listar_todos");
        exit();
    }

    // Validar los campos del horario
    private function validarHorario($idEmpleado, $diaSemana, $horaInicio, $horaFin) {
        return !empty($idEmpleado) && !empty($diaSemana) && !empty($horaInicio) && !empty($horaFin);
    }

    // Redireccionar con un mensaje de error
    private function redireccionConError($ruta, $mensaje) {
        $_SESSION['error'] = $mensaje;
        header("Location: ?path=$ruta");
        exit();
    }

    // Cargar una vista con datos
    private function cargarVista($vista, $datos = []) {
        $rutaVista = __DIR__ . "/../views/$vista.php";

        if (file_exists($rutaVista)) {
            extract($datos);
            require_once $rutaVista;
        } else {
            die("Error: La vista '$vista' no existe en la ruta: $rutaVista");
        }
    }
}
