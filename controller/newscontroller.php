<?php
require_once __DIR__ . '/../functions/getnews.php';

function handlegetnews() {
    return getAllNews();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../database/dbconnection.php';
    $conn = db_connect();

    $accion = $_POST['action'] ?? '';

    if ($accion === 'add') {
        $titulo = $conn->real_escape_string($_POST['titulo']);
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $fecha = $conn->real_escape_string($_POST['fecha']);
        $tipo = $conn->real_escape_string($_POST['tipo']);
        $bodega_id = intval($_POST['bodega_id']);

        $sql = "INSERT INTO novedad (titulo, descripcion, tipo, bodega_id, fecha) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $titulo, $descripcion, $tipo, $bodega_id, $fecha);
        $success = $stmt->execute();

        $msg = $success ? "Novedad registrada correctamente." : "Error al registrar novedad.";
        echo "<script>window.location.href = '../server.php#Novedades::" . urlencode($msg) . "';</script>";
        exit;
    }

    if ($accion === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM novedad WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        $msg = $success ? "Novedad eliminada correctamente." : "Error al eliminar novedad.";
        echo "<script>window.location.href = '../server.php#Novedades::" . urlencode($msg) . "';</script>";
        exit;
    }
}
