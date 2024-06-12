<?php
class Modelo{
    private $Modelo;
    private $db;
    private $datos;
    
    // Constructor
    public function __construct(){
        $this->Modelo = array();
        $this->db = new PDO('mysql:host=localhost;dbname=bd_Campeonato', "root", "");
    }

    // Método para insertar datos en una tabla
    public function insertar($tabla, $data) {
        $consulta = "INSERT INTO $tabla VALUES ($data)";
        $resultado = $this->db->query($consulta);
        return $resultado ? true : false;
    }


    // Método para mostrar datos de una tabla con una condición
    public function mostrar($tabla, $condicion){
        $consulta = "SELECT * FROM $tabla WHERE $condicion";
        $resu = $this->db->query($consulta);
        while($filas = $resu->fetchAll(PDO::FETCH_ASSOC)) {
            $this->datos[] = $filas;
        }
        return $this->datos;
    }
    
    // Método para actualizar datos en una tabla
    public function actualizar($tabla, $data, $condicion) {
        $consulta = "UPDATE $tabla SET $data WHERE $condicion";
        $resultado = $this->db->query($consulta);
        return $resultado ? true : false;
    }

    // Método para eliminar datos de una tabla
    public function eliminar($tabla, $condicion) {
        $eli = "DELETE FROM $tabla WHERE $condicion";
        $res = $this->db->query($eli);
        return $res ? true : false;
    }
}
?>
