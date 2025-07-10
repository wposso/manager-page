<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllSubcategoriesFromDb()
{
    $conn = db_connect();
    $sql = "
        SELECT s.id, s.nombre, s.descripcion, c.nombre AS categoria, s.estado
        FROM subcategoria s
        INNER JOIN categoria c ON s.categoria_id = c.id
        ORDER BY s.id DESC
    ";
    $result = $conn->query($sql);

    $subcategorias = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subcategorias[] = $row;
        }
    }

    $conn->close();
    return $subcategorias;
}

function getCategoriasForSelect()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM categoria WHERE estado = 1 ORDER BY nombre";
    $result = $conn->query($sql);

    $categorias = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }

    $conn->close();
    return $categorias;
}

function agregarSubcategoria($nombre, $descripcion, $categoria_id, $estado)
{
    $conn = db_connect();
    $stmt = $conn->prepare("
        INSERT INTO subcategoria (nombre, descripcion, categoria_id, estado, creado_en, actualizado_en)
        VALUES (?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->bind_param("ssii", $nombre, $descripcion, $categoria_id, $estado);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

function eliminarSubcategoria($id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM subcategoria WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}
