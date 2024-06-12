<?php
require_once("layouts/header.php");

// Procesar el formulario cuando se envíe
if (isset($_GET['btn']) && $_GET['btn'] === 'ACTUALIZAR') {
    // Aquí iría el código para actualizar los datos
    
    // Después de actualizar, redirigir a la página deseada
    header("Location: http://localhost/Valido/CrudEquipo/index.php?m=index&table=Equipo");
    exit; // Asegurarse de detener el script después de la redirección
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e3f2fd; /* Fondo azul claro */
        }
        .container {
            background-color: #ffffff; /* Fondo blanco para el contenedor */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0d47a1; /* Azul oscuro */
        }
        .btn-primary {
            background-color: #1976d2; /* Azul medio */
            border-color: #1976d2;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .floating-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div id="floating-message" class="floating-message"></div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">EDITAR</h2>
        <form action="" method="get">
            <?php
            $NTablas = $producto->db->query("DESCRIBE $table");

            if ($NTablas):
                $filas = $NTablas->fetchAll(PDO::FETCH_ASSOC);
                $counter = 0; // Contador para identificar los campos
                foreach ($filas as $key):
                    $NCampo = $key['Field'];
                    if (!str_contains($NCampo, 'EquipoID')):
                        $counter++;
                        if ($counter == 1): ?>
                            <label for="<?php echo strval($NCampo)?>">Nombre de Equipo</label>
                            <input class="form-control" type="text" value="<?php echo trim($dato[0][strval($NCampo)]) ?>" name="<?php echo strval($NCampo)?>" id="<?php echo strval($NCampo)?>"> <br>
                        <?php elseif ($counter == 2): ?>
                            <label for="<?php echo strval($NCampo)?>">Ciudad</label>
                            <select class="form-control" name="<?php echo strval($NCampo)?>" id="<?php echo strval($NCampo)?>">
                                <option value="Cusco" <?php if (trim($dato[0][strval($NCampo)]) == 'Cusco') echo 'selected'; ?>>Cusco</option>
                                <option value="Lima" <?php if (trim($dato[0][strval($NCampo)]) == 'Lima') echo 'selected'; ?>>Lima</option>
                                <option value="Quillabamba" <?php if (trim($dato[0][strval($NCampo)]) == 'Quillabamba') echo 'selected'; ?>>Quillabamba</option>
                                <option value="Paucartambo" <?php if (trim($dato[0][strval($NCampo)]) == 'Paucartambo') echo 'selected'; ?>>Paucartambo</option>
                                <option value="Otros" <?php if (trim($dato[0][strval($NCampo)]) == 'Otros') echo 'selected'; ?>>Otros</option>
                            </select> <br>
                        <?php elseif ($counter == 3): ?>
                            <label for="<?php echo strval($NCampo)?>">Categoría</label>
                            <select class="form-control" name="<?php echo strval($NCampo)?>" id="<?php echo strval($NCampo)?>">
                                <option value="Primera Division" <?php if (trim($dato[0][strval($NCampo)]) == 'Primera Division') echo 'selected'; ?>>Primera Division</option>
                                <option value="Segunda Division" <?php if (trim($dato[0][strval($NCampo)]) == 'Segunda Division') echo 'selected'; ?>>Segunda Division</option>
                                <option value="Amateur" <?php if (trim($dato[0][strval($NCampo)]) == 'Amateur') echo 'selected'; ?>>Amateur</option>
                            </select> <br>
                        <?php elseif ($counter == 4): ?>
                            <label for="<?php echo strval($NCampo)?>">DT ID</label>
                            <input class="form-control" type="text" value="<?php echo trim($dato[0][strval($NCampo)]) ?>" name="<?php echo strval($NCampo)?>" id="<?php echo strval($NCampo)?>" readonly> <br>
                        <?php elseif ($counter == 5): ?>
                            <label for="<?php echo strval($NCampo)?>">Competición ID</label>
                            <input class="form-control" type="text" value="<?php echo trim($dato[0][strval($NCampo)]) ?>" name="<?php echo strval($NCampo)?>" id="<?php echo strval($NCampo)?>" readonly> <br>
                        <?php endif; ?>
                    <?php else: ?>
                        <input type="hidden" value="<?php echo trim($dato[0][strval($NCampo)]) ?>" name="id"> <br>
                    <?php endif ?>
                <?php endforeach; ?>
                <!-- Campo adicional para el Nombre de DT -->
                <label for="NombreDT">Nombre de DT</label>
                <input class="form-control" type="text" value="<?php echo trim($dato[0]['NombreDt']) ?>" name="NombreDt" id="NombreDt"> <br>
            <?php endif ?>
            <input type="submit" class="btn btn-primary" name="btn" value="ACTUALIZAR"> <br>
            <input type="hidden" name="m" value="actualizar">
            <input type="hidden" name="table" value="<?php echo $_REQUEST['table']?>">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
require_once("layouts/footer.php");
?>
