<?php
require_once __DIR__ . '/../functions/getinventory.php';

// Datos para la vista
$products = getAllProducts();
$categorias = getCategorias();
$subcategorias = getSubcategorias();
$proveedores = getProveedores();
$bodegas = getBodegas();

function handleGetInventory()
{
    global $products;
    return $products;
}

function getCatalogosForSelects()
{
    global $categorias, $subcategorias, $proveedores, $bodegas;
    return compact('categorias', 'subcategorias', 'proveedores', 'bodegas');
}

// Procesamiento de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $data = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'cantidad' => $_POST['cantidad'],
            'categoria_id' => $_POST['categoria_id'],
            'subcategoria_id' => $_POST['subcategoria_id'],
            'proveedor_id' => $_POST['proveedor_id'],
            'bodega_id' => $_POST['bodega_id'],
            'estado' => $_POST['estado']
        ];

        if (addProduct($data)) {
            echo "<script>alert('Producto registrado exitosamente'); window.location.href = '../../server.php#Inventario';</script>";
        } else {
            echo "<script>alert('Error al registrar el producto'); window.location.href = '../../server.php#Inventario';</script>";
        }
        exit;
    }

    if ($action === 'delete') {
        $codigo = $_POST['codigo'];

        if (deleteProduct($codigo)) {
            echo "<script>alert('Producto eliminado exitosamente'); window.location.href = '../../server.php#Inventario';</script>";
        } else {
            echo "<script>alert('Error al eliminar el producto'); window.location.href = '../../server.php#Inventario';</script>";
        }
        exit;
    }
}
