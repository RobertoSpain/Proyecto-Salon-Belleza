<?php
namespace Rober\SalonBelleza\Models;

use Rober\SalonBelleza\Lib\DB;
use PDO;
use Exception;

class Cliente {
    private $db;

    public function __construct() {
        // Conectar a la base de datos con manejo de errores
        $this->db = DB::connect();
        if (!$this->db) {
            throw new Exception("Error: No se pudo conectar a la base de datos.");
        }
    }

    /*
     * Registrar un nuevo cliente.
     * 
     * @param string $nombre
     * @param string $email
     * @param string $telefono
     * @param string $fecha_nacimiento
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function registrar($nombre, $email, $telefono, $fecha_nacimiento, $password) {
        try {
            // Verificar si el email ya existe
            if ($this->existeEmail($email)) {
                throw new Exception("El email ya está registrado.");
            }

            // Insertar cliente
            $sql = "INSERT INTO clientes (nombre, email, telefono, fecha_nacimiento, password) 
                    VALUES (:nombre, :email, :telefono, :fecha_nacimiento, :password)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al registrar el cliente: " . $e->getMessage());
        }
    }

    /**
     * Verificar si un email ya existe en la base de datos.
     * 
     * @param string $email
     * @return bool
     */
    public function existeEmail($email) {
        $sql = "SELECT COUNT(*) FROM clientes WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Listar todos los clientes.
     * 
     * @return array
     */
    public function listar() {
        $sql = "SELECT id_cliente, nombre, email, telefono, fecha_nacimiento FROM clientes";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
