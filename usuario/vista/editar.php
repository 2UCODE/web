<?php
require_once("layouts/header.php");

// Incluir el archivo de conexión a la base de datos
require_once("modelo/index.php");

// Instanciar el modelo
$modelo = new Modelo();

// Obtener datos del usuario para editar
$dni_usuario = $_GET['DNI']; // Obtener el DNI del usuario desde la URL
$dato = $modelo->mostrar("Usuario", "DNI = '$dni_usuario'");

// Procesar la actualización del usuario
if(isset($_GET['m']) && $_GET['m'] == 'actualizar') {
    $dni = $_GET['DNI'];
    $apellido_paterno = $_GET['apellido_paterno'];
    $apellido_materno = $_GET['apellido_materno'];
    $nombres = $_GET['nombres'];
    $contrasena = $_GET['contrasena'];
    $data = "ApellidoPaterno='$apellido_paterno', ApellidoMaterno='$apellido_materno', Nombres='$nombres', Contrasena='$contrasena'";
    $modelo->actualizar("Usuario", $data, "DNI='$dni'");
    header("location:index.php"); // Redireccionar después de actualizar
}

?>
<h1 class="text-center">EDITAR USUARIO</h1>
<form action="index.php" method="get">
    <?php
    foreach ($dato as $key => $value):
        foreach($value as $v):
            ?>
            <input type="text" value="<?php echo $v['DNI'] ?>" name="DNI" readonly> <br> 
            <input type="text" value="<?php echo $v['ApellidoPaterno'] ?>" name="apellido_paterno"> <br> 
            <input type="text" value="<?php echo $v['ApellidoMaterno'] ?>" name="apellido_materno"> <br> 
            <input type="text" value="<?php echo $v['Nombres'] ?>" name="nombres"> <br> 
            <input type="text" value="<?php echo $v['Contrasena'] ?>" name="contrasena"> <br> 
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
