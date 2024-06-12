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

    // Consulta SQL para obtener las competiciones asociadas al DNI del usuario
    $sql_competiciones = "SELECT idCompeticion, NomCompeticion FROM Competicion WHERE DNI = '$dni_usuario'";
    $result_competiciones = $conn->query($sql_competiciones);

    // Procesar la selección de competición
    $idCompeticionSeleccionada = isset($_GET['idCompeticion']) ? $_GET['idCompeticion'] : null;

    // Consulta SQL para obtener los datos de la tabla Equipo según el idCompeticion seleccionado
    if ($idCompeticionSeleccionada !== null) {
        $sql = "SELECT 
                    e.Nombre,
                    COUNT(CASE WHEN p.ScoreLocal IS NOT NULL AND p.ScoreVisitante IS NOT NULL THEN p.PartidoID ELSE NULL END) AS PartidosJugados,
                    SUM(CASE WHEN (p.EquipoLocal = e.EquipoID AND p.ScoreLocal > p.ScoreVisitante) OR (p.EquipoVisitante = e.EquipoID AND p.ScoreVisitante > p.ScoreLocal) THEN 1 ELSE 0 END) AS PartidosGanados,
                    SUM(CASE WHEN (p.EquipoLocal = e.EquipoID OR p.EquipoVisitante = e.EquipoID) AND p.ScoreLocal = p.ScoreVisitante THEN 1 ELSE 0 END) AS PartidosEmpatados,
                    SUM(CASE WHEN (p.EquipoLocal = e.EquipoID AND p.ScoreLocal < p.ScoreVisitante) OR (p.EquipoVisitante = e.EquipoID AND p.ScoreVisitante < p.ScoreLocal) THEN 1 ELSE 0 END) AS PartidosPerdidos,
                    SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreLocal ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreVisitante ELSE 0 END) AS GolesFavor,
                    SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreVisitante ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreLocal ELSE 0 END) AS GolesContra,
                    (SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreLocal ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreVisitante ELSE 0 END)) - 
                    (SUM(CASE WHEN p.EquipoLocal = e.EquipoID THEN p.ScoreVisitante ELSE 0 END) + SUM(CASE WHEN p.EquipoVisitante = e.EquipoID THEN p.ScoreLocal ELSE 0 END)) AS DiferenciaGoles,
                    SUM(CASE WHEN (p.EquipoLocal = e.EquipoID AND p.ScoreLocal > p.ScoreVisitante) OR (p.EquipoVisitante = e.EquipoID AND p.ScoreVisitante > p.ScoreLocal) THEN 3 WHEN (p.EquipoLocal = e.EquipoID OR p.EquipoVisitante = e.EquipoID) AND p.ScoreLocal = p.ScoreVisitante THEN 1 ELSE 0 END) AS Puntos
                FROM Equipo e
                LEFT JOIN Partido p ON (e.EquipoID = p.EquipoLocal OR e.EquipoID = p.EquipoVisitante) AND p.ScoreLocal IS NOT NULL AND p.ScoreVisitante IS NOT NULL
                WHERE e.idCompeticion = '$idCompeticionSeleccionada'
                GROUP BY e.EquipoID, e.Nombre
                ORDER BY Puntos DESC, PartidosGanados DESC, DiferenciaGoles DESC, GolesFavor DESC";
        
        $result = $conn->query($sql);
    }
    ?>

    <h2 class="mb-4">Selecciona una competición:</h2>
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-inline mb-4">
        <div class="form-group mr-2">
            <label for="idCompeticion" class="mr-2">Competición:</label>
            <select name="idCompeticion" id="idCompeticion" class="form-control">
                
                <?php
                // Mostrar opciones de competiciones asociadas al DNI del usuario
                if ($result_competiciones->num_rows > 0) {
                    while ($row_competiciones = $result_competiciones->fetch_assoc()) {
                        $idCompeticion = $row_competiciones['idCompeticion'];
                        echo "<option value='$idCompeticion' " . ($idCompeticion == $idCompeticionSeleccionada ? 'selected' : '') . ">" . $row_competiciones['NomCompeticion'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mostrar Tabla</button>
    </form>

    <a href="../CrudEquipo/index.php?m=index&table=Equipo" class="btn btn-secondary mb-4">Editar Equipos</a>
    <?php
    // Mostrar tabla de posiciones si se ha seleccionado una competición
    if ($idCompeticionSeleccionada !== null) {
        if ($result->num_rows > 0) {
            echo "<h2>Tabla de Posiciones</h2>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='thead-dark'>";
            echo "<tr><th>Posición</th><th>Equipo</th><th>Partidos Jugados</th><th>Partidos Ganados</th><th>Partidos Empatados</th><th>Partidos Perdidos</th><th>Goles a Favor</th><th>Goles en Contra</th><th>Diferencia de Goles</th><th>Puntos</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            $posicion = 1;

            // Iterar sobre cada fila de la tabla Equipo
            while ($row = $result->fetch_assoc()) {
                $nombreEquipo = $row["Nombre"];
                $partidosJugados = $row["PartidosJugados"];
                $partidosGanados = $row["PartidosGanados"];
                $partidosEmpatados = $row["PartidosEmpatados"];
                $partidosPerdidos = $row["PartidosPerdidos"];
                $golesFavor = $row["GolesFavor"];
                $golesContra = $row["GolesContra"];
                $diferenciaGoles = $row["DiferenciaGoles"];
                $puntos = $row["Puntos"];

                // Mostrar los datos en la tabla
                echo "<tr>";
                echo "<td>$posicion</td><td>$nombreEquipo</td><td>$partidosJugados</td><td>$partidosGanados</td><td>$partidosEmpatados</td><td>$partidosPerdidos</td><td>$golesFavor</td><td>$golesContra</td><td>$diferenciaGoles</td><td>$puntos</td>";
                echo "</tr>";

                $posicion++;
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No hay resultados para la competición seleccionada.</p>";
        }
    }

    $conn->close();
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
