<?php
// Paso 1: Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Paso 2: Obtener los datos del formulario
    $partidoID = $_POST['partidoID'];
    $scoreLocal = $_POST['scoreLocal'];
    $scoreVisitante = $_POST['scoreVisitante'];

    // Paso 3: Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bd_Campeonato";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]);
        exit();
    }

    // Paso 4: Actualizar el marcador del partido en la base de datos
    $sqlUpdateMarcador = "UPDATE Partido SET ScoreLocal = '{$scoreLocal}', ScoreVisitante = '{$scoreVisitante}' WHERE PartidoID = '{$partidoID}'";

    if ($conn->query($sqlUpdateMarcador) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Marcador actualizado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar el marcador: " . $conn->error]);
    }

    $conn->close();
}
?>