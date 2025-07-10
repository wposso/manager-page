<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getProjects()
{
    $conn = db_connect();
    $proyectos = [];

    $query = "SELECT id, nombre, ubicacion FROM proyecto";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }

    return $proyectos;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['action'] ?? '';

    if ($accion === 'add') {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $ubicacion = $conn->real_escape_string($_POST['ubicacion']);

        $stmt = $conn->prepare("INSERT INTO proyecto (nombre, ubicacion, creado_en, actualizado_en) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("ss", $nombre, $ubicacion);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Proyecto registrado correctamente." : "Error al registrar proyecto.";
        echo "<script>window.location.href = '../server.php#Administrador::" . urlencode($msg) . "';</script>";
        exit;
    }

    if ($accion === 'delete') {
        $id = (int) $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM proyecto WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Proyecto eliminado correctamente." : "Error al eliminar proyecto.";
        echo "<script>window.location.href = '../server.php#Administrador::" . urlencode($msg) . "';</script>";
        exit;
    }
}
