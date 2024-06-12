<?php
require_once("layouts/header.php");

// Incluir el archivo de conexión a la base de datos
require_once("modelo/index.php");

// Instanciar el modelo
$modelo = new Modelo();

// Obtener datos de la competición para editar
$id_competicion = $_GET['idCompeticion']; // Obtener el ID de la competición desde la URL
$dato = $modelo->mostrar("Competicion", "idCompeticion = '$id_competicion'");

// Procesar la actualización de la competición
if(isset($_GET['m']) && $_GET['m'] == 'actualizar') {
    $idCompeticion = $_GET['idCompeticion'];
    $NomCompeticion = $_GET['NomCompeticion'];
    $tipoComp = $_GET['tipoComp'];
    $tipoDeporteComp = $_GET['tipoDeporteComp'];
    $nroPartComp = $_GET['nroPartComp'];
    $dni = $_GET['DNI'];
    $data = "NomCompeticion='$NomCompeticion',TipoComp='$tipoComp', TipoDeporteComp='$tipoDeporteComp', NroPartComp=$nroPartComp, DNI='$dni'";
    $modelo->actualizar("Competicion", $data, "idCompeticion='$idCompeticion'");
header("Location: index.php");
}

?>
<h1 class="text-center">EDITAR COMPETICIÓN</h1>
<form action="index.php" method="get">
    <?php foreach ($dato as $key => $value): ?>
        <?php foreach($value as $v): ?>
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="NomCompeticion" style="width: 250px;">Nombre de la competición:</label>
                <input type="text" id="NomCompeticion" name="NomCompeticion" value="<?php echo $v['NomCompeticion'] ?>">
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="tipoComp" style="width: 250px;">Tipo de Competición:</label>
                <select id="tipoComp" name="tipoComp">
                    <option value="Liga" <?php if($v['TipoComp'] == 'Liga') echo 'selected'; ?>>Liga</option>
                    <option value="Eliminacion directa" disabled <?php if($v['TipoComp'] == 'Eliminacion directa') echo 'selected'; ?>>Eliminacion directa (EN DESARROLLO)</option>
                    <option value="Liga y Eliminacion" disabled <?php if($v['TipoComp'] == 'Liga y Eliminacion') echo 'selected'; ?>>Liga y Eliminacion (EN DESARROLLO)</option>
                </select>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="tipoDeporteComp" style="width: 250px;">Tipo de Deporte de la Competición:</label>
                <select id="tipoDeporteComp" name="tipoDeporteComp">
                    <option value="Futbol" <?php if($v['TipoDeporteComp'] == 'Futbol') echo 'selected'; ?>>Fútbol</option>
                    <option value="Baloncesto" disabled <?php if($v['TipoDeporteComp'] == 'Baloncesto') echo 'selected'; ?>>Baloncesto (EN DESARROLLO)</option>
                    <option value="Tenis" disabled <?php if($v['TipoDeporteComp'] == 'Tenis') echo 'selected'; ?>>Tenis (EN DESARROLLO)</option>
                </select>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="nroPartComp" style="width: 250px;">Número de Participantes:</label>
                <input type="number" id="nroPartComp" name="nroPartComp" min="1" max="10" value="<?php echo $v['NroPartComp'] ?>">
            </div>
            <input type="submit" class="btn" name="btn" value="ACTUALIZAR"> <br> 
            <input type="hidden" name="m" value="actualizar">
            <?php
        endforeach;
    endforeach;
    ?>
</form>
<?php
require_once("layouts/footer.php");
?>
