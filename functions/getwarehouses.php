<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllWarehouses()
{
    $conn = db_connect();
    $bodegas = [];

    $query = "SELECT id, nombre, ubicacion FROM bodega"; // incluir todo lo que usan ambas vistas
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $bodegas[] = $row;
    }

    return $bodegas;
}

?>