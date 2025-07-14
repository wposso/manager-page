<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../functions/getnews.php';
require_once __DIR__ . '/../database/dbconnection.php';
// require_once __DIR__ . '/../functions/log.php';

function handlegetnews()
{
    return getAllNews();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['action'] ?? '';

    if ($accion === 'add') {
        $titulo = $conn->real_escape_string($_POST['titulo'] ?? '');
        $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
        $fecha = $conn->real_escape_string($_POST['fecha'] ?? '');
        $tipo = $conn->real_escape_string($_POST['tipo'] ?? '');
        $bodega_id = intval($_POST['bodega_id'] ?? 0);

        $sql = "INSERT INTO novedad (titulo, descripcion, tipo, bodega_id, fecha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $titulo, $descripcion, $tipo, $bodega_id, $fecha);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        // logAction($_SESSION['usuario_id'], "Agregó novedad: $titulo");

        $msg = $success ? "Novedad registrada correctamente." : "Error al registrar la novedad.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Novedades';
        </script>";
        exit;
    }

    if ($accion === 'delete') {
        $id = intval($_POST['id'] ?? 0);

        $stmt = $conn->prepare("DELETE FROM novedad WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        // logAction($_SESSION['usuario_id'], "Eliminó novedad ID: $id");

        $msg = $success ? "Novedad eliminada correctamente." : "Error al eliminar la novedad.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Novedades';
        </script>";
        exit;
    }
}
