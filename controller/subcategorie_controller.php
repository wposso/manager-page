<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/func_subcategorie.php';
require_once __DIR__ . '/../database/dbconnection.php';
// Opcional: para logs de acciones
// require_once __DIR__ . '/../functions/log.php';

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

        if ($success) {
            $msg = 'Subcategoría registrada exitosamente.';
            // logAction($_SESSION['usuario_id'], "Agregó subcategoría: $nombre");
        } else {
            $msg = 'Error al registrar subcategoría.';
        }
    }

    if ($accion === 'delete') {
        $nombre = trim($_POST['nombre']);
        $success = eliminarSubcategoria($nombre);

        if ($success) {
            $msg = 'Subcategoría eliminada correctamente.';
            // logAction($_SESSION['usuario_id'], "Eliminó subcategoría: $nombre");
        } else {
            $msg = 'No se encontró la subcategoría o error al eliminar.';
        }
    }

    echo "<script>
        window.location.href = '../server.php#Subcategorías::" . urlencode($msg) . "';
    </script>";
    exit;
}
