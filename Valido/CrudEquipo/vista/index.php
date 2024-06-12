<?php
require_once("layouts/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Datos</title>
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
        h1 {
            color: #0d47a1; /* Azul oscuro */
        }
        .btn-primary {
            background-color: #1976d2; /* Azul medio */
            border-color: #1976d2;
        }
        .table {
            background-color: #bbdefb; /* Fondo azul muy claro para la tabla */
        }
        .thead-dark th {
            background-color: #1565c0; /* Azul muy oscuro para el encabezado */
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #90caf9; /* Azul muy claro para filas impares */
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e3f2fd; /* Azul claro para filas pares */
        }
        .btn {
            margin-right: 5px;
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
        <h1 class="mb-4">Tabla de Datos</h1>
        <div class="TableContainer">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <?php
                        $NTablas = $producto->db->query("DESCRIBE $table");

                        if ($NTablas):
                            while ($fila = $NTablas->fetch(PDO::FETCH_ASSOC)):
                                $NCampo = $fila['Field'];
                                if ($NCampo != 'EquipoID' && $NCampo != 'NombreDt'): ?>
                                    <th class="TableHeader"><?php echo strtoupper($NCampo) ?></th>
                                <?php endif;
                            endwhile;
                        endif ?>
                        <th class="TableHeader">NOMBRE DT</th>
                        <th class="TableHeader">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Obtener los nombres de los DTs
                    $dtNames = [];
                    $dtQuery = $producto->db->query("SELECT DTId, Nombre FROM DT");
                    if ($dtQuery) {
                        while ($dtRow = $dtQuery->fetch(PDO::FETCH_ASSOC)) {
                            $dtNames[$dtRow['DTId']] = $dtRow['Nombre'];
                        }
                    }

                    if (!empty($dato)): 
                        foreach ($dato as $key => $fila): ?>
                            <tr>
                                <?php foreach ($fila as $v => $value):
                                    if ($v != 'EquipoID' && $v != 'NombreDt'): ?>
                                        <td><?php echo $value ?></td>
                                    <?php endif;
                                endforeach; ?>
                                <td>
                                    <?php echo trim($fila['NombreDt']); ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="index.php?m=editar&id=<?php echo $fila['EquipoID'] ?>&table=<?php echo $_REQUEST['table'] ?>">EDITAR</a>
                                    <a class="btn btn-danger" href="index.php?m=eliminar&id=<?php echo $fila['EquipoID'] ?>&table=<?php echo $_REQUEST['table'] ?>" onclick="return confirm('¿ESTÁ SEGURO?');">ELIMINAR</a>
                                </td>
                            </tr>
                        <?php endforeach; 
                    else: ?>
                        <tr>
                            <td colspan="4">NO HAY REGISTROS</td>
                        </tr>
                    <?php endif ?>    
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.update-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const partidoID = formData.get('partidoID');
                    const scoreLocal = formData.get('scoreLocal');
                    const scoreVisitante = formData.get('scoreVisitante');

                    fetch('editar_marcador.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const messageBox = document.getElementById('floating-message');
                        if (data.status === 'success') {
                            messageBox.textContent = data.message;
                            messageBox.style.backgroundColor = 'green';
                        } else {
                            messageBox.textContent = data.message;
                            messageBox.style.backgroundColor = 'red';
                        }
                        messageBox.style.display = 'block';
                        setTimeout(() => {
                            messageBox.style.display = 'none';
                        }, 3000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</body>
</html>

<?php
require_once("layouts/footer.php");
?>
