<?php
require_once __DIR__ . '/../database/dbconnection.php';

function getAllProducts()
{
    $conn = db_connect();
    $sql = "
        SELECT 
            i.id, i.codigo, i.nombre, i.cantidad, i.estado,
            c.nombre AS categoria,
            s.nombre AS subcategoria,
            p.nombre AS proveedor,
            b.nombre AS bodega
        FROM inventario i
        JOIN categoria c ON i.categoria_id = c.id
        JOIN subcategoria s ON i.subcategoria_id = s.id
        JOIN proveedor p ON i.proveedor_id = p.id
        JOIN bodega b ON i.bodega_id = b.id
        ORDER BY i.id DESC
    ";
    $result = $conn->query($sql);
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $conn->close();
    return $products;
}

function addProduct($data)
{
    $conn = db_connect();
    $stmt = $conn->prepare("
        INSERT INTO inventario (
            codigo, nombre, cantidad, categoria_id, subcategoria_id,
            proveedor_id, bodega_id, estado, creado_en, actualizado_en
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->bind_param(
        "ssiissii",
        $data['codigo'],
        $data['nombre'],
        $data['cantidad'],
        $data['categoria_id'],
        $data['subcategoria_id'],
        $data['proveedor_id'],
        $data['bodega_id'],
        $data['estado']
    );
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

function deleteProduct($codigo)
{
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM inventario WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

function getCategorias()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM categoria WHERE estado = 1";
    $result = $conn->query($sql);
    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    $conn->close();
    return $categorias;
}

function getSubcategorias()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM subcategoria WHERE estado = 1";
    $result = $conn->query($sql);
    $subcategorias = [];
    while ($row = $result->fetch_assoc()) {
        $subcategorias[] = $row;
    }
    $conn->close();
    return $subcategorias;
}

function getProveedores()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM proveedor";
    $result = $conn->query($sql);
    $proveedores = [];
    while ($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }
    $conn->close();
    return $proveedores;
}

function getBodegas()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM bodega WHERE estado = 1";
    $result = $conn->query($sql);
    $bodegas = [];
    while ($row = $result->fetch_assoc()) {
        $bodegas[] = $row;
    }
    $conn->close();
    return $bodegas;
}
