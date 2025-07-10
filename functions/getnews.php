<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllNews()
{
    $conn = db_connect();
    $registros = [];

    $sql = "SELECT n.id, n.titulo, n.descripcion, n.fecha, n.tipo, b.nombre as bodega
            FROM novedad n
            LEFT JOIN bodega b ON n.bodega_id = b.id
            ORDER BY n.fecha DESC";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }

    return $registros;
}
