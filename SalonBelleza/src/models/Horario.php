<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\Lib\DB;


class Horario {
    private $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    // Obtener horarios por empleado con su nombre
    public function obtenerPorEmpleado($idEmpleado) {
        $sql = "SELECT h.id_horario, h.dia_semana, h.hora_inicio, h.hora_fin, 
                       e.nombre AS nombre_empleado
                FROM horarios h
                INNER JOIN empleados e ON h.id_empleado = e.id_empleado
                WHERE h.id_empleado = :id_empleado 
                ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), 
                         h.hora_inicio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_empleado' => $idEmpleado]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener todos los horarios con información completa del empleado
    public function obtenerTodos() {
        $sql = "SELECT h.id_horario, h.dia_semana, h.hora_inicio, h.hora_fin, 
                       e.id_empleado, e.nombre AS nombre_empleado
                FROM horarios h
                INNER JOIN empleados e ON h.id_empleado = e.id_empleado
                ORDER BY e.nombre, 
                         FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), 
                         h.hora_inicio";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Agregar un nuevo horario
    public function agregar($idEmpleado, $diaSemana, $horaInicio, $horaFin) {
        $sql = "INSERT INTO horarios (id_empleado, dia_semana, hora_inicio, hora_fin) 
                VALUES (:id_empleado, :dia_semana, :hora_inicio, :hora_fin)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_empleado' => $idEmpleado,
            ':dia_semana' => $diaSemana,
            ':hora_inicio' => $horaInicio,
            ':hora_fin' => $horaFin
        ]);
    }

    // Actualizar un horario existente
    public function actualizar($idHorario, $idEmpleado, $diaSemana, $horaInicio, $horaFin) {
        $sql = "UPDATE horarios 
                SET id_empleado = :id_empleado, dia_semana = :dia_semana, hora_inicio = :hora_inicio, hora_fin = :hora_fin
                WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_horario' => $idHorario,
            ':id_empleado' => $idEmpleado,
            ':dia_semana' => $diaSemana,
            ':hora_inicio' => $horaInicio,
            ':hora_fin' => $horaFin
        ]);
    }

    // Eliminar un horario
    public function eliminar($idHorario) {
        $sql = "DELETE FROM horarios WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_horario' => $idHorario]);
    }

    // Verificar si un horario ya existe para evitar duplicados
    public function existeHorario($idEmpleado, $diaSemana, $horaInicio, $horaFin) {
        $sql = "SELECT COUNT(*) FROM horarios 
                WHERE id_empleado = :id_empleado 
                AND dia_semana = :dia_semana 
                AND hora_inicio = :hora_inicio 
                AND hora_fin = :hora_fin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_empleado' => $idEmpleado,
            ':dia_semana' => $diaSemana,
            ':hora_inicio' => $horaInicio,
            ':hora_fin' => $horaFin
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // Obtener citas libres por empleado y fecha específica
    public function obtenerCitasLibres($idEmpleado, $fecha) {
        $query = "SELECT hora_inicio, hora_fin 
                  FROM horarios 
                  WHERE id_empleado = :id_empleado AND dia_semana = DAYNAME(:fecha)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_empleado' => $idEmpleado, ':fecha' => $fecha]);
        $horario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$horario) {
            return [];
        }

        $horaInicio = new \DateTime($horario['hora_inicio']);
        $horaFin = new \DateTime($horario['hora_fin']);

        // Obtener citas ocupadas
        $query = "SELECT hora FROM citas WHERE id_empleado = :id_empleado AND fecha = :fecha";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_empleado' => $idEmpleado, ':fecha' => $fecha]);
        $citasOcupadas = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        // Generar horas libres
        $intervalo = new \DateInterval('PT30M'); 
        $horasDisponibles = [];
        while ($horaInicio < $horaFin) {
            $horaActual = $horaInicio->format('H:i:s');
            if (!in_array($horaActual, $citasOcupadas)) {
                $horasDisponibles[] = $horaActual;
            }
            $horaInicio->add($intervalo);
        }
        return $horasDisponibles;
    }

    public function obtenerPorId($idHorario) {
        $sql = "SELECT * FROM horarios WHERE id_horario = :id_horario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_horario' => $idHorario]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
}