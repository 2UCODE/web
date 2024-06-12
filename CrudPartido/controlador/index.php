<?php

require_once("modelo/index.php");

class modeloController{
    // Atributos de la clase
    private $model;

    public function __construct(){
        $this->model = new Modelo();
    }
    
    // mostrar
    static function index(){
        $table = "Partido";
        $producto   = new Modelo();
        $dato       = $producto->mostrar($table,"1");
        require_once("vista/index.php");
    }

    //nuevo
    static function nuevo(){ 
        $table = "Partido";
        require_once("vista/nuevo.php");
    }

    //guardar
    static function guardar(){
        $table = "Partido"; 

        $data = "";
        $producto = new Modelo();
        $NCampo = $producto->db->query("DESCRIBE $table");
        if ($NCampo) {
           $filas = $NCampo->fetchAll(PDO::FETCH_ASSOC);
            
           foreach ($filas as $key) {
                if ($key['Field'] != 'PartidoID') { 
                    if (str_contains($key['Type'], 'varchar') || str_contains($key['Type'], 'date') ) {
                       $data .="'";
                       $data .= $_REQUEST[$key['Field']];
                       $data .= "',";    
                    }
                    else{
                        $data .= $_REQUEST[$key['Field']];
                        $data .= ",";
                    }
                }
           }
           $data = substr($data, 0, -1);
        }

        $dato = $producto->insertar($table,$data);

        // Redirige a la página principal
        header("location:".urlsite);
    }


    //editar
    static function editar(){    
        $id = $_REQUEST['id'];
        $table = "Partido";
        $producto = new Modelo();
        $dato = $producto->mostrar($table,"PartidoID=".$id);        
        require_once("vista/editar.php");
    }

    //actualizar
    static function actualizar(){
        $id = $_REQUEST['id'];
        $table = "Partido";

        $data = "";
        $producto = new Modelo();
        $NCampo = $producto->db->query("DESCRIBE $table");
        if ($NCampo) {
           $filas = $NCampo->fetchAll(PDO::FETCH_ASSOC);
            
           foreach ($filas as $key) {
                if ($key['Field'] != 'PartidoID') { 
                    $data .= $key['Field']."=";
                    $data .="'";
                    $data .= $_REQUEST[$key['Field']];
                    $data .= "',";
                }
           }
           $data = substr($data, 0, -1);
        }

        $dato = $producto->actualizar($table,$data,"PartidoID=".$id);
        // Redirige a la página principal
        header("location:".urlsite);
    }

   
    //eliminar
    static function eliminar(){    
        $id = $_REQUEST['id'];
        $table = "Partido";
        $producto = new Modelo();
        $dato = $producto->eliminar($table,"PartidoID=".$id);
        // Redirige a la página principal
        header("location:".urlsite);
    }
}
?>
