<?php
namespace Rober\SalonBelleza\Controllers;

use Rober\SalonBelleza\Models\Servicio;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ServiciosController {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    // Acción para listar servicios
public function listar() {
   // Obtener todos los servicios desde el modelo
   require_once __DIR__ . '/../models/Servicio.php';
   $servicioModel = new \Rober\SalonBelleza\Models\Servicio();
   $servicios = $servicioModel->obtenerTodos();
    
   // Cargar la vista y pasarle los servicios
      require_once __DIR__ . '/../views/servicios/listar.php';
    }
    
    // Acción para agregar un nuevo servicio
  public function agregar() {
  session_start();
  $this->verificarAcceso('encargado');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $nombre = $_POST['nombre'] ?? '';
   $descripcion = $_POST['descripcion'] ?? '';
   $precio = $_POST['precio'] ?? 0;
   $duracion = $_POST['duracion'] ?? 0;

  if (empty($nombre) || empty($precio) || empty($duracion)) {
     $_SESSION['error'] = "Todos los campos obligatorios deben ser completados.";
      require_once __DIR__ . '/../views/servicios/agregar.php';
      return;
            }

  $resultado = $this->servicioModel->agregar($nombre, $descripcion, $precio, $duracion);

  if ($resultado) {
     $_SESSION['success'] = "Servicio agregado con éxito.";
     header('Location: ?path=servicios/listar');
      exit();
       } else {
           $_SESSION['error'] = "Error al agregar el servicio.";
       }
   }

    require_once __DIR__ . '/../views/servicios/agregar.php';
    }

    // Acción para eliminar un servicio
  public function eliminar($id) {
     session_start();
    $this->verificarAcceso('encargado');

   $resultado = $this->servicioModel->eliminar($id);

   if ($resultado) {
      $_SESSION['success'] = "Servicio eliminado con éxito.";
    } else {
        $_SESSION['error'] = "Error al eliminar el servicio.";
    }

    header('Location: ?path=servicios/listar');
     exit();
  }

    // Acción para editar un servicio
  public function editar($id) {
     session_start();
     $this->verificarAcceso('encargado');

   $servicio = $this->servicioModel->obtenerPorId($id);

   if (!$servicio) {
     $_SESSION['error'] = "El servicio no existe.";
    header('Location: ?path=servicios/listar');
       exit();
     }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $nombre = $_POST['nombre'] ?? $servicio['nombre'];
     $descripcion = $_POST['descripcion'] ?? $servicio['descripcion'];
     $precio = $_POST['precio'] ?? $servicio['precio'];
     $duracion = $_POST['duracion'] ?? $servicio['duracion'];

     $resultado = $this->servicioModel->editar($id, $nombre, $descripcion, $precio, $duracion);

  if ($resultado) {
          $_SESSION['success'] = "Servicio editado correctamente.";
           header('Location: ?path=servicios/listar');
           exit();
        } else {
             $_SESSION['error'] = "Error al editar el servicio.";
        }
   }
        require_once __DIR__ . '/../views/servicios/editar.php';
    }

 // Método para verificar acceso
  private function verificarAcceso($rolesPermitidos) {
    echo "Rol permitido: ";
    var_dump($rolesPermitidos);
    echo "<br>Rol del usuario: ";
    var_dump($_SESSION['usuario']['rol']);
    exit();
    
    if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], (array)$rolesPermitidos)) {
      $_SESSION['error'] = "Acceso denegado. No tienes permisos.";
        header("Location: ?path=sesion/login");
         exit();
       }
   }
}      