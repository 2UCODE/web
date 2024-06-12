<?php
require_once("layouts/header.php");

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$dni_usuario = $_SESSION['usuario'];

require_once('../Conexion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn"]) && $_POST["btn"] == "GUARDAR") {
    // Procesar el formulario y guardar los datos en la base de datos
    $nomCompeticion = $_POST['NomCompeticion'];
    $tipoComp = $_POST['tipoComp'];
    $tipoDeporteComp = $_POST['tipoDeporteComp'];
    $nroPartComp = $_POST['nroPartComp'];
    $dni_usuario = $_POST['DNI'];

    // Insertar la competición en la base de datos
    $queryInsertCompeticion = "INSERT INTO Competicion (NomCompeticion, TipoComp, TipoDeporteComp, NroPartComp, DNI) VALUES ('$nomCompeticion', '$tipoComp', '$tipoDeporteComp', $nroPartComp, '$dni_usuario')";
    mysqli_query($conexion, $queryInsertCompeticion);

    // Obtener el ID de la competición recién insertada
    $idCompeticion = mysqli_insert_id($conexion);

    // Insertar los equipos automáticamente
    for ($i = 1; $i <= $nroPartComp; $i++) {
        $nombreEquipo = "Equipo " . $i;

        // Insertar el equipo en la base de datos utilizando el idCompeticion
        $queryInsertEquipo = "INSERT INTO Equipo (Nombre, idCompeticion) VALUES ('$nombreEquipo', $idCompeticion)";
        mysqli_query($conexion, $queryInsertEquipo);
    }

    // Redirigir al índice principal después de guardar
    header("Location: index.php");
    exit(); // Asegura que el script se detenga después de la redirección
}
?>

<h1 class="text-center">NUEVA COMPETICIÓN</h1>
<form action="index.php?m=guardar" method="post">
    <div style="display: flex; flex-direction: column; max-width: 500px; margin: 0 auto;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="NomCompeticion" style="width: 250px;">Nombre de la competición:</label>
            <input type="text" id="NomCompeticion" name="NomCompeticion" style="flex: 1;"> 
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="tipoComp" style="width: 250px;">Tipo de Competición:</label>
            <select id="tipoComp" name="tipoComp" style="flex: 1;">
                <option value="Liga">Liga</option>
                <option value="Eliminacion directa" disabled>Eliminacion directa (EN DESARROLLO)</option>
                <option value="Liga y Eliminacion" disabled>Liga y Eliminacion (EN DESARROLLO)</option>
            </select>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="tipoDeporteComp" style="width: 250px;">Tipo de Deporte de la Competición:</label>
            <select id="tipoDeporteComp" name="tipoDeporteComp" style="flex: 1;">
                <option value="Futbol">Fútbol</option>
                <option value="Baloncesto" disabled>Baloncesto (EN DESARROLLO)</option>
                <option value="Tenis" disabled>Tenis (EN DESARROLLO)</option>
            </select>
        </div>
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="nroPartComp" style="width: 250px;">Número de Participantes:</label>
            <input type="number" id="nroPartComp" name="nroPartComp" min="1" max="10" style="flex: 1;"> 
        </div>
        <div style="display: none;">
            <input type="text" id="DNI" name="DNI" value="<?php echo $dni_usuario; ?>"> 
        </div>
        <div style="margin-bottom: 10px;">
            <input type="submit" class="btn" name="btn" value="GUARDAR" style="width: 100%;"> 
        </div>
    </div>
    <input type="hidden" name="m" value="guardar">
</form>

<?php require_once("layouts/footer.php"); ?>
