<?php
require_once("layouts/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Competiciones</title>
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
        .btn-secondary {
            background-color: #64b5f6; /* Azul claro */
            border-color: #64b5f6;
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
    </style>
</head>
<body>
    <div class="container my-4">
        <?php
        session_start();

        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "bd_Campeonato";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Obtener el DNI del usuario que ha iniciado sesión
        $dni_usuario = $_SESSION['usuario'];

        // Consultar las competiciones asociadas al DNI del usuario
        $sql = "SELECT * FROM Competicion WHERE DNI = '$dni_usuario'";
        $result = $conn->query($sql);
        ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Competiciones</h2>
            <div>
                <a href="index.php?m=nuevo" class="btn btn-primary">NUEVO</a>
                <a href="../loginP/inicio.php" class="btn btn-secondary">VOLVER</a>
            </div>
        </div>
        
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Competición</th>
                    <th>Tipo de Competición</th>
                    <th>Tipo de Deporte de la Competición</th>
                    <th>Número de Participantes</th>
                    <th>ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['idCompeticion']; ?></td>
                            <td><?php echo $row['NomCompeticion']; ?></td>
                            <td><?php echo $row['TipoComp']; ?></td>
                            <td><?php echo $row['TipoDeporteComp']; ?></td>
                            <td><?php echo $row['NroPartComp']; ?></td>
                            <td>
                                <a class="btn btn-danger" href="index.php?m=eliminar&idCompeticion=<?php echo $row['idCompeticion']; ?>" onclick="return confirm('¿Está seguro?');">ELIMINAR</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">NO HAY REGISTROS</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
require_once("layouts/footer.php");
?>
