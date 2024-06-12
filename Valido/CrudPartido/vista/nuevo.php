<?php
require_once("layouts/header.php");
?>

<h2 class="text-center">NUEVO PARTIDO</h2>
<form action="" method="get">
    <?php
    require_once("config.php"); // Asegurémonos de que se cargue la configuración de la base de datos

    // Conexión a la base de datos
    $db = new PDO('mysql:host=localhost;dbname=bd_Campeonato', "root", "");

    // Consulta para obtener la estructura de la tabla "Partido"
    $NTablas = $db->query("DESCRIBE Partido");

    if ($NTablas):
        while ($fila = $NTablas->fetch(PDO::FETCH_ASSOC)):
            $NCampo = $fila['Field'];
            if (!str_contains($NCampo, 'PartidoID')):?> 
                
                <input class="Input" type="text" placeholder="INGRESE <?php echo strtoupper($NCampo)?>:" name="<?php echo strval($NCampo)?>"> <br>

            <?php endif ?> 
        <?php endwhile; ?>
    <?php endif ?>  

    <input type="submit" class="btn" name="btn" value="GUARDAR"> <br>
    <input type="hidden" name="m" value="guardar">
    <input type="hidden" name="table" value="Partido">
</form>

<?php
require_once("layouts/footer.php");
?>
