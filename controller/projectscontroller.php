<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/dbconnection.php';
// require_once __DIR__ . '/../functions/log.php';

function getProjects()
{
    $conn = db_connect();
    $proyectos = [];

    $query = "SELECT id, nombre, ubicacion FROM proyecto";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }

    return $proyectos;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db_connect();
    $accion = $_POST['action'] ?? '';

    if ($accion === 'add') {
        $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
        $ubicacion = $conn->real_escape_string($_POST['ubicacion'] ?? '');

        $stmt = $conn->prepare("INSERT INTO proyecto (nombre, ubicacion, creado_en, actualizado_en) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("ss", $nombre, $ubicacion);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        // logAction($_SESSION['usuario_id'], "Agregó proyecto: $nombre");

        $msg = $success ? "Proyecto registrado correctamente." : "Error al registrar proyecto.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Administrador';
        </script>";
        exit;
    }

    if ($accion === 'delete') {
        $identificador = trim($_POST['identificador'] ?? '');
        $success = false;

        if (!empty($identificador)) {
            if (is_numeric($identificador)) {
                $stmt = $conn->prepare("DELETE FROM proyecto WHERE id = ?");
                $stmt->bind_param("i", $identificador);
            } else {
                $stmt = $conn->prepare("DELETE FROM proyecto WHERE nombre = ?");
                $stmt->bind_param("s", $identificador);
            }

            $success = $stmt->execute();
            $stmt->close();
        }

        $conn->close();
        // logAction($_SESSION['usuario_id'], "Eliminó proyecto: $identificador");

        $msg = $success ? "Proyecto eliminado correctamente." : "Error al eliminar proyecto.";

        echo "<script>
            alert('$msg');
            window.location.href = '../server.php#Administrador';
        </script>";
        exit;
    }
}
