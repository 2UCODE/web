<?php
require_once("modelo/index.php");

class modeloController {
    // Atributos de la clase
    private $model;
    
    public function __construct() {
        $this->model = new Modelo();
    }

    // Método para mostrar
    public static function index() {
        $usuario = new Modelo();
        $dato = $usuario->mostrar("Usuario", "1");
        require_once("vista/index.php");
    }

    // Método para nuevo
    public static function nuevo() {
        require_once("vista/nuevo.php");
    }

    // Método para guardar
    public static function guardar() {
        $dni = $_REQUEST['DNI'];
        $apellido_paterno = $_REQUEST['apellido_paterno'];
        $apellido_materno = $_REQUEST['apellido_materno'];
        $nombres = $_REQUEST['nombres'];
        $contrasena = $_REQUEST['contrasena'];
        $data = "'$dni','$apellido_paterno','$apellido_materno','$nombres','$contrasena'";
        $usuario = new Modelo();
        $usuario->insertar("Usuario", $data);
        header("location:".urlsite);
    }

    // Método para editar
    public static function editar() {
        $dni = $_REQUEST['DNI'];
        $usuario = new Modelo();
        $dato = $usuario->mostrar("Usuario", "DNI='$dni'");
        require_once("vista/editar.php");
    }

    // Método para actualizar
    public static function actualizar() {
        $dni = $_REQUEST['DNI'];
        $apellido_paterno = $_REQUEST['apellido_paterno'];
        $apellido_materno = $_REQUEST['apellido_materno'];
        $nombres = $_REQUEST['nombres'];
        $contrasena = $_REQUEST['contrasena'];
        $data = "ApellidoPaterno='$apellido_paterno', ApellidoMaterno='$apellido_materno', Nombres='$nombres', Contrasena='$contrasena'";
        $usuario = new Modelo();
        $usuario->actualizar("Usuario", $data, "DNI='$dni'");
        header("location:".urlsite);
    }

    // Método para eliminar
    public static function eliminar() {
        $dni = $_REQUEST['DNI'];
        $usuario = new Modelo();
        $usuario->eliminar("Usuario", "DNI='$dni'");
        header("location:".urlsite);
    }
}
?>
