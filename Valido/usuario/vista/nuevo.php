<?php
require_once("layouts/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn"]) && $_POST["btn"] == "GUARDAR") {
    // Procesar el formulario y guardar los datos en la base de datos

    // Redirigir al índice principal después de guardar
    header("Location: index.php");
    exit(); // Asegura que el script se detenga después de la redirección
}
?>

<h1 class="text-center">NUEVO USUARIO</h1>
<form action="index.php?m=guardar" method="post">
    <input type="text" placeholder="Ingrese DNI:" name="dni"> <br> 
    <input type="text" placeholder="Ingrese Apellido Paterno:" name="apellido_paterno"> <br> 
    <input type="text" placeholder="Ingrese Apellido Materno:" name="apellido_materno"> <br> 
    <input type="text" placeholder="Ingrese Nombres:" name="nombres"> <br> 
    <input type="text" placeholder="Ingrese Contraseña:" name="contrasena"> <br> 
    <input type="submit" class="btn" name="btn" value="GUARDAR"> <br> 
    <input type="hidden" name="m" value="guardar">
</form>

<?php
require_once("layouts/footer.php");
?>
