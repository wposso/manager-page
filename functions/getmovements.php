<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllMovements()
{
    $conn = db_connect();

    $query = "
        SELECT 
            m.id,
            m.tipo,
            i.nombre AS producto,
            m.cantidad,
            bo.nombre AS bodega_origen,
            bd.nombre AS bodega_destino,
            m.motivo,
            m.usuario_responsable,
            m.fecha
        FROM movimiento m
        LEFT JOIN inventario i ON m.producto_id = i.id
        LEFT JOIN bodega bo ON m.bodega_origen_id = bo.id
        LEFT JOIN bodega bd ON m.bodega_destino_id = bd.id
        ORDER BY m.fecha DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $movimientos = [];
    while ($row = $result->fetch_assoc()) {
        $movimientos[] = $row;
    }

    return $movimientos;
}
