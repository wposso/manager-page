<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllWarehouses()
{
    $conn = db_connect();
    $bodegas = [];

    $query = "SELECT id, nombre, ubicacion FROM bodega";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $bodegas[] = $row;
    }

    return $bodegas;
}

// Manejar solicitudes POST (añadir o eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $conn = db_connect();

    if ($action === 'add') {
        $nombre = $_POST['nombre'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';

        if (!empty($nombre) && !empty($ubicacion)) {
            $stmt = $conn->prepare("INSERT INTO bodega (nombre, ubicacion) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $ubicacion);
            $stmt->execute();
        }

        header("Location: ../views/administrator_view.php");
        exit();
    }

    if ($action === 'delete') {
        $identificador = $_POST['identificador'] ?? '';

        if (!empty($identificador)) {
            // Intentar eliminar por ID (si es numérico), o por nombre
            if (is_numeric($identificador)) {
                $stmt = $conn->prepare("DELETE FROM bodega WHERE id = ?");
                $stmt->bind_param("i", $identificador);
            } else {
                $stmt = $conn->prepare("DELETE FROM bodega WHERE nombre = ?");
                $stmt->bind_param("s", $identificador);
            }

            $stmt->execute();
        }

        header("Location: ../views/administrator_view.php");
        exit();
    }
}
?>