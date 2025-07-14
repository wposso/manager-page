<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/dbconnection.php';
require_once __DIR__ . '/../functions/getwarehouses.php';
// require_once __DIR__ . '/../functions/log.php';

function getWarehouses()
{
    return getAllWarehouses();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $action = $_POST['action'] ?? '';
    $msg = '';
    $success = false;

    if ($action === 'add') {
        $nombre = $conn->real_escape_string(trim($_POST['nombre'] ?? ''));
        $ubicacion = $conn->real_escape_string(trim($_POST['ubicacion'] ?? ''));

        if ($nombre && $ubicacion) {
            $stmt = $conn->prepare("INSERT INTO bodega (nombre, ubicacion, creado_en, actualizado_en) VALUES (?, ?, NOW(), NOW())");
            $stmt->bind_param("ss", $nombre, $ubicacion);
            $success = $stmt->execute();
            $stmt->close();
            $conn->close();

            $msg = $success ? "Bodega registrada correctamente." : "Error al registrar la bodega.";

            // if ($success) logAction($_SESSION['usuario_id'], "Registró bodega: $nombre");

            echo "<script>
                alert('$msg');
                window.location.href = '../server.php#Administrador';
            </script>";
            exit;
        } else {
            $msg = "Todos los campos son obligatorios para registrar una bodega.";
            echo "<script>
                alert('$msg');
                window.location.href = '../server.php#Administrador';
            </script>";
            exit;
        }
    }

    if ($action === 'delete') {
        $identificador = $conn->real_escape_string(trim($_POST['identificador'] ?? ''));

        if (!empty($identificador)) {
            if (is_numeric($identificador)) {
                $stmt = $conn->prepare("DELETE FROM bodega WHERE id = ?");
                $stmt->bind_param("i", $identificador);
            } else {
                $stmt = $conn->prepare("DELETE FROM bodega WHERE nombre = ?");
                $stmt->bind_param("s", $identificador);
            }

            $success = $stmt->execute();
            $stmt->close();
            $conn->close();

            $msg = $success ? "Bodega eliminada correctamente." : "Error al eliminar la bodega.";

            // if ($success) logAction($_SESSION['usuario_id'], "Eliminó bodega: $identificador");

            echo "<script>
                alert('$msg');
                window.location.href = '../server.php#Administrador';
            </script>";
            exit;
        } else {
            $conn->close();
            $msg = "Debes indicar el ID o nombre de la bodega a eliminar.";
            echo "<script>
                alert('$msg');
                window.location.href = '../server.php#Administrador';
            </script>";
            exit;
        }
    }

    // Por si llega sin acción válida
    $conn->close();
    echo "<script>
        alert('Acción no válida.');
        window.location.href = '../server.php#Administrador';
    </script>";
    exit;
}
