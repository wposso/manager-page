<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/dbconnection.php';
// Opcional si vas a usar logs: require_once __DIR__ . '/../functions/log.php';

// Control de acceso
if (!isset($_SESSION['rol']) || $_SESSION['rol'] === 'operador') {
    http_response_code(403);
    exit("Acción no autorizada.");
}

function handlesuppliers()
{
    $conn = db_connect();
    $query = "SELECT id, nit, nombre, contacto, telefono, correo FROM proveedor ORDER BY id DESC";
    $result = $conn->query($query);

    $proveedores = [];
    while ($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }

    $conn->close();
    return $proveedores;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $conn = db_connect();
    $msg = '';
    $success = false;

    if ($accion === 'agregar') {
        $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
        $contacto = $conn->real_escape_string($_POST['contacto'] ?? '');
        $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
        $correo = $conn->real_escape_string($_POST['correo'] ?? '');
        $nit = $conn->real_escape_string($_POST['nit'] ?? '');
        $direccion = $conn->real_escape_string($_POST['direccion'] ?? '');

        $stmt = $conn->prepare("INSERT INTO proveedor (nombre, contacto, telefono, correo, nit, direccion, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssss", $nombre, $contacto, $telefono, $correo, $nit, $direccion);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $msg = 'Proveedor registrado exitosamente.';
            // logAction($_SESSION['usuario_id'], "Agregó proveedor: $nombre");
        } else {
            $msg = 'Error al registrar proveedor.';
        }
    }

    if ($accion === 'eliminar') {
        $identificador = $conn->real_escape_string($_POST['identificador'] ?? '');

        if (is_numeric($identificador)) {
            $stmt = $conn->prepare("DELETE FROM proveedor WHERE id = ?");
            $stmt->bind_param("i", $identificador);
        } else {
            $stmt = $conn->prepare("DELETE FROM proveedor WHERE nit = ?");
            $stmt->bind_param("s", $identificador);
        }

        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $msg = 'Proveedor eliminado exitosamente.';
            // logAction($_SESSION['usuario_id'], "Eliminó proveedor ID/NIT: $identificador");
        } else {
            $msg = 'Error al eliminar proveedor.';
        }
    }

    $conn->close();
    echo "<script>
        window.location.href = '../server.php#Proveedores::" . urlencode($msg) . "';
    </script>";
    exit;
}
