<?php
session_start();

// Verificar si ya hay una sesión iniciada, redirigir al usuario si es así
if (isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once('../Conexion/conexion.php');

// Si se envió el formulario
if (isset($_POST['btningresar'])) {
    // Obtener el DNI y la contraseña del formulario
    $dni = $_POST['dni'];
    $password = md5($_POST['password']); // Cifrar la contraseña con md5

    // Consulta SQL para verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM Usuario WHERE DNI = ? AND Contrasena = ?";
    
    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    
    // Vincular los parámetros
    $stmt->bind_param("ss", $dni, $password);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado de la consulta
    $result = $stmt->get_result();
    
    // Verificar si se encontró un usuario con las credenciales proporcionadas
    if ($result->num_rows == 1) {
        // Usuario autenticado, iniciar sesión y redirigir a la página de inicio
        $_SESSION['usuario'] = $dni;
        header("Location: inicio.php");
        exit();
    } else {
        // Credenciales incorrectas, mostrar mensaje de error
        $error_message = "DNI o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
   <title>Inicio de sesión</title>
</head>
<body>
   <img class="wave" src="../img/wave.png">
   <div class="container">
      <div class="img">
         <img src="../img/imgLogin.png">
      </div>
      <div class="login-content">
         <form method="post" action="">
            <img src="../img/avatar.svg">
            <h2 class="title">BIENVENIDO</h2>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-id-card"></i>
               </div>
               <div class="div">
                  <h5>DNI</h5>
                  <input id="dni" type="number" class="input" name="dni" required>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="input" class="input" name="password" required>
               </div>
            </div>

            <div class="text-center">
               <a class="font-italic isai5" href="registrar.php">Registrarse</a>
            </div>
            <input name="btningresar" class="btn" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>
   <script src="js/fontawesome.js"></script>
   <script src="js/main.js"></script>
   <script src="js/main2.js"></script>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
