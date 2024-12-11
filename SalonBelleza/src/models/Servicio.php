<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\Lib\DB;

class Servicio {
    private $db;

    public function __construct() {
        try {
            $this->db = DB::connect();
        } catch (\Exception $e) {
            echo "Error de conexión: " . $e->getMessage() . "<br>";
        }
    }
    
    // Método para obtener todos los servicios
    public function obtenerTodos() {
        $sql = "SELECT * FROM servicios ORDER BY nombre";
        $stmt = $this->db->query($sql);
    
        if ($stmt === false) {
            return [];
        }
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo servicio
    public function agregar($nombre, $descripcion, $precio, $duracion) {
        $sql = "INSERT INTO servicios (nombre, descripcion, precio, duracion)
                VALUES (:nombre, :descripcion, :precio, :duracion)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':duracion' => $duracion,
        ]);
    }

    // Método para obtener un servicio por ID
    public function obtenerPorId($idServicio) {
        $sql = "SELECT * FROM servicios WHERE id_servicio = :id_servicio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_servicio' => $idServicio]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Método para editar un servicio
    public function editar($idServicio, $nombre, $descripcion, $precio, $duracion) {
        $sql = "UPDATE servicios 
                SET nombre = :nombre, descripcion = :descripcion, precio = :precio, duracion = :duracion 
                WHERE id_servicio = :id_servicio";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_servicio' => $idServicio,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':duracion' => $duracion,
        ]);
    }

    // Método para eliminar un servicio
    public function eliminar($idServicio) {
        $sql = "DELETE FROM servicios WHERE id_servicio = :id_servicio";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id_servicio' => $idServicio]);
    }

    // Método para validar si existe un servicio
    public function existeServicio($idServicio) {
        $sql = "SELECT COUNT(*) AS total FROM servicios WHERE id_servicio = :id_servicio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_servicio' => $idServicio]);

        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }

    // Método para obtener servicios por nombre (búsqueda)
    public function obtenerPorNombre($nombre) {
        $sql = "SELECT * FROM servicios WHERE nombre LIKE :nombre ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nombre' => '%' . $nombre . '%']);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Método para obtener servicios por precio mínimo y máximo
    public function obtenerPorRangoPrecio($min, $max) {
        $sql = "SELECT * FROM servicios WHERE precio BETWEEN :min AND :max ORDER BY precio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':min' => $min, ':max' => $max]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Método para obtener duración del servicio
    public function obtenerDuracion($idServicio) {
        $sql = "SELECT duracion FROM servicios WHERE id_servicio = :id_servicio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_servicio' => $idServicio]);

        return $stmt->fetchColumn() ?? 30; 
    }

    // Método para asignar un servicio a un empleado
    public function asignarServicioAEmpleado($idEmpleado, $idServicio) {
        $sql = "INSERT INTO empleados_servicios (id_empleado, id_servicio)
                VALUES (:id_empleado, :id_servicio)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_empleado' => $idEmpleado,
            ':id_servicio' => $idServicio,
        ]);
    }
}
