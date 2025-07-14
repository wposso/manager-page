<?php
require_once __DIR__ . '/../functions/getcategories.php';
require_once __DIR__ . '/../functions/logger.php';
require_once __DIR__ . '/../database/dbconnection.php';

session_start();

function handlecategories()
{
    $fetch = getAllCategoriesFromDb();
    return $fetch ?: [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['accion'] ?? '';
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    if ($accion === 'agregar') {
        $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
        $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
        $estado = (int) ($_POST['estado'] ?? 1);

        $stmt = $conn->prepare("INSERT INTO categoria (nombre, descripcion, estado, creado_en, actualizado_en) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        if ($success && $usuario_id) {
            logAction($usuario_id, "Agregó la categoría: {$nombre}");
        }

        $msg = $success ? "Categoría agregada exitosamente." : "Ocurrió un error al agregar la categoría.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Categorias';
        </script>";
        exit;
    }

    if ($accion === 'eliminar') {
        $identificador = $conn->real_escape_string($_POST['identificador'] ?? '');
        $isNumeric = is_numeric($identificador);

        if ($isNumeric) {
            $stmt = $conn->prepare("DELETE FROM categoria WHERE id = ?");
            $stmt->bind_param("i", $identificador);
        } else {
            $stmt = $conn->prepare("DELETE FROM categoria WHERE nombre = ?");
            $stmt->bind_param("s", $identificador);
        }

        $success = $stmt->execute();

        if ($success && $usuario_id) {
            $ref = $isNumeric ? "ID {$identificador}" : "nombre '{$identificador}'";
            logAction($usuario_id, "Eliminó la categoría con {$ref}");
        }

        $stmt->close();
        $conn->close();

        $msg = $success ? "Categoría eliminada correctamente." : "No se pudo eliminar la categoría.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Categorias';
        </script>";
        exit;
    }

    // Si llega aquí sin acción válida
    $conn->close();
    echo "<script>
        alert('Acción no válida.');
        window.location.href = '../server.php#Categorias';
    </script>";
    exit;
}
