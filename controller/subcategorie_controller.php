<?php
require_once __DIR__ . '/../functions/func_subcategorie.php';

function handlesubcategories()
{
    return getAllSubcategoriesFromDb();
}

function getCategorias()
{
    return getCategoriasForSelect();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['action'] ?? '';
    $msg = '';

    if ($accion === 'add') {
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $categoria_id = intval($_POST['categoria_id']);
        $estado = intval($_POST['estado']);

        $success = agregarSubcategoria($nombre, $descripcion, $categoria_id, $estado);
        $msg = $success ? 'Subcategoría registrada exitosamente' : 'Error al registrar subcategoría';
    }

    if ($accion === 'delete') {
        $nombre = trim($_POST['nombre']);
        $success = eliminarSubcategoria($nombre);
        $msg = $success ? 'Subcategoría eliminada correctamente' : 'No se encontró o error al eliminar';
    }

    echo "<script>
        window.location.href = '../server.php#Subcategorías::" . urlencode($msg) . "';
    </script>";
    exit;
}
