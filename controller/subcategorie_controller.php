<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/func_subcategorie.php';
require_once __DIR__ . '/../database/dbconnection.php';

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

    if ($accion === 'add') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoria_id = intval($_POST['categoria_id'] ?? 0);
        $estado = intval($_POST['estado'] ?? 1);

        $success = agregarSubcategoria($nombre, $descripcion, $categoria_id, $estado);

        $msg = $success ? 'Subcategoría registrada exitosamente.' : 'Error al registrar subcategoría.';

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Subcategorias';
        </script>";
        exit;
    }

    if ($accion === 'delete') {
        $nombre = trim($_POST['nombre'] ?? '');
        $success = eliminarSubcategoria($nombre);

        $msg = $success ? 'Subcategoría eliminada correctamente.' : 'No se encontró la subcategoría o error al eliminar.';

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Subcategorias';
        </script>";
        exit;
    }
}
