<?php
require_once __DIR__ . '/../functions/getcategories.php';
require_once __DIR__ . '/../database/dbconnection.php';

function handlecategories()
{
    $fetch = getAllCategoriesFromDb();
    return $fetch ?: [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $estado = (int) $_POST['estado'];

        $stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion, estado, creado_en, actualizado_en) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        $msg = $success ? "Categoría agregada exitosamente." : "Ocurrió un error al agregar la categoría.";
        echo "<script>
            window.location.href = '../server.php#Categorías::" . urlencode($msg) . "';
        </script>";
        exit;
    }

    if ($accion === 'eliminar') {
        $identificador = $conn->real_escape_string($_POST['identificador']);

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

        $msg = $success ? "Categoría eliminada correctamente." : "No se pudo eliminar la categoría.";
        echo "<script>
            window.location.href = '../server.php#Categorías::" . urlencode($msg) . "';
        </script>";
        exit;
    }
}
