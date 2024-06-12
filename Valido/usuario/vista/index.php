<?php
require_once("layouts/header.php");
?>
<a href="index.php?m=nuevo" class="btn">NUEVO</a>
<table>
    <tr>
        <td>DNI</td>
        <td>Apellido Paterno</td>
        <td>Apellido Materno</td>
        <td>Nombres</td>
        <td>ACCIÓN</td>
    </tr>
    <tbody>
        <?php if(!empty($dato)): ?>
            <?php foreach ($dato as $key => $value): ?>
                <?php foreach($value as $v): ?>
                    <tr>
                        <td><?php echo isset($v['DNI']) ? $v['DNI'] : ''; ?></td>
                        <td><?php echo isset($v['ApellidoPaterno']) ? $v['ApellidoPaterno'] : ''; ?></td>
                        <td><?php echo isset($v['ApellidoMaterno']) ? $v['ApellidoMaterno'] : ''; ?></td>
                        <td><?php echo isset($v['Nombres']) ? $v['Nombres'] : ''; ?></td>
                        <td>
                            <a class="btn" href="index.php?m=editar&DNI=<?php echo isset($v['DNI']) ? $v['DNI'] : ''; ?>">EDITAR</a>
                            <a class="btn" href="index.php?m=eliminar&DNI=<?php echo isset($v['DNI']) ? $v['DNI'] : ''; ?>" onclick="return confirm('¿Está seguro?');">ELIMINAR</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">NO HAY REGISTROS</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>
<?php
require_once("layouts/footer.php");
?>
