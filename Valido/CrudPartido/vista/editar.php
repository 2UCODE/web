<?php
    require_once("layouts/header.php");
?>

<h2 class="text-center">EDITAR</h2>
<form action="" method="get">
    <?php
        $NTablas = $producto->db->query("DESCRIBE $table");

        if ($NTablas):
            $filas = $NTablas->fetchAll(PDO::FETCH_ASSOC);
            foreach ($filas as $key):
                $NCampo = $key['Field'];
                    if (!str_contains($NCampo, 'PartidoID')):?> 

                        <input class="Input" type="text" value="<?php echo $dato[0][strval($NCampo)] ?>" name="<?php echo strval($NCampo)?>"> <br>

                    <?php else:?>

                        <input type="hidden" value="<?php echo $dato[0][strval($NCampo)] ?>" name="id"> <br>

                    <?php endif ?> 

            <?php endforeach; ?>
        <?php endif ?>
        <input type="submit" class="btn" name="btn" value="ACTUALIZAR"> <br>
        <input type="hidden" name="m" value="actualizar">
        <input type="hidden" name="table" value="<?php echo $_REQUEST['table']?>">
</form>
