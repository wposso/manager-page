<?php require_once __DIR__ . "/../database/dbconnection.php";
function getAllMovementFromDb()
{
    $conn = db_connect();
    if ($conn->connect_error) {
        return die("Error en la conexión");
    }

    $query = $conn->query("SELECT id, tipo, producto, cantidad, fecha, observacion FROM movimientos");
    if ($query == null) {
        return die("Error al obtener datos");
    }
    $movement = [];
    while ($response = $query->fetch_assoc()) {
        $movement[] = $response;
    }

    return $movement;
}
?>