<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\Lib\DB;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = DB::connect(); 
    }

    public function verificarCredenciales($email, $password) {
        try {
            // Consulta SQL para obtener el usuario por email
            $sql = "
                SELECT 'cliente' AS tipo, id_cliente AS id, nombre, password, 'cliente' AS rol
                FROM clientes 
                WHERE email = :email
                UNION ALL
                SELECT 'empleado' AS tipo, id_empleado AS id, nombre, password, COALESCE(rol, 'empleado') AS rol
                FROM empleados 
                WHERE email = :email
            ";
    
            // Preparar y ejecutar la consulta
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            // Verificar si la contraseña coincide
            if ($usuario && password_verify($password, $usuario['password'])) {
                return $usuario;
            }
    
            return false;
    
        } catch (\Exception $e) {
            error_log("Error al verificar credenciales: " . $e->getMessage());
            return false;
        }
    }
    
   
    public function registrarUsuario($nombre, $correo, $telefono, $fecha_nacimiento, $contrasena) {
        try {
            $sql = "INSERT INTO clientes (nombre, email, telefono, fecha_nacimiento, password) 
                    VALUES (:nombre, :correo, :telefono, :fecha_nacimiento, :contrasena)";
            
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
            $stmt->bindParam(':contrasena', $contrasena);
    
            return $stmt->execute();
    
        } catch (\PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }
}    