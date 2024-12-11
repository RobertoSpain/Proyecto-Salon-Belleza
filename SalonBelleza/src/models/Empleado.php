<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\lib\DB;
use Exception;

class Empleado {
  private $db;

  public function __construct() {
     $this->db = DB::connect();
 }

 // Obtener un empleado por su ID
 public function obtenerPorId($idEmpleado) {
     $sql = "SELECT * FROM empleados WHERE id_empleado = :id_empleado";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':id_empleado' => $idEmpleado]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
 }

 // Registrar un nuevo empleado
public function registrar($nombre, $especialidad, $email, $telefono, $password) {
  try {
        // Verificar si el email ya existe
     $sql = "SELECT COUNT(*) FROM empleados WHERE email = :email";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':email' => $email]);
     if ($stmt->fetchColumn() > 0) {
     throw new Exception("El email ya está registrado.");
          }

  // Insertar el empleado
     $sql = "INSERT INTO empleados (nombre, especialidad, email, telefono, password, rol) 
             VALUES (:nombre, :especialidad, :email, :telefono, :password, 'empleado')";
     $stmt = $this->db->prepare($sql);

  return $stmt->execute([
            ':nombre' => $nombre,
            ':especialidad' => $especialidad,
            ':email' => $email,
            ':telefono' => $telefono,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
      ]);

    } catch (Exception $e) {
       error_log("Error al registrar empleado: " . $e->getMessage());
        return false;
      }
 }

    // Obtener todos los empleados
  public function obtenerTodos() {
     $sql = "SELECT * FROM empleados";
     $stmt = $this->db->query($sql);
     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Obtener empleados que pueden realizar un servicio específico
  public function obtenerPorServicio($idServicio) {
     $sql = "SELECT e.id_empleado, e.nombre, e.especialidad
             FROM empleados e
             JOIN empleados_servicios es ON e.id_empleado = es.id_empleado
             WHERE es.id_servicio = :id_servicio";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':id_servicio' => $idServicio]);
     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Verificar si un empleado existe por su ID
    public function existeEmpleado($idEmpleado) {
        $sql = "SELECT COUNT(*) FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_empleado' => $idEmpleado]);
        return $stmt->fetchColumn() > 0;
    }

    // Actualizar la información de un empleado
  public function actualizar($idEmpleado, $nombre, $especialidad, $email, $telefono) {
     $sql = "SELECT COUNT(*) FROM empleados WHERE email = :email AND id_empleado != :id_empleado";
     $stmt = $this->db->prepare($sql);
     $stmt->execute([':email' => $email, ':id_empleado' => $idEmpleado]);
     if ($stmt->fetchColumn() > 0) {
         throw new Exception("El email ya está registrado por otro empleado.");
   }

  // Actualizar datos del empleado
    $sql = "UPDATE empleados SET 
                nombre = :nombre,
                especialidad = :especialidad,
                email = :email,
                telefono = :telefono
            WHERE id_empleado = :id_empleado";
    $stmt = $this->db->prepare($sql);
     return $stmt->execute([
      ':id_empleado' => $idEmpleado,
        ':nombre' => $nombre,
        ':especialidad' => $especialidad,
        ':email' => $email,
        ':telefono' => $telefono,
      ]);
  }

    // Eliminar un empleado por su ID
    public function eliminar($idEmpleado) {
        if (!$this->existeEmpleado($idEmpleado)) {
            throw new Exception("El empleado no existe.");
        }

        $sql = "DELETE FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_empleado' => $idEmpleado]);
    }
}
