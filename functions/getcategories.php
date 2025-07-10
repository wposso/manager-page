<?php
require_once __DIR__ . "/../database/dbconnection.php";

function getAllCategoriesFromDb()
{
    $conn = db_connect();

    if ($conn->connect_error) {
        die("Conexión interrumpida");
    }

    $categoria = [];
    $response = $conn->query("SELECT id, nombre, descripcion, estado FROM categoria");

    while ($row = $response->fetch_assoc()) {
        $categoria[] = $row;
    }

    return $categoria;
}

function agregarCategoria($nombre, $descripcion, $estado)
{
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion, estado, creado_en, actualizado_en) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
    $success = $stmt->execute();

    $stmt->close();
    $conn->close();
    return $success;
}

function eliminarCategoria($identificador)
{
    $conn = db_connect();

    if (is_numeric($identificador)) {
        $stmt = $conn->prepare("DELETE FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $identificador);
    } else {
        $stmt = $conn->prepare("DELETE FROM categoria WHERE nombre = ?");
        $stmt->bind_param("s", $identificador);
    }

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}
