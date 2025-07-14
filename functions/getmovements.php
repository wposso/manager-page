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
            m.fecha,
            COALESCE(u.nombre, 'Sin asignar') AS responsable_nombre,
            bo.nombre AS bodega_origen,
            bd.nombre AS bodega_destino,
            po.nombre AS proyecto_origen,
            pd.nombre AS proyecto_destino,
            m.motivo
        FROM movimiento m
        LEFT JOIN inventario i ON m.producto_id = i.id
        LEFT JOIN usuario u ON m.usuario_responsable = u.id
        LEFT JOIN bodega bo ON m.bodega_origen_id = bo.id
        LEFT JOIN bodega bd ON m.bodega_destino_id = bd.id
        LEFT JOIN proyecto po ON m.proyecto_origen_id = po.id
        LEFT JOIN proyecto pd ON m.proyecto_destino_id = pd.id
        ORDER BY m.fecha DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $movimientos = [];
    while ($row = $result->fetch_assoc()) {
        // Normaliza nulos a strings vacíos para evitar warnings
        $movimientos[] = [
            'id' => $row['id'],
            'tipo' => $row['tipo'],
            'producto' => $row['producto'] ?? 'Desconocido',
            'cantidad' => $row['cantidad'],
            'fecha' => $row['fecha'],
            'responsable_nombre' => $row['responsable_nombre'],
            'bodega_origen' => $row['bodega_origen'] ?? '',
            'bodega_destino' => $row['bodega_destino'] ?? '',
            'proyecto_origen' => $row['proyecto_origen'] ?? '',
            'proyecto_destino' => $row['proyecto_destino'] ?? '',
            'motivo' => $row['motivo'] ?? ''
        ];
    }

    return $movimientos;
}
