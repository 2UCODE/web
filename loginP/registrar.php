<?php
// Iniciar sesión
session_start();

// Verificar si ya hay una sesión iniciada, redirigir al usuario si es así
if (isset($_SESSION['usuario'])) {
    header("Location: registrar.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once('../Conexion/conexion.php');

// Mensaje de error
$error_message = '';

// Si se envió el formulario
if (isset($_POST['btnregistrar'])) {
    // Obtener los datos del formulario
    $dni = $_POST['dni'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $nombres = $_POST['nombres'];
    $contrasena = md5($_POST['contrasena']); // Cifrar la contraseña con md5

    // Consulta SQL para verificar si el usuario ya existe en la base de datos
    $sql_verificar = "SELECT * FROM Usuario WHERE DNI = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $dni);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    // Verificar si el usuario ya existe
    if ($result_verificar->num_rows > 0) {
        $error_message = "El usuario ya existe. Por favor, ingresa otro DNI.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql_insertar = "INSERT INTO Usuario (DNI, ApellidoPaterno, ApellidoMaterno, Nombres, Contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt_insertar = $conexion->prepare($sql_insertar);
        $stmt_insertar->bind_param("sssss", $dni, $apellido_paterno, $apellido_materno, $nombres, $contrasena);

        if ($stmt_insertar->execute()) {
            // Registro exitoso, redirigir al usuario a la página de inicio de sesión
            header("Location: login.php");
            exit();
        } else {
            // Error al registrar el usuario
            $error_message = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
        }
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
   <title>Registro de Usuario</title>
</head>
<body>
   <img class="wave" src="../img/wave.png">
   <div class="container">
      <div class="img">
         <img src="../img/imgRegistro.png">
      </div>
      <div class="login-content">
         <form method="post" action="">
            <h2 class="title">Registro de Usuario</h2>
            <?php if (!empty($error_message)) { ?>
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
                  <input type="number" min="0" max="99999999" class="input" name="dni" required>
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Apellido Paterno</h5>
                  <input type="text" class="input" name="apellido_paterno" required>
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Apellido Materno</h5>
                  <input type="text" class="input" name="apellido_materno">
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Nombres</h5>
                  <input type="text" class="input" name="nombres" required>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" class="input" name="contrasena" required>
               </div>
            </div>
            <input name="btnregistrar" class="btn" type="submit" value="REGISTRAR">
            <a href="login.php" class="btn" id="btnregistrar">VOLVER</a>
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
