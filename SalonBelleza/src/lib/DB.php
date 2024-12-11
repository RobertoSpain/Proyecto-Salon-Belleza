<?php
namespace Rober\SalonBelleza\Lib;

require_once __DIR__ . '/../../config/config.php';

use PDO;
use PDOException;

class DB {
    private static $instance = null;

    private function __construct() {} 
    private function __clone() {}     

 public static function connect() {
   if (self::$instance === null) {
      try {
           $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
           self::$instance = new PDO($dsn, DB_USER, DB_PASS);
           self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           } catch (PDOException $e) {
               die("Error de conexión: " . $e->getMessage());
          }
        }
        return self::$instance;
    }
}
