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
        $competicion = new Modelo();
        $dato = $competicion->mostrar("Competicion", "1");
        require_once("vista/index.php");
    }

    // Método para nuevo
    public static function nuevo() {
        require_once("vista/nuevo.php");
    }

    // Método para guardar
    public static function guardar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn"]) && $_POST["btn"] == "GUARDAR") {
            // Validar los datos del formulario
            $NomCompeticion = $_POST['NomCompeticion'];
            $tipoComp = $_POST['tipoComp'];
            $tipoDeporteComp = $_POST['tipoDeporteComp'];
            $nroPartComp = $_POST['nroPartComp'];
            $dni = $_POST['DNI'];

            // Procesar el formulario y guardar los datos en la base de datos
            $competicion = new Modelo();
            $data = "NULL, '$NomCompeticion', '$tipoComp', '$tipoDeporteComp', $nroPartComp, '$dni'";
            $competicion->insertar("Competicion", $data);

            // Redirigir al índice principal después de guardar
            header("Location: index.php");
            exit(); // Asegura que el script se detenga después de la redirección
        }
    }

    // Método para editar
    public static function editar() {
        if (isset($_GET['idCompeticion'])) {
            $idCompeticion = $_GET['idCompeticion'];
            $competicion = new Modelo();
            $dato = $competicion->mostrar("Competicion", "idCompeticion='$idCompeticion'");
            require_once("vista/editar.php");
        }
    }

    // Método para actualizar
    public static function actualizar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn"]) && $_POST["btn"] == "ACTUALIZAR") {
            // Validar los datos del formulario
            $idCompeticion = $_POST['idCompeticion'];
            $NomCompeticion = $_POST['NomCompeticion'];
            $tipoComp = $_POST['tipoComp'];
            $tipoDeporteComp = $_POST['tipoDeporteComp'];
            $nroPartComp = $_POST['nroPartComp'];
            $dni = $_POST['DNI'];

            // Procesar el formulario y actualizar los datos en la base de datos
            $competicion = new Modelo();
            $data = "TipoComp='$tipoComp', NomCompeticion='$NomCompeticion', TipoDeporteComp='$tipoDeporteComp', NroPartComp=$nroPartComp, DNI='$dni'";
            $competicion->actualizar("Competicion", $data, "idCompeticion='$idCompeticion'");

            // Redirigir al índice principal después de actualizar
            header("Location: index.php");
            exit(); // Asegura que el script se detenga después de la redirección
        }
    }

    // Método para eliminar
    public static function eliminar() {
        if (isset($_GET['idCompeticion'])) {
            $idCompeticion = $_GET['idCompeticion'];
            $competicion = new Modelo();
            $competicion->eliminar("Competicion", "idCompeticion='$idCompeticion'");

            // Redirigir al índice principal después de eliminar
            header("Location: index.php");
            exit(); // Asegura que el script se detenga después de la redirección
        }
    }
}
?>
