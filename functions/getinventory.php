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
            COALESCE(b.nombre, pr.nombre) AS ubicacion
        FROM inventario i
        JOIN categoria c ON i.categoria_id = c.id
        JOIN subcategoria s ON i.subcategoria_id = s.id
        JOIN proveedor p ON i.proveedor_id = p.id
        LEFT JOIN bodega b ON i.bodega_id = b.id
        LEFT JOIN proyecto pr ON i.proyecto_id = pr.id
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
            proveedor_id, bodega_id, proyecto_id, estado, creado_en, actualizado_en
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->bind_param(
        "ssiiiiiii",
        $data['codigo'],
        $data['nombre'],
        $data['cantidad'],
        $data['categoria_id'],
        $data['subcategoria_id'],
        $data['proveedor_id'],
        $data['bodega_id'],
        $data['proyecto_id'],
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

function getProductsByProject($proyecto_id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("
        SELECT i.*, 
               c.nombre AS categoria, 
               s.nombre AS subcategoria, 
               p.nombre AS proveedor, 
               COALESCE(b.nombre, pr.nombre) AS ubicacion
        FROM inventario i
        JOIN categoria c ON i.categoria_id = c.id
        JOIN subcategoria s ON i.subcategoria_id = s.id
        JOIN proveedor p ON i.proveedor_id = p.id
        LEFT JOIN bodega b ON i.bodega_id = b.id
        LEFT JOIN proyecto pr ON i.proyecto_id = pr.id
        WHERE i.proyecto_id = ?
    ");
    $stmt->bind_param("i", $proyecto_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $productos = [];

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    $stmt->close();
    $conn->close();
    return $productos;
}

function getProjectName($proyecto_id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT nombre FROM proyecto WHERE id = ?");
    $stmt->bind_param("i", $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row ? $row['nombre'] : "Proyecto Desconocido";
}

function getProductsByBodega($bodega_id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("
        SELECT i.*, 
               c.nombre AS categoria, 
               s.nombre AS subcategoria, 
               p.nombre AS proveedor, 
               COALESCE(b.nombre, pr.nombre) AS ubicacion
        FROM inventario i
        JOIN categoria c ON i.categoria_id = c.id
        JOIN subcategoria s ON i.subcategoria_id = s.id
        JOIN proveedor p ON i.proveedor_id = p.id
        LEFT JOIN bodega b ON i.bodega_id = b.id
        LEFT JOIN proyecto pr ON i.proyecto_id = pr.id
        WHERE i.bodega_id = ? AND i.cantidad > 0
    ");
    $stmt->bind_param("i", $bodega_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $productos = [];

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    $stmt->close();
    $conn->close();
    return $productos;
}

function getBodegaName($bodega_id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT nombre FROM bodega WHERE id = ?");
    $stmt->bind_param("i", $bodega_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row ? $row['nombre'] : "Bodega Desconocida";
}

function getProductById($id)
{
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM inventario WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $producto = $res->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $producto;
}

function getProyectos()
{
    $conn = db_connect();
    $sql = "SELECT id, nombre FROM proyecto";
    $result = $conn->query($sql);
    $proyectos = [];
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
    $conn->close();
    return $proyectos;
}

function getCatalogosForSelects()
{
    return [
        'categorias' => getCategorias(),
        'subcategorias' => getSubcategorias(),
        'proveedores' => getProveedores(),
        'bodegas' => getBodegas(),
        'proyectos' => getProyectos()
    ];
}
