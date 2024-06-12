<?php
require_once("layouts/header.php");
?>

<a href="index.php?m=nuevo&table=<?php echo $_REQUEST['table']?>" class="btn">NUEVO</a>

<div class="TableContainer">
    <table>
        <tr >
            <?php
            $NTablas = $producto->db->query("DESCRIBE $table");

            if ($NTablas):
                while ($fila = $NTablas->fetch(PDO::FETCH_ASSOC)):
                    $NCampo = $fila['Field'];?>
                    <?php if ($NCampo != 'PartidoID'): ?> 
                        <td class="TableHeader" style="font-weight: bold;"><?php echo strtoupper($NCampo)?></td>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif ?>                     
            <td class="TableHeader" style="font-weight: bold;">ACCIONES</td>
        </tr>
        <tbody>
            <?php 
            if(!empty($dato)): 
                foreach ($dato as $key => $fila):  ?>
                    <tr>
                        <?php foreach ($fila as $v => $value): ?>
                            <?php if ($v != 'PartidoID'): ?> 
                                <td><?php echo $value?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <a class="btn" href="index.php?m=editar&id=<?php echo $fila['PartidoID']?>&table=<?php echo $_REQUEST['table']?>">EDITAR</a>
                            <a class="btn" href="index.php?m=eliminar&id=<?php echo $fila['PartidoID']?>&table=<?php echo $_REQUEST['table']?>" onclick="return confirm('ESTA SEGURO');">ELIMINAR</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">NO HAY REGISTROS</td>
                </tr>
                <?php endif ?>    
        </tbody>
    </table>
</div>

