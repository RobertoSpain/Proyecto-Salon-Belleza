<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\Lib\DB;

class Cita {
    private $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    // Crear una nueva cita
 public function crear($idEmpleado, $idCliente, $idServicio, $fecha, $hora) {
   $sql = "INSERT INTO citas (id_cliente, id_empleado, id_servicio, fecha, hora) 
        VALUES (:id_cliente, :id_empleado, :id_servicio, :fecha, :hora)";
  $stmt = $this->db->prepare($sql);
   return $stmt->execute([
       ':id_cliente' => $idCliente,
       ':id_empleado' => $idEmpleado,
        ':id_servicio' => $idServicio,
        ':fecha' => $fecha,
        ':hora' => $hora
      ]);
  }

  // Eliminar una cita
  public function eliminar($idCita) {
    $sql = "DELETE FROM citas WHERE id_cita = :id_cita";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':id_cita' => $idCita]);
 }

    // Obtener todas las citas
  public function obtenerTodas() {
  $sql = "SELECT c.id_cita, cl.nombre AS cliente, e.nombre AS empleado, 
            s.nombre AS servicio, c.fecha, c.hora
          FROM citas c
          JOIN clientes cl ON c.id_cliente = cl.id_cliente
          JOIN empleados e ON c.id_empleado = e.id_empleado
          JOIN servicios s ON c.id_servicio = s.id_servicio
          ORDER BY c.fecha, c.hora";
    $stmt = $this->db->query($sql);
   return $stmt->fetchAll(\PDO::FETCH_ASSOC);
 }

  // Obtener citas de un cliente
 public function obtenerPorCliente($idCliente) {
 $sql = "SELECT c.id_cita, e.nombre AS empleado, s.nombre AS servicio, c.fecha, c.hora
        FROM citas c
        JOIN empleados e ON c.id_empleado = e.id_empleado
        JOIN servicios s ON c.id_servicio = s.id_servicio
        WHERE c.id_cliente = :id_cliente
        ORDER BY c.fecha, c.hora";
  $stmt = $this->db->prepare($sql);
  $stmt->execute([':id_cliente' => $idCliente]);
   return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

    // Obtener citas de un empleado
  public function obtenerPorEmpleado($idEmpleado) {
    $sql = "SELECT c.id_cita, cl.nombre AS cliente, s.nombre AS servicio, c.fecha, c.hora
            FROM citas c
            JOIN clientes cl ON c.id_cliente = cl.id_cliente
            JOIN servicios s ON c.id_servicio = s.id_servicio
            WHERE c.id_empleado = :id_empleado
            ORDER BY c.fecha, c.hora";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id_empleado' => $idEmpleado]);
     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Obtener todos los clientes
   public function obtenerClientes() {
      $sql = "SELECT id_cliente, nombre FROM clientes ORDER BY nombre ASC";
      $stmt = $this->db->query($sql);
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Verificar si un horario está disponible para un empleado
  public function verificarDisponibilidad($idEmpleado, $fecha, $hora) {
   $sql = "SELECT COUNT(*) FROM citas 
           WHERE id_empleado = :id_empleado AND fecha = :fecha AND hora = :hora";
   $stmt = $this->db->prepare($sql);
   $stmt->execute([
         ':id_empleado' => $idEmpleado,
         ':fecha' => $fecha,
         ':hora' => $hora
     ]);
      return $stmt->fetchColumn() == 0;
  }

   // Obtener detalles de una cita por ID
  public function obtenerPorId($idCita) {
   $sql = "SELECT c.id_cita, cl.nombre AS cliente, e.nombre AS empleado, 
                  s.nombre AS servicio, c.fecha, c.hora
           FROM citas c
           JOIN clientes cl ON c.id_cliente = cl.id_cliente
           JOIN empleados e ON c.id_empleado = e.id_empleado
           JOIN servicios s ON c.id_servicio = s.id_servicio
           WHERE c.id_cita = :id_cita";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':id_cita' => $idCita]);
     return $stmt->fetch(\PDO::FETCH_ASSOC);
 }

  // Obtener horarios libres
  public function obtenerHorariosLibres($fecha, $horaApertura = '09:00', $horaCierre = '17:00') {
      $horarios = [];
      $hora = strtotime($horaApertura);
      $fin = strtotime($horaCierre);
     while ($hora < $fin) {
          $horarios[] = date('H:i', $hora);
          $hora = strtotime('+30 minutes', $hora);
     }

      $horasOcupadas = $this->obtenerHorasOcupadas($fecha);
      return array_diff($horarios, $horasOcupadas);
  }

    // Obtener horas ocupadas
  public function obtenerHorasOcupadas($fecha) {
     $sql = "SELECT hora FROM citas WHERE fecha = :fecha";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':fecha' => $fecha]);
     return $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }

 // Verificar si una cita pertenece a un cliente
 public function esCitaDelCliente($idCita, $idCliente) {
   $sql = "SELECT COUNT(*) FROM citas 
            WHERE id_cita = :id_cita AND id_cliente = :id_cliente";
   $stmt = $this->db->prepare($sql);
   $stmt->execute([':id_cita' => $idCita, ':id_cliente' => $idCliente]);
   return $stmt->fetchColumn() > 0;
  }

 // Validar horario válido
   public function esHorarioValido($fecha, $hora) {
      $horaActual = date('H:i');
      $fechaActual = date('Y-m-d');
       if ($hora < '09:00' || $hora > '17:00') {
           return false; 
     }

      if ($fecha < $fechaActual || ($fecha === $fechaActual && $hora < $horaActual)) {
           return false; 
      }
        return true;
    }
}
