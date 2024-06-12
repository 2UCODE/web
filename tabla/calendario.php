<?php
require_once("../vista/header.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen</title>
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
        h1, h2 {
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
        <h1 class="mb-4">Jornadas de Liga</h1>
        <?php
        // Paso 1: Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "bd_Campeonato";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Paso 2: Obtener todas las competiciones disponibles
        $sqlCompeticiones = "SELECT idCompeticion FROM Competicion";
        $resultCompeticiones = $conn->query($sqlCompeticiones);

        $competiciones = array();
        if ($resultCompeticiones->num_rows > 0) {
            while ($row = $resultCompeticiones->fetch_assoc()) {
                $competiciones[] = $row["idCompeticion"];
            }
        }

        // Paso 3: Generar jornadas de liga y sus partidos solo si no existen previamente
        foreach ($competiciones as $competicion) {
            // Verificar si ya existen partidos para esta competición
            $sqlVerificarPartidos = "SELECT COUNT(*) as total FROM Partido WHERE idCompeticion = '{$competicion}'";
            $resultVerificarPartidos = $conn->query($sqlVerificarPartidos);
            $rowVerificarPartidos = $resultVerificarPartidos->fetch_assoc();
            $totalPartidos = $rowVerificarPartidos['total'];

            // Si no hay partidos, entonces generar las jornadas y partidos
            if ($totalPartidos == 0) {
                $sqlEquipos = "SELECT EquipoID, Nombre FROM Equipo WHERE idCompeticion = '{$competicion}'";
                $resultEquipos = $conn->query($sqlEquipos);

                $equipos = array();
                if ($resultEquipos->num_rows > 0) {
                    while ($row = $resultEquipos->fetch_assoc()) {
                        $equipos[] = $row;
                    }
                }

                $numEquipos = count($equipos);
                $numJornadas = $numEquipos - 1;

                for ($i = 1; $i <= $numJornadas; $i++) {
                    $jornada = "J" . $i;

                    for ($j = 0; $j < $numEquipos / 2; $j++) {
                        $localID = $equipos[$j]["EquipoID"];
                        $visitanteID = $equipos[$numEquipos - 1 - $j]["EquipoID"];
                        $sqlInsertPartido = "INSERT INTO Partido (EquipoLocal, EquipoVisitante, Jornada, idCompeticion) VALUES ('{$localID}', '{$visitanteID}', '{$jornada}', '{$competicion}')";
                        $conn->query($sqlInsertPartido);
                    }

                    // Rotar equipos para la próxima jornada (algoritmo de round-robin)
                    $ultimoEquipo = array_pop($equipos);
                    array_splice($equipos, 1, 0, array($ultimoEquipo));
                }
            }
        }

        // Paso 4: Mostrar los partidos en una página web con botón para editar el marcador
        foreach ($competiciones as $competicion) {
            echo "<h2>Competición: " . $competicion . "</h2>";

            $sqlSelectPartidos = "
                SELECT p.PartidoID, p.EquipoLocal, p.EquipoVisitante, p.Jornada, p.ScoreLocal, p.ScoreVisitante, 
                       el.Nombre AS NombreLocal, ev.Nombre AS NombreVisitante
                FROM Partido p
                JOIN Equipo el ON p.EquipoLocal = el.EquipoID
                JOIN Equipo ev ON p.EquipoVisitante = ev.EquipoID
                WHERE p.idCompeticion = '{$competicion}'
            ";
            $resultPartidos = $conn->query($sqlSelectPartidos);

            if ($resultPartidos->num_rows > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped'>";
                echo "<thead class='thead-dark'><tr><th>Equipo Local</th><th>Equipo Visitante</th><th>Jornada</th><th>Score Local</th><th>Score Visitante</th><th>Acciones</th></tr></thead>";
                echo "<tbody>";

                while ($row = $resultPartidos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['NombreLocal']}</td>";
                    echo "<td>{$row['NombreVisitante']}</td>";
                    echo "<td>{$row['Jornada']}</td>";
                    echo "<td><input type='number' class='form-control' name='scoreLocal' value='{$row['ScoreLocal']}' form='form-{$row['PartidoID']}'></td>";
                    echo "<td><input type='number' class='form-control' name='scoreVisitante' value='{$row['ScoreVisitante']}' form='form-{$row['PartidoID']}'></td>";
                    echo "<td>
                            <form id='form-{$row['PartidoID']}' class='update-form' action='editar_marcador.php' method='post'>
                                <input type='hidden' name='partidoID' value='{$row['PartidoID']}'>
                                <button type='submit' class='btn btn-primary'>Guardar</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No se encontraron partidos para esta competición.</p>";
            }
        }

        $conn->close();
        ?>
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
require_once("../vista/footer.php");
?>
