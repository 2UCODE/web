<?php
require_once("layouts/header.php");
?>

<h2 class="text-center">NUEVO EQUIPO</h2>
<form action="" method="get">
    <?php
    require_once("config.php"); // Asegurémonos de que se cargue la configuración de la base de datos

    // Conexión a la base de datos
    $db = new PDO('mysql:host=localhost;dbname=bd_Campeonato', "root", "");

    // Consulta para obtener la estructura de la tabla "Equipo"
    $NTablas = $db->query("DESCRIBE Equipo");

    if ($NTablas):
        while ($fila = $NTablas->fetch(PDO::FETCH_ASSOC)):
            $NCampo = $fila['Field'];
            if (!str_contains($NCampo, 'EquipoID')):?> 
                
                <input class="Input" type="text" placeholder="INGRESE <?php echo strtoupper($NCampo)?>:" name="<?php echo strval($NCampo)?>"> <br>

            <?php endif ?> 
        <?php endwhile; ?>
    <?php endif ?>  

    <input type="submit" class="btn" name="btn" value="GUARDAR"> <br>
    <input type="hidden" name="m" value="guardar">
    <input type="hidden" name="table" value="Equipo">
</form>

<?php
require_once("layouts/footer.php");
?>
