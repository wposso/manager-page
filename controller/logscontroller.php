<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getLogs()
{
    $conn = db_connect();
    $query = "SELECT u.nombre AS usuario, l.accion, l.fecha 
              FROM log l 
              JOIN usuario u ON l.usuario_id = u.id 
              ORDER BY l.fecha DESC";
    $result = $conn->query($query);

    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }

    $conn->close();
    return $logs;
}
