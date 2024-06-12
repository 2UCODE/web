<?php
require_once("../vista/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen</title>
    <link rel="stylesheet" href="../css/estilo.css">
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
    </style>
</head>
<body>
    <div class="container my-4">
        <?php
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $dni_usuario = $_SESSION['usuario'];

        require_once('../Conexion/conexion.php');

        // Verificar conexión
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Consulta para obtener las competiciones asociadas al DNI del usuario
        $query = "SELECT Competicion.idCompeticion, Competicion.NomCompeticion 
                  FROM Competicion 
                  INNER JOIN Usuario ON Competicion.DNI = Usuario.DNI 
                  WHERE Usuario.DNI = ?";
        
        // Preparar la consulta
        if ($stmt = $conexion->prepare($query)) {
            // Vincular el parámetro
            $stmt->bind_param("s", $dni_usuario);
            
            // Ejecutar la consulta
            $stmt->execute();
            
            // Obtener el resultado de la consulta
            $result = $stmt->get_result();
        } else {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
        ?>

        <h2 class="mb-4">Seleccione la competición:</h2>
        <form method="post" action="" class="form-inline">
            <div class="form-group mb-2">
                <label for="idCompeticion" class="mr-2">Competición:</label>
                <select name="idCompeticion" id="idCompeticion" class="form-control">
                    <?php
                    // Generar opciones del select con las competiciones asociadas al usuario
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idCompeticion'] . "'>" . $row['NomCompeticion'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Mostrar Detalles</button>
        </form>

        <?php
        // Verificar si se seleccionó una competición
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener el IdCompeticion seleccionado
            if(isset($_POST['idCompeticion'])){
                $idCompeticion = $_POST['idCompeticion'];

                // Consulta para obtener los detalles de la competición seleccionada
                $query = "SELECT idCompeticion, TipoDeporteComp FROM Competicion WHERE idCompeticion = ?";
                if ($stmt = $conexion->prepare($query)) {
                    $stmt->bind_param("s", $idCompeticion);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Mostrar detalles de la competición
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<h2 class='mt-4'>Detalles de la Competición</h2>";
                        echo "<table class='table table-bordered'>";
                        echo "<tr><th>Código de Competición</th><td>" . $row['idCompeticion'] . "</td></tr>";
                        echo "<tr><th>Deporte</th><td>" . $row['TipoDeporteComp'] . "</td></tr>";
                        echo "</table>";

                        // Mostrar equipos de la competición seleccionada
                        echo "<h2 class='mt-4'>Equipos</h2>";

                        // Consulta SQL para obtener los datos de la tabla de posiciones
                        $sql = "SELECT 
                                    e.Nombre,
                                    SUM(CASE WHEN (p.EquipoLocal = e.EquipoID AND p.ScoreLocal > p.ScoreVisitante) OR (p.EquipoVisitante = e.EquipoID AND p.ScoreVisitante > p.ScoreLocal) THEN 3 WHEN (p.EquipoLocal = e.EquipoID OR p.EquipoVisitante = e.EquipoID) AND p.ScoreLocal = p.ScoreVisitante THEN 1 ELSE 0 END) AS Puntos
                                FROM Equipo e
                                LEFT JOIN Partido p ON e.EquipoID = p.EquipoLocal OR e.EquipoID = p.EquipoVisitante
                                WHERE e.idCompeticion = ?
                                GROUP BY e.EquipoID, e.Nombre
                                ORDER BY Puntos DESC, SUM(CASE WHEN (p.EquipoLocal = e.EquipoID AND p.ScoreLocal > p.ScoreVisitante) OR (p.EquipoVisitante = e.EquipoID AND p.ScoreVisitante > p.ScoreLocal) THEN 1 ELSE 0 END) DESC, 
                                         (SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreLocal ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreVisitante ELSE 0 END)) - 
                                         (SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreVisitante ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreLocal ELSE 0 END)) DESC, 
                                         SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreLocal ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreVisitante ELSE 0 END) DESC";
                        
                        if ($stmt_equipos = $conexion->prepare($sql)) {
                            $stmt_equipos->bind_param("s", $idCompeticion);
                            $stmt_equipos->execute();
                            $result_equipos = $stmt_equipos->get_result();

                            if ($result_equipos->num_rows > 0) {
                                echo "<table class='table table-striped table-bordered mt-4'>";
                                echo "<thead class='thead-dark'>";
                                echo "<tr><th>Posición</th><th>Nombre del Equipo</th><th>Puntos</th></tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $posicion = 1;
                                while ($row_equipos = $result_equipos->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $posicion . "</td>";
                                    echo "<td>" . $row_equipos['Nombre'] . "</td>";
                                    echo "<td>" . $row_equipos['Puntos'] . "</td>";
                                    echo "</tr>";
                                    $posicion++;
                                }
                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo "<p>No se encontraron equipos para la competición seleccionada.</p>";
                            }
                        } else {
                            echo "<p>Error en la preparación de la consulta de equipos: " . $conexion->error . "</p>";
                        }
                    } else {
                        echo "<p>No se encontraron detalles para la competición seleccionada.</p>";
                    }
                } else {
                    echo "<p>Error en la preparación de la consulta de competición: " . $conexion->error . "</p>";
                }
            }
        }

        // Cerrar conexión
        $conexion->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
require_once("../vista/footer.php");
?>
